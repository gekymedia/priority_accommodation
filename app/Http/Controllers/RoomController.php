<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('hostel', 'currentBooking.student');

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('room_number', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by hostel
        if ($request->has('hostel_id') && $request->hostel_id) {
            $query->where('hostel_id', $request->hostel_id);
        }

        $rooms = $query->latest()->paginate(12);

        // Room statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', Room::STATUS_AVAILABLE)->count();
        $occupiedRooms = Room::where('status', Room::STATUS_OCCUPIED)->count();
        $maintenanceRooms = Room::where('status', Room::STATUS_MAINTENANCE)->count();

        // Get hostels for filter dropdown
        $hostels = Hostel::active()->get();

        return view('rooms.index', compact(
            'rooms',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'hostels'
        ));
    }

    public function create()
    {
        $hostels = Hostel::active()->get();
        $roomTypes = Room::getTypes();
        $roomStatuses = Room::getStatuses();
        
        return view('rooms.create', compact('hostels', 'roomTypes', 'roomStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|max:10',
            'type' => 'required|in:single,double,suite',
            'capacity' => 'required|integer|min:1|max:6',
            'price_per_academic_year' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:available,occupied,maintenance',
            'available' => 'required|boolean',
            'video_url' => 'nullable|url',
            'features' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Ensure room number is unique within the same hostel
        $existingRoom = Room::where('hostel_id', $validated['hostel_id'])
                            ->where('room_number', $validated['room_number'])
                            ->first();
        
        if ($existingRoom) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['room_number' => 'This room number already exists in the selected hostel.']);
        }

        // Handle photos upload
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('rooms/photos', 'public');
            }
            $validated['photos'] = $photos;
        }

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load(['hostel', 'bookings.student', 'currentBooking.student']);
        
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $hostels = Hostel::active()->get();
        $roomTypes = Room::getTypes();
        $roomStatuses = Room::getStatuses();
        
        return view('rooms.edit', compact('room', 'hostels', 'roomTypes', 'roomStatuses'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|max:10',
            'type' => 'required|in:single,double,suite',
            'capacity' => 'required|integer|min:1|max:6',
            'price_per_academic_year' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:available,occupied,maintenance',
            'video_url' => 'nullable|url',
            'features' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'existing_photos' => 'nullable|array',
            'existing_photos.*' => 'string',
        ]);

        // Handle available checkbox (comes from checkbox)
        $validated['available'] = $request->has('available');

        // Check for duplicate room number in the same hostel (excluding current room)
        $existingRoom = Room::where('hostel_id', $validated['hostel_id'])
                            ->where('room_number', $validated['room_number'])
                            ->where('id', '!=', $room->id)
                            ->first();
        
        if ($existingRoom) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['room_number' => 'This room number already exists in the selected hostel.']);
        }

        // Handle photos - keep only existing_photos and add new ones
        $currentPhotos = $room->photos ?? [];
        $photosToKeep = $request->input('existing_photos', []);
        
        // Delete photos that were removed
        foreach ($currentPhotos as $photo) {
            if (!in_array($photo, $photosToKeep)) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Start with photos to keep
        $finalPhotos = $photosToKeep;

        // Handle new photos upload
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $finalPhotos[] = $photo->store('rooms/photos', 'public');
            }
        }
        $validated['photos'] = array_values($finalPhotos);

        // Remove fields that are not model fields
        unset($validated['existing_photos']);

        $room->update($validated);

        return redirect()->route('admin.rooms.show', $room)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        // Check if room has active bookings
        if ($room->bookings()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete room with active bookings.');
        }

        // Delete photos
        if ($room->photos) {
            foreach ($room->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,maintenance'
        ]);

        try {
            \DB::beginTransaction();

            // Business logic validation
            if (in_array($request->status, ['maintenance', 'available']) && 
                $room->currentBooking) {
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot change status: Room has active booking from ' . 
                                    $room->currentBooking->student->name . '.'
                    ], 422);
                }
                return back()->with('error', 'Cannot change status: Room has active booking.');
            }

            // Update both status and available field
            $room->update([
                'status' => $request->status,
                'available' => $request->status === 'available'
            ]);

            \DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Room status updated to ' . ucfirst($request->status) . ' successfully.'
                ]);
            }

            return back()->with('success', 'Room status updated successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Room status update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating room status: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error updating room status: ' . $e->getMessage());
        }
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HostelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hostel::withCount(['rooms', 'availableRooms', 'occupiedRooms', 'maintenanceRooms']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%")
                  ->orWhere('contact_email', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $hostels = $query->latest()->paginate(12);

        // Statistics
        $totalHostels = Hostel::count();
        $activeHostels = Hostel::where('is_active', true)->count();
        $totalRooms = Room::count();
        $totalCapacity = Room::sum('capacity');

        return view('hostels.index', compact(
            'hostels',
            'totalHostels',
            'activeHostels',
            'totalRooms',
            'totalCapacity'
        ));
    }

    public function create()
    {
        return view('hostels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hostels,name',
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
            'amenities' => 'nullable|array',
            'is_active' => 'required|boolean',
            'hostel_type' => 'nullable|string|in:boys,girls,mixed',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'walking_time_minutes' => 'nullable|integer|min:1',
            'driving_time_minutes' => 'nullable|integer|min:1',
            'distance_km' => 'nullable|numeric|min:0',
            'google_maps_url' => 'nullable|url',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('hostels/covers', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hostels/gallery', 'public');
            }
            $validated['images'] = $images;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        Hostel::create($validated);

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel created successfully.');
    }

    public function show(Hostel $hostel)
    {
        $hostel->load(['rooms' => function($query) {
            $query->with('currentBooking.student');
        }]);

        $roomStats = [
            'total' => $hostel->total_rooms,
            'available' => $hostel->available_rooms_count,
            'occupied' => $hostel->occupied_rooms_count,
            'maintenance' => $hostel->maintenance_rooms_count,
        ];

        return view('hostels.show', compact('hostel', 'roomStats'));
    }

    public function edit(Hostel $hostel)
    {
        return view('hostels.edit', compact('hostel'));
    }

    public function update(Request $request, Hostel $hostel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hostels,name,' . $hostel->id,
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
            'amenities' => 'nullable|array',
            'is_active' => 'required|boolean',
            'hostel_type' => 'nullable|string|in:boys,girls,mixed',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'walking_time_minutes' => 'nullable|integer|min:1',
            'driving_time_minutes' => 'nullable|integer|min:1',
            'distance_km' => 'nullable|numeric|min:0',
            'google_maps_url' => 'nullable|url',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($hostel->cover_image) {
                Storage::disk('public')->delete($hostel->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('hostels/covers', 'public');
        }

        // Handle images - keep only existing_images and add new ones
        $currentImages = $hostel->images ?? [];
        $imagesToKeep = $request->input('existing_images', []);

        // Delete images that were removed
        foreach ($currentImages as $image) {
            if (!in_array($image, $imagesToKeep)) {
                Storage::disk('public')->delete($image);
            }
        }

        // Start with images to keep
        $finalImages = $imagesToKeep;

        // Handle new images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $finalImages[] = $image->store('hostels/gallery', 'public');
            }
        }
        $validated['images'] = array_values($finalImages);

        // Update slug if name changed
        if ($hostel->name !== $validated['name']) {
            $validated['slug'] = $hostel->generateUniqueSlug($validated['name']);
        }

        // Remove fields that are not model fields
        unset($validated['existing_images']);

        $hostel->update($validated);

        return redirect()->route('hostels.show', $hostel)
            ->with('success', 'Hostel updated successfully.');
    }

    public function destroy(Hostel $hostel)
    {
        // Check if hostel has rooms
        if ($hostel->rooms()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete hostel that has rooms. Please delete or move all rooms first.');
        }

        // Delete images
        if ($hostel->cover_image) {
            Storage::disk('public')->delete($hostel->cover_image);
        }
        if ($hostel->images) {
            foreach ($hostel->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $hostel->delete();

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel deleted successfully.');
    }

    public function toggleStatus(Hostel $hostel)
    {
        $hostel->update(['is_active' => !$hostel->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $hostel->is_active,
            'message' => 'Hostel status updated successfully.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Room;
use App\Models\BookingRequest;
use App\Models\PaymentSettings;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicHostelController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;

    public function __construct(BookingService $bookingService, PaymentService $paymentService)
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
    }

    /**
     * Show public hostel listing for students
     */
    public function index(Request $request)
    {
        $query = Hostel::with(['rooms' => function ($q) {
            $q->where('status', Room::STATUS_AVAILABLE)
              ->where('available', true);
        }])
        ->where('is_active', true)
        ->where('has_partnership_agreement', true);

        // Filter by hostel type
        if ($request->filled('type')) {
            $query->where('hostel_type', $request->type);
        }

        // Filter by distance
        if ($request->filled('max_walking_time')) {
            $query->where('walking_time_minutes', '<=', $request->max_walking_time);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price_per_semester', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price_per_semester', '<=', $request->max_price);
                }
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'distance');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price_per_semester) FROM rooms WHERE rooms.hostel_id = hostels.id AND rooms.status = "available") ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MIN(price_per_semester) FROM rooms WHERE rooms.hostel_id = hostels.id AND rooms.status = "available") DESC');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            default:
                $query->orderBy('walking_time_minutes')->orderBy('distance_km');
        }

        $hostels = $query->paginate(12);

        return view('public.hostels.index', [
            'hostels' => $hostels,
            'filters' => $request->only(['type', 'max_walking_time', 'min_price', 'max_price', 'sort']),
        ]);
    }

    /**
     * Show single hostel details
     */
    public function show(Hostel $hostel)
    {
        // Ensure hostel is active and has partnership
        if (!$hostel->is_active || !$hostel->has_partnership_agreement) {
            abort(404);
        }

        $hostel->load(['rooms' => function ($q) {
            $q->where('status', Room::STATUS_AVAILABLE)
              ->where('available', true)
              ->orderBy('price_per_semester');
        }]);

        return view('public.hostels.show', [
            'hostel' => $hostel,
        ]);
    }

    /**
     * Show room details
     */
    public function showRoom(Hostel $hostel, Room $room)
    {
        // Verify room belongs to hostel
        if ($room->hostel_id !== $hostel->id) {
            abort(404);
        }

        // Ensure hostel is active and has partnership
        if (!$hostel->is_active || !$hostel->has_partnership_agreement) {
            abort(404);
        }

        $room->load('hostel');
        
        // Get pricing with commission
        $pricing = $room->calculatePriceWithCommission();

        // Get roommates info (only for logged-in users with bookings)
        $roommates = [];
        if (Auth::check()) {
            $roommates = $room->roommates;
        }

        return view('public.hostels.room', [
            'hostel' => $hostel,
            'room' => $room,
            'pricing' => $pricing,
            'roommates' => $roommates,
            'paymentConfigured' => $this->paymentService->isConfigured(),
        ]);
    }

    /**
     * Start booking process
     */
    public function startBooking(Request $request, Hostel $hostel, Room $room)
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to book a room.');
        }

        $user = Auth::user();

        // Check if user has completed profile (profile picture for non-CUG)
        if (!$user->is_cug_verified && !$user->profile_picture) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Please upload a profile picture before booking a room.');
        }

        // Check room availability
        if (!$room->hasAvailableBeds()) {
            return back()->with('error', 'Sorry, this room is no longer available.');
        }

        // Check if payment is configured
        if (!$this->paymentService->isConfigured()) {
            return back()->with('error', 'Online booking is currently unavailable. Please contact the hostel directly.');
        }

        $pricing = $room->calculatePriceWithCommission();
        $settings = $this->paymentService->getSettings();

        return view('public.hostels.booking', [
            'hostel' => $hostel,
            'room' => $room,
            'pricing' => $pricing,
            'settings' => $settings,
            'user' => $user,
        ]);
    }

    /**
     * Create booking request and initialize payment
     */
    public function createBooking(Request $request, Hostel $hostel, Room $room)
    {
        $request->validate([
            'beds_requested' => 'required|integer|min:1|max:' . $room->beds_available,
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'terms_accepted' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Get or create student record for user
        $student = $user->student ?? $this->createStudentForUser($user);

        // Create booking request
        $bookingRequest = $this->bookingService->createBookingRequest([
            'student_id' => $student->id,
            'room_id' => $room->id,
            'beds_requested' => $request->beds_requested,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
        ]);

        // Initialize payment
        $paymentResult = $this->paymentService->initializePayment($bookingRequest, [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        if (!$paymentResult['success']) {
            return back()->with('error', $paymentResult['error']);
        }

        // Redirect to payment provider
        return redirect($paymentResult['checkout_url']);
    }

    /**
     * Handle payment callback
     */
    public function paymentCallback(Request $request, string $provider)
    {
        $reference = $request->get('reference') ?? $request->get('clientReference');

        if (!$reference) {
            return redirect()->route('hostels.index')
                ->with('error', 'Invalid payment reference.');
        }

        $bookingRequest = BookingRequest::where('payment_reference', $reference)->first();

        if (!$bookingRequest) {
            return redirect()->route('hostels.index')
                ->with('error', 'Booking request not found.');
        }

        // Verify payment based on provider
        if ($provider === 'hubtel') {
            $verification = $this->paymentService->verifyHubtelPayment($reference);
        } else {
            $verification = $this->paymentService->verifyPaystackPayment($reference);
        }

        if ($verification['success'] && in_array($verification['status'], ['success', 'Success', 'Paid'])) {
            // Process successful payment
            $this->paymentService->processSuccessfulPayment($bookingRequest, $verification['data'] ?? []);

            return redirect()->route('bookings.status', ['token' => $bookingRequest->request_token]);
        }

        return redirect()->route('hostels.index')
            ->with('error', 'Payment verification failed. Please contact support.');
    }

    /**
     * Show booking status page (waiting for confirmation)
     */
    public function bookingStatus(string $token)
    {
        $bookingRequest = BookingRequest::where('request_token', $token)
            ->with(['hostel', 'room', 'student.user'])
            ->firstOrFail();

        return view('public.hostels.booking-status', [
            'request' => $bookingRequest,
        ]);
    }

    /**
     * Create student record for user
     */
    protected function createStudentForUser($user)
    {
        return \App\Models\Student::create([
            'user_id' => $user->id,
            'student_number' => $user->student_id_number ?? 'USR-' . $user->id,
            'first_name' => explode(' ', $user->name)[0] ?? $user->name,
            'last_name' => explode(' ', $user->name)[1] ?? '',
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => 'active',
        ]);
    }
}


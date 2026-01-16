<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $range = $request->get('range', 'month');
            $type = $request->get('type', 'overview');
            
            // Calculate date range
            $dateRange = $this->getDateRange($range, $request);
            
            // Room statistics
            $totalRooms = Room::count();
            $occupiedRooms = Room::where('status', Room::STATUS_OCCUPIED)->count();
            $maintenanceRooms = Room::where('status', Room::STATUS_MAINTENANCE)->count();
            $vacantRooms = $totalRooms - $occupiedRooms - $maintenanceRooms;
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
            
            // Student statistics
            $activeStudents = Student::has('currentBooking')->count();
            $newStudents = Student::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
            
            // Booking statistics
            $pendingBookings = Booking::where('status', Booking::STATUS_PENDING)->count();
            
            // Revenue calculations
            $totalRevenue = Payment::where('status', Payment::STATUS_COMPLETED)
                                 ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                                 ->sum('amount');
            
            // Calculate revenue growth (compared to previous period)
            $previousPeriodRevenue = $this->getPreviousPeriodRevenue($range, $dateRange);
            $revenueGrowth = $previousPeriodRevenue > 0 ? 
                round((($totalRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100, 2) : 0;
            
            // Average room rate
            $averageRoomRate = Room::where('status', Room::STATUS_AVAILABLE)->avg('price_per_academic_year') ?? 0;
            
            // Revenue by type
            $revenueByType = [
                'rent' => Payment::where('status', Payment::STATUS_COMPLETED)
                               ->where('type', Payment::TYPE_RENT)
                               ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                               ->sum('amount'),
                'security' => Payment::where('status', Payment::STATUS_COMPLETED)
                                   ->where('type', Payment::TYPE_SECURITY)
                                   ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                                   ->sum('amount'),
                'other' => Payment::where('status', Payment::STATUS_COMPLETED)
                                ->where('type', Payment::TYPE_OTHER)
                                ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                                ->sum('amount'),
            ];
            
            // Top performing rooms (by revenue)
            $topRooms = Room::with(['hostel', 'bookings.payments'])
                          ->withSum(['bookings' => function($query) use ($dateRange) {
                              $query->whereHas('payments', function($q) use ($dateRange) {
                                  $q->where('status', Payment::STATUS_COMPLETED)
                                    ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']]);
                              });
                          }], 'total_amount')
                          ->having('bookings_sum_total_amount', '>', 0)
                          ->orderBy('bookings_sum_total_amount', 'desc')
                          ->limit(5)
                          ->get()
                          ->each(function($room) use ($totalRooms) {
                              $room->total_revenue = $room->bookings_sum_total_amount ?? 0;
                              $room->occupancy_rate = $totalRooms > 0 ? 
                                  round(($room->bookings()->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_CHECKED_IN])->count() / $totalRooms) * 100, 2) : 0;
                          });

            return view('reports.index', compact(
                'totalRevenue',
                'revenueGrowth',
                'occupancyRate',
                'occupiedRooms',
                'totalRooms',
                'vacantRooms',
                'maintenanceRooms',
                'activeStudents',
                'newStudents',
                'pendingBookings',
                'averageRoomRate',
                'revenueByType',
                'topRooms'
            ));

        } catch (\Exception $e) {
            \Log::error('Reports error: ' . $e->getMessage());
            
            // Return default values in case of error
            return view('reports.index', [
                'totalRevenue' => 0,
                'revenueGrowth' => 0,
                'occupancyRate' => 0,
                'occupiedRooms' => 0,
                'totalRooms' => 0,
                'vacantRooms' => 0,
                'maintenanceRooms' => 0,
                'activeStudents' => 0,
                'newStudents' => 0,
                'pendingBookings' => 0,
                'averageRoomRate' => 0,
                'revenueByType' => ['rent' => 0, 'security' => 0, 'other' => 0],
                'topRooms' => collect()
            ]);
        }
    }

    public function financialReport(Request $request)
    {
        try {
            $range = $request->get('range', 'month');
            $dateRange = $this->getDateRange($range, $request);

            $financialData = Payment::select(
                    DB::raw('MONTH(payment_date) as month'),
                    DB::raw('YEAR(payment_date) as year'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as transaction_count')
                )
                ->where('status', Payment::STATUS_COMPLETED)
                ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            // Revenue by payment type
            $revenueByType = Payment::select('type', DB::raw('SUM(amount) as total'))
                ->where('status', Payment::STATUS_COMPLETED)
                ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                ->groupBy('type')
                ->get()
                ->pluck('total', 'type');

            // Payment methods breakdown
            $paymentMethods = Payment::select('payment_method', DB::raw('SUM(amount) as total'))
                ->where('status', Payment::STATUS_COMPLETED)
                ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                ->groupBy('payment_method')
                ->get();

            return response()->json([
                'financial_data' => $financialData,
                'revenue_by_type' => $revenueByType,
                'payment_methods' => $paymentMethods
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate financial report'], 500);
        }
    }

    public function occupancyReport(Request $request)
    {
        try {
            $range = $request->get('range', 'month');
            $dateRange = $this->getDateRange($range, $request);

            // Room status breakdown
            $roomStatusData = Room::select('status', DB::raw('count(*) as count'))
                               ->groupBy('status')
                               ->get();

            // Occupancy trends over time
            $occupancyTrends = Booking::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as bookings_count')
                )
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_CHECKED_IN])
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            // Room type occupancy
            $roomTypeOccupancy = Room::select(
                    'type',
                    DB::raw('COUNT(*) as total_rooms'),
                    DB::raw('SUM(CASE WHEN status = "occupied" THEN 1 ELSE 0 END) as occupied_rooms')
                )
                ->groupBy('type')
                ->get()
                ->map(function($item) {
                    $item->occupancy_rate = $item->total_rooms > 0 ? 
                        round(($item->occupied_rooms / $item->total_rooms) * 100, 2) : 0;
                    return $item;
                });

            return response()->json([
                'room_status' => $roomStatusData,
                'occupancy_trends' => $occupancyTrends,
                'room_type_occupancy' => $roomTypeOccupancy
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate occupancy report'], 500);
        }
    }

    public function studentReport(Request $request)
    {
        try {
            $range = $request->get('range', 'month');
            $dateRange = $this->getDateRange($range, $request);

            // Student statistics
            $totalStudents = Student::count();
            $activeStudents = Student::has('currentBooking')->count();
            $newStudents = Student::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();

            // University distribution
            $universityDistribution = Student::select('university', DB::raw('COUNT(*) as count'))
                ->whereNotNull('university')
                ->groupBy('university')
                ->orderBy('count', 'desc')
                ->get();

            // Course distribution
            $courseDistribution = Student::select('course', DB::raw('COUNT(*) as count'))
                ->whereNotNull('course')
                ->groupBy('course')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Year of study distribution
            $yearDistribution = Student::select('year_of_study', DB::raw('COUNT(*) as count'))
                ->whereNotNull('year_of_study')
                ->groupBy('year_of_study')
                ->orderBy('year_of_study', 'asc')
                ->get();

            return response()->json([
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'new_students' => $newStudents,
                'university_distribution' => $universityDistribution,
                'course_distribution' => $courseDistribution,
                'year_distribution' => $yearDistribution
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate student report'], 500);
        }
    }

    public function exportReport(Request $request)
    {
        try {
            $range = $request->get('range', 'month');
            $type = $request->get('type', 'overview');
            $dateRange = $this->getDateRange($range, $request);
            
            $filename = "reports_{$type}_{$range}_" . now()->format('Y_m_d') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($type, $dateRange, $range) {
                $handle = fopen('php://output', 'w');
                
                switch ($type) {
                    case 'financial':
                        $this->exportFinancialReport($handle, $dateRange, $range);
                        break;
                    case 'occupancy':
                        $this->exportOccupancyReport($handle, $dateRange, $range);
                        break;
                    case 'student':
                        $this->exportStudentReport($handle, $dateRange, $range);
                        break;
                    default:
                        $this->exportOverviewReport($handle, $dateRange, $range);
                        break;
                }
                
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }

    /**
     * Get date range based on filter
     */
    private function getDateRange($range, $request)
    {
        $now = Carbon::now();
        
        switch ($range) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'quarter':
                return [
                    'start' => $now->copy()->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter()
                ];
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'custom':
                return [
                    'start' => $request->get('from_date') ? Carbon::parse($request->get('from_date'))->startOfDay() : $now->copy()->startOfMonth(),
                    'end' => $request->get('to_date') ? Carbon::parse($request->get('to_date'))->endOfDay() : $now->copy()->endOfMonth()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Get previous period revenue for growth calculation
     */
    private function getPreviousPeriodRevenue($range, $currentPeriod)
    {
        $start = Carbon::parse($currentPeriod['start']);
        $end = Carbon::parse($currentPeriod['end']);
        $duration = $start->diffInDays($end);
        
        $previousStart = $start->copy()->subDays($duration + 1);
        $previousEnd = $start->copy()->subDay();
        
        return Payment::where('status', Payment::STATUS_COMPLETED)
                    ->whereBetween('payment_date', [$previousStart, $previousEnd])
                    ->sum('amount');
    }

    /**
     * Export methods for different report types
     */
    private function exportOverviewReport($handle, $dateRange, $range)
    {
        fputcsv($handle, ['Priority Accommodations - Overview Report', 'Period: ' . $range]);
        fputcsv($handle, ['']);
        fputcsv($handle, ['Metric', 'Value']);
        
        $totalRevenue = Payment::where('status', Payment::STATUS_COMPLETED)
                             ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                             ->sum('amount');
        
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', Room::STATUS_OCCUPIED)->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
        
        fputcsv($handle, ['Total Revenue', '₵' . number_format($totalRevenue, 2)]);
        fputcsv($handle, ['Occupancy Rate', $occupancyRate . '%']);
        fputcsv($handle, ['Total Rooms', $totalRooms]);
        fputcsv($handle, ['Occupied Rooms', $occupiedRooms]);
        fputcsv($handle, ['Active Students', Student::has('currentBooking')->count()]);
    }

    private function exportFinancialReport($handle, $dateRange, $range)
    {
        fputcsv($handle, ['Priority Accommodations - Financial Report', 'Period: ' . $range]);
        fputcsv($handle, ['']);
        fputcsv($handle, ['Type', 'Amount']);
        
        $revenueByType = [
            'Rent' => Payment::where('status', Payment::STATUS_COMPLETED)
                           ->where('type', Payment::TYPE_RENT)
                           ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                           ->sum('amount'),
            'Security Deposits' => Payment::where('status', Payment::STATUS_COMPLETED)
                                       ->where('type', Payment::TYPE_SECURITY)
                                       ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                                       ->sum('amount'),
            'Other Income' => Payment::where('status', Payment::STATUS_COMPLETED)
                                   ->where('type', Payment::TYPE_OTHER)
                                   ->whereBetween('payment_date', [$dateRange['start'], $dateRange['end']])
                                   ->sum('amount'),
        ];
        
        foreach ($revenueByType as $type => $amount) {
            fputcsv($handle, [$type, '₵' . number_format($amount, 2)]);
        }
    }

    private function exportOccupancyReport($handle, $dateRange, $range)
    {
        fputcsv($handle, ['Priority Accommodations - Occupancy Report', 'Period: ' . $range]);
        fputcsv($handle, ['']);
        fputcsv($handle, ['Status', 'Count', 'Percentage']);
        
        $totalRooms = Room::count();
        $statuses = Room::select('status', DB::raw('count(*) as count'))
                      ->groupBy('status')
                      ->get();
        
        foreach ($statuses as $status) {
            $percentage = $totalRooms > 0 ? round(($status->count / $totalRooms) * 100, 2) : 0;
            fputcsv($handle, [ucfirst($status->status), $status->count, $percentage . '%']);
        }
    }

    private function exportStudentReport($handle, $dateRange, $range)
    {
        fputcsv($handle, ['Priority Accommodations - Student Report', 'Period: ' . $range]);
        fputcsv($handle, ['']);
        fputcsv($handle, ['Metric', 'Count']);
        
        fputcsv($handle, ['Total Students', Student::count()]);
        fputcsv($handle, ['Active Students', Student::has('currentBooking')->count()]);
        fputcsv($handle, ['New Students (Period)', Student::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count()]);
    }
}
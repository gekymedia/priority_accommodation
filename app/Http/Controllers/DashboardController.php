<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Room statistics - USING CORRECT CONSTANTS
            $totalRooms = Room::count();
            $availableRooms = Room::where('status', Room::STATUS_AVAILABLE)->count();
            $occupiedRooms = Room::where('status', Room::STATUS_OCCUPIED)->count();
            $maintenanceRooms = Room::where('status', Room::STATUS_MAINTENANCE)->count();
            
            // Calculate occupancy rate
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

            // Student statistics
            $totalStudents = Student::count();
            $activeStudents = Student::has('currentBooking')->count();

            // Booking statistics - USING CORRECT CONSTANTS
            $pendingBookings = Booking::where('status', Booking::STATUS_PENDING)->count();
            $todayBookings = Booking::whereDate('created_at', today())->count();

            // Revenue for current month - USING CORRECT CONSTANTS
            $revenue = Payment::where('status', Payment::STATUS_COMPLETED)
                             ->whereMonth('payment_date', now()->month)
                             ->whereYear('payment_date', now()->year)
                             ->sum('amount');

            // Recent bookings
            $recentBookings = Booking::with(['student', 'room'])
                                    ->latest()
                                    ->limit(10)
                                    ->get();

            // Recent activities from audit logs - with safe table check
            $recentActivities = collect();
            if (Schema::hasTable('audit_logs')) {
                $recentActivities = AuditLog::with('user')
                    ->latest()
                    ->limit(5)
                    ->get();
            }

            return view('dashboard', compact(
                'totalRooms',
                'availableRooms', 
                'occupiedRooms',
                'maintenanceRooms',
                'occupancyRate',
                'totalStudents',
                'activeStudents',
                'pendingBookings',
                'todayBookings',
                'revenue',
                'recentBookings',
                'recentActivities'
            ));

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            
            // Return default values in case of error
            return view('dashboard', [
                'totalRooms' => 0,
                'availableRooms' => 0,
                'occupiedRooms' => 0,
                'maintenanceRooms' => 0,
                'occupancyRate' => 0,
                'totalStudents' => 0,
                'activeStudents' => 0,
                'pendingBookings' => 0,
                'todayBookings' => 0,
                'revenue' => 0,
                'recentBookings' => collect(),
                'recentActivities' => collect()
            ]);
        }
    }
    
    public function reports(Request $request)
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
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
            
            // Student statistics
            $activeStudents = Student::has('currentBooking')->count();
            $newStudents = Student::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
            
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
                'activeStudents',
                'newStudents',
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
                'activeStudents' => 0,
                'newStudents' => 0,
                'averageRoomRate' => 0,
                'revenueByType' => ['rent' => 0, 'security' => 0, 'other' => 0],
                'topRooms' => collect()
            ]);
        }
    }

    public function occupancyReport()
    {
        try {
            $occupancyData = Room::select('status', DB::raw('count(*) as count'))
                               ->groupBy('status')
                               ->get();

            return response()->json($occupancyData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate occupancy report'], 500);
        }
    }

    public function financialReport()
    {
        try {
            $financialData = Payment::select(
                    DB::raw('MONTH(payment_date) as month'),
                    DB::raw('YEAR(payment_date) as year'),
                    DB::raw('SUM(amount) as total')
                )
                ->where('status', Payment::STATUS_COMPLETED)
                ->whereYear('payment_date', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            return response()->json($financialData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate financial report'], 500);
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
}
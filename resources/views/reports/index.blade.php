@extends('layouts.app')

@section('title', 'Reports & Analytics - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Reports & Analytics</h1>
    <div class="page-actions">
        <button class="btn btn-secondary">
            <i class="fas fa-download"></i>
            Export Report
        </button>
        <button class="btn btn-primary" onclick="location.reload()">
            <i class="fas fa-sync"></i>
            Refresh Data
        </button>
    </div>
</div>

<!-- Date Range Filter Card -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Report Filters</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <select name="range" class="filter-select" onchange="this.form.submit()">
                        <option value="today" {{ request('range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('range') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="quarter" {{ request('range') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                        <option value="year" {{ request('range') == 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                
                @if(request('range') == 'custom')
                <div class="filter-group">
                    <label class="filter-label">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="filter-select">
                </div>
                <div class="filter-group">
                    <label class="filter-label">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="filter-select">
                </div>
                @endif
                
                <div class="filter-group">
                    <label class="filter-label">Report Type</label>
                    <select name="type" class="filter-select" onchange="this.form.submit()">
                        <option value="overview" {{ request('type') == 'overview' ? 'selected' : '' }}>Overview</option>
                        <option value="financial" {{ request('type') == 'financial' ? 'selected' : '' }}>Financial</option>
                        <option value="occupancy" {{ request('type') == 'occupancy' ? 'selected' : '' }}>Occupancy</option>
                        <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Student Analytics</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Key Metrics Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">₵{{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-trend {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($revenueGrowth) }}%
                </div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $occupancyRate }}%</div>
                <div class="stat-label">Occupancy Rate</div>
                <div class="stat-subtext">{{ $occupiedRooms }}/{{ $totalRooms }} rooms</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-bed"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $activeStudents }}</div>
                <div class="stat-label">Active Students</div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $newStudents }} new
                </div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">₵{{ number_format($averageRoomRate, 2) }}</div>
                <div class="stat-label">Avg. Room Rate</div>
                <div class="stat-subtext">Per academic year</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics Grid -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalRooms - $occupiedRooms - ($maintenanceRooms ?? 0) }}</div>
                <div class="stat-label">Vacant Rooms</div>
            </div>
            <div class="stat-icon gray">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $pendingBookings ?? 0 }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $maintenanceRooms ?? 0 }}</div>
                <div class="stat-label">Under Maintenance</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Detailed Reports -->
<div class="content-grid">
    <!-- Revenue Overview -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue Overview</h3>
            <div class="card-actions">
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <div class="chart-placeholder">
                    <i class="fas fa-chart-bar chart-icon"></i>
                    <p class="chart-text">Revenue chart visualization</p>
                    <p class="chart-subtext">Bar chart showing revenue trends over time</p>
                </div>
            </div>
            
            <!-- Revenue Breakdown -->
            <div class="metric-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span class="breakdown-color rent"></span>
                        Rent Income
                    </div>
                    <div class="breakdown-value">₵{{ number_format($revenueByType['rent'] ?? 0, 2) }}</div>
                    <div class="breakdown-percentage">
                        {{ $totalRevenue > 0 ? number_format(($revenueByType['rent'] / $totalRevenue) * 100, 1) : 0 }}%
                    </div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span class="breakdown-color security"></span>
                        Security Deposits
                    </div>
                    <div class="breakdown-value">₵{{ number_format($revenueByType['security'] ?? 0, 2) }}</div>
                    <div class="breakdown-percentage">
                        {{ $totalRevenue > 0 ? number_format(($revenueByType['security'] / $totalRevenue) * 100, 1) : 0 }}%
                    </div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">
                        <span class="breakdown-color other"></span>
                        Other Income
                    </div>
                    <div class="breakdown-value">₵{{ number_format($revenueByType['other'] ?? 0, 2) }}</div>
                    <div class="breakdown-percentage">
                        {{ $totalRevenue > 0 ? number_format(($revenueByType['other'] / $totalRevenue) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy Trends -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Occupancy Trends</h3>
            <div class="card-actions">
                <button class="btn btn-secondary btn-sm">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <div class="chart-placeholder">
                    <i class="fas fa-chart-pie chart-icon"></i>
                    <p class="chart-text">Occupancy chart visualization</p>
                    <p class="chart-subtext">Pie chart showing room occupancy distribution</p>
                </div>
            </div>
            
            <!-- Occupancy Stats -->
            <div class="occupancy-stats">
                <div class="occupancy-item">
                    <div class="occupancy-indicator occupied"></div>
                    <div class="occupancy-info">
                        <div class="occupancy-count">{{ $occupiedRooms }}</div>
                        <div class="occupancy-label">Occupied</div>
                    </div>
                </div>
                <div class="occupancy-item">
                    <div class="occupancy-indicator available"></div>
                    <div class="occupancy-info">
                        <div class="occupancy-count">{{ $totalRooms - $occupiedRooms - ($maintenanceRooms ?? 0) }}</div>
                        <div class="occupancy-label">Available</div>
                    </div>
                </div>
                <div class="occupancy-item">
                    <div class="occupancy-indicator maintenance"></div>
                    <div class="occupancy-info">
                        <div class="occupancy-count">{{ $maintenanceRooms ?? 0 }}</div>
                        <div class="occupancy-label">Maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performing Rooms -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Top Performing Rooms</h3>
        <div class="card-actions">
            <span class="text-sm text-gray-500">By Revenue</span>
        </div>
    </div>
    <div class="card-body">
        <div class="performance-list">
            @foreach($topRooms as $index => $room)
            <div class="performance-item">
                <div class="performance-rank">
                    <div class="rank-number">#{{ $index + 1 }}</div>
                </div>
                <div class="performance-info">
                    <div class="room-avatar">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="room-details">
                        <div class="room-number">{{ $room->room_number }}</div>
                        <div class="hostel-name">{{ $room->hostel->name ?? 'No Hostel' }}</div>
                    </div>
                </div>
                <div class="performance-metrics">
                    <div class="metric">
                        <div class="metric-value">₵{{ number_format($room->total_revenue, 2) }}</div>
                        <div class="metric-label">Revenue</div>
                    </div>
                    <div class="metric">
                        <div class="metric-value">{{ $room->occupancy_rate }}%</div>
                        <div class="metric-label">Occupancy</div>
                    </div>
                </div>
                <div class="performance-chart">
                    <div class="mini-chart">
                        <div class="chart-bar" style="height: {{ $room->occupancy_rate }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($topRooms->count() === 0)
            <div class="empty-performance">
                <i class="fas fa-chart-line empty-icon"></i>
                <p class="empty-text">No performance data available</p>
                <p class="empty-subtext">Revenue data will appear here when available</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Insights -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Insights</h3>
    </div>
    <div class="card-body">
        <div class="insights-grid">
            <div class="insight-item">
                <div class="insight-icon positive">
                    <i class="fas fa-trending-up"></i>
                </div>
                <div class="insight-content">
                    <h4 class="insight-title">Revenue Growth</h4>
                    <p class="insight-description">
                        @if($revenueGrowth > 0)
                        Revenue has increased by {{ $revenueGrowth }}% compared to the previous period.
                        @elseif($revenueGrowth < 0)
                        Revenue has decreased by {{ abs($revenueGrowth) }}% compared to the previous period.
                        @else
                        Revenue remains stable compared to the previous period.
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="insight-item">
                <div class="insight-icon {{ $occupancyRate >= 80 ? 'positive' : 'warning' }}">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="insight-content">
                    <h4 class="insight-title">Occupancy Health</h4>
                    <p class="insight-description">
                        @if($occupancyRate >= 80)
                        Excellent occupancy rate! Consider expanding capacity.
                        @elseif($occupancyRate >= 60)
                        Good occupancy rate with room for optimization.
                        @else
                        Lower occupancy detected. Review marketing strategies.
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="insight-item">
                <div class="insight-icon positive">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="insight-content">
                    <h4 class="insight-title">Student Growth</h4>
                    <p class="insight-description">
                        {{ $newStudents }} new students joined this period, bringing total active residents to {{ $activeStudents }}.
                    </p>
                </div>
            </div>
            
            <div class="insight-item">
                <div class="insight-icon {{ $averageRoomRate > 0 ? 'positive' : 'neutral' }}">
                    <i class="fas fa-cedi-sign"></i>
                </div>
                <div class="insight-content">
                    <h4 class="insight-title">Pricing Strategy</h4>
                    <p class="insight-description">
                        Average room rate is ₵{{ number_format($averageRoomRate, 2) }} per academic year. 
                        @if($averageRoomRate > 0)
                        Monitor market rates for competitive pricing.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stats Grid Base */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray);
    font-weight: 500;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-icon.blue {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.stat-icon.green {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.stat-icon.orange {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.stat-icon.purple {
    background: rgba(147, 51, 234, 0.1);
    color: #8b5cf6;
}

/* Filter Form */
.filter-form {
    width: 100%;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dark);
}

.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: white;
    transition: var(--transition);
    font-size: 0.875rem;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.mb-6 {
    margin-bottom: 1.5rem;
}

/* Reports Specific Styles */
.stat-trend {
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.25rem;
}

.stat-trend.positive {
    color: var(--success);
}

.stat-trend.negative {
    color: var(--danger);
}

.stat-subtext {
    font-size: 0.75rem;
    color: var(--gray);
    margin-top: 0.25rem;
}

.stat-icon.gray {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.stat-icon.red {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

/* Chart Styles */
.chart-container {
    height: 200px;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.chart-placeholder {
    text-align: center;
    color: var(--gray);
}

.chart-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.chart-text {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.chart-subtext {
    font-size: 0.875rem;
    opacity: 0.7;
}

/* Metric Breakdown */
.metric-breakdown {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.breakdown-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.breakdown-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: var(--dark);
}

.breakdown-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.breakdown-color.rent {
    background: var(--primary);
}

.breakdown-color.security {
    background: #8b5cf6;
}

.breakdown-color.other {
    background: var(--warning);
}

.breakdown-value {
    font-weight: 600;
    color: var(--dark);
}

.breakdown-percentage {
    font-size: 0.875rem;
    color: var(--gray);
    min-width: 3rem;
    text-align: right;
}

/* Occupancy Stats */
.occupancy-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.occupancy-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.occupancy-indicator {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}

.occupancy-indicator.occupied {
    background: var(--success);
}

.occupancy-indicator.available {
    background: var(--primary);
}

.occupancy-indicator.maintenance {
    background: var(--warning);
}

.occupancy-info {
    display: flex;
    flex-direction: column;
}

.occupancy-count {
    font-weight: 700;
    color: var(--dark);
    font-size: 1.25rem;
}

.occupancy-label {
    font-size: 0.75rem;
    color: var(--gray);
}

/* Performance List */
.performance-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.performance-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.performance-item:hover {
    background: var(--gray-100);
    transform: translateY(-1px);
}

.performance-rank {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
}

.rank-number {
    width: 2rem;
    height: 2rem;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.performance-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.room-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.room-details {
    display: flex;
    flex-direction: column;
}

.room-number {
    font-weight: 600;
    color: var(--dark);
}

.hostel-name {
    font-size: 0.75rem;
    color: var(--gray);
}

.performance-metrics {
    display: flex;
    gap: 2rem;
}

.metric {
    text-align: center;
}

.metric-value {
    font-weight: 700;
    color: var(--dark);
    font-size: 1.125rem;
}

.metric-label {
    font-size: 0.75rem;
    color: var(--gray);
}

.performance-chart {
    width: 4rem;
    height: 2rem;
}

.mini-chart {
    width: 100%;
    height: 100%;
    background: var(--gray-200);
    border-radius: 1rem;
    position: relative;
    overflow: hidden;
}

.chart-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--primary);
    transition: height 0.3s ease;
}

.empty-performance {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--gray);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-text {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-subtext {
    font-size: 0.875rem;
    opacity: 0.7;
}

/* Insights Grid */
.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.insight-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary);
}

.insight-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.insight-icon.positive {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.insight-icon.warning {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.insight-icon.neutral {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.insight-content {
    flex: 1;
}

.insight-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.insight-description {
    color: var(--gray);
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .performance-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .performance-metrics {
        justify-content: center;
    }
    
    .insights-grid {
        grid-template-columns: 1fr;
    }
    
    .occupancy-stats {
        grid-template-columns: 1fr;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Simple animation for chart bars
document.addEventListener('DOMContentLoaded', function() {
    const chartBars = document.querySelectorAll('.chart-bar');
    chartBars.forEach(bar => {
        const height = bar.style.height;
        bar.style.height = '0%';
        setTimeout(() => {
            bar.style.height = height;
        }, 300);
    });
});

// Filter form enhancement
document.addEventListener('DOMContentLoaded', function() {
    const rangeSelect = document.querySelector('select[name="range"]');
    const customDateFields = document.querySelectorAll('input[type="date"]');
    
    function toggleCustomDates() {
        const customDateContainer = document.querySelector('.filter-grid');
        if (rangeSelect.value === 'custom') {
            customDateFields.forEach(field => field.style.display = 'block');
        } else {
            customDateFields.forEach(field => field.style.display = 'none');
        }
    }
    
    rangeSelect.addEventListener('change', toggleCustomDates);
    toggleCustomDates(); // Initial call
});
</script>
@endsection
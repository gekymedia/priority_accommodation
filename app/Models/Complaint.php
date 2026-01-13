<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Traits\Auditable;

class Complaint extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'student_id',
        'booking_id',
        'room_id',
        'hostel_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'admin_response',
        'assigned_to',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';
    const STATUS_REJECTED = 'rejected';

    // Category constants
    const CATEGORY_MAINTENANCE = 'maintenance';
    const CATEGORY_NOISE = 'noise';
    const CATEGORY_SECURITY = 'security';
    const CATEGORY_CLEANLINESS = 'cleanliness';
    const CATEGORY_UTILITIES = 'utilities';
    const CATEGORY_ROOMMATE = 'roommate';
    const CATEGORY_OTHER = 'other';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function getCategories()
    {
        return [
            self::CATEGORY_MAINTENANCE => 'Maintenance',
            self::CATEGORY_NOISE => 'Noise',
            self::CATEGORY_SECURITY => 'Security',
            self::CATEGORY_CLEANLINESS => 'Cleanliness',
            self::CATEGORY_UTILITIES => 'Utilities',
            self::CATEGORY_ROOMMATE => 'Roommate Issue',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        ];
    }

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_IN_PROGRESS => 'info',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_CLOSED => 'secondary',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'secondary',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
            default => 'info',
        };
    }

    public function getIsResolvedAttribute()
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function getIsPendingAttribute()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getDaysOpenAttribute()
    {
        return $this->resolved_at 
            ? $this->created_at->diffInDays($this->resolved_at)
            : $this->created_at->diffInDays(now());
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', self::PRIORITY_URGENT);
    }

    // Business logic methods
    public function markAsInProgress(?int $assignedTo = null)
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'assigned_to' => $assignedTo ?? $this->assigned_to,
        ]);
    }

    public function markAsResolved(string $response, ?int $resolvedBy = null)
    {
        $this->update([
            'status' => self::STATUS_RESOLVED,
            'admin_response' => $response,
            'resolved_by' => $resolvedBy,
            'resolved_at' => now(),
        ]);
    }

    public function markAsClosed()
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'resolved_at' => $this->resolved_at ?? now(),
        ]);
    }

    public function reject(string $reason)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'admin_response' => $reason,
        ]);
    }
}

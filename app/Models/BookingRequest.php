<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingRequest extends Model
{
    protected $fillable = [
        'request_token',
        'student_id',
        'room_id',
        'hostel_id',
        'beds_requested',
        'check_in_date',
        'check_out_date',
        'academic_year',
        'semester',
        'base_price',
        'commission_amount',
        'total_amount',
        'status',
        'payment_initiated_at',
        'payment_completed_at',
        'confirmation_sent_at',
        'confirmation_deadline',
        'owner_responded_at',
        'expires_at',
        'rejection_reason',
        'responded_by',
        'payment_reference',
        'payment_provider',
        'payment_response',
        'owner_notified',
        'student_notified',
        'admin_notified',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'base_price' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_initiated_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'confirmation_sent_at' => 'datetime',
        'confirmation_deadline' => 'datetime',
        'owner_responded_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_response' => 'array',
        'owner_notified' => 'boolean',
        'student_notified' => 'boolean',
        'admin_notified' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->request_token) {
                $model->request_token = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    // Status checks
    public function isPending(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function isPaymentProcessing(): bool
    {
        return $this->status === 'payment_processing';
    }

    public function isAwaitingConfirmation(): bool
    {
        return $this->status === 'awaiting_confirmation';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isTimedOut(): bool
    {
        return $this->status === 'timeout';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Check if confirmation deadline has passed
     */
    public function hasConfirmationExpired(): bool
    {
        return $this->confirmation_deadline && now()->isAfter($this->confirmation_deadline);
    }

    /**
     * Get remaining time for confirmation
     */
    public function getConfirmationRemainingSeconds(): int
    {
        if (!$this->confirmation_deadline) {
            return 0;
        }

        $remaining = now()->diffInSeconds($this->confirmation_deadline, false);
        return max(0, $remaining);
    }

    /**
     * Confirm the booking request
     */
    public function confirm(User $user = null): bool
    {
        if (!$this->isAwaitingConfirmation()) {
            return false;
        }

        $this->update([
            'status' => 'confirmed',
            'owner_responded_at' => now(),
            'responded_by' => $user?->id,
        ]);

        return true;
    }

    /**
     * Reject the booking request
     */
    public function reject(string $reason, User $user = null): bool
    {
        if (!$this->isAwaitingConfirmation()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'owner_responded_at' => now(),
            'responded_by' => $user?->id,
        ]);

        return true;
    }

    /**
     * Mark as timed out (no response from hostel owner)
     */
    public function markAsTimedOut(): void
    {
        $this->update(['status' => 'timeout']);
    }

    // Scopes
    public function scopePendingConfirmation($query)
    {
        return $query->where('status', 'awaiting_confirmation')
            ->where('confirmation_deadline', '>', now());
    }

    public function scopeExpiredConfirmation($query)
    {
        return $query->where('status', 'awaiting_confirmation')
            ->where('confirmation_deadline', '<=', now());
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_payment' => 'Pending Payment',
            'payment_processing' => 'Processing Payment',
            'awaiting_confirmation' => 'Awaiting Confirmation',
            'confirmed' => 'Confirmed',
            'rejected' => 'Rejected',
            'timeout' => 'Timed Out',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending_payment' => 'warning',
            'payment_processing' => 'info',
            'awaiting_confirmation' => 'primary',
            'confirmed' => 'success',
            'rejected', 'timeout', 'expired' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }
}


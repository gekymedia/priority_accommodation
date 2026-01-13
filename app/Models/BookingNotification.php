<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingNotification extends Model
{
    protected $fillable = [
        'booking_request_id',
        'booking_id',
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'send_push',
        'send_sms',
        'send_email',
        'push_sent',
        'sms_sent',
        'email_sent',
        'push_sent_at',
        'sms_sent_at',
        'email_sent_at',
        'is_read',
        'read_at',
        'action_url',
        'action_taken',
        'action_taken_at',
    ];

    protected $casts = [
        'data' => 'array',
        'send_push' => 'boolean',
        'send_sms' => 'boolean',
        'send_email' => 'boolean',
        'push_sent' => 'boolean',
        'sms_sent' => 'boolean',
        'email_sent' => 'boolean',
        'is_read' => 'boolean',
        'action_taken' => 'boolean',
        'push_sent_at' => 'datetime',
        'sms_sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'read_at' => 'datetime',
        'action_taken_at' => 'datetime',
    ];

    // Relationships
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markActionTaken(): void
    {
        if (!$this->action_taken) {
            $this->update([
                'action_taken' => true,
                'action_taken_at' => now(),
            ]);
        }
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'booking_request' => 'fa-calendar-plus',
            'payment_received' => 'fa-money-bill-wave',
            'confirmation_required' => 'fa-clock',
            'booking_confirmed' => 'fa-check-circle',
            'booking_rejected' => 'fa-times-circle',
            'booking_timeout' => 'fa-hourglass-end',
            'roommate_joined' => 'fa-user-plus',
            'payment_transferred' => 'fa-exchange-alt',
            'reminder' => 'fa-bell',
            default => 'fa-info-circle',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->type) {
            'booking_confirmed', 'payment_received', 'payment_transferred' => 'success',
            'booking_rejected', 'booking_timeout' => 'danger',
            'confirmation_required', 'booking_request' => 'warning',
            'roommate_joined' => 'info',
            default => 'primary',
        };
    }
}


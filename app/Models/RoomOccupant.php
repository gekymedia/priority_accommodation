<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomOccupant extends Model
{
    protected $fillable = [
        'room_id',
        'student_id',
        'booking_id',
        'name',
        'phone',
        'email',
        'student_id_number',
        'profile_picture',
        'bed_number',
        'move_in_date',
        'move_out_date',
        'academic_year',
        'semester',
        'status',
        'is_system_booking',
        'visible_to_roommates',
        'added_by',
        'notes',
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'is_system_booking' => 'boolean',
        'visible_to_roommates' => 'boolean',
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function addedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        // If linked to a student in the system
        if ($this->student && $this->student->user) {
            return $this->student->user->name;
        }
        
        // Use manually entered name
        return $this->name ?? 'Unknown';
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        // If linked to a student in the system
        if ($this->student && $this->student->user && $this->student->user->profile_picture) {
            return asset('storage/' . $this->student->user->profile_picture);
        }

        // Use manually uploaded picture
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        return null;
    }

    public function getContactPhoneAttribute(): ?string
    {
        if ($this->student && $this->student->user) {
            return $this->student->user->phone;
        }
        return $this->phone;
    }

    public function getContactEmailAttribute(): ?string
    {
        if ($this->student && $this->student->user) {
            return $this->student->user->email;
        }
        return $this->email;
    }

    /**
     * Get info that's safe to share with roommates
     */
    public function getRoommateVisibleInfoAttribute(): array
    {
        if (!$this->visible_to_roommates) {
            return [
                'name' => 'Roommate',
                'profile_picture' => null,
                'hidden' => true,
            ];
        }

        return [
            'name' => $this->display_name,
            'profile_picture' => $this->profile_picture_url,
            'programme' => $this->student?->user?->programme,
            'level' => $this->student?->user?->level,
            'bed_number' => $this->bed_number,
            'hidden' => false,
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSystemBookings($query)
    {
        return $query->where('is_system_booking', true);
    }

    public function scopeManualEntries($query)
    {
        return $query->where('is_system_booking', false);
    }

    public function scopeVisibleToRoommates($query)
    {
        return $query->where('visible_to_roommates', true);
    }

    // Status checks
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }
}


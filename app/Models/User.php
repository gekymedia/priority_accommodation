<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        "name", 
        "email", 
        "phone", 
        "password", 
        "role",
        "app_id", // CUG Application ID for SSO
        "address",
        "bio",
        "profile_picture",
        "timezone",
        "language",
        "notifications",
        "email_notifications",
        "sms_notifications",
        // Hostel owner fields
        "hostel_name",
        "hostel_type", 
        "capacity",
        "country",
        // Student fields
        "student_id_number",
        "programme",
        "level",
        "is_cug_verified",
        "cug_verified_at",
        "profile_complete",
        "emergency_contact_name",
        "emergency_contact_phone",
        "is_hostel_owner",
        "receive_booking_notifications",
    ];

    protected $hidden = [
        "password", 
        "remember_token"
    ];

    protected $casts = [
        'notifications' => 'boolean',
        'email_notifications' => 'array',
        'sms_notifications' => 'array',
        'capacity' => 'integer',
        'email_verified_at' => 'datetime',
        'is_cug_verified' => 'boolean',
        'cug_verified_at' => 'datetime',
        'profile_complete' => 'boolean',
        'is_hostel_owner' => 'boolean',
        'receive_booking_notifications' => 'boolean',
    ];

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class, 'owner_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(BookingNotification::class);
    }

    // Helper methods
    public function isHostelOwner(): bool
    {
        return $this->is_hostel_owner || $this->hostels()->exists();
    }

    public function isProfileComplete(): bool
    {
        // For CUG verified users, profile is complete
        if ($this->is_cug_verified) {
            return true;
        }

        // For non-CUG users, must have profile picture
        return $this->profile_picture !== null;
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
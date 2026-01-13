<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Traits\Auditable;

class Student extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'student_id',
        'university',
        'course',
        'year_of_study', // Use this instead of year_level
        'date_of_birth',
        'address',
        'emergency_contact_name', // Use this instead of emergency_contact
        'emergency_contact_phone', // Use this instead of emergency_phone
        'id_proof',
        'photo',
        'department'
        // Remove 'year_level', 'emergency_contact', 'emergency_phone' from fillable
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->latest();
    }

    // Accessors - FIXED: All date methods now use Carbon::parse()
    public function getIsActiveAttribute()
    {
        return $this->currentBooking !== null;
    }

    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        // Convert to Carbon instance and calculate age manually
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getCurrentRoomAttribute()
    {
        return $this->currentBooking?->room;
    }

    public function getStatusAttribute()
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'secondary';
    }

    // Additional helpful accessors - FIXED: format() method
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->format('M d, Y') : 'N/A';
    }

    public function getIsAdultAttribute()
    {
        return $this->age >= 18;
    }

    public function getYearLevelAttribute()
    {
        return "Year {$this->year_of_study}";
    }

    // Scopes - FIXED: Date comparisons in scopes
    public function scopeActive($query)
    {
        return $query->has('currentBooking');
    }

    public function scopeInactive($query)
    {
        return $query->doesntHave('currentBooking');
    }

    public function scopeByUniversity($query, $university)
    {
        return $query->where('university', 'like', "%{$university}%");
    }

    public function scopeMinors($query)
    {
        return $query->where('date_of_birth', '>', Carbon::now()->subYears(18));
    }

    public function scopeAdults($query)
    {
        return $query->where('date_of_birth', '<=', Carbon::now()->subYears(18));
    }

    // Business logic methods
    public function hasActiveBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function getBookingHistory()
    {
        return $this->bookings()
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTotalAmountPaid()
    {
        return $this->bookings()
            ->withSum('payments', 'amount')
            ->get()
            ->sum('payments_sum_amount');
    }

    // Additional helper methods
    public function getNextOfKinAttribute()
    {
        return $this->emergency_contact_name . ' (' . $this->emergency_contact_phone . ')';
    }

    public function getAcademicInfoAttribute()
    {
        return "{$this->course} - {$this->year_level}";
    }
}
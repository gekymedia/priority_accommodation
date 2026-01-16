<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Room extends Model
{
    use HasFactory, Auditable;

    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied'; 
    const STATUS_MAINTENANCE = 'maintenance';

    const TYPE_SINGLE = 'single';
    const TYPE_DOUBLE = 'double';
    const TYPE_SUITE = 'suite';

    protected $fillable = [
        'hostel_id',
        'room_number', 
        'type',
        'capacity',
        'price_per_academic_year',
        'description',
        'status',
        'available',
        'video_url',
        'photos',
        'features'
    ];

    protected $casts = [
        'available' => 'boolean',
        'photos' => 'array',
        'features' => 'array',
        'price_per_academic_year' => 'float'
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_OCCUPIED => 'Occupied',
            self::STATUS_MAINTENANCE => 'Maintenance'
        ];
    }

    public static function getTypes()
    {
        return [
            self::TYPE_SINGLE => 'Single',
            self::TYPE_DOUBLE => 'Double', 
            self::TYPE_SUITE => 'Suite'
        ];
    }

    // Relationships
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function occupants(): HasMany
    {
        return $this->hasMany(RoomOccupant::class);
    }

    public function activeOccupants(): HasMany
    {
        return $this->occupants()->where('status', 'active');
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function pendingRequests(): HasMany
    {
        return $this->bookingRequests()->whereIn('status', ['pending_payment', 'payment_processing', 'awaiting_confirmation']);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('available', true)
                    ->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Check if room is available for booking
     */
    public function isAvailable()
    {
        return $this->available && $this->status === self::STATUS_AVAILABLE;
    }

    // Accessors
    public function getIsAvailableAttribute()
    {
        return $this->isAvailable();
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->price_per_academic_year ? (float) $this->price_per_academic_year : 0.0;
        return '₵' . number_format($price, 2);
    }

    public function getFeaturesListAttribute()
    {
        if (empty($this->features)) {
            return [];
        }
        return $this->features;
    }

    // Mutator to ensure price is stored as proper decimal
    public function setPricePerAcademicYearAttribute($value)
    {
        $this->attributes['price_per_academic_year'] = is_numeric($value) ? $value : 0;
    }

    /**
     * Get number of beds available for booking
     */
    public function getBedsAvailableAttribute(): int
    {
        $totalBeds = $this->capacity ?? 1;
        $occupied = $this->current_occupants ?? 0;
        return max(0, $totalBeds - $occupied);
    }

    /**
     * Check if room has space for more occupants
     */
    public function hasAvailableBeds(int $bedsNeeded = 1): bool
    {
        return $this->beds_available >= $bedsNeeded;
    }

    /**
     * Check if room is fully occupied
     */
    public function isFullyOccupied(): bool
    {
        return $this->beds_available <= 0;
    }

    /**
     * Get active roommates (for showing to other students)
     */
    public function getRoommatesAttribute()
    {
        return $this->activeOccupants()
            ->where('visible_to_roommates', true)
            ->get()
            ->map(fn($occupant) => $occupant->roommate_visible_info);
    }

    /**
     * Calculate price with commission
     */
    public function calculatePriceWithCommission(?float $commissionPercentage = null): array
    {
        $basePrice = $this->base_price ?? $this->price_per_academic_year;
        $commission = $commissionPercentage ?? $this->hostel?->commission_percentage ?? 10;
        $commissionAmount = ($basePrice * $commission) / 100;
        
        return [
            'base_price' => $basePrice,
            'commission_percentage' => $commission,
            'commission_amount' => round($commissionAmount, 2),
            'total_price' => round($basePrice + $commissionAmount, 2),
        ];
    }

    /**
     * Get price per bed if shared room
     */
    public function getPricePerBedAttribute(): float
    {
        $capacity = $this->capacity ?? 1;
        return round($this->price_per_academic_year / $capacity, 2);
    }

    /**
     * Update occupancy count
     */
    public function updateOccupancyCount(): void
    {
        $count = $this->activeOccupants()->count();
        $this->update(['current_occupants' => $count]);

        // Update status based on occupancy
        if ($count >= $this->capacity) {
            $this->update(['status' => self::STATUS_OCCUPIED]);
        } elseif ($this->status === self::STATUS_OCCUPIED && $count < $this->capacity) {
            $this->update(['status' => self::STATUS_AVAILABLE]);
        }
    }

    /**
     * Get image URLs
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }

        return array_map(fn($photo) => asset('storage/' . $photo), $this->photos);
    }
}
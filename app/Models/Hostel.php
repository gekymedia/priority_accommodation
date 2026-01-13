<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Hostel extends Model
{
    use HasFactory;

    const TYPE_BOYS = 'boys';
    const TYPE_GIRLS = 'girls';
    const TYPE_MIXED = 'mixed';

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'address',
        'contact_phone',
        'contact_email',
        'description',
        'amenities',
        'images',
        'videos',
        'cover_image',
        'latitude',
        'longitude',
        'google_maps_url',
        'walking_time_minutes',
        'driving_time_minutes',
        'distance_km',
        'has_partnership_agreement',
        'partnership_start_date',
        'partnership_end_date',
        'commission_percentage',
        'hostel_type',
        'total_capacity',
        'rules',
        'nearby_landmarks',
        'is_active'
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'nearby_landmarks' => 'array',
        'is_active' => 'boolean',
        'has_partnership_agreement' => 'boolean',
        'partnership_start_date' => 'date',
        'partnership_end_date' => 'date',
        'commission_percentage' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distance_km' => 'decimal:2',
    ];

    // Route key for slug-based routing
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Auto-generate slug on create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hostel) {
            if (empty($hostel->slug)) {
                $hostel->slug = $hostel->generateUniqueSlug($hostel->name);
            }
        });

        static::updating(function ($hostel) {
            if ($hostel->isDirty('name') && empty($hostel->slug)) {
                $hostel->slug = $hostel->generateUniqueSlug($hostel->name);
            }
        });
    }

    // Generate unique slug
    public function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    // Owner relationship
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Get hostel types
    public static function getTypes(): array
    {
        return [
            self::TYPE_BOYS => 'Boys Only',
            self::TYPE_GIRLS => 'Girls Only',
            self::TYPE_MIXED => 'Mixed',
        ];
    }

    // Check if hostel has valid partnership
    public function hasValidPartnership(): bool
    {
        if (!$this->has_partnership_agreement) {
            return false;
        }

        if ($this->partnership_end_date && $this->partnership_end_date->isPast()) {
            return false;
        }

        return true;
    }

    // Get distance display
    public function getDistanceDisplayAttribute(): string
    {
        if ($this->walking_time_minutes && $this->walking_time_minutes <= 30) {
            return "{$this->walking_time_minutes} min walk";
        }

        if ($this->driving_time_minutes) {
            return "{$this->driving_time_minutes} min drive";
        }

        if ($this->distance_km) {
            return "{$this->distance_km} km away";
        }

        return 'Distance not available';
    }

    // Get cover image URL
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        // Use first image as cover if no specific cover
        if ($this->images && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }

        return asset('images/hostel-placeholder.jpg');
    }

    // Get all image URLs
    public function getImageUrlsAttribute(): array
    {
        if (!$this->images) {
            return [];
        }

        return array_map(fn($img) => asset('storage/' . $img), $this->images);
    }

    // Get beds available count
    public function getBedsAvailableAttribute(): int
    {
        return $this->rooms()
            ->where('status', Room::STATUS_AVAILABLE)
            ->sum(\DB::raw('beds_count - current_occupants'));
    }

    // Add the active scope method
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relationships
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function availableRooms(): HasMany
    {
        return $this->rooms()->where('available', true)->where('status', Room::STATUS_AVAILABLE);
    }

    public function occupiedRooms(): HasMany
    {
        return $this->rooms()->where('status', Room::STATUS_OCCUPIED);
    }

    public function maintenanceRooms(): HasMany
    {
        return $this->rooms()->where('status', Room::STATUS_MAINTENANCE);
    }

    public function getOccupancyRateAttribute()
    {
        $totalRooms = $this->rooms()->count();
        $occupiedRooms = $this->occupiedRooms()->count();
        
        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }

    public function getTotalRevenueAttribute()
    {
        return $this->rooms()->withSum('bookings', 'total_amount')->get()->sum('bookings_sum_total_amount');
    }

    // Additional helpful methods
    public function getTotalRoomsAttribute()
    {
        return $this->rooms()->count();
    }

    public function getAvailableRoomsCountAttribute()
    {
        return $this->availableRooms()->count();
    }

    public function getOccupiedRoomsCountAttribute()
    {
        return $this->occupiedRooms()->count();
    }

    public function getMaintenanceRoomsCountAttribute()
    {
        return $this->maintenanceRooms()->count();
    }

    // Accessor for formatted contact info
    public function getFormattedContactAttribute()
    {
        $contact = [];
        if ($this->contact_phone) {
            $contact[] = "📞 " . $this->contact_phone;
        }
        if ($this->contact_email) {
            $contact[] = "✉️ " . $this->contact_email;
        }
        return implode(' | ', $contact);
    }

    // Check if hostel has available rooms
    public function getHasAvailableRoomsAttribute()
    {
        return $this->available_rooms_count > 0;
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Traits\Auditable;

class Booking extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'student_id',
        'room_id',
        'check_in',
        'check_out',
        'semesters',
        'status',
        'total_amount',
        'advance_paid',
        'special_requirements',
        'actual_check_in',
        'actual_check_out'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime',
        'total_amount' => 'decimal:2',
        'advance_paid' => 'decimal:2'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_CHECKED_OUT = 'checked_out';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CHECKED_IN => 'Checked In',
            self::STATUS_CHECKED_OUT => 'Checked Out',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
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

    // ADD THIS PAYMENTS RELATIONSHIP
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors - FIXED: Convert dates to Carbon instances for diffInDays
    public function getDurationAttribute()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);
            return $checkIn->diffInDays($checkOut);
        }
        return 0;
    }

    public function getBalanceDueAttribute()
    {
        return $this->total_amount - $this->advance_paid;
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_CHECKED_IN]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_CHECKED_IN => 'success',
            self::STATUS_CHECKED_OUT => 'secondary',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    // New accessors for the additional fields
    public function getDaysRemainingAttribute()
    {
        if ($this->check_out && $this->is_active) {
            $checkOut = Carbon::parse($this->check_out);
            return now()->diffInDays($checkOut, false);
        }
        return null;
    }

    public function getIsOverdueAttribute()
    {
        return $this->days_remaining < 0;
    }

    public function getFormattedCheckInAttribute()
    {
        return $this->check_in ? Carbon::parse($this->check_in)->format('M d, Y') : 'N/A';
    }

    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out ? Carbon::parse($this->check_out)->format('M d, Y') : 'N/A';
    }

    public function getFormattedActualCheckInAttribute()
    {
        return $this->actual_check_in ? $this->actual_check_in->format('M d, Y h:i A') : 'N/A';
    }

    public function getFormattedActualCheckOutAttribute()
    {
        return $this->actual_check_out ? $this->actual_check_out->format('M d, Y h:i A') : 'N/A';
    }

    // Payment-related accessors
    public function getTotalPaidAttribute()
    {
        return $this->payments()->completed()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->total_amount <= 0) return 0;
        return min(100, ($this->total_paid / $this->total_amount) * 100);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', self::STATUS_CHECKED_IN);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_CHECKED_IN]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('check_in', '>', now());
    }

    public function scopeCurrent($query)
    {
        return $query->where('check_in', '<=', now())
                    ->where('check_out', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('check_out', '<', now())
                    ->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_CHECKED_IN]);
    }

    // Business logic methods
    public function markAsConfirmed()
    {
        $this->update(['status' => self::STATUS_CONFIRMED]);
    }

    public function markAsCheckedIn()
    {
        $this->update([
            'status' => self::STATUS_CHECKED_IN,
            'actual_check_in' => now()
        ]);
    }

    public function markAsCheckedOut()
    {
        $this->update([
            'status' => self::STATUS_CHECKED_OUT,
            'actual_check_out' => now()
        ]);
    }

    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function calculateTotalAmount()
    {
        if ($this->room && $this->check_in && $this->check_out) {
            $duration = Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
            // Calculate based on academic year pricing
            if ($this->room && $this->check_in && $this->check_out) {
                $checkIn = Carbon::parse($this->check_in);
                $checkOut = Carbon::parse($this->check_out);
                $durationInDays = $checkIn->diffInDays($checkOut);
                $daysInYear = 365;
                
                // Calculate proportional price for the duration
                $academicYearPrice = $this->room->price_per_academic_year ?? 0;
                return ($academicYearPrice / $daysInYear) * $durationInDays;
            }
            return 0;
        }
        return 0;
    }

    // Payment methods
    public function addPayment($amount, $paymentMethod, $type = 'rent', $description = null)
    {
        return $this->payments()->create([
            'student_id' => $this->student_id,
            'receipt_number' => 'PAY-' . now()->format('YmdHis') . '-' . $this->id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'type' => $type,
            'status' => Payment::STATUS_COMPLETED,
            'payment_date' => now(),
            'description' => $description
        ]);
    }

    public function hasOutstandingBalance()
    {
        return $this->remaining_balance > 0;
    }

    // Helper method to check if dates are valid
    public function isValidDuration()
    {
        if (!$this->check_in || !$this->check_out) {
            return false;
        }
        
        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);
        
        return $checkOut->greaterThan($checkIn);
    }

    // Method to get semester-based duration
    public function getSemesterDurationAttribute()
    {
        return $this->semesters;
    }
}
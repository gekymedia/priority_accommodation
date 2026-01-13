<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Traits\Auditable;

class Payment extends Model
{
    use HasFactory, Auditable;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const TYPE_RENT = 'rent';
    const TYPE_SECURITY = 'security';
    const TYPE_MAINTENANCE = 'maintenance';
    const TYPE_OTHER = 'other';

    const METHOD_CASH = 'cash';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_UPI = 'upi';
    const METHOD_CARD = 'card';

    protected $fillable = [
        'booking_id',
        'student_id',
        'receipt_number',
        'amount',
        'payment_method',
        'type',
        'status',
        'payment_date',
        'description',
        'transaction_id',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'metadata' => 'array'
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    public static function getTypes()
    {
        return [
            self::TYPE_RENT => 'Rent',
            self::TYPE_SECURITY => 'Security Deposit',
            self::TYPE_MAINTENANCE => 'Maintenance',
            self::TYPE_OTHER => 'Other',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::METHOD_CASH => 'Cash',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_UPI => 'UPI',
            self::METHOD_CARD => 'Credit/Debit Card',
        ];
    }

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
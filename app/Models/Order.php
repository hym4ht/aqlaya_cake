<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_AWAITING_CONFIRMATION = 'awaiting_confirmation';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_READY = 'ready';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'order_code',
        'status',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'scheduled_for',
        'message_on_cake',
        'order_notes',
        'subtotal',
        'total_amount',
        'rejection_reason',
        'midtrans_reference',
        'paid_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_AWAITING_CONFIRMATION => 'Menunggu Konfirmasi',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_READY => 'Siap Diambil',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function statusLabelFor(string $status): string
    {
        return self::statusOptions()[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public function statusLabel(): string
    {
        return self::statusLabelFor($this->status);
    }

    public static function paymentOptions(): array
    {
        return [
            self::PAYMENT_UNPAID => 'Belum Dibayar',
            self::PAYMENT_PAID => 'Sudah Dibayar',
            self::PAYMENT_REFUNDED => 'Dana Dikembalikan',
        ];
    }

    public static function paymentLabelFor(string $paymentStatus): string
    {
        return self::paymentOptions()[$paymentStatus] ?? ucfirst(str_replace('_', ' ', $paymentStatus));
    }

    public function paymentLabel(): string
    {
        return self::paymentLabelFor($this->payment_status);
    }
}

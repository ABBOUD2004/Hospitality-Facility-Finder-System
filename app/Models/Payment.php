<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'status',
        'payment_method',
        'transaction_id',
        'payment_gateway',
        'currency',
        'guest_name',
        'guest_email',
        'guest_phone',
        'notes',
        'failure_reason',
        'refund_reason',
        'paid_at',
        'refunded_at',
        'failed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'failed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'notes',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate transaction ID
        static::creating(function ($payment) {
            if (empty($payment->transaction_id)) {
                $payment->transaction_id = 'TXN-' . strtoupper(uniqid());
            }
        });

        // Set timestamps based on status
        static::updating(function ($payment) {
            if ($payment->isDirty('status')) {
                switch ($payment->status) {
                    case 'completed':
                        $payment->paid_at = $payment->paid_at ?? now();
                        break;
                    case 'failed':
                        $payment->failed_at = $payment->failed_at ?? now();
                        break;
                    case 'refunded':
                        $payment->refunded_at = $payment->refunded_at ?? now();
                        break;
                }
            }
        });
    }

    /**
     * Get the booking that owns the payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include refunded payments.
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    /**
     * Scope a query to filter by payment method.
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is refunded.
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Mark payment as completed.
     */
    public function markAsCompleted(): bool
    {
        $this->status = 'completed';
        $this->paid_at = now();
        return $this->save();
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(string $reason = null): bool
    {
        $this->status = 'failed';
        $this->failed_at = now();
        if ($reason) {
            $this->failure_reason = $reason;
        }
        return $this->save();
    }

    /**
     * Mark payment as refunded.
     */
    public function markAsRefunded(string $reason = null): bool
    {
        $this->status = 'refunded';
        $this->refunded_at = now();
        if ($reason) {
            $this->refund_reason = $reason;
        }
        return $this->save();
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get customer name (guest or user).
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->guest_name ?? $this->user->name ?? 'N/A';
    }

    /**
     * Get customer email (guest or user).
     */
    public function getCustomerEmailAttribute(): string
    {
        return $this->guest_email ?? $this->user->email ?? 'N/A';
    }

    /**
     * Get customer phone (guest or user).
     */
    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->guest_phone ?? $this->user->phone ?? null;
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get payment method icon.
     */
    public function getPaymentMethodIconAttribute(): string
    {
        return match($this->payment_method) {
            'credit_card', 'debit_card' => 'credit-card',
            'mobile_wallet' => 'smartphone',
            'cash' => 'dollar-sign',
            'bank_transfer' => 'briefcase',
            default => 'help-circle',
        };
    }

    /**
     * Get formatted payment method.
     */
    public function getFormattedPaymentMethodAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->payment_method ?? 'N/A'));
    }
}

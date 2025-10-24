<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'facility_id',
        'room_id',
        'booking_type',
        'booking_reference',
        'guest_name',
        'guest_firstname',
        'guest_lastname',
        'guest_email',
        'guest_phone',
        'check_in_date',
        'check_out_date',
        'checkin_date',
        'checkout_date',
        'nights',
        'reservation_date',
        'reservation_time',
        'adults',
        'children',
        'number_of_guests',
        'pickup_location',
        'destination',
        'special_requests',
        'special_instructions',
        'total_price',
        'total_price_rwf',
        'total_price_usd',
        'payment_method',
        'payment_phone',
        'payment_details',
        'transaction_id',
        'payment_status',
        'status',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'admin_notes',
        'order_items',
        'rating',
        'review',
        'cancelled_reason',
        'refund_amount',
        'refund_status',
    ];

    protected $casts = [
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
        'checkin_date' => 'datetime',
        'checkout_date' => 'datetime',
        'reservation_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'order_items' => 'array',
        'payment_details' => 'array',
        'adults' => 'integer',
        'children' => 'integer',
        'nights' => 'integer',
        'number_of_guests' => 'integer',
        'total_price' => 'decimal:2',
        'total_price_rwf' => 'decimal:2',
        'total_price_usd' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'rating' => 'integer',
    ];

    protected $appends = [
        'full_guest_name',
        'check_in',
        'check_out',
        'total_price_formatted',
        'status_color',
        'status_badge',
        'payment_status_color',
        'payment_status_badge',
        'total_guests',
        'duration',
        'is_refundable',
        'days_until_checkin',
        'order_summary',
    ];

    // ==================== Relationships ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Facility::class, 'facility_id')->where('type', 'restaurant');
    }

    public function coffeeShop()
    {
        return $this->belongsTo(Facility::class, 'facility_id')->where('type', 'coffee');
    }

    // public function transport()
    // {
    //     return $this->belongsTo(Facility::class, 'facility_id')->where('type', 'transport');
    // }

    // ==================== Accessors ====================

    public function getFullGuestNameAttribute()
    {
        if ($this->guest_firstname && $this->guest_lastname) {
            return "{$this->guest_firstname} {$this->guest_lastname}";
        }
        return $this->guest_name ?? 'Guest';
    }

    public function getCheckInAttribute()
    {
        return $this->check_in_date ?? $this->checkin_date;
    }

    public function getCheckOutAttribute()
    {
        return $this->check_out_date ?? $this->checkout_date;
    }

    public function getTotalPriceFormattedAttribute()
    {
        $priceRwf = $this->total_price ?? $this->total_price_rwf ?? 0;
        $priceUsd = $this->total_price_usd ?? 0;

        if ($priceUsd > 0) {
            return number_format($priceRwf, 0) . ' RWF / $' . number_format($priceUsd, 2);
        }

        return number_format($priceRwf, 0) . ' RWF';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
            'completed' => 'blue',
            'checked_in' => 'indigo',
            'checked_out' => 'purple',
            'in_progress' => 'cyan',
            'ready' => 'teal',
            default => 'gray',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'confirmed' => '✅ Confirmed',
            'pending' => '⏳ Pending',
            'cancelled' => '❌ Cancelled',
            'completed' => '✔️ Completed',
            'checked_in' => '🔑 Checked In',
            'checked_out' => '🚪 Checked Out',
            'in_progress' => '🔄 In Progress',
            'ready' => '🍽️ Ready',
            default => '❓ Unknown',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'refunded' => 'blue',
            'partially_paid' => 'orange',
            'processing' => 'indigo',
            default => 'gray',
        };
    }

    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => '💳 Paid',
            'pending' => '⏳ Pending Payment',
            'failed' => '❌ Payment Failed',
            'refunded' => '↩️ Refunded',
            'partially_paid' => '💰 Partially Paid',
            'processing' => '🔄 Processing',
            default => '❓ Unknown',
        };
    }

    public function getTotalGuestsAttribute()
    {
        if ($this->number_of_guests) {
            return $this->number_of_guests;
        }
        return ($this->adults ?? 0) + ($this->children ?? 0);
    }

    public function getDurationAttribute()
    {
        if ($this->nights) {
            return $this->nights . ' night' . ($this->nights > 1 ? 's' : '');
        }

        if ($this->check_in && $this->check_out) {
            $nights = Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
            return $nights . ' night' . ($nights > 1 ? 's' : '');
        }

        return 'N/A';
    }

    public function getIsRefundableAttribute()
    {
        if (!$this->isPaid() || $this->isCancelled() || $this->isCompleted()) {
            return false;
        }

        $checkIn = Carbon::parse($this->check_in);
        $now = Carbon::now();
        $hoursDiff = $now->diffInHours($checkIn, false);

        return $hoursDiff >= 48;
    }

    public function getDaysUntilCheckinAttribute()
    {
        if (!$this->check_in) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in);
        $now = Carbon::now();

        if ($checkIn->isPast()) {
            return 0;
        }

        return $now->diffInDays($checkIn);
    }

    public function getOrderSummaryAttribute()
    {
        if (!$this->order_items || !is_array($this->order_items)) {
            return null;
        }

        $totalItems = array_sum(array_column($this->order_items, 'quantity'));
        $itemCount = count($this->order_items);

        return "{$totalItems} items ({$itemCount} dishes)";
    }

    // ==================== Status Methods ====================

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $this->sendConfirmationNotification();

        return $this;
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $this;
    }

    public function cancel($reason = null)
    {
        $updateData = [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ];

        if ($reason) {
            $updateData['cancelled_reason'] = $reason;
        }

        $this->update($updateData);

        if ($this->room) {
            $this->room->increment('availability');
        }

        $this->sendCancellationNotification();

        return $this;
    }

    public function checkIn()
    {
        $this->update([
            'status' => 'checked_in',
        ]);

        return $this;
    }

    public function checkOut()
    {
        $this->update([
            'status' => 'checked_out',
            'completed_at' => now(),
        ]);

        return $this;
    }

    public function startProgress()
    {
        $this->update([
            'status' => 'in_progress',
        ]);

        return $this;
    }

    public function markAsReady()
    {
        $this->update([
            'status' => 'ready',
        ]);

        return $this;
    }

    public function markAsPaid($transactionId = null, $paymentMethod = null)
    {
        $updateData = [
            'payment_status' => 'paid',
        ];

        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }

        if ($paymentMethod) {
            $updateData['payment_method'] = $paymentMethod;
        }

        $this->update($updateData);

        if ($this->status === 'pending') {
            $this->confirm();
        }

        return $this;
    }

    public function processRefund($amount = null)
    {
        $refundAmount = $amount ?? $this->total_price;

        $this->update([
            'payment_status' => 'refunded',
            'refund_amount' => $refundAmount,
            'refund_status' => 'completed',
        ]);

        return $this;
    }

    // ==================== Validation Methods ====================

    public function canBeCancelled()
    {
        if (in_array($this->status, ['cancelled', 'completed', 'checked_out'])) {
            return false;
        }

        if ($this->booking_type === 'restaurant' && $this->status === 'completed') {
            return false;
        }

        if ($this->check_in) {
            $checkInDate = Carbon::parse($this->check_in);
            $now = Carbon::now();
            $hoursDiff = $now->diffInHours($checkInDate, false);

            return $hoursDiff >= 24;
        }

        return true;
    }

    public function canBeModified()
    {
        if (in_array($this->status, ['cancelled', 'completed', 'checked_out'])) {
            return false;
        }

        if ($this->check_in) {
            $checkInDate = Carbon::parse($this->check_in);
            $now = Carbon::now();

            return $now->lt($checkInDate);
        }

        return true;
    }

    public function canBeRated()
    {
        return in_array($this->status, ['completed', 'checked_out']) && !$this->rating;
    }

    public function isUpcoming()
    {
        if (!$this->check_in) {
            return false;
        }

        $checkInDate = Carbon::parse($this->check_in);
        return $checkInDate->isFuture() && in_array($this->status, ['confirmed', 'pending']);
    }

    public function isPast()
    {
        if (!$this->check_out) {
            return false;
        }

        $checkOutDate = Carbon::parse($this->check_out);
        return $checkOutDate->isPast();
    }

    public function isActive()
    {
        if (!$this->check_in || !$this->check_out) {
            return false;
        }

        $now = Carbon::now();
        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        return $now->between($checkIn, $checkOut) &&
               in_array($this->status, ['confirmed', 'checked_in']);
    }

    // ==================== Scopes ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in', 'in_progress'])
            ->where(function($q) {
                $q->where('checkout_date', '>=', now())
                  ->orWhere('check_out_date', '>=', now())
                  ->orWhereNull('checkout_date');
            });
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['confirmed', 'pending'])
            ->where(function($q) {
                $q->where('checkin_date', '>', now())
                  ->orWhere('check_in_date', '>', now())
                  ->orWhere('reservation_date', '>', now());
            });
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['pending', 'failed']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('booking_type', $type);
    }

    public function scopeForHotel($query)
    {
        return $query->where('booking_type', 'hotel');
    }

    public function scopeForRestaurant($query)
    {
        return $query->whereIn('booking_type', ['restaurant', 'coffee', 'coffee_shop']);
    }

    public function scopeForTransport($query)
    {
        return $query->where('booking_type', 'transport');
    }

    public function scopeCheckInToday($query)
    {
        return $query->whereDate('checkin_date', today())
                    ->orWhereDate('check_in_date', today());
    }

    public function scopeCheckOutToday($query)
    {
        return $query->whereDate('checkout_date', today())
                    ->orWhereDate('check_out_date', today());
    }

    public function scopeTodayReservations($query)
    {
        return $query->whereDate('reservation_date', today());
    }

    public function scopeByReference($query, $reference)
    {
        return $query->where('booking_reference', $reference);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('booking_reference', 'like', "%{$term}%")
              ->orWhere('guest_name', 'like', "%{$term}%")
              ->orWhere('guest_firstname', 'like', "%{$term}%")
              ->orWhere('guest_lastname', 'like', "%{$term}%")
              ->orWhere('guest_phone', 'like', "%{$term}%")
              ->orWhere('guest_email', 'like', "%{$term}%");
        });
    }

    // ==================== Helper Methods ====================

    public function generateReference()
    {
        $prefix = match($this->booking_type) {
            'hotel' => 'HTL',
            'restaurant' => 'RST',
            'coffee', 'coffee_shop' => 'CFE',
            'transport' => 'TRP',
            default => 'BKG'
        };

        $this->booking_reference = $prefix . '-' . strtoupper(Str::random(8));
    }

    public function calculateTotal()
    {
        if ($this->room && $this->nights) {
            $this->total_price_usd = $this->room->price_usd * $this->nights;
            $this->total_price_rwf = $this->room->price_rwf * $this->nights;
            $this->total_price = $this->total_price_rwf;
        } elseif ($this->order_items && is_array($this->order_items)) {
            $total = array_reduce($this->order_items, function($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            $this->total_price = $total;
            $this->total_price_rwf = $total;
            $this->total_price_usd = round($total / 1000, 2);
        }
    }

    public function sendConfirmationNotification()
    {
        // TODO: Implement notification
        // Notification::send($this->user, new BookingConfirmed($this));
    }

    public function sendCancellationNotification()
    {
        // TODO: Implement notification
        // Notification::send($this->user, new BookingCancelled($this));
    }

    public function addRating($rating, $review = null)
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }

        $this->update([
            'rating' => $rating,
            'review' => $review,
        ]);

        return $this;
    }

    public function getReceiptData()
    {
        return [
            'reference' => $this->booking_reference,
            'date' => $this->created_at->format('d/m/Y H:i'),
            'type' => ucfirst($this->booking_type),
            'guest' => $this->full_guest_name,
            'facility' => $this->facility->name ?? 'N/A',
            'items' => $this->order_items ?? [],
            'total' => $this->total_price_formatted,
            'status' => $this->status_badge,
            'payment' => $this->payment_status_badge,
        ];
    }

    // ==================== Static Boot Method ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // Auto-generate booking reference
            if (empty($booking->booking_reference)) {
                $prefix = match($booking->booking_type) {
                    'hotel' => 'HTL',
                    'restaurant' => 'RST',
                    'coffee', 'coffee_shop' => 'CFE',
                    'transport' => 'TRP',
                    default => 'BKG'
                };
                $booking->booking_reference = $prefix . '-' . strtoupper(Str::random(8));
            }

            // Set default status
            if (empty($booking->status)) {
                $booking->status = 'pending';
            }

            // Set default payment status
            if (empty($booking->payment_status)) {
                $booking->payment_status = 'pending';
            }

            // Calculate nights if not provided
            if (empty($booking->nights) && $booking->check_in && $booking->check_out) {
                $booking->nights = Carbon::parse($booking->check_in)
                    ->diffInDays(Carbon::parse($booking->check_out));
            }

            // Calculate total if not provided
            if (empty($booking->total_price)) {
                if ($booking->room && $booking->nights) {
                    $booking->total_price_rwf = $booking->room->price_rwf * $booking->nights;
                    $booking->total_price_usd = $booking->room->price_usd * $booking->nights;
                    $booking->total_price = $booking->total_price_rwf;
                }
            }
        });

        static::updating(function ($booking) {
            // Recalculate nights if dates changed
            if ($booking->isDirty(['check_in_date', 'check_out_date', 'checkin_date', 'checkout_date'])) {
                if ($booking->check_in && $booking->check_out) {
                    $booking->nights = Carbon::parse($booking->check_in)
                        ->diffInDays(Carbon::parse($booking->check_out));
                }
            }
        });
    }
    public function transport()
{
    return $this->hasOne(Transport::class, 'booking_id');
}
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'driver_id',
        'customer_name',
        'customer_email',
        'phone',
        'pickup_location',
        'drop_location',
        'pickup_date',
        'pickup_time',
        'vehicle_type',
        'passengers',
        'distance',
        'price',
        'status',
        'notes',
        'started_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user who created the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned driver
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Scope for active transports
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending transports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed transports
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'yellow',
            'active' => 'green',
            'completed' => 'blue',
            'cancelled' => 'red',
        ][$this->status] ?? 'gray';
    }

    /**
     * Get vehicle type icon
     */
    public function getVehicleIconAttribute()
    {
        return [
            'car' => 'fa-car',
            'van' => 'fa-shuttle-van',
            'truck' => 'fa-truck',
            'bus' => 'fa-bus',
        ][$this->vehicle_type] ?? 'fa-car';
    }
    public function booking()
{
    return $this->belongsTo(Booking::class);
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'icon',
        'price',
        'duration',
        'is_active',
        'is_featured',
        'category',
        'capacity',
        'booking_required',
        'meta_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'booking_required' => 'boolean',
        'price' => 'decimal:2',
        'duration' => 'integer',
        'capacity' => 'integer',
        'meta_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_price',
        'icon_class',
        'status_badge'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::creating(function ($service) {
            if (empty($service->icon)) {
                $service->icon = self::getDefaultIcon($service->name);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the facility that owns the service.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get all bookings for this service.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service')
                    ->withPivot('quantity', 'price', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get service reviews.
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get service images/gallery.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured services.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by facility.
     */
    public function scopeForFacility(Builder $query, int $facilityId): Builder
    {
        return $query->where('facility_id', $facilityId);
    }

    /**
     * Scope a query to search services.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope for services that require booking.
     */
    public function scopeRequiresBooking(Builder $query): Builder
    {
        return $query->where('booking_required', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price) {
            return number_format($this->price, 0) . ' RWF';
        }
        return 'Free';
    }

    /**
     * Get the icon class for Font Awesome.
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?: 'fa-circle-check';
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_active) {
            return '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Active</span>';
        }
        return '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Inactive</span>';
    }

    /**
     * Get duration in human readable format.
     */
    public function getFormattedDurationAttribute(): ?string
    {
        if (!$this->duration) {
            return null;
        }

        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            return "{$minutes} minute" . ($minutes > 1 ? 's' : '');
        }
    }

    /**
     * Set the name attribute and auto-generate icon if needed.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if service is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->facility && $this->facility->is_active;
    }

    /**
     * Check if service has capacity available.
     */
    public function hasCapacity(int $requested = 1): bool
    {
        if (!$this->capacity) {
            return true; // Unlimited capacity
        }

        return $this->capacity >= $requested;
    }

    /**
     * Get average rating.
     */
    public function averageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0.0;
    }

    /**
     * Get total bookings count.
     */
    public function totalBookings(): int
    {
        return $this->bookings()->count();
    }

    /**
     * Get revenue generated.
     */
    public function totalRevenue(): float
    {
        return $this->bookings()
                    ->whereHas('booking', function ($q) {
                        $q->where('status', 'completed');
                    })
                    ->sum('price') ?? 0.0;
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(): bool
    {
        $this->is_featured = !$this->is_featured;
        return $this->save();
    }

    /**
     * Get default icon based on service name.
     */
    protected static function getDefaultIcon(string $name): string
    {
        $name = strtolower($name);

        $iconMap = [
            'wifi' => 'fa-wifi',
            'parking' => 'fa-parking',
            'pool' => 'fa-swimming-pool',
            'gym' => 'fa-dumbbell',
            'spa' => 'fa-spa',
            'restaurant' => 'fa-utensils',
            'bar' => 'fa-wine-glass',
            'breakfast' => 'fa-coffee',
            'laundry' => 'fa-soap',
            'room service' => 'fa-concierge-bell',
            'airport' => 'fa-plane',
            'shuttle' => 'fa-bus',
            'meeting' => 'fa-handshake',
            'conference' => 'fa-users',
            'business' => 'fa-briefcase',
            'kids' => 'fa-child',
            'pet' => 'fa-paw',
            'smoking' => 'fa-smoking',
            'air conditioning' => 'fa-snowflake',
            'heating' => 'fa-fire',
            'tv' => 'fa-tv',
            'security' => 'fa-shield-alt',
            'wheelchair' => 'fa-wheelchair',
            'wedding' => 'fa-ring',
            'birthday' => 'fa-birthday-cake',
            'music' => 'fa-music',
            'outdoor' => 'fa-tree',
            'garden' => 'fa-leaf',
            'beach' => 'fa-umbrella-beach',
            'massage' => 'fa-hand-sparkles',
            'sauna' => 'fa-hot-tub',
        ];

        foreach ($iconMap as $keyword => $icon) {
            if (Str::contains($name, $keyword)) {
                return $icon;
            }
        }

        return 'fa-check-circle';
    }

    /**
     * Get all available categories.
     */
    public static function getCategories(): array
    {
        return [
            'Amenity' => 'General Amenities',
            'Food & Beverage' => 'Food & Beverage Services',
            'Recreation' => 'Recreation & Entertainment',
            'Business' => 'Business Services',
            'Transportation' => 'Transportation Services',
            'Wellness' => 'Wellness & Spa',
            'Event' => 'Event Services',
            'Technology' => 'Technology Services',
            'Other' => 'Other Services',
        ];
    }

    /**
     * Get popular services.
     */
    public static function popular(int $limit = 10)
    {
        return self::active()
                   ->withCount('bookings')
                   ->orderBy('bookings_count', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get services by facility with cache.
     */
    public static function getByFacility(int $facilityId, bool $activeOnly = true)
    {
        $query = self::where('facility_id', $facilityId);

        if ($activeOnly) {
            $query->active();
        }

        return $query->orderBy('is_featured', 'desc')
                     ->orderBy('name')
                     ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get services with their relationships loaded.
     */
    public static function withRelations()
    {
        return self::with(['facility', 'reviews', 'images']);
    }

    /**
     * Get full service details.
     */
    public function getFullDetails(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'duration' => $this->formatted_duration,
            'icon' => $this->icon_class,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'capacity' => $this->capacity,
            'booking_required' => $this->booking_required,
            'average_rating' => round($this->averageRating(), 1),
            'total_bookings' => $this->totalBookings(),
            'facility' => [
                'id' => $this->facility->id,
                'name' => $this->facility->name,
            ],
            'available' => $this->isAvailable(),
        ];
    }
}

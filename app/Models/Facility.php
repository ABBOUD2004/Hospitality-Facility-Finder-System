<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'manager',
        'contact',
        'email',
        'website',
        'capacity',
        'image',
        'type',
        'phone',
        'opening_hours',
        'cuisine_type',
        'delivery_available',
    ];

    // ✅ استخدم الطريقة الحديثة للـ Accessors
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image) {
                    // لو الصورة URL كامل
                    if (str_starts_with($this->image, 'http')) {
                        return $this->image;
                    }
                    // لو الصورة في storage
                    return asset('storage/' . $this->image);
                }

                // صورة افتراضية حسب النوع
                $defaults = [
                    'hotel' => 'images/default-hotel.jpg',
                    'restaurant' => 'images/default-restaurant.jpg',
                    'coffee_shop' => 'images/default-coffee.jpg',
                ];

                return asset($defaults[$this->type] ?? 'images/default-facility.jpg');
            }
        );
    }

    // ✅ أو لو عايز تخلي الـ image نفسه يرجع الـ URL مباشرة
    // استخدم الطريقة دي (اختار واحدة بس من الاتنين):
    /*
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    if (str_starts_with($value, 'http')) {
                        return $value;
                    }
                    return asset('storage/' . $value);
                }

                $defaults = [
                    'hotel' => 'images/default-hotel.jpg',
                    'restaurant' => 'images/default-restaurant.jpg',
                    'coffee_shop' => 'images/default-coffee.jpg',
                ];

                return asset($defaults[$this->type] ?? 'images/default-facility.jpg');
            }
        );
    }
    */

    // علاقة الغرف (للفنادق)
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // علاقة الخدمات
    public function services()
    {
        return $this->hasMany(Service::class, 'facility_id');
    }

    // علاقة المعرض
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'facility_id');
    }

    // علاقة المعرض (نفس الشيء بس اسم مختلف للتوافق)
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    // علاقة عناصر القائمة (للمطاعم والكوفي شوب)
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'category',
        'price',
        'image',
        'description'
    ];

    // 👇 ضيف الدالة دي
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // لو الصورة URL كامل
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            // لو الصورة في storage
            return asset('storage/' . $this->image);
        }

        // صورة افتراضية
        return asset('images/default-menu.jpg');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}

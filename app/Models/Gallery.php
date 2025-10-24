<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id', 'image'];

    // 👇 ضيف الدالة دي
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        return asset('images/default-gallery.jpg');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}

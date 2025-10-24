<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id', 'name', 'description', 'price_usd', 'price_rwf', 'availability', 'image'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}

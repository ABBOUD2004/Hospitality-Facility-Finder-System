<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * الحقول المسموح تعبئتها جماعياً (لتفادي MassAssignmentException)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
    ];

    /**
     * الحقول التي لا تُعرض عند تحويل النموذج إلى JSON أو Array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويل التلقائي للأنواع
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * التحقق من أن المستخدم Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * التحقق من أن المستخدم عميل عادي
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * التحقق من أن المستخدم موظف
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'manager']);
    }

    /**
     * رابط الصورة الرمزية (Avatar)
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        // صورة افتراضية
        return asset('images/default-avatar.png');
    }

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * العلاقة مع وسائل النقل
     */
    public function transports()
    {
        return $this->hasMany(Transport::class, 'user_id');
    }
}

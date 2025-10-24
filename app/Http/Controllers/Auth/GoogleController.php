<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    // الخطوة 1: الانتقال إلى صفحة تسجيل الدخول بجوجل
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // الخطوة 2: استقبال بيانات المستخدم بعد تسجيل الدخول
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // البحث عن المستخدم أو إنشاؤه
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );

            // تسجيل الدخول مباشرة
            Auth::login($user);

            // إعادة التوجيه إلى الصفحة الرئيسية أو لوحة التحكم
            return redirect('/dashboard');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google.');
        }
    }
}

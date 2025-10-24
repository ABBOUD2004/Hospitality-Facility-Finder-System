<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form with enhanced statistics
     */
  public function edit(Request $request): View
    {
        try {
            $user = $request->user()->load([
                'bookings' => function($query) {
                    $query->latest()->take(5);
                }
            ]);

            // إحصائيات المستخدم
            $stats = [
                'total_bookings' => $user->bookings()->count(),
                'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
                'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
                'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
                'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
            ];

            return view('profile.edit', compact('user', 'stats'));

        } catch (\Exception $e) {
            Log::error('❌ Error loading profile: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل الملف الشخصي');
        }
    }
    /**
     * Update the user's profile information with validation
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Simple validation - only name and email (fields that exist)
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);

            DB::beginTransaction();

            // Update only existing columns
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            // Only update phone and address if columns exist
            if (\Schema::hasColumn('users', 'phone')) {
                $user->phone = $request->input('phone');
            }

            if (\Schema::hasColumn('users', 'address')) {
                $user->address = $request->input('address');
            }

            $user->save();

            DB::commit();

            Log::info('✅ Profile updated successfully for user: ' . $user->id);

            return redirect()
                ->route('profile.edit')
                ->with('success', 'تم تحديث الملف الشخصي بنجاح! ✅');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Profile update error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Update avatar with image optimization and compression
     */
    public function updateAvatar(Request $request)
    {
        try {
            $validated = $request->validate([
                'avatar' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120', // 5MB
            ]);

            $user = $request->user();

            DB::beginTransaction();

            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                Log::info('🗑️ Old avatar deleted for user: ' . $user->id);
            }

            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $filename = 'avatar_' . $user->id . '_' . time() . '.webp';

                // Create directory if not exists
                if (!Storage::disk('public')->exists('avatars')) {
                    Storage::disk('public')->makeDirectory('avatars');
                }

                $path = storage_path('app/public/avatars/' . $filename);

                // Optimize and compress image (if Intervention Image is installed)
                if (class_exists('\Intervention\Image\Facades\Image')) {
                    Image::make($image)
                        ->fit(500, 500) // Resize to 500x500
                        ->encode('webp', 85) // Convert to WebP with 85% quality
                        ->save($path);
                } else {
                    // Fallback: simple storage
                    $image->storeAs('avatars', $filename, 'public');
                }

                $user->avatar = 'avatars/' . $filename;
                $user->save();

                // Clear cache
                Cache::forget("user_stats_{$user->id}");

                Log::info('✅ Avatar uploaded', [
                    'user_id' => $user->id,
                    'filename' => $filename,
                    'size' => $image->getSize()
                ]);
            }

            DB::commit();

            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الصورة الشخصية بنجاح!',
                    'avatar_url' => Storage::url($user->avatar),
                ]);
            }

            return back()->with('success', 'تم تحديث الصورة الشخصية بنجاح! ✅');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى اختيار صورة صحيحة (JPG, PNG, WebP - حد أقصى 5MB)'
                ], 422);
            }
            return back()->withErrors($e->errors())->with('error', 'يرجى اختيار صورة صحيحة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating avatar: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الصورة'
                ], 500);
            }
            return back()->with('error', 'حدث خطأ أثناء تحديث الصورة');
        }
    }

    /**
     * Remove the user's profile picture
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            DB::beginTransaction();

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                Log::info('🗑️ Avatar deleted for user: ' . $user->id);
            }

            $user->avatar = null;
            $user->save();

            // Clear cache
            Cache::forget("user_stats_{$user->id}");

            DB::commit();

            return back()->with('success', 'تم حذف الصورة الشخصية بنجاح! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error removing avatar: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الصورة');
        }
    }

    /**
     * Update password with enhanced security
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                ],
            ], [
                'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
                'password.mixed' => 'يجب أن تحتوي على أحرف كبيرة وصغيرة',
                'password.numbers' => 'يجب أن تحتوي على أرقام',
                'password.symbols' => 'يجب أن تحتوي على رموز خاصة',
                'password.uncompromised' => 'كلمة المرور ضعيفة، اختر كلمة أقوى',
            ]);

            $user = $request->user();

            DB::beginTransaction();

            $user->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => now(), // Track last password change
            ]);

            DB::commit();

            Log::info('✅ Password updated', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // TODO: Send password change notification email
            // Mail::to($user)->send(new PasswordChangedNotification());

            return back()->with('success', 'تم تحديث كلمة المرور بنجاح! ✅');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->with('error', 'يرجى التحقق من كلمة المرور');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating password: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث كلمة المرور');
        }
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'email_notifications' => 'boolean',
                'sms_notifications' => 'boolean',
                'marketing_emails' => 'boolean',
                'booking_reminders' => 'boolean',
                'newsletter' => 'boolean',
            ]);

            $user = $request->user();

            DB::beginTransaction();

            $user->notification_preferences = $validated;
            $user->save();

            DB::commit();

            Log::info('✅ Notification preferences updated', [
                'user_id' => $user->id,
                'preferences' => $validated
            ]);

            return back()->with('success', 'تم تحديث إعدادات الإشعارات بنجاح! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating notifications: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات');
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'profile_visibility' => 'boolean',
                'show_activity_status' => 'boolean',
                'show_booking_history' => 'boolean',
                'data_sharing' => 'boolean',
            ]);

            $user = $request->user();

            DB::beginTransaction();

            $user->privacy_settings = $validated;
            $user->save();

            DB::commit();

            Log::info('✅ Privacy settings updated', [
                'user_id' => $user->id,
                'settings' => $validated
            ]);

            return back()->with('success', 'تم تحديث إعدادات الخصوصية بنجاح! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating privacy: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات');
        }
    }

    /**
     * Delete account with proper cleanup
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
                'confirmation' => ['required', 'in:DELETE'],
            ]);

            $user = $request->user();

            DB::beginTransaction();

            // Delete avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Anonymize bookings instead of deleting
            $user->bookings()->update([
                'user_name' => 'Deleted User',
                'user_email' => 'deleted@example.com',
                'user_phone' => null,
            ]);

            // Clear all caches
            Cache::forget("user_stats_{$user->id}");
            Cache::tags(['user_' . $user->id])->flush();

            $userId = $user->id;
            $userName = $user->name;
            $userEmail = $user->email;

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            DB::commit();

            Log::warning('⚠️ Account deleted', [
                'user_id' => $userId,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'deleted_at' => now(),
                'ip' => $request->ip()
            ]);

            return redirect('/')->with('success', 'تم حذف الحساب بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->with('error', 'يرجى إدخال كلمة المرور الصحيحة وكتابة DELETE للتأكيد');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting account: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الحساب');
        }
    }

    /**
     * Export user data (GDPR compliance)
     */
    public function exportData(Request $request)
    {
        try {
            $user = $request->user()->load([
                'bookings.facility',
                'bookings.room'
            ]);

            $data = [
                'exported_at' => now()->toIso8601String(),
                'user_info' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A',
                    'address' => $user->address ?? 'N/A',
                    'date_of_birth' => $user->date_of_birth ?? 'N/A',
                    'member_since' => $user->created_at->format('Y-m-d H:i:s'),
                    'email_verified' => $user->email_verified_at ? 'Yes' : 'No',
                ],
                'statistics' => [
                    'total_bookings' => $user->bookings()->count(),
                    'total_spent' => $user->bookings()
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->sum('total_amount'),
                ],
                'bookings' => $user->bookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'facility' => $booking->facility->name ?? 'N/A',
                        'check_in' => $booking->check_in_date ?? 'N/A',
                        'check_out' => $booking->check_out_date ?? 'N/A',
                        'guests' => $booking->number_of_guests ?? 'N/A',
                        'status' => $booking->status,
                        'total_amount' => $booking->total_amount,
                        'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'privacy_notice' => 'This data export contains all personal information we hold about you.',
            ];

            $filename = 'user_data_' . $user->id . '_' . date('Y-m-d_His') . '.json';

            Log::info('✅ User data exported', [
                'user_id' => $user->id,
                'filename' => $filename
            ]);

            return response()->json($data, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\""
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            Log::error('❌ Error exporting user data: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تصدير البيانات');
        }
    }

    /**
     * Get default stats in case of error
     */
    private function getDefaultStats(): array
    {
        return [
            'total_bookings' => 0,
            'pending_bookings' => 0,
            'confirmed_bookings' => 0,
            'completed_bookings' => 0,
            'cancelled_bookings' => 0,
            'total_spent' => 0,
            'upcoming_bookings' => 0,
            'this_month_bookings' => 0,
            'favorite_facility_type' => 'hotel',
            'member_days' => 0,
        ];
    }

    /**
     * Get user's favorite facility type
     */
    private function getFavoriteFacilityType($user): string
    {
        try {
            $facilityTypes = $user->bookings()
                ->with('facility')
                ->get()
                ->pluck('facility.type')
                ->filter()
                ->countBy()
                ->sortDesc();

            return $facilityTypes->keys()->first() ?? 'hotel';
        } catch (\Exception $e) {
            return 'hotel';
        }
    }

    /**
     * Get recent activity timeline
     */
    private function getRecentActivity($user): array
    {
        try {
            return $user->bookings()
                ->with('facility')
                ->latest()
                ->take(10)
                ->get()
                ->map(function($booking) {
                    return [
                        'type' => 'booking',
                        'action' => 'New booking created',
                        'facility' => $booking->facility->name ?? 'Unknown',
                        'status' => $booking->status,
                        'amount' => $booking->total_amount ?? 0,
                        'created_at' => $booking->created_at,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            Log::warning('Error getting recent activity: ' . $e->getMessage());
            return [];
        }
    }
}


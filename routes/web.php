<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Public Pages (Home & Facilities)
|--------------------------------------------------------------------------
*/

// ✅ استخدم الـ Controller بدل الـ Closure
Route::get('/', [FacilityController::class, 'index'])->name('home');

Route::get('/hotels', [FacilityController::class, 'showHotels'])->name('hotels');
Route::get('/restaurants', [FacilityController::class, 'showRestaurants'])->name('restaurants');
Route::get('/coffee-shops', [FacilityController::class, 'showCoffeeShops'])->name('coffee-shops');

// Facility Details
Route::get('/facility/{id}', [FacilityController::class, 'show'])->name('facility.show');
Route::get('/hotel/{id}', [FacilityController::class, 'showHotel'])->name('hotel.show');
Route::get('/restaurant/{id}', [FacilityController::class, 'showRestaurant'])->name('restaurant.show');
Route::get('/coffee/{id}', [CoffeeController::class, 'show'])->name('coffee.show');

// Other Public Pages
Route::get('/rooms/{id}/book', [RoomController::class, 'book'])->name('rooms.book');
Route::get('/transport', fn() => view('transport'))->name('transport');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Google Authentication
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Public Booking Routes (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/orders/store', [BookingController::class, 'store'])->name('orders.store');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile extras
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/bookings', [ProfileController::class, 'bookings'])->name('profile.bookings');
    Route::post('/profile/bookings/{booking}/cancel', [ProfileController::class, 'cancelBooking'])->name('profile.bookings.cancel');
    Route::get('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');

    // My Bookings
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Room Booking Process
    Route::post('/bookings/store', [RoomController::class, 'processBooking'])->name('bookings.process');
    Route::get('/bookings/{id}/success', [RoomController::class, 'bookingSuccess'])->name('bookings.success');

    // Filter Rooms
    Route::get('/facilities/{facilityId}/rooms/category/{category}', [RoomController::class, 'filterByCategory'])
        ->name('facilities.rooms.category');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    // Room Availability & Pricing
    Route::post('/rooms/{id}/check-availability', [RoomController::class, 'checkAvailability'])
        ->name('api.rooms.check-availability');
    Route::post('/rooms/{id}/calculate-price', [RoomController::class, 'calculatePrice'])
        ->name('api.rooms.calculate-price');

    // Services
    Route::get('/services/facility/{facilityId}', [ServiceController::class, 'getByFacility'])
        ->name('api.services.byFacility');
    Route::get('/services/all', [ServiceController::class, 'getAll'])
        ->name('api.services.all');

    // Statistics
    Route::get('/bookings/statistics', [BookingController::class, 'statistics'])
        ->name('api.bookings.statistics');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // Dashboard Home
    Route::get('/', fn() => redirect()->route('dashboard.hotel'))->name('index');

    /*
    |--------------------------------------------------------------------------
    | Hotel Management
    |--------------------------------------------------------------------------
    */
    Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
    Route::post('/hotel', [HotelController::class, 'store'])->name('hotel.store');
    Route::get('/hotel/{id}/edit', [HotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotel/{id}', [HotelController::class, 'update'])->name('hotel.update');
    Route::delete('/hotel/{id}', [HotelController::class, 'destroy'])->name('hotel.destroy');

    /*
    |--------------------------------------------------------------------------
    | Restaurant Management
    |--------------------------------------------------------------------------
    */
    Route::get('/restaurant', [FacilityController::class, 'createRestaurant'])->name('restaurant');
    Route::post('/restaurant', [FacilityController::class, 'storeRestaurant'])->name('restaurant.store');
    Route::get('/restaurant/{id}/edit', [FacilityController::class, 'editRestaurant'])->name('restaurant.edit');
    Route::put('/restaurant/{id}', [FacilityController::class, 'updateRestaurant'])->name('restaurant.update');
    Route::delete('/restaurant/{id}', [FacilityController::class, 'deleteRestaurant'])->name('restaurant.delete');

    /*
    |--------------------------------------------------------------------------
    | Coffee Shop Management
    |--------------------------------------------------------------------------
    */
    Route::get('/coffee', [FacilityController::class, 'createCoffee'])->name('coffee');
    Route::post('/coffee', [FacilityController::class, 'storeCoffee'])->name('coffee.store');
    Route::get('/coffee/{id}', [FacilityController::class, 'showCoffee'])->name('coffee.show');
    Route::get('/coffee/{id}/edit', [CoffeeController::class, 'edit'])->name('coffee.edit');
    Route::put('/coffee/{id}', [CoffeeController::class, 'update'])->name('coffee.update');
    Route::delete('/coffee/{id}', [CoffeeController::class, 'destroy'])->name('coffee.destroy');

    /*
    |--------------------------------------------------------------------------
    | Bookings Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::get('/{id}/invoice', [BookingController::class, 'invoice'])->name('invoice');
        Route::patch('/{id}/status', [BookingController::class, 'updateStatus'])->name('update-status');
        Route::post('/{booking}/accept-transport', [BookingController::class, 'acceptTransport'])->name('accept-transport');
        Route::delete('/{id}', [BookingController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [BookingController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | Orders Management (Restaurant & Coffee)
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', [BookingController::class, 'orders'])->name('orders');

    /*
    |--------------------------------------------------------------------------
    | Transport Management
    |--------------------------------------------------------------------------
    */
    Route::get('/transport', [TransportController::class, 'index'])->name('transport');
    Route::get('/transport/create', [TransportController::class, 'create'])->name('transport.create');
    Route::post('/transport', [TransportController::class, 'store'])->name('transport.store');
    Route::get('/transport/{id}', [TransportController::class, 'show'])->name('transport.show');
    Route::get('/transport/{id}/edit', [TransportController::class, 'edit'])->name('transport.edit');
    Route::put('/transport/{id}', [TransportController::class, 'update'])->name('transport.update');
    Route::delete('/transport/{id}', [TransportController::class, 'destroy'])->name('transport.destroy');
    Route::post('/transport/{id}/assign-driver', [TransportController::class, 'assignDriver'])->name('transport.assign-driver');
    Route::post('/transport/{id}/start', [TransportController::class, 'startTrip'])->name('transport.start');
    Route::post('/transport/{id}/complete', [TransportController::class, 'completeTrip'])->name('transport.complete');
    Route::post('/transport/{id}/cancel', [TransportController::class, 'cancelTrip'])->name('transport.cancel');
    Route::get('/transport/{id}/track', [TransportController::class, 'track'])->name('transport.track');
    Route::get('/transport/export', [TransportController::class, 'export'])->name('transport.export');

    /*
    |--------------------------------------------------------------------------
    | Payment Management
    |--------------------------------------------------------------------------
    */
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
    Route::post('/payments/{id}/retry', [PaymentController::class, 'retryPayment'])->name('payments.retry');
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    Route::post('/payments/{id}/status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::get('/payments/{id}/invoice', [PaymentController::class, 'downloadInvoice'])->name('payments.invoice');
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');

    /*
    |--------------------------------------------------------------------------
    | Services, Gallery, Menu Items
    |--------------------------------------------------------------------------
    */
    Route::resource('service', ServiceController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('menu', MenuItemController::class);

    /*
    |--------------------------------------------------------------------------
    | Other Dashboard Pages
    |--------------------------------------------------------------------------
    */
    Route::get('/facility-managers', fn() => view('dashboard.facility-managers.index'))->name('facility-managers');
    Route::get('/account', fn() => view('dashboard.account'))->name('account');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::get('/profile/bookings', [ProfileController::class, 'bookings'])->name('profile.bookings');
    Route::get('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');
    Route::post('/profile/bookings/{booking}/cancel', [ProfileController::class, 'cancelBooking'])->name('profile.bookings.cancel');
});


Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    // 👇 هذه السطر هو المهم
    Route::resource('service', ServiceController::class, ['as' => 'dashboard']);
});


Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('service', ServiceController::class);
});
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // Service Management
    Route::post('/services/store', [ServiceController::class, 'store'])->name('service.store');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('service.destroy');


    // في ملف routes/web.php




Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Routes
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
});
// في routes/web.php


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// في ملف routes/web.php

Route::middleware(['auth'])->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Password Update
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});



Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ممكن تضيف كل مسارات الإدارة هنا
});



use Illuminate\Http\Request;


// About Page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Services Page
Route::get('/services', function () {
    return view('services');
})->name('services');

// Contact Page - GET
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Contact Page - POST (Form Submission)
Route::post('/contact', function (Request $request) {
    // Validate form data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10|max:1000',
    ]);

    // هنا يمكنك إضافة كود لإرسال الإيميل أو حفظ البيانات
    // مثال: Mail::to('admin@hffinder.com')->send(new ContactMail($validated));

    // Return with success message
    return back()->with('success', 'Thank you for contacting us! We will get back to you within 24 hours.');
})->name('contact.send');

// Login Page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');





// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        $user = auth()->user();

        // Get bookings stats
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
        ];

        $bookings = $user->bookings()->latest()->get();

        return view('profile.edit', compact('user', 'stats', 'bookings'));
    })->name('profile.edit');

    Route::patch('/profile', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    Route::post('/profile/avatar', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:5120', // 5MB max
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    })->name('profile.avatar.update');

    Route::put('/password', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update(['password' => \Hash::make($validated['password'])]);

        return back()->with('success', 'Password updated successfully!');
    })->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

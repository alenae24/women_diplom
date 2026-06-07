<?php

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminMasterController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPriceController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminGalleryController;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/filter', [GalleryController::class, 'filter'])->name('gallery.filter');

Auth::routes(['verify' => false]);

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Некорректная ссылка подтверждения.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect()->route('login')
        ->with('status', 'Email успешно подтверждён. Теперь вы можете войти.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/profile', [ClientController::class, 'editProfile'])->name('client.profile.edit');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('client.profile.update');
    Route::get('/booking', [ClientController::class, 'bookingForm'])->name('client.booking');
    Route::post('/booking', [ClientController::class, 'storeBooking'])->name('client.booking.store');
    Route::get('/free-time', [ClientController::class, 'getFreeTime'])->name('client.free-time');
    Route::get('/available-dates', [ClientController::class, 'getAvailableDates'])->name('client.available-dates');
    Route::delete('/appointment/{appointment}', [ClientController::class, 'cancelAppointment'])->name('client.appointment.cancel');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('services', AdminServiceController::class);
    Route::resource('masters', AdminMasterController::class);
    Route::resource('users', AdminUserController::class);
    Route::resource('gallery', AdminGalleryController::class);

    Route::get('prices', [AdminPriceController::class, 'index'])->name('prices.index');
    Route::post('prices', [AdminPriceController::class, 'store'])->name('prices.store');
    Route::put('prices/{price}', [AdminPriceController::class, 'update'])->name('prices.update'); 
    Route::delete('prices/{price}', [AdminPriceController::class, 'destroy'])->name('prices.destroy');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/free-slots', [AdminAppointmentController::class, 'freeSlots'])->name('appointments.free-slots');
    Route::get('/appointments/available-dates', [AdminAppointmentController::class, 'availableDates'])->name('appointments.available-dates');
    
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
    ->name('appointments.status');
    
    Route::get('/appointments/{appointment}/edit', [AdminAppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');

    
    Route::get('/schedule', [AdminScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/{master}', [AdminScheduleController::class, 'calendar'])->name('schedule.calendar');
    Route::post('/schedule/{master}', [AdminScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedule.destroy');

    
});
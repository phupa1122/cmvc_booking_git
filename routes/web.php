<?php

//ถ้าจะใช้ controller อะไรต้องมาใส่ที่ web.php ด้วย
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserImportController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MeetingReportController;

//user
Route::get('/', [BookingController::class, 'create']);

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('admin/home',[HomeController::class,'adminHome'])->name('admin.home')->middleware('is_admin');

//Booking
Route::middleware(['auth'])->group(function () {
   Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
   Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});

//Meeting-room
Route::resource('/meeting-rooms', MeetingRoomController::class);
Route::get('/bookings/check-availability', [App\Http\Controllers\BookingController::class, 'checkAvailability'])->name('bookings.checkAvailability');
//Route::get('/users/autocomplete', [App\Http\Controllers\UserController::class, 'autocomplete'])->name('users.autocomplete');
Route::get('/users/autocomplete', [BookingController::class, 'getAvailableUsers'])->name('users.autocomplete');

//Route::get('/meeting-rooms/create', [MeetingRoomController::class,'create'])->name('meeting_room.create');

//feedback
Route::middleware(['auth'])->group(function () {
   Route::resource('feedback', FeedbackController::class);
   Route::get('feedback/create', [FeedbackController::class, 'create'])->name('feedback.create')->middleware('is_not_admin');
   Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
});

//user mangement
Route::middleware(['auth', 'is_admin'])->group(function () {
   Route::get('users', [UserController::class, 'index'])->name('users.index');
   Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
   Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
   Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
   Route::get('/import', [UserImportController::class, 'showImportForm'])->name('import.form');
   Route::post('/import', [UserImportController::class, 'import'])->name('users.import');
});

//Accept
Route::post('meeting/respond/{participant}', [HomeController::class, 'respondToMeeting'])->name('meeting.respond');

//Admin
Route::middleware(['auth', 'is_admin'])->group(function () {
   Route::get('admin/home', [AdminController::class, 'index'])->name('admin.home');
   Route::post('booking/respond/{booking}', [AdminController::class, 'respondToBooking'])->name('booking.respond');
   //Route::get('booking/details/{booking}', [AdminController::class, 'bookingDetails'])->name('booking.details');
   Route::get('booking/details/ajax/{booking}', [AdminController::class, 'bookingDetailsAjax'])->name('booking.details.ajax');
   Route::resource('equipment', EquipmentController::class);
});
Route::get('booking/details/{id}', [BookingController::class, 'getBookingDetails'])->name('booking.details.ajax');
Route::get('/my-calendar', [BookingController::class, 'myCalendar'])->name('my.calendar')->middleware('auth');

// Route::get('/meeting-report/create', 'MeetingReportController@create')->name('meeting-report.create');
// Route::post('/meeting-report/store', 'MeetingReportController@store')->name('meeting-report.store');

Route::get('/meeting-report/create', [MeetingReportController::class, 'create'])->name('meeting-report.create');
Route::post('/meeting-report/store', [MeetingReportController::class, 'store'])->name('meeting-report.store');

Route::middleware(['auth'])->group(function () {
   Route::get('meeting-reports', [MeetingReportController::class, 'index'])->name('meeting-report.index');
   Route::get('meeting-reports/create', [MeetingReportController::class, 'create'])->name('meeting-report.create');
   Route::post('meeting-reports/store', [MeetingReportController::class, 'store'])->name('meeting-report.store');
   Route::get('meeting-reports/view/{id}', [MeetingReportController::class, 'view'])->name('meeting-report.view');
   Route::delete('/meeting-report/{id}', [MeetingReportController::class, 'destroy'])->name('meeting-report.destroy');

});

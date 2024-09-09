<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AdminController;
//use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController; //ถ้าจะใช้ controller อะไรต้องมาใส่ที่ web.php ด้วย
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


//user
Route::get('/',[BookingController::class,'create']);
//Route::get('/detail/{id}',[BlogController::class,'detail'])->name('detail');

//admin
 Route::prefix('admin')->group(function(){
    //  Route::get('/blog',[AdminController::class,'index'])->name('blog');
    //  Route::get('/create',[AdminController::class,'create'])->name('create')->middleware('is_admin');
    //  Route::post('/insert',[AdminController::class,'insert'])->name('insert')->middleware('is_admin'); 
    //  Route::get('/delete/{id}',[AdminController::class,'delete'])->name ('delete');
    //  Route::get('/change/{id}',[AdminController::class,'change'])->name('change');
    //  Route::get('/edit/{id}',[AdminController::class,'edit'])->name('edit');
    //  Route::post('/update/{id}',[AdminController::class,'update'])->name('update');
 });

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
});

//Accept
Route::post('meeting/respond/{participant}', [HomeController::class, 'respondToMeeting'])->name('meeting.respond');

//Admin
Route::middleware(['auth', 'is_admin'])->group(function () {
   Route::get('admin/home', [AdminController::class, 'index'])->name('admin.home');
   Route::post('booking/respond/{booking}', [AdminController::class, 'respondToBooking'])->name('booking.respond');
   Route::get('booking/details/{booking}', [AdminController::class, 'bookingDetails'])->name('booking.details');
   Route::get('booking/details/ajax/{booking}', [AdminController::class, 'bookingDetailsAjax'])->name('booking.details.ajax');

});

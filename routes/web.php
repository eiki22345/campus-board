<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ThreadSubscriptionController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UniversityRequestController;
use GuzzleHttp\Middleware;

Route::controller(WelcomeController::class)->group(function () {
  Route::get('/', 'index')->middleware('throttle:10,1')->name('welcome');
});

Route::get('/universities', [UniversityController::class, 'index'])->middleware('throttle:15,1')->name('universities.index');

Route::get('university-request', [UniversityRequestController::class, 'create'])
  ->middleware('throttle:15,1')->name('university-request.create');
Route::post('university-request', [UniversityRequestController::class, 'store'])
  ->middleware('throttle:6,1')->name('university-request.store');

Route::get('/legal', function () {
  return view('legal.index');
})->middleware('throttle:10,1')->name('legal.index');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
  Route::controller(BoardController::class)->group(function () {
    Route::get('/board/index', 'index')->middleware('throttle:30,1')->name('dashboard');
  });

  Route::controller(ThreadController::class)->group(function () {
    Route::get('/boards/{board}/threads', 'index')->middleware('throttle:30,1')->name('threads.index');
    Route::get('/boards/{board}/threads/{thread}', 'show')->middleware('throttle:30,1')->name('threads.show');
    Route::post('/boards/threads/{board}', 'store')->middleware('throttle:5,1')->name('threads.store');
    Route::post('/threads/{thread}/like', 'toggleLike')->middleware('throttle:15,1')->name('threads.like');
    Route::delete('/boards/{board}/threads/{thread}', 'destroy')->middleware('throttle:3,1')->name('threads.destroy');
  });

  Route::controller(PostController::class)->group(function () {
    Route::post('/threads/{thread}/posts', 'store')->middleware('throttle:15,1')->name('posts.store');
    Route::post('/posts/{post}/like', 'toggleLike')->middleware('throttle:15,1')->name('posts.like');
    Route::post('/threads/{thread}/posts/{post}', 'store')->middleware('throttle:15,1')->name('posts.mention');
    Route::delete('/posts/{post}', 'destroy')->middleware('throttle:3,1')->name('posts.destroy');
  });

  Route::controller(ReportController::class)->group(function () {
    Route::post('/report', 'store')->middleware('throttle:5,1')->name('reports.store');
  });

  Route::controller(UserController::class)->group(function () {
    Route::get('/users/mypage', 'mypage')->middleware('throttle:30,1')->name('mypage');
    Route::get('/users/edit', 'edit')->middleware('throttle:30,1')->name('users.edit');
    Route::patch('/users/edit', 'update')->middleware('throttle:5,1')->name('users.update');
    Route::delete('/users/edit', 'destroy')->middleware('throttle:3,1')->name('users.destroy');
  });



  Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->middleware('throttle:30,1')
    ->name('notifications.read');


  Route::post('/threads/{thread}/subscribe', [ThreadSubscriptionController::class, 'toggle'])
    ->middleware('throttle:30,1')
    ->name('threads.subscribe');
});

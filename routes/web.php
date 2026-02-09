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


Route::controller(WelcomeController::class)->group(function () {
  Route::get('/', 'index')->middleware('throttle:10,1')->name('welcome');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {


  Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware('throttle:30,1')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('throttle:5,1')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('throttle:3,1')->name('profile.destroy');
  });



  Route::controller(BoardController::class)->group(function () {
    Route::get('/board/index', 'index')->middleware('throttle:30,1')->name('dashboard');
  });

  Route::controller(ThreadController::class)->group(function () {
    Route::get('/boards/{board}/threads', 'index')->middleware('throttle:30,1')->name('threads.index');
    Route::get('/boards/{board}/threads/{thread}', 'show')->middleware('throttle:30,1')->name('threads.show');
    Route::post('/boards/threads/{board}', 'store')->middleware('throttle:5,1')->name('threads.store');
    Route::post('/threads/{thread}/like', 'toggleLike')->middleware('throttle:10,1')->name('threads.like');
    Route::delete('/boards/{board}/threads/{thread}', 'destroy')->middleware('throttle:3,1')->name('threads.destroy');
  });

  Route::controller(PostController::class)->group(function () {
    Route::post('/threads/{thread}/posts', 'store')->middleware('throttle:10,1')->name('posts.store');
    Route::post('/posts/{post}/like', 'toggleLike')->middleware('throttle:10,1')->name('posts.like');
    Route::post('/threads/{thread}/posts/{post}', 'store')->middleware('throttle:10,1')->name('posts.mention');
    Route::delete('/posts/{post}', 'destroy')->middleware('throttle:3,1')->name('posts.destroy');
  });

  Route::controller(ReportController::class)->group(function () {
    Route::post('/report', 'store')->middleware('throttle:5,1')->name('reports.store');
  });

  Route::controller(UserController::class, 'mypage')->group(function () {
    Route::get('users/mypage', 'mypage')->middleware('throttle:30,1')->name('mypage');
    Route::get('users/mypage/edit', 'edit')->middleware('throttle:30,1')->name('mypage.edit');
    Route::put('users/mypage/edit', 'update')->middleware('throttle:5,1')->name('mypage.update');
    Route::get('users/mypage/password/edit', 'edit_password')->middleware('throttle:30,1')->name('mypage.edit_password');
    Route::put('users/mypage/password', 'update_password')->middleware('throttle:3,1')->name('mypage.update_password');
  });
});

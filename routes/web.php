<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;



Route::get('/board/index', [BoardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/', [WelcomeController::class, 'index'])->name('welcomm');

Route::middleware(['auth', 'verified'])->group(function () {

  Route::controller(ThreadController::class)->group(function () {
    Route::get('/boards/{board}/threads', 'index')->name('threads.index');
    Route::get('/boards/{board}/threads/{thread}', 'show')->name('threads.show');
    Route::post('/boards/threads/{board}', 'store')->name('threads.store');
    Route::post('/threads/{thread}/like', 'toggleLike')->name('threads.like');
  });

  Route::controller(PostController::class)->group(function () {
    Route::post('/threads/{thread}/posts', 'store')->name('posts.store');
  });

  Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])
    ->name('posts.like');
});

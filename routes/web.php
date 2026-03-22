<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueDemoController;
use App\Http\Controllers\RedisCacheController;
use App\Http\Controllers\SessionDemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Session demo — one URL for displaying, setting, and deleting a session value.
     */
    Route::get('/session-testing', [SessionDemoController::class, 'index'])->name('session.demo');
    Route::post('/session-testing', [SessionDemoController::class, 'store'])->name('session.store');
    Route::delete('/session-testing', [SessionDemoController::class, 'destroy'])->name('session.destroy');

    /**
     * There should be 100 Users in DB. If not, run php artisan db:seed. We can test Redis, by
     * measuring the time to get these 100 users for the first request (that is obviously not cached)
     * and then for the second request (that is cached).
     */
    Route::get('/redis-testing', [RedisCacheController::class, 'index'])->name('redis.demo');

    /**
     * Queue demo — dispatch jobs, view pending/failed counts, and learn how queues work.
     */
    Route::get('/queue-testing', [QueueDemoController::class, 'index'])->name('queue.demo');
    Route::post('/queue-testing', [QueueDemoController::class, 'dispatch'])->name('queue.dispatch');

    // File upload download routes
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::resource('posts', PostController::class);

});

require __DIR__.'/auth.php';

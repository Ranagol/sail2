<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Jobs\SendTestEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/**
 * Writing data into session
 */
Route::get('/session-set', function () {
    session(['demo' => 'hello']);
    return "Session value set!";
});

/**
 * Getting data from the session
 */
Route::get('/session-get', function () {
    return session('demo', 'not found');
});

/**
 * Rate limitin example. The url /rate-test can be requested only 3 times in 1 minute, from the same
 * IP addres. Then returns OK response. 4. time a 429 error (too many requests) is returned. This
 * is how this rate limiting works.
 */
Route::middleware('throttle:3,1')->get('/rate-test', function () {
    return "OK";
});

/**
 * There should be 100 Users in DB. If not, run php artisan db:seed. We can test Redis, by
 * measuring the time to get these 100 users for the first request (that is obviously not cached)
 * and then for the second request (that is cached).
 */
Route::get('/redis-testing', function () {

    // $users = Cache::remember('users.all', 600, function () {
    //     return User::all();
    // });

    // // return $users as JSON response
    // return response()->json($users);

    $start = microtime(true);

    // Cache the users for 60 seconds
    $users = Cache::remember('users.all', 60, function () {
        return User::all();  // Fetch all users from the database
    });

    $executionTime = microtime(true) - $start;  // Measure elapsed time

    return response()->json([
        'execution_time_seconds' => $executionTime,
        'users_count' => $users->count(),
    ]);

});

/**
 * Here we want to send 100 test emails. We dispatch a job for each email, and the job will handle
 * the actual sending of the email. This way we can test the queue system.
 *
 * Trigger the worker with this command:    sail artisan queue:work.
 */
Route::get('/queue-testing', function () {

    for ($i = 0; $i < 100; $i++) {
        SendTestEmailJob::dispatch();
    }

    return 'Test email job dispatched!';
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //File upload download routes
     Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');


});

require __DIR__.'/auth.php';




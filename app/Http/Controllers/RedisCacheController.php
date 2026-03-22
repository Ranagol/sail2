<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class RedisCacheController extends Controller
{
    public function index(): View
    {
        /**
         * This is the first request (without Redis cache). It will be slower.
         */
        $firstStart = microtime(true);
        Cache::forget('users.all');
        $usersFirstLoad = Cache::remember('users.all', 60, function () {
            return User::all();
        });
        $firstDuration = microtime(true) - $firstStart;

        /**
         * This is the second request (with Redis cache). It should be much faster.
         */
        $secondStart = microtime(true);

        /** @infection-ignore-all */
        Cache::remember('users.all', 60, function () {
            return User::all();
        });
        $secondDuration = microtime(true) - $secondStart;

        /** @infection-ignore-all */
        $speedupFactor = $firstDuration > 0 ? round($firstDuration / max($secondDuration, 0.000001), 1) : null;

        return view('redis.demo', [
            'usersCount' => $usersFirstLoad->count(),
            'firstDurationMs' => round($firstDuration * 1000.0, 3),
            'secondDurationMs' => round($secondDuration * 1000.0, 3),
            'speedupFactor' => $speedupFactor,
        ]);
    }
}

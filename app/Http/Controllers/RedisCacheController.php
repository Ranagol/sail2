<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class RedisCacheController extends Controller
{
    public function index(): View
    {
        $firstStart = microtime(true);
        Cache::forget('users.all');
        $usersFirstLoad = Cache::remember('users.all', 60, function () {
            return User::all();
        });
        $firstDuration = microtime(true) - $firstStart;

        $secondStart = microtime(true);
        $usersCachedLoad = Cache::remember('users.all', 60, function () {
            return User::all();
        });
        $secondDuration = microtime(true) - $secondStart;

        $speedupFactor = $firstDuration > 0 ? round($firstDuration / max($secondDuration, 0.000001), 1) : null;

        return view('redis.demo', [
            'usersCount' => $usersFirstLoad->count(),
            'firstDurationMs' => round($firstDuration * 1000, 3),
            'secondDurationMs' => round($secondDuration * 1000, 3),
            'speedupFactor' => $speedupFactor,
        ]);
    }
}

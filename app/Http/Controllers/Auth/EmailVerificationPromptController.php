<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Email verification is disabled for this application.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return redirect()->intended(route('dashboard', absolute: false));
    }
}

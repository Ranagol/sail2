<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionDemoController extends Controller
{
    public function index(Request $request): View
    {
        return view('session.demo', [
            'sessionValue' => $request->session()->get('demo'),
        ]);
    }

    public function handle(Request $request): RedirectResponse
    {
        if ($request->input('_action') === 'set') {
            $request->session()->put('demo', 'hello');
            $status = 'Session value stored successfully.';
        } else {
            $request->session()->forget('demo');
            $status = 'Session value deleted successfully.';
        }

        return redirect()->route('session.demo')->with('status', $status);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionDemoController extends Controller
{
    public function set(Request $request): RedirectResponse
    {
        $request->session()->put('demo', 'hello');

        return redirect()
            ->route('session.get')
            ->with('status', 'Session value stored successfully.');
    }

    public function get(Request $request): View
    {
        return view('session.demo', [
            'sessionValue' => $request->session()->get('demo', 'not found'),
        ]);
    }

    public function delete(Request $request): RedirectResponse
    {
        $request->session()->forget('demo');

        return redirect()
            ->route('session.get')
            ->with('status', 'Session value deleted successfully.');
    }
}

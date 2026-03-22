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

            // We look for a 'demo' session key. If it exists, we pass its value to the view. If not, we pass null.
            'sessionValue' => $request->session()->get('demo'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // We store a value in the session under the key 'demo'. The value is 'hello'.
        $request->session()->put('demo', 'hello');

        return redirect()->route('session.demo')->with('status', 'Session value stored successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // We remove the 'demo' key from the session.
        $request->session()->forget('demo');

        return redirect()->route('session.demo')->with('status', 'Session value deleted successfully.');
    }
}

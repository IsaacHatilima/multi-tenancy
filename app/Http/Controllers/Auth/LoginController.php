<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
            'socialAuth' => [
                'google' => config('auth.socialAuth.google'),
                'github' => config('auth.socialAuth.github'),
                'facebook' => config('auth.socialAuth.facebook'),
            ],
        ]);
    }

    public function authenticate(LoginRequest $request)
    {
        // TODO:
        //       add email 2FA

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            session(['tenant_id' => tenant('id')]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid E-mail or Password provided.',
        ])->onlyInput('email');
    }
}

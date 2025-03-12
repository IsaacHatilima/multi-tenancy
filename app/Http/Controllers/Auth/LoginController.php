<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
            'twoFactorType' => session('email') ? $this->auth_type(session('email')) : null,
            'fortifyAuth' => config('auth.fortify_auth'),
            'socialAuth' => [
                'google' => config('auth.socialAuth.google'),
                'github' => config('auth.socialAuth.github'),
                'facebook' => config('auth.socialAuth.facebook'),
            ],
        ]);
    }

    /**
     * Display the login view.
     */
    public function auth_type($email)
    {
        return User::where('email', $email)->first()?->two_factor_type;
    }

    public function auth_check(LoginRequest $request)
    {
        $twoFactorType = $this->auth_type($request->email);

        if ($twoFactorType) {
            session(['email' => $request->email]);
        }

        return redirect()->back();
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

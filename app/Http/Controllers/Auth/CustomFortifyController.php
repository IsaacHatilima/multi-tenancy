<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CurrentPasswordRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class CustomFortifyController extends Controller
{
    /*
     * This design decision was preferred to have custom fortify manager routes
     * and use one password confirm manager
     * */
    public function enable(CurrentPasswordRequest $request, EnableTwoFactorAuthentication $enable2FA)
    {
        $enable2FA($request->user());

        return redirect()->back();
    }

    public function disable(CurrentPasswordRequest $request, DisableTwoFactorAuthentication $disable2FA)
    {
        $disable2FA($request->user());

        return redirect()->back();
    }

    public function confirm(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $request->validate([
            'code' => ['required', 'numeric'],
        ]);

        $user = $request->user();

        if (! $provider->verify(decrypt($user->two_factor_secret), $request->code)) {
            return back()->withErrors([
                'code' => 'Invalid code provided.',
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return redirect()->back();
    }
}

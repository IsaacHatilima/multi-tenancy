<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\CurrentPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SecurityController extends Controller
{
    public function __construct(private readonly SetPasswordAction $setPasswordAction) {}

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Security', [
            'otpCode' => auth()->user()->two_factor_secret ? decrypt(auth()->user()->two_factor_secret) : '',
            'fortify' => config('auth.fortify_auth'),
            'social_auth' => $request->user()?->password === null,
        ]);
    }

    public function copy_recovery_codes()
    {
        auth()->user()->update(['copied_codes' => true]);

        return redirect()->back();
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $this->setPasswordAction->change_password($request);

        return back();
    }

    public function email_two_factor(CurrentPasswordRequest $request, $authType): RedirectResponse
    {
        auth()->user()->update([
            'two_factor_type' => $authType == 'disable' ? null : $authType,
            'two_factor_secret' => $authType != 'fortify' ? null : auth()->user()->two_factor_secret,
            'two_factor_confirmed_at' => $authType != 'fortify' ? null : auth()->user()->two_factor_confirmed_at,
            'two_factor_recovery_codes' => $authType != 'fortify' ? null : auth()->user()->two_factor_recovery_codes,
        ]);

        return back();
    }
}

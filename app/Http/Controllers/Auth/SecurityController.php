<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
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
            'fortify' => config('fortify.auth'),
            'social_auth' => $request->user()?->password === null,
        ]);
    }

    public function copy_recovery_codes()
    {
        auth()->user()->update(['copied_codes' => true]);

        return response(null, 200);
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $this->setPasswordAction->change_password($request);

        return back();
    }
}

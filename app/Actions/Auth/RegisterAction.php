<?php

namespace App\Actions\Auth;

use App\Actions\Profile\ProfileManagerAction;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;

class RegisterAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ProfileManagerAction $profileManagerAction)
    {
        //
    }

    public function register($request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $this->profileManagerAction->create_profile($request, $user);

        $user->notify(new VerifyEmailNotification($user));

        return $user;
    }

    public function create_user($request)
    {
        // TODO: generate password and email it to user
        $user = User::create([
            'tenant_id' => tenant()->id,
            'email' => $request->email,
            'password' => 'Password1#',
        ]);

        $this->profileManagerAction->create_profile($request, $user);

        $user->notify(new VerifyEmailNotification($user));

        return $user;
    }

    public function googleRegister($request)
    {
        $user = User::create([
            'email' => $request->email,
            'email_verified_at' => now(),
        ]);

        $this->profileManagerAction->create_profile($request, $user);

        return $user;
    }
}

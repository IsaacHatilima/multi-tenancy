<?php

namespace App\Actions\Auth;

use App\Actions\Profile\ProfileManagerAction;
use App\Models\User;
use App\Notifications\UserPasswordNotification;
use App\Notifications\VerifyEmailNotification;

readonly class RegisterAction
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
        $password = $this->generateStrongPassword();

        $user = User::create([
            'tenant_id' => tenant()->id,
            'email' => $request->email,
            'password' => $password,
        ]);

        $this->profileManagerAction->create_profile($request, $user);

        $user->notify(new UserPasswordNotification($user, $password, tenant()));
        $user->notify(new VerifyEmailNotification($user));

        return $user;
    }

    private function generateStrongPassword(): string
    {
        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_=+<>?';

        $password = [
            $upperCase[random_int(0, strlen($upperCase) - 1)],
            $lowerCase[random_int(0, strlen($lowerCase) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $specialChars[random_int(0, strlen($specialChars) - 1)],
        ];

        $allCharacters = $upperCase.$lowerCase.$numbers.$specialChars;

        for ($i = 4; $i < 16; $i++) {
            $password[] = $allCharacters[random_int(0, strlen($allCharacters) - 1)];
        }

        shuffle($password);

        return implode('', $password);
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

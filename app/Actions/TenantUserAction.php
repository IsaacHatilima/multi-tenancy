<?php

namespace App\Actions;

use App\Actions\Profile\ProfileManagerAction;
use App\Models\User;
use App\Notifications\UserPasswordNotification;
use App\Notifications\VerifyEmailNotification;

class TenantUserAction
{
    private ProfileManagerAction $profileManager;

    /**
     * Create a new class instance.
     */
    public function __construct(ProfileManagerAction $profileManager)
    {
        $this->profileManager = $profileManager;
    }

    public function get_tenant_users($tenant, $request)
    {
        return $tenant->run(function () use ($request) {
            $query = User::query()->with('profile')->orderBy('created_at', 'DESC');

            if ($request->filled('first_name')) {
                $query->whereHas('profile', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%'.ucwords($request->first_name).'%');
                });
            }

            if ($request->filled('last_name')) {
                $query->whereHas('profile', function ($q) use ($request) {
                    $q->where('last_name', 'like', '%'.ucwords($request->last_name).'%');
                });
            }

            if ($request->filled('email')) {
                $query->where('email', 'like', '%'.strtolower($request->email).'%');
            }

            if ($request->filled('role')) {
                $query->where('role', 'like', '%'.strtolower($request->role).'%');
            }

            return $query->paginate(10)->withQueryString()->toArray();
        });
    }

    public function create_user($request)
    {
        $password = $this->generateStrongPassword();

        $user = User::create([
            'tenant_id' => tenant()->id,
            'email' => strtolower($request->email),
            'password' => $password,
            'role' => strtolower($request->role),
        ]);

        $this->profileManager->create_profile($request, $user);

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

    public function update_profile($request, $user): void
    {
        $user->profile->first_name = ucwords($request->first_name);
        $user->profile->last_name = ucwords($request->last_name);
        $user->profile->gender = $request->gender;
        $user->profile->date_of_birth = $request->date_of_birth;

        $user->email = strtolower($request->email);
        $user->role = strtolower($request->role);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->profile->save();
        $user->save();
    }

    public function toggle_user_status($user): void
    {
        $user->is_active = ! $user->is_active;
        $user->save();
    }

    public function delete_user($user): void
    {
        $user->delete();
    }
}

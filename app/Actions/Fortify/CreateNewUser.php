<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Notifications\AdminNewUserRegistered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'instrument' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        /** @var User $user */
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'instrument' => $input['instrument'],
            'password' => $input['password'],
        ]);

        if ($this->isAdminEmail($user->email)) {
            $user->markAsAdminVerified();
        }

        $adminEmails = config('admin.emails', []);

        if ($adminEmails !== []) {
            Notification::route('mail', $adminEmails)
                ->notify(new AdminNewUserRegistered($user));
        }

        return $user;
    }

    protected function isAdminEmail(string $email): bool
    {
        return in_array(strtolower($email), config('admin.emails', []), true);
    }
}

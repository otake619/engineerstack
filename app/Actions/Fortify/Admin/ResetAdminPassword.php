<?php

namespace App\Actions\Fortify\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetAdminPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the admin's forgotten password.
     *
     * @param  mixed  $admin
     * @param  array  $input
     * @return void
     */
    public function reset($admin, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $admin->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}

<?php

namespace App\Actions\Fortify\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateAdminProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given admin's profile information.
     *
     * @param  mixed  $admin
     * @param  array  $input
     * @return void
     */
    public function update($admin, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $admin->email &&
            $admin instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($admin, $input);
        } else {
            $admin->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified admin's profile information.
     *
     * @param  mixed  $admin
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($admin, array $input)
    {
        $admin->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $admin->sendEmailVerificationNotification();
    }
}

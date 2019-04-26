<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait ValidatesUserRequests
{
    /**
     * Validate user info update
     *
     * @param  Request $request
     *
     * @throws ValidationException
     */
    protected function validateUpdate(Request $request)
    {
        if ($request->user->email === $request->input('email')) {
            $email_rule = 'email';
        } else {
            $email_rule = 'sometimes|max:255|email|unique:users,email';
        }

        $this->validate($request, [
            'name' => 'sometimes|max:50|alpha_num',
            'email'    => $email_rule,
            'password' => 'sometimes|min:8',
        ]);
    }
}
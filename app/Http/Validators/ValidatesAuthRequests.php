<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait ValidatesAuthRequests
{
    /**
     * Validate login request input
     *
     * @param  Request $request
     *
     * @throws ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|max:255|email',
            'password' => 'required',
        ]);
    }

    /**
     * Validate refresh_token input
     *
     * @param  Request $request
     *
     * @throws ValidationException
     */
    protected function validateRefreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token'    => 'required|size:32|alpha_num'
        ]);
    }

    /**
     * Validate register request input
     *
     * @param  Request $request
     *
     * @throws ValidationException
     */
    protected function validateRegister(Request $request)
    {
        $this->validate($request, [
            'captcha_response' => 'required',
            'name' => 'required|max:50|alpha_num',
            'email'    => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:8',
        ]);
    }
}
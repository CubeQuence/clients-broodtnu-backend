<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesAuthRequests
{
    /**
     * Validate login request input
     *
     * @param  Request $request
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
     */
    protected function validateRefreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|size:32|alpha_num'
        ]);
    }

    /**
     * Validate register request input but don't check existing email
     *
     * @param  Request $request
     */
    protected function validateRegisterPreCaptcha(Request $request)
    {
        $this->validate($request, [
            'captcha_response'  => 'required',
            'name'              => 'required|max:50|alpha_num',
            'email'             => 'required|max:255|email',
            'password'          => 'required|min:8',
        ]);
    }

    /**
     * Validate register request input
     *
     * @param  Request $request
     */
    protected function validateRegisterPostCaptcha(Request $request)
    {
        $this->validate($request, [
            'captcha_response'  => 'required',
            'name'              => 'required|max:50|alpha_num',
            'email'             => 'required|max:255|email|unique:users,email',
            'password'          => 'required|min:8',
        ]);
    }
}

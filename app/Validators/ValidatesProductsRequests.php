<?php

namespace App\Validators;

use Illuminate\Http\Request;

trait ValidatesProductsRequests
{
    /**
     * Validate creation of new product
     *
     * @param  Request $request
     */
    protected function validateCreate(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|max:255|email',
            'password' => 'required',
        ]);
    }

    /**
     * Validate updating of existing product
     *
     * @param  Request $request
     */
    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|size:32|alpha_num'
        ]);
    }
}
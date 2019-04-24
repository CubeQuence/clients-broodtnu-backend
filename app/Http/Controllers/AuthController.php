<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Helper\JWTHelper;

class AuthController extends Controller {
    /**
     * Add JWTHelper so it can be accessed
     *
     * @param JWTHelper   $jwt
     */
    public function __construct(JWTHelper $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param Request $request
     *
     * @return mixed
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('email', $request->get('email'))->first();

        // Validate user credentials
        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'error' => 'Email or password is wrong.',
            ], 400);
        }

        // Return token for successful auth
        return response()->json($this->jwt->issue($user->id), 200);
    }

    // refresh function
    // call JWT refresh

    // logout function
    // call jwt logout function
}
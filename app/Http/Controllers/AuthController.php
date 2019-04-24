<?php


namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Validation\ValidationException;

class AuthController extends Controller {
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

        if (!$user) {

            // TODO: Add helper to assure same style messages.
            return response()->json([
                'error' => 'Email does not exist.',
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($request->get('password'), $user->password)) {

            // TODO: Add helper to assure same style messages.
            return response()->json([
                'token' => $this->generateJWT($user),
            ], 200);
        }

        // Bad Request response
        // TODO: Add helper to assure same style messages.
        return response()->json([
            'error' => 'Email or password is wrong.',
        ], 400);
    }

    /**
     * Create a new token.
     *
     * @param User $user
     *
     * @return string
     */
    protected function generateJWT(User $user)
    {
        $payload = [
            'iss' => "Broodt-Nu", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + config('JWT.ttl') // Expiration time
        ];

        return JWT::encode($payload, config('JWT.private_key'), config('JWT.algorithm'));
    }
}
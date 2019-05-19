<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RefreshToken;
use App\Http\Helpers\JWTHelper;
use App\Http\Validators\ValidatesUserRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ValidatesUserRequests;

    /**
     * View user account
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(User::findOrFail($request->user->id));
    }

    /**
     * Update user account
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws
     */
    public function update(Request $request)
    {
        $this->validateUpdate($request);

        $user = User::findOrFail($request->user->id);
        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        $user->save();

        // If user resets password revoke all refresh_tokens
        if ($request->input('password')) {
            JWTHelper::revokeAllRefreshTokens($user->id);
        }

        return response()->json($user, 200);
    }

    /**
     * Delete user account
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        JWTHelper::revokeAllRefreshTokens($request->user->id);
        User::findOrFail($request->user->id)->delete();

        return response()->json(null, 204);
    }
}

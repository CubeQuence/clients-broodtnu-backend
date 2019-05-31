<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\JWTHelper;
use App\Helpers\HttpStatusCodes;
use App\Validators\ValidatesUserRequests;
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
        return response()->json(
            User::findOrFail($request->user->id),
            HttpStatusCodes::SUCCESS_OK
        );
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

        return response()->json(
            $user,
            HttpStatusCodes::SUCCESS_OK
        );
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

        return response()->json(
            null,
            HttpStatusCodes::SUCCESS_NO_CONTENT
        );
    }
}

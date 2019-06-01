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
        $user = User::findOrFail($request->user->id);

        $this->validateUpdate($request, $user);

        $user->update([
            'name' => $request->get('name', $user->name),
            'email' => $request->get('email', $user->email),
            'password' => $request->has('password') ? Hash::make($request->input('password')) : $user->password
        ]);

        $user->save();

        // If user changes password revoke all refresh_tokens
        if ($request->has('password')) {
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

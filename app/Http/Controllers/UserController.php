<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * View user account
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function view(Request $request)
    {
        return response()->json(User::findOrFail($request->auth->id));
    }

    /**
     * Update user account
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->auth->id);
        $user->update($request->all());

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
        User::findOrFail($request->auth->id)->delete();

        return response()->json(null, 204);
    }
}

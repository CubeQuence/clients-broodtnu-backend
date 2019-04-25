<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Http\ResponseFactory;

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
     * @return Response|ResponseFactory
     */
    public function delete(Request $request)
    {
        User::findOrFail($request->auth->id)->delete();

        return response(null, 204);
    }
}

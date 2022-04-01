<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $admin = Gate::authorize('delete', 'users');

        if (!$admin) {
            return response(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::get();

        return UserResource::collection($user, Response::HTTP_ACCEPTED);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['message' => 'User not found']);
        }

        return new UserResource($user, Response::HTTP_ACCEPTED);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = Auth::user();

        $user->update($request->only('first_name', 'middle_name', 'last_name', 'email'));

        return response(['message' => 'User information successfully updated'], Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response(['message' => 'Current password is incorrect'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response(['message' => 'Password updated successfully'], Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['messsage' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response(['message' => 'User deleted successfully'], Response::HTTP_ACCEPTED);
    }

    public function currentUser()
    {
        $user = Auth::user();

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);

        return response([$user], Response::HTTP_ACCEPTED);
    }
}

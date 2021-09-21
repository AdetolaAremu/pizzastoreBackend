<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('delete', 'users');

        return RoleResource::collection(Role::get());
    }

    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role ID not found', Response::HTTP_NOT_FOUND]);
        }

        return new RoleResource($role, Response::HTTP_ACCEPTED);
    }

    public function store(RoleCreateRequest $request)
    {
        $role = Role::create($request->only('name'));

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(['message' => 'Role created successfully']);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role ID not found', Response::HTTP_NOT_FOUND]);
        }

        $role->update($request->only('name'));

        DB::table('role_permission')->where('role_id', $role->id)->delete();

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response(['message' => 'Role ID not found'], Response::HTTP_NOT_FOUND);
        }

        DB::table('role_permission')->where('role_id', $id)->delete();

        $role->delete();

        return response(['message' => 'Role deleted successfully'], Response::HTTP_ACCEPTED);
    }
}

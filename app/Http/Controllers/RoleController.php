<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Role::with('permissions')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name']
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json($role, 201);
    }
    public function assignPermissions(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($request->permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'role' => $role->load('permissions')
        ]);
    }
}

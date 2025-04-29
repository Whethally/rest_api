<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Auth;
use Illuminate\Http\Request;


class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name']
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json($permission, 201);
    }
}

<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;

class RoleController extends Controller
{
    public function index() {
        return response()->json(['success' => true, 'roles' => Role::with('permissions')->get()], 200);
    }


    public function store(Request $request) {
        try {
            $validated = $request->validate(['name' => 'required|string|unique:roles']);
            $role = Role::create(['name' => $validated['name']]);
            return response()->json(['success' => true, 'message' => 'Role created', 'role' => $role], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create role'], 500);
        }
    }


    public function update(Request $request, Role $role) {
        try {
            $validated = $request->validate(['name' => 'required|string|unique:roles,name,' . $role->id]);
            $role->update(['name' => $validated['name']]);
            return response()->json(['success' => true, 'message' => 'Role updated', 'role' => $role], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update role'], 500);
        }
    }


    public function destroy(Role $role) {
        try {
            $role->permissions()->detach();
            $role->users()->detach();
            $role->delete();
            return response()->json(['success' => true, 'message' => 'Role deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete role'], 500);
        }
    }


    public function assignPermissions(Request $request, Role $role) {
        try {
            $validated = $request->validate(['permission_ids' => 'required|array']);
            $role->permissions()->sync($validated['permission_ids']);
            return response()->json(['success' => true, 'message' => 'Permissions assigned', 'role' => $role->load('permissions')], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to assign permissions'], 500);
        }
    }

    
}
<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Exception;

class PermissionController extends Controller
{
    public function index() {
        return response()->json(['success' => true, 'permissions' => Permission::all()], 200);
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate(['name' => 'required|string|unique:permissions']);
            $permission = Permission::create(['name' => $validated['name']]);
            return response()->json(['success' => true, 'message' => 'Permission created', 'permission' => $permission], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create permission'], 500);
        }
    }


    public function destroy(Permission $permission) {
        try {
            if ($permission->roles()->count() > 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot delete! This permission is assigned to one or more roles.'
                ], 422);
            }
            $permission->delete();
            return response()->json(['success' => true, 'message' => 'Permission deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete permission'], 500);
        }
    }

}
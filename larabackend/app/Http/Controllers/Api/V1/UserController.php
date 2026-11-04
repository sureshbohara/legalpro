<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Exception;

class UserController extends Controller
{
    public function index(){
        try {
            $users = User::with('roles')->where('id', '!=', auth()->id())->get();
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function store(StoreUserRequest $request){
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['status'] = $request->has('status') ? $request->status : 1;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('users', 'public');
            }

            $user = User::create($data);
            $user->roles()->attach($data['role_id']);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user->load('roles')
            ], 201); 
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create user'], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user){
        try {
            $data = $request->validated();
            
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $data['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($data);
            $user->roles()->sync([$data['role_id']]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user->load('roles')
            ], 200); 
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update user'], 500);
        }
    }


    public function destroy(User $user){
        try {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->roles()->detach();
            $user->delete(); 
            return response()->json(['success' => true, 'message' => 'User deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }

     public function userStatus(Request $request, User $user){
        try {
            $request->validate(['status' => 'required|in:0,1']);
            $user->update(['status' => $request->status]);
            $message = $request->status == 1 ? 'User activated successfully' : 'User deactivated successfully';
            return response()->json([
                'success' => true,
                'message' => $message,
                'user' => $user->only(['id', 'name', 'email', 'status'])
            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }
}
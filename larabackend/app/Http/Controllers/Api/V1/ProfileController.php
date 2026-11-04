<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request){
        try {
            $user = $request->user();
            $data = $request->validated();
            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $data['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh(['roles']) 
            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update profile'], 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request){
        try {
            $user = $request->user();
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to change password'], 500);
        }
    }
}
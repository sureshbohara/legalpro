<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function login(LoginRequest $request){
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }
        $user = Auth::user();
        if ($user->status != 1) {
            Auth::guard('web')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403); 
        }
        // $request->session()->regenerate();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image_url' => $user->image_url,
                'role' => $user->role,
                'bio_info' => $user->bio_info,
                'status' => $user->status,
                'roles' => $user->roles,
            ]
        ], 200); 
    }

    public function user(Request $request){
        $user = $request->user();
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image_url' => $user->image_url,
                'role' => $user->role,
                'bio_info' => $user->bio_info,
                'status' => $user->status,
                'roles' => $user->roles,
            ]
        ], 200);
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    
}
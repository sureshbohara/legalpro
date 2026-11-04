<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller {
    

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $client = Client::where('email', $request->email)->first();
        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }
        if ($client->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact the firm.'
            ], 403);
        }
        $token = $client->createToken('client-portal-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'role' => 'client'
            ]
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    public function profile(Request $request) {
        return response()->json([
            'success' => true,
            'client' => $request->user()
        ]);
    }
    
}
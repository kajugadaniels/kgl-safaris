<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function getUsers()
    {
        try {
            // Check if the user is authenticated via Sanctum
            if (!Auth::check()) {
                return response()->json(['message' => 'Login to access this page.'], 401);
            }

            // If authenticated, proceed to retrieve users
            $users = User::all();

            return response()->json(['users' => $users], 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required'
            ], [
                'email.exists' => 'The selected email is invalid or the account does not exist.',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'credentials' => ['Incorrect credentials. Please try again.'],
                ]);
            }

            $token = $user->createToken($user->name)->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'name' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}

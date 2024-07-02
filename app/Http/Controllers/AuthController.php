<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Resource not found.'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error.'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected error occurred.'], 500);
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}

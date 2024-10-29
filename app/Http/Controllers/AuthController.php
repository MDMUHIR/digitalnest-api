<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return $this->success('Successfully created user!', [
                'accessToken' => $token,
            ]);
        } else {
            return $this->error('Provide proper details');
        }
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->error('Unauthorized');
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return $this->success('Successfully logged in', [
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function user(Request $request) {
        return response()->json($request->user());
    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUsers() {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
        ]);
    }


    public function addUser(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'type' => 'required|string', // Validate that type is required and must be a string
        ]);
    
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type, // Assign the type to the user
        ]);
    
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User added successfully',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add user',
            ], 500);
        }
    }

    public function deleteUser(Request $request, $id) {
        $user = User::find($id);
        $user->delete();
        return $this->success('User deleted successfully', $user);
    }

    public function updateUser(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:users,id', // Ensure that the user exists
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $request->id,
            'type' => 'required|string',
            'password' => 'nullable|string|min:6', // Password is optional and should have a minimum length if provided
        ]);
    
        $user = User::find($request->id);
        if (!$user) {
            return $this->error('User not found', 404);
        }
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
    
        // Update the password only if it is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        return $this->success('User updated successfully', $user);
    }
    
}

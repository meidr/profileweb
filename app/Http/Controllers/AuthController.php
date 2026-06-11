<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function check()
    {
        return response()->json(['authenticated' => Auth::check(), 'user' => Auth::user()]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Logged in']);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password'])) {
                return response()->json(['message' => 'Current password is required to set a new password.'], 400);
            }
            if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
                return response()->json(['message' => 'The provided current password does not match our records.'], 400);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        // Update the session to prevent logging out the user after password change
        if (!empty($validated['new_password'])) {
            Auth::login($user);
        }

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
}

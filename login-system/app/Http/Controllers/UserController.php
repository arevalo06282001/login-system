<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Make sure the email and password fields are filled in correctly
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Try to log the user in with the provided credentials
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // If login fails, send the user back with a generic error message
        return back()->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput();
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the user input before creating a new account
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Create a new user record in the database
            $user = User::create([
                'name' => strip_tags($request->name), // basic sanitization
                'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
                'password' => Hash::make($request->password), // password is hashed for security
            ]);

            // Log the new user in right after registration
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Account created successfully.');
        } catch (\Throwable $e) {
            // Log the error for debugging but show a safe message to the user
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function dashboard()
    {
        // Get all users to display in the dashboard
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function edit($id)
    {
        // Find the user or throw a 404 if not found
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Fetch the user we want to update
        $user = User::findOrFail($id);

        // Validate the input, allow password to be optional
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        try {
            // Update the fields
            $user->name = strip_tags($request->name);
            $user->email = filter_var($request->email, FILTER_SANITIZE_EMAIL);

            // Only update the password if one was provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('dashboard')->with('success', 'User updated successfully!');
        } catch (\Throwable $e) {
            Log::error('User update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update user.');
        }
    }

    public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    try {
        // Create the user
        User::create([
            'name' => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'New user created successfully.');
    } catch (\Throwable $e) {
        Log::error('Create user failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to create user. Please try again.');
    }
}

    public function destroy($id)
    {
        // Find the user we want to delete
        $user = User::findOrFail($id);

        // Stop a logged-in user from deleting themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('dashboard')->with('error', 'You cannot delete your own account while logged in.');
        }

        try {
            $user->delete();
            return redirect()->route('dashboard')->with('success', 'User deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('User delete failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete user.');
        }
    }

    public function logout(Request $request)
    {
        // Log the user out and clear their session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}

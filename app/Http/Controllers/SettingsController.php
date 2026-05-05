<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        return view('settings');
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('settings')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'current_password.required' => 'Please enter your current password',
            'current_password.current_password' => 'The current password is incorrect',
            'password.required' => 'Please enter a new password',
            'password.confirmed' => 'Passwords do not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters',
            'password.numbers' => 'Password must contain at least one number',
            'password.symbols' => 'Password must contain at least one special character',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('settings')->with('success', 'Password updated successfully!');
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        $preferences = [
            'email_notifications' => $request->has('email_notifications'),
            'weekly_digest' => $request->has('weekly_digest'),
            'language' => $request->input('language', 'en'),
        ];

        // Store preferences in user metadata or separate preferences column
        // For now, we'll just store in a JSON column if available, or create a preferences table
        // For this implementation, we'll store as JSON in a preferences column
        $user->update([
            'preferences' => json_encode($preferences),
        ]);

        return redirect()->route('settings')->with('success', 'Preferences updated successfully!');
    }
}

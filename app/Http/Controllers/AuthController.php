<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController - Handles user authentication (login and logout)
 * 
 * This controller manages:
 * - User registration with name, email, and password
 * - User login with email and password
 * - Session creation and user authentication
 * - User logout and session destruction
 */
class AuthController extends Controller
{
    /**
     * Store the correct active company in the session.
     *
     * Admin users are allowed to choose any company, so we leave the session
     * empty until they pick one.
     * Normal users are locked to the company assigned to their account.
     */
    private function syncActiveCompanySession(User $user, Request $request): void
    {
        // Load the user's role once so we can make a simple admin check.
        $userRoleName = $user->role?->name;

        if ($userRoleName === 'Admin') {
            // Admins can view all companies, so no fixed company is forced.
            $request->session()->forget('active_company_id');
            return;
        }

        // Normal users are always tied to their own company.
        $request->session()->put('active_company_id', $user->company_id);
    }

    /**
     * Handle signup form submission
     *
     * This method:
     * 1. Validates the registration form inputs
     * 2. Creates a new user record with the default 'Employee' role
     * 3. Logs the new user in automatically
     * 4. Redirects to the dashboard on success
     *
     * @param Request $request - Contains name, email, password, and password confirmation
     * @return \Illuminate\Http\RedirectResponse - Redirects to dashboard or back to signup
     */
    public function register(Request $request)
    {
        //  Validate the incoming registration request
        // - name must be provided
        // - email must be valid and unique
        // - password must be at least 6 characters and match the confirmation field
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            // Custom messages keep the form friendly for beginners
            'name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        //  Get the Employee role ID.
        // All new signups start as employees for safety.
        // Admins can change roles later from the Users page.
        $employeeRole = Role::all()->firstWhere('name', 'Employee');

        // Step 3: Create the user record with the employee role
        // Laravel will hash the password automatically because the User model uses a hashed cast
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $employeeRole?->id,  // Assign employee role by default
        ]);

        // Step 4: Log the new user in immediately
        Auth::login($user);

        // Step 5: Regenerate the session for security
        $request->session()->regenerate();

        // Step 6: Save the active company context in the session.
        $this->syncActiveCompanySession($user, $request);

        // Step 7: Send the user to the dashboard with a success message
        return redirect()
            ->route('dashboard')
            ->with('success', 'Your account has been created successfully!');
    }

    /**
     * Handle login form submission
     * 
     * This method:
     * 1. Validates the email and password inputs
     * 2. Checks if a user exists with that email
     * 3. Verifies the password is correct
     * 4. Creates an authenticated session if credentials are valid
     * 5. Redirects to dashboard on success or back with error on failure
     * 
     * @param Request $request - Contains email and password from form
     * @return \Illuminate\Http\RedirectResponse - Redirects to dashboard or back to login
     */
    public function login(Request $request)
    {
        // Step 1: Validate the incoming request
        // - email must be provided, valid email format, and max 255 chars
        // - password must be provided and at least 6 characters
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ], [
            // Custom error messages for better user experience
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        // Step 2: Try to authenticate using Laravel's Auth facade
        // This will check if a user exists with this email and verify the password
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Get the authenticated user so we can set the active company context.
            $user = Auth::user();
            
            // Step 3: If authentication succeeds, regenerate the session
            // This is a security best practice to prevent session fixation attacks
            $request->session()->regenerate();

            // Step 4: Save the active company context in the session.
            if ($user instanceof User) {
                $this->syncActiveCompanySession($user, $request);
            }

            // Step 5: Redirect to the dashboard
            return redirect()->route('dashboard')->with('success', 'You have been logged in successfully!');
        }

        // Step 5: If authentication fails, redirect back to login with error message
        // withInput() keeps the email field filled so user doesn't have to retype it
        return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password. Please try again.');
    }

    /**
     * Handle user logout
     * 
     * This method:
     * 1. Logs out the authenticated user
     * 2. Invalidates the current session
     * 3. Regenerates the session token
     * 4. Redirects to login page
     * 
     * @param Request $request - The current request
     * @return \Illuminate\Http\RedirectResponse - Redirects to login page
     */
    public function logout(Request $request)
    {
        // Step 1: Log out the currently authenticated user
        Auth::logout();

        // Step 2: Invalidate the user's session
        $request->session()->invalidate();

        // Step 3: Regenerate the session token as security best practice
        $request->session()->regenerateToken();

        // Step 4: Redirect to login page with success message
        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully!');
    }
}

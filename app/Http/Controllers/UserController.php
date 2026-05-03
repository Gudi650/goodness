<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;

/**
 * UserController
 * 
 * Handles listing users and managing their roles and company assignments.
 * Only admins should have access to this controller.
 */
class UserController extends Controller
{
    /**
     * Show all users with their roles and companies.
     * 
     * Supports searching by name or email.
     */
    public function index(Request $request)
    {
        // Read search keyword from URL query string
        $search = trim((string) $request->query('search', ''));

        // Start building a query for users, eagerly load role and company relationships
        $usersQuery = User::with('role', 'company');

        // Apply search filter only when user has typed something
        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get all users with newest first
        $users = $usersQuery->latest()->get();

        // Get all available roles for the assignment dropdown
        $roles = Role::orderBy('name')->get();

        // Get all available companies for the assignment dropdown
        $companies = Company::orderBy('name')->get();

        return view('users', [
            'users' => $users,
            'roles' => $roles,
            'companies' => $companies,
            'search' => $search,
        ]);
    }

    /**
     * Update a user's role.
     * 
     * This is called when an admin assigns a new role to a user.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ], [
            'role_id.required' => 'Role is required.',
            'role_id.exists' => 'Invalid role selected.',
        ]);

        // Update the user's role
        $user->update(['role_id' => $validated['role_id']]);

        // Redirect back with success message
        return redirect()
            ->route('users')
            ->with('success', "Role updated for {$user->name}.");
    }

    /**
     * Update a user's company assignment.
     * 
     * This is called when an admin assigns a company to a user.
     */
    public function updateCompany(Request $request, User $user)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
        ], [
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Invalid company selected.',
        ]);

        // Update the user's company
        $user->update(['company_id' => $validated['company_id']]);

        // Redirect back with success message
        $companyName = Company::find($validated['company_id'])->name ?? 'Unknown';
        return redirect()
            ->route('users')
            ->with('success', "{$user->name} is now assigned to {$companyName}.");
    }
}

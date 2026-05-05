<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * UserController
 * 
 * Handles listing users and managing their roles and company assignments.
 * Only admins should have access to this controller.
 */
class UserController extends Controller
{
    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'company_id' => 'nullable|exists:companies,id',
            'join_date' => 'nullable|date',
        ], [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'department_id.exists' => 'Invalid department selected.',
            'company_id.exists' => 'Invalid company selected.',
            'join_date.date' => 'Join date must be a valid date.',
        ]);

        $companyId = $validated['company_id'] ?? $activeCompanyId;
        if (! $isAdmin && ! empty($currentUser?->company_id)) {
            $companyId = $currentUser->company_id;
        }

        if (empty($companyId)) {
            return redirect()
                ->route('hrm')
                ->with('error', 'Please select a company before creating an employee.');
        }

        $departmentId = $validated['department_id'] ?? null;
        if (! empty($departmentId)) {
            $department = Department::query()
                ->where('id', $departmentId)
                ->where('company_id', $companyId)
                ->first();

            if (! $department) {
                return redirect()
                    ->route('hrm')
                    ->with('error', 'Selected department does not belong to the chosen company.');
            }
        }

        $employeeRole = Role::query()->where('name', 'Employee')->first();

        // Use phone_number as password if provided, otherwise use default
        $password = ! empty($validated['phone_number']) 
            ? $validated['phone_number'] 
            : 'password123';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role_id' => $employeeRole?->id,
            'company_id' => $companyId,
            'department_id' => $departmentId,
            'phone_number' => $validated['phone_number'] ?? null,
        ]);

        if (! empty($validated['join_date'])) {
            $user->created_at = $validated['join_date'] . ' 00:00:00';
            $user->save();
        }

        return redirect()
            ->route('hrm')
            ->with('success', "Employee '{$validated['name']}' has been created successfully.");
    }

    /**
     * Show all users with their roles and companies.
     * 
     * Supports searching by name or email.
     */
    public function index(Request $request)
    {
        // Read search keyword from URL query string
        $search = trim((string) $request->query('search', ''));

        // Determine the active company scope.
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        // Start building a query for users, eagerly load role and company relationships
        $usersQuery = User::with('role', 'company');

        // Admins can filter by the active company selected in the topbar.
        // Normal users are locked to the company assigned on their profile.
        if ($isAdmin) {
            if (! empty($activeCompanyId)) {
                $usersQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $usersQuery->where('company_id', $currentUser?->company_id);
        }

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
        $roles = Role::all()->sortBy('name')->values();

        // Get all available companies for the assignment dropdown
        $companies = Company::all()->sortBy('name')->values();

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
        $company = Company::all()->firstWhere('id', $validated['company_id']);
        $companyName = $company?->name ?? 'Unknown';
        return redirect()
            ->route('users')
            ->with('success', "{$user->name} is now assigned to {$companyName}.");
    }

    /**
     * Delete an employee from HRM.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        if ($isAdmin) {
            if (! empty($activeCompanyId) && (int) $user->company_id !== (int) $activeCompanyId) {
                return redirect()->route('hrm')->with('error', 'You are not authorized to delete this employee.');
            }
        } elseif ((int) $user->company_id !== (int) $currentUser?->company_id) {
            return redirect()->route('hrm')->with('error', 'You are not authorized to delete this employee.');
        }

        User::query()->where('id', $user->id)->delete();

        return redirect()->route('hrm')->with('success', 'Employee deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

/**
 * RoleController
 *
 * Simple controller to handle role creation from the UI.
 * Kept intentionally small and beginner-friendly.
 */
class RoleController extends Controller
{
    /**
     * Store a newly created role in storage.
     *
     * Validates the input and creates a new Role record.
     * Redirects back to the users page with a success message.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request. 'name' must be unique in roles table.
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Please provide a role name.',
            'name.unique' => 'A role with this name already exists.',
        ]);

        // Create the role using mass assignment; Role model uses $fillable for safety.
        Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Redirect back to the users page with a friendly success message.
        return redirect()->route('users')->with('success', 'Role created successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DepartmentController
 *
 * Handles department creation and management.
 */
class DepartmentController extends Controller
{
    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'company_id' => 'nullable|exists:companies,id',
        ], [
            'name.required' => 'Department name is required.',
            'name.max' => 'Department name cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ]);

        // Determine which company to associate with.
        // Prefer the explicit form selection, then the active session company,
        // and finally the current user's company for non-admin users.
        $companyId = $validated['company_id'] ?? $activeCompanyId;
        if (!$isAdmin && !empty($currentUser?->company_id)) {
            $companyId = $currentUser->company_id;
        }

        if (empty($companyId)) {
            return redirect()
                ->route('hrm')
                ->with('error', 'Please select a company before creating a department.');
        }

        // Create the department
        Department::create([
            'company_id' => $companyId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('hrm')
            ->with('success', "Department '{$validated['name']}' has been created successfully.");
    }
}

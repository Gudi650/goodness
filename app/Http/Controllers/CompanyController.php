<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CompanyController
 *
 * Handles listing and creating companies.
 */
class CompanyController extends Controller
{
    /**
     * Save the active company selected from the topbar.
     *
     * Admins may switch between companies or choose all companies.
     * Normal users are forced back to their assigned company so they cannot
     * override access by changing the dropdown value manually.
     */
    public function setActiveCompany(Request $request)
    {
        $user = Auth::user();

        // If we do not have a logged-in user, just send them back.
        if (! $user) {
            return redirect()->back();
        }

        $isAdmin = $user->role?->name === 'Admin';

        if ($isAdmin) {
            // Admins can select any company, or leave it blank to view all companies.
            $validated = $request->validate([
                'company_id' => 'nullable|exists:companies,id',
            ]);

            if (empty($validated['company_id'])) {
                $request->session()->forget('active_company_id');
            } else {
                $request->session()->put('active_company_id', (int) $validated['company_id']);
            }

            return redirect()->back();
        }

        // Non-admin users are always locked to their assigned company.
        $request->session()->put('active_company_id', $user->company_id);

        return redirect()->back();
    }

    /**
     * Show the companies page with data from the database.
     *
     * Supports simple search by name or country using the `search` query parameter.
     */
    public function index(Request $request)
    {
        // Read search keyword from URL query string.
        $search = trim((string) $request->query('search', ''));

        // Determine who is viewing the page so we can apply the right company scope.
        $currentUser = Auth::user();

        // If no user is authenticated, redirect to login
        if (! $currentUser) {
            return redirect()->route('login');
        }

        $isAdmin = $currentUser->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        // Start building a query for companies.
        $companiesQuery = Company::query();

        // Admins can filter to one company from the session, or see all companies.
        // Normal users are always limited to their own assigned company.
        if ($isAdmin) {
            if (! empty($activeCompanyId)) {
                $companiesQuery->where('id', $activeCompanyId);
            }
            // Admins can see all companies if no active company is selected
        } else {
            // Non-admin users: use their assigned company
            $userCompanyId = $currentUser->company_id;
            if ($userCompanyId) {
                $companiesQuery->where('id', $userCompanyId);
            } else {
                // User has no company assigned - return empty result with message
                return view('companies', [
                    'companies' => collect(),
                    'search' => $search,
                ]);
            }
        }

        // Apply search filter only when user has typed something.
        if ($search !== '') {
            $companiesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Newest companies appear first.
        $companies = $companiesQuery->latest()->get();

        return view('companies', [
            'companies' => $companies,
            'search' => $search,
        ]);
    }

    /**
     * Store a new company in the database.
     */
    public function store(Request $request)
    {
        // Validate incoming form data.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'revenue' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ], [
            'name.required' => 'Company name is required.',
            'country.required' => 'Country is required.',
            'revenue.required' => 'Revenue is required.',
            'revenue.numeric' => 'Revenue must be a valid number.',
            'revenue.min' => 'Revenue cannot be negative.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be Active or Inactive.',
        ]);

        // Create the company record.
        Company::create($validated);

        // Redirect back to companies page with a success flash message.
        return redirect()
            ->route('companies')
            ->with('success', 'Company registered successfully.');
    }

    /**
     * Delete a company (soft or hard delete depending on model setup).
     */
    public function destroy(Request $request, Company $company)
    {
        $user = Auth::user();
        $isAdmin = $user?->role?->name === 'Admin';

        // Only admins may delete arbitrary companies. Non-admins may only
        // delete if the company matches their assigned company.
        if (! $isAdmin && $user->company_id !== $company->id) {
            return redirect()->back()->withErrors('You are not authorized to delete this company.');
        }

        // Perform delete via query to satisfy static analyzer (soft delete if enabled).
        Company::query()->where('id', $company->id)->delete();

        return redirect()->route('companies')->with('success', 'Company deleted successfully.');
    }
}

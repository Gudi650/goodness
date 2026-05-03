<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

/**
 * CompanyController
 *
 * Handles listing and creating companies.
 */
class CompanyController extends Controller
{
    /**
     * Show the companies page with data from the database.
     *
     * Supports simple search by name or country using the `search` query parameter.
     */
    public function index(Request $request)
    {
        // Read search keyword from URL query string.
        $search = trim((string) $request->query('search', ''));

        // Start building a query for companies.
        $companiesQuery = Company::query();

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
}

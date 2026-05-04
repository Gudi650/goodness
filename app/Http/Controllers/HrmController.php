<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

/**
 * HrmController
 *
 * Serves HRM data views.
 */
class HrmController extends Controller
{
    /**
     * Show HRM page with employees and departments fetched from database.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');
        $companies = $isAdmin ? Company::orderBy('name')->get() : collect([$currentUser?->company])->filter();

        $usersQuery = User::with('role', 'company', 'department');

        if ($isAdmin) {
            if (!empty($activeCompanyId)) {
                $usersQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $usersQuery->where('company_id', $currentUser?->company_id);
        }

        $employees = $usersQuery
            ->latest()
            ->get()
            ->map(function (User $user) {
                $departmentName = $user->department?->name ?? 'Unassigned';

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'department' => $departmentName,
                    'position' => $user->role?->name ?? 'Unassigned',
                    'company_name' => $user->company?->name ?? 'Unassigned',
                    'join_date' => optional($user->created_at)->format('Y-m-d') ?? '-',
                    'status' => 'Active',
                ];
            })
            ->values();

        // Fetch departments for the active company
        $departmentsQuery = Department::query();
        if ($isAdmin) {
            if (!empty($activeCompanyId)) {
                $departmentsQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $departmentsQuery->where('company_id', $currentUser?->company_id);
        }

        $departments = $departmentsQuery
            ->latest()
            ->get()
            ->map(function (Department $dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'description' => $dept->description ?? '-',
                    'created_at' => optional($dept->created_at)->format('Y-m-d') ?? '-',
                ];
            })
            ->values();

        return view('hrm', [
            'employees' => $employees,
            'employeeNames' => $employees->pluck('name')->values(),
            'departments' => $departments,
            'companies' => $companies,
            'isAdmin' => $isAdmin,
            'activeCompanyId' => $activeCompanyId,
        ]);
    }
}

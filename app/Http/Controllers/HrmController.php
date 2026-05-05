<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Salary;
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

        $companies = $isAdmin
            ? Company::query()->orderBy('name', 'asc')->get()
            : collect($currentUser?->company ? [$currentUser->company] : []);

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
            ->with('company')
            ->latest()
            ->get()
            ->map(function (Department $dept) {
                return [
                    'id' => $dept->id,
                    'company_id' => $dept->company_id,
                    'name' => $dept->name,
                    'description' => $dept->description ?? '-',
                    'company_name' => $dept->company?->name ?? 'Unassigned',
                    'created_at' => optional($dept->created_at)->format('Y-m-d') ?? '-',
                ];
            })
            ->values();

        $departmentOptions = Department::query()
            ->orderBy('name', 'asc')
            ->get()
            ->map(function (Department $dept) {
                return [
                    'id' => $dept->id,
                    'company_id' => $dept->company_id,
                    'name' => $dept->name,
                ];
            })
            ->values();

        // Fetch recent salary records for the active company (limited)
        $salaryQuery = Salary::with('user');
        if ($isAdmin) {
            if (!empty($activeCompanyId)) {
                $salaryQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $salaryQuery->where('company_id', $currentUser?->company_id);
        }

        $salaries = $salaryQuery->orderBy('effective_date', 'desc')
            ->limit(200)
            ->get()
            ->map(function (Salary $s) {
                return [
                    'id' => $s->id,
                    'user_id' => $s->user_id,
                    'employee' => $s->user?->name ?? 'Unknown',
                    'basic' => $s->amount,
                    'deductions' => $s->deductions ?? 0,
                    'net' => $s->net_amount ?? ($s->amount - ($s->deductions ?? 0)),
                    'effective_date' => optional($s->effective_date)->format('Y-m-d') ?? '-',
                    'status' => 'Recorded',
                ];
            })
            ->values();

        return view('hrm', [
            'employees' => $employees,
            'employeeNames' => $employees->pluck('name')->values(),
            'departments' => $departments,
            'departmentOptions' => $departmentOptions,
            'companies' => $companies,
            'isAdmin' => $isAdmin,
            'activeCompanyId' => $activeCompanyId,
            'salaries' => $salaries,
        ]);
    }
}

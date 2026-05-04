<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * HrmController
 *
 * Serves HRM data views.
 */
class HrmController extends Controller
{
    /**
     * Show HRM page with employees fetched from database.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        $usersQuery = User::with('role', 'company');

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
                $roleName = $user->role?->name ?? 'Unassigned';

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'department' => $roleName,
                    'position' => $roleName,
                    'company_name' => $user->company?->name ?? 'Unassigned',
                    'join_date' => optional($user->created_at)->format('Y-m-d') ?? '-',
                    'status' => 'Active',
                ];
            })
            ->values();

        return view('hrm', [
            'employees' => $employees,
            'employeeNames' => $employees->pluck('name')->values(),
        ]);
    }
}

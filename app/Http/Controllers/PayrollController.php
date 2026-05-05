<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('active_company_id') ?? auth()->user()?->company_id;

        $salaries = Salary::with('user')
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('effective_date', 'desc')
            ->limit(200)
            ->get();

        $employees = User::where('company_id', $companyId)->get();

        return view('payroll.index', compact('salaries', 'employees'));
    }

    public function store(Request $request)
    {
        $companyId = session('active_company_id') ?? auth()->user()?->company_id;

        $data = $request->validate([
            'user_id' => ['required','exists:users,id'],
            'amount' => ['required','numeric','min:0'],
            'deductions' => ['nullable','numeric','min:0'],
            'net_amount' => ['required','numeric'],
            'currency' => ['nullable','string','max:10'],
            'effective_date' => ['nullable','date'],
            'frequency' => ['nullable','string','max:50'],
            'notes' => ['nullable','string','max:2000'],
        ]);

        $data['company_id'] = $companyId;
        $data['created_by'] = auth()->id();

        // Ensure deductions default
        $data['deductions'] = $data['deductions'] ?? 0;

        // If net_amount wasn't provided correctly, compute it server-side for safety
        if (!isset($data['net_amount']) || $data['net_amount'] === null) {
            $data['net_amount'] = $data['amount'] - $data['deductions'];
        }

        Salary::create($data);

        return back()->with('success', 'Salary record saved.');
    }

    public function update(Request $request, Salary $salary)
    {
        $companyId = session('active_company_id') ?? auth()->user()?->company_id;

        // Ensure user can only update salaries in their company
        if ($salary->company_id !== $companyId) {
            return back()->withErrors('Unauthorized');
        }

        $data = $request->validate([
            'amount' => ['required','numeric','min:0'],
            'deductions' => ['nullable','numeric','min:0'],
            'net_amount' => ['required','numeric'],
            'currency' => ['nullable','string','max:10'],
            'effective_date' => ['nullable','date'],
            'frequency' => ['nullable','string','max:50'],
            'notes' => ['nullable','string','max:2000'],
        ]);

        // Ensure deductions default
        $data['deductions'] = $data['deductions'] ?? 0;

        // If net_amount wasn't provided correctly, compute it server-side for safety
        if (!isset($data['net_amount']) || $data['net_amount'] === null) {
            $data['net_amount'] = $data['amount'] - $data['deductions'];
        }

        $salary->update($data);

        return back()->with('success', 'Salary record updated.');
    }
}

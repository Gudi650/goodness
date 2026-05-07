<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $invoices = Invoice::with(['company', 'creator', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        //get the companies
        $companies = DB::table('companies')->pluck('name', 'id');

        // Get departments with company mapping for dependent dropdowns in modals.
        $departments = DB::table('departments')
            ->select('id', 'name', 'company_id')
            ->orderBy('name')
            ->get();

        

        $expenses = [];
        $payments = [];

        return view('finance', [
            'invoices' => $invoices,
            'expenses' => $expenses,
            'payments' => $payments,
            'companies' => $companies,
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

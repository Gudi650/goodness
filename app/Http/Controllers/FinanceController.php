<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //get the current logged in user
        $user = Auth::user();

        //get teh role of the user
        $isAdmin = $user && $user->role && $user->role->name === 'Admin';
        $isManager = $user && $user->role && $user->role->name === 'Manager';
        $isHr = $user && $user->role && $user->role->name === 'HR Manager';
        $isCEO = $user && $user->role && $user->role->name === 'CEO';

        //get the active company id from the session
        $activeCompanyId = session('active_company_id');

        $usersQuery = User::with('role', 'company', 'department');

        if ($isAdmin && !empty($activeCompanyId)) {
            $usersQuery->where('company_id', $activeCompanyId);
        } elseif ($user) {
            $usersQuery->where('company_id', $user->company_id);
        }

        // finalize the query
        $users = $usersQuery->get();

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

        //function to get the expense details to be displayed from the expenses table
        $expenses = $this->getExpenses($isAdmin, $activeCompanyId,$user,$isManager,$isHr,$isCEO);

        // Summary metrics for top cards
        $expensesCollection = collect($expenses);
        $totalExpensesCount = $expensesCollection->count();
        $totalExpensesAmount = $expensesCollection->sum(fn ($e) => (float) ($e['net_amount'] ?? 0));
        $pendingApprovals = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'checked')->count();
        $approvedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'approved')->count();
        $draftedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'draft')->count();
        $issuedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'issued')->count();

        $payments = Payment::query()
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function (Payment $payment) {
                $attachmentUrl = $payment->proof_of_payment_path ? asset('storage/' . $payment->proof_of_payment_path) : null;
                $attachmentIsImage = false;

                if ($payment->proof_of_payment_path) {
                    $ext = strtolower(pathinfo($payment->proof_of_payment_path, PATHINFO_EXTENSION));
                    $attachmentIsImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                }

                $tzsEquivalent = $payment->currency === 'TZS'
                    ? (float) $payment->amount
                    : (float) ($payment->amount * $payment->exchange_rate);

                return [
                    'id' => $payment->id,
                    'payment_reference' => $payment->payment_reference,
                    'payment_date_value' => Carbon::parse($payment->payment_date)->format('Y-m-d'),
                    'payment_date' => Carbon::parse($payment->payment_date)->format('M d, Y'),
                    'company' => $payment->company,
                    'payment_direction' => $payment->payment_direction,
                    'party_name' => $payment->party_name,
                    'payment_method' => $payment->payment_method,
                    'reference_number' => $payment->reference_number ?: '-',
                    'payment_category' => $payment->payment_category ?: '-',
                    'linked_to' => $payment->linked_to ?: '-',
                    'amount' => (float) $payment->amount,
                    'currency' => $payment->currency,
                    'exchange_rate' => (float) $payment->exchange_rate,
                    'tzs_equivalent' => $tzsEquivalent,
                    'payment_status' => $payment->payment_status,
                    'notes' => $payment->notes ?: '-',
                    'proof_of_payment_path' => $payment->proof_of_payment_path,
                    'original_proof_filename' => $payment->original_proof_filename,
                    'attachment_url' => $attachmentUrl,
                    'attachment_is_image' => $attachmentIsImage,
                    'edit_url' => route('payments.edit', $payment->id),
                    'update_url' => route('payments.update', $payment->id),
                    'delete_url' => route('payments.destroy', $payment->id),
                    'download_url' => route('payments.download-proof', $payment->id),
                ];
            })
            ->all();

        return view('finance', [
            'invoices' => $invoices,
            'expenses' => $expenses,
            'payments' => $payments,
            'companies' => $companies,
            'departments' => $departments,
            'totalExpensesCount' => $totalExpensesCount,
            'totalExpensesAmount' => $totalExpensesAmount,
            'pendingApprovals' => $pendingApprovals,
            'approvedCount' => $approvedCount,
            'draftedCount' => $draftedCount,
            'issuedCount' => $issuedCount,
        ]);
    }

    /**
     * fuction to get the expense details to be displayed from the expenses table
     * but check if the user is admin or CEO, if admin then get all expenses, if not admin then get only the expenses of his company
     */
    protected function getExpenses($isAdmin, $activeCompanyId, $user, $isManager, $isHr, $isCEO)
    {
        //get all when user is admin or CEO, otherwise get only the expenses of his company
        $expenses = Expense::with(['company', 'department', 'creator', 'approver', 'issuer', 'checker'])
            ->when(!$isAdmin && !$isCEO && $user, fn($query) => $query->where('company_id', $user->company_id))
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (Expense $expense) {
                $attachmentUrl = $expense->attachment_path ? asset('storage/' . $expense->attachment_path) : null;
                $attachmentIsImage = false;
                if ($expense->attachment_path) {
                    $ext = strtolower(pathinfo($expense->attachment_path, PATHINFO_EXTENSION));
                    $attachmentIsImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                }

                return [
                    'id' => $expense->id,
                    'display_id' => $expense->expense_number,
                    'expense_date' => Carbon::parse($expense->expense_date)->format('M d, Y'),
                    'company_name' => $expense->company?->name ?? '-',
                    'department_name' => $expense->department?->name ?? '-',
                    'category' => $expense->category,
                    'sub_category' => $expense->sub_category ?: '-',
                    'payment_method' => $expense->payment_method,
                    'reference_number' => $expense->reference_number ?: '-',
                    'amount' => (float) $expense->net_amount,
                    'gross_amount' => (float) $expense->amount,
                    'vat_included' => (bool) $expense->vat_included,
                    'vat_rate' => (float) $expense->vat_rate,
                    'vat_amount' => (float) $expense->vat_amount,
                    'net_amount' => (float) $expense->net_amount,
                    'status' => $expense->status,
                    'description' => $expense->notes ?: '-',
                    'notes' => $expense->notes ?: '-',
                    'creator_name' => $expense->creator?->name ?? '-',
                    'approved_by_name' => $expense->approver?->name ?? '-',
                    'issued_by_name' => $expense->issuer?->name ?? '-',
                    'checked_by_name' => $expense->checker?->name ?? '-',
                    'submitted_at' => $expense->submitted_at ? Carbon::parse($expense->submitted_at)->format('M d, Y h:i A') : '-',
                    'approved_at' => $expense->approved_at ? Carbon::parse($expense->approved_at)->format('M d, Y h:i A') : '-',
                    'attachment_url' => $attachmentUrl,
                    'attachment_is_image' => $attachmentIsImage,
                ];
            })
            ->all();

        return $expenses;

    }

    //function to get the 

}

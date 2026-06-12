<?php

namespace App\Http\Controllers;

use App\Models\AssetsCategories;
use App\Models\CreateAssets;
use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\FinanceItems;
use App\Models\IncomeCategory;
use App\Models\IncomeItem;
use App\Models\Invoice;
use App\Models\ItemsCategory;
use App\Models\LiabilityCategory;
use App\Models\Payment;
use App\Models\User;
use App\Models\VirtualAccounts;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // get the current logged in user
        $user = Auth::user();

        // get teh role of the user
        $isAdmin = $user && $user->role && $user->role->name === 'Admin';
        $isManager = $user && $user->role && $user->role->name === 'Manager';
        $isHr = $user && $user->role && $user->role->name === 'HR Manager';
        $isCEO = $user && $user->role && $user->role->name === 'CEO';
        $isAccountant = $user && $user->role && $user->role->name === 'Accountant';

        // get the active company id from the session
        $activeCompanyId = session('active_company_id');

        $usersQuery = User::with('role', 'company', 'department');

        if ($isAdmin && ! empty($activeCompanyId)) {
            $usersQuery->where('company_id', $activeCompanyId);
        } elseif ($user) {
            $usersQuery->where('company_id', $user->company_id);
        }

        // finalize the query
        $users = $usersQuery->get();

        //
        $invoices = $this->getInvoices($isAdmin, $activeCompanyId, $user);

        // get the companies
        $companies = DB::table('companies')->pluck('name', 'id');

        // Get departments with company mapping for dependent dropdowns in modals.
        $departments = DB::table('departments')
            ->select('id', 'name', 'company_id')
            ->orderBy('name')
            ->get();

        // function to get the expense details to be displayed from the expenses table
        $expenses = $this->getExpenses($isAdmin, $isAccountant, $user, $isCEO);

        // Summary metrics for top cards
        $expensesCollection = collect($expenses);
        $totalExpensesCount = $expensesCollection->count();
        $totalExpensesAmount = $expensesCollection->sum(fn ($e) => (float) ($e['net_amount'] ?? 0));
        $pendingApprovals = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'checked')->count();
        $approvedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'approved')->count();
        $draftedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'draft')->count();
        $issuedCount = $expensesCollection->filter(fn ($e) => ($e['status'] ?? '') === 'issued')->count();

        // get the payment details to be displayed from the payments table
        $payments = $this->getPayments($isAdmin, $user, $isCEO, $isAccountant);

        // function to check if the approve button should be displayed for the expense based on the user role and expense status
        foreach ($expenses as &$expense) {
            $expense['can_approve'] = $this->canApproveExpense($expense, $user, $isManager, $isCEO, $isAccountant);
            $expense['can_review'] = $this->canReviewExpense($expense, $user);
        }

        $reviewableExpenses = collect($expenses)->filter(fn ($expense) => ! empty($expense['can_review']))->values();
        $pendingReviewCount = $reviewableExpenses->count();
        $firstPendingReviewExpenseId = $reviewableExpenses->first()['id'] ?? null;

        // get the details of the virtual accounts to be displayed from the virtual_accounts table
        $virtualAccounts = $this->getVirtualAccounts($isAdmin, $isCEO, $isAccountant, $user);

        // get the assets categories to be displayed from the assets_categories table
        $assetsCategories = $this->getAssetsCategories();

        // get the liabilities categories to be displayed from the liability_categories table
        $liabilityCategories = $this->getLiabilityCategories();

        // get the assets details to be displayed from the create_assets table
        $assetsDetails = $this->getAssetsDetails();

        //get the liailities details to be displayed from the create_liabilities table
        $liabilitiesDetails = $this->getLiabilitiesDetails();

        //get the finance items and its categores to be displayed in the items page
        $items = FinanceItems::with('category')->get();

        // Create a lightweight array for JS
        $itemData = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'description' => $item->description,
                'category_id' => $item->category_id,
                'category_name' => $item->category ? $item->category->category_name : null,
            ];
        }); 

        //get the income items and its categores to be displayed in the items page
        $incomeItems = IncomeItem::with('category')->get();

        //get the finance items category to be displayed in the items page
        $itemsCategories = ItemsCategory::all();

        //get the income categories only to be displayed in the items page
        $incomeCategories = $this->getIncomeCategories();

        //dd($incomeCategories);

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
            'pendingReviewCount' => $pendingReviewCount,
            'firstPendingReviewExpenseId' => $firstPendingReviewExpenseId,
            'virtualAccounts' => $virtualAccounts,
            'assetsCategories' => $assetsCategories,
            'liabilityCategories' => $liabilityCategories,
            'assetsDetails' => $assetsDetails,
            'liabilitiesDetails' => $liabilitiesDetails,
            'items' => $items,
            'itemsCategories' => $itemsCategories,
            'itemData' => $itemData,
            'incomeItems' => $incomeItems,
            'incomeCategories' => $incomeCategories,
        ]);
    }

    /**
     * fuction to get the expense details to be displayed from the expenses table
     * but check if the user is admin or CEO, if admin then get all expenses, if not admin then get only the expenses of his company
     */
    protected function getExpenses($isAdmin, $isAccountant, $user, $isCEO)
    {
        // get all when user is admin or CEO, otherwise get only the expenses of his company
        $expenses = Expense::with(['company','financeItem', 'department', 'creator', 'approver', 'issuer', 'checker'])
            ->when(! $isAdmin && ! $isCEO && ! $isAccountant, fn ($query) => $query->where('company_id', $user->company_id))
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (Expense $expense) {
                $attachmentUrl = $expense->attachment_path ? asset('storage/'.$expense->attachment_path) : null;
                $attachmentIsImage = false;
                if ($expense->attachment_path) {
                    $ext = strtolower(pathinfo($expense->attachment_path, PATHINFO_EXTENSION));
                    $attachmentIsImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                }

                return [
                    'id' => $expense->id,
                    'company_id' => $expense->company_id,
                    'display_id' => $expense->expense_number,
                    'expense_date' => Carbon::parse($expense->expense_date)->format('M d, Y'),
                    'company_name' => $expense->company?->name ?? '-',
                    'department_name' => $expense->department?->name ?? '-',
                    'category' => $expense->category,
                    'sub_category' => $expense->financeItem?->item_name ?? '-',
                    'payment_method' => $expense->payment_method,
                    'reference_number' => $expense->reference_number ?: '-',
                    'amount' => (float) $expense->net_amount,
                    'gross_amount' => (float) $expense->amount,
                    'vat_included' => (bool) $expense->vat_included,
                    'vat_rate' => (float) $expense->vat_rate,
                    'vat_amount' => (float) $expense->vat_amount,
                    'net_amount' => (float) $expense->net_amount,
                    'status' => $expense->status,
                    'description' => $expense->description ?: '-',
                    'notes' => $expense->notes ?: '-',
                    'creator_id' => $expense->created_by,
                    'creator_name' => $expense->creator?->name ?? '-',
                    'approved_by_name' => $expense->approver?->name ?? '-',
                    'issued_by_name' => $expense->issuer?->name ?? '-',
                    'checked_by_name' => $expense->checker?->name ?? '-',
                    'submitted_at' => $expense->submitted_at ? Carbon::parse($expense->submitted_at)->format('M d, Y h:i A') : '-',
                    'approved_at' => $expense->approved_at ? Carbon::parse($expense->approved_at)->format('M d, Y h:i A') : '-',
                    'reviewed_at' => $expense->reviewed_at ? Carbon::parse($expense->reviewed_at)->format('M d, Y h:i A') : null,
                    'review_feedback' => $expense->review_feedback,
                    'review_items' => $expense->review_items ?? [],
                    'review_evidence_paths' => $expense->review_evidence_paths ?? [],
                    'attachment_url' => $attachmentUrl,
                    'attachment_is_image' => $attachmentIsImage,
                    'term' => $expense->term,
                ];
            })
            ->all();

        return $expenses;

    }

    // function to get the payment details to be displayed from the payments table
    protected function getPayments($isAdmin, $user, $isCEO, $isAccountant)
    {
        $payments = Payment::query()
            ->orderByDesc('created_at')
            ->when(! $isAdmin && ! $isCEO && ! $isAccountant && $user, fn ($query) => $query->where('company_id', $user->company_id))
            ->limit(100)
            ->get()
            ->map(function (Payment $payment) {
                $attachmentUrl = $payment->proof_of_payment_path ? asset('storage/'.$payment->proof_of_payment_path) : null;
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

        return $payments;
    }

    // function to display the invoices details to be displayed from the invoices table
    protected function getInvoices($isAdmin, $activeCompanyId, $user)
    {
        $invoices = Invoice::query()
            ->with('company', 'creator', 'items')
            ->when(! $isAdmin && $user, fn ($query) => $query->where('company_id', $user->company_id))
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (Invoice $invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'display_id' => $invoice->invoice_number,
                    'company_id' => $invoice->company_id,
                    'invoice_type' => $invoice->invoice_type,
                    'invoice_date' => Carbon::parse($invoice->invoice_date)->format('M d, Y'),
                    'invoice_date_raw' => $invoice->invoice_date,
                    'due_date' => $invoice->due_date ? Carbon::parse($invoice->due_date)->format('M d, Y') : null,
                    'due_date_raw' => $invoice->due_date,
                    'company_name' => $invoice->company?->name ?? '-',
                    'client_name' => $invoice->client_name,
                    'client_email' => $invoice->client_email,
                    'client_phone' => $invoice->client_phone,
                    'client_address' => $invoice->client_address,
                    'subtotal' => (float) $invoice->subtotal,
                    'tax_amount' => (float) $invoice->tax_amount,
                    'discount_amount' => (float) $invoice->discount_amount,
                    'total_amount' => (float) $invoice->total_amount,
                    'amount' => (float) $invoice->total_amount,
                    'status' => $invoice->status,
                    'payment_method' => $invoice->payment_method,
                    'description' => $invoice->description ?: '-',
                    'notes' => $invoice->notes,
                    'creator_name' => $invoice->creator?->name ?? '-',
                    'created_at' => $invoice->created_at?->format('M d, Y h:i A'),
                    'items' => $invoice->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'item_number' => $item->item_number,
                            'description' => $item->description,
                            'quantity' => (int) $item->quantity,
                            'unit_price' => (float) $item->unit_price,
                            'total_price' => (float) $item->total_price,
                        ];
                    })->all(),
                ];
            })
            ->all();

        return $invoices;
    }

    // function to check if the approve button should be displayed for the expense based on the user role and expense status
    protected function canApproveExpense($expense, $user, $isManager, $isCEO, $isAccountant)
    {
        // check to see if user is neither manager, HR nor CEO, if not then return false
        if (! $isManager && ! $isCEO && ! $isAccountant) {
            return false;
        }

        switch ($expense['status'] ?? '') {
            case 'draft':
                return $isManager && $user->company_id === $expense['company_id'];
            case 'checked':
                return $isCEO;
            case 'approved':
                return $isAccountant;
            default:
                return false;
        }

    }

    /**
     * Decide whether the submitting user can open the review page for this expense.
     * Also check if the expense review is submited if so the review button should not be displayed
     */
    protected function canReviewExpense($expense, $user)
    {
        if (! $user) {
            return false;
        }

        // if its already reviewed then it should return false.
        if (! empty($expense['reviewed_at'])) {
            return false;
        }

        return (int) ($expense['company_id'] ?? 0) === (int) $user->company_id
            && (int) ($expense['creator_id'] ?? 0) === (int) $user->id
            && in_array($expense['status'] ?? '', ['approved', 'issued'], true);
    }

    /**
     * Getting the details of the virtual accounts to be displayed from the virtual_accounts table
     */
    protected function getVirtualAccounts($isAdmin, $isCEO, $isAccountant, $user)
    {

        // check if the user can view the virtual accounts, if not then return empty array
        if (! $this->canViewVirtualAccounts($user, $isAdmin, $isCEO, $isAccountant)) {
            return [];
        }

        $virtualAccounts = VirtualAccounts::query()
            ->with('company')
            ->when(! $isAdmin && ! $isCEO && $user, fn ($query) => $query->where('company_id', $user->company_id))
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (VirtualAccounts $account) {
                return [
                    'id' => $account->id,
                    'bank_name' => $account->bank_name,
                    'account_name' => $account->account_name,
                    'account_number' => $account->account_number,
                    'account_type' => $account->account_type,
                    'card_number' => $account->card_number ?: '-',
                    'company_name' => $account->company?->name ?? '-',
                    'currency' => $account->currency,
                    'balance' => (float) $account->balance,
                    'description' => $account->description ?: '-',
                    'status' => $account->status,
                    'created_at' => $account->created_at?->format('M d, Y h:i A'),
                ];
            })
            ->all();

        return $virtualAccounts;

    }

    /**
     * function to check who can view the virtual accounts, only admin ,CEO and accountant can view the virtual accounts, and if the user is not admin then he can only view the virtual accounts of his company
     */
    protected function canViewVirtualAccounts($user, $isAdmin, $isCEO, $isAccountant)
    {
        if (! $user) {
            return false;
        }

        return $isAdmin || $isCEO || $isAccountant;
    }

    /**
     * function to get the assets categories
     */
    public function getAssetsCategories()
    {
        $AssetsCategories = AssetsCategories::query()
            ->latest()
            ->get()
            ->map(function (AssetsCategories $category) {
                return [
                    'id' => $category->id,
                    'category' => $category->category,
                    'description' => $category->description ?: '-',
                    'created_at' => $category->created_at?->format('M d, Y h:i A'),
                ];
            })
            ->all();

        return $AssetsCategories;
    }

    /**
     * function to get the liabilities categories
     */
    public function getLiabilityCategories()
    {
        $LiabilityCategories = LiabilityCategory::query()
            ->latest()
            ->get()
            ->map(function (LiabilityCategory $category) {
                return [
                    'id' => $category->id,
                    'category' => $category->category,
                    'description' => $category->description ?: '-',
                    'created_at' => $category->created_at?->format('M d, Y h:i A'),
                ];
            })
            ->all();

        return $LiabilityCategories;
    }

    /**
     * function to get the assets details to be displayed from the create_assets table
     */
    public function getAssetsDetails()
    {
        $assetsDetails = CreateAssets::query()
            ->with('company', 'category')
            ->latest()
            ->get()
            ->map(function (CreateAssets $asset) {
                return [
                    'id' => $asset->id,
                    'code' => $asset->code,
                    'name' => $asset->name,
                    'company_name' => $asset->company?->name ?? '-',
                    'category_name' => $asset->category?->category ?? '-',
                    'type' => $asset->type,
                    'term' => $asset->term,
                    'original_value' => (float) $asset->original_value,
                    'current_value' => (float) $asset->current_value,
                    'depreciation_value' => (float) $asset->depreciation_value,
                    'acquired' => $asset->acquired ? Carbon::parse($asset->acquired)->format('M d, Y') : '-',
                    'status' => $asset->status,
                    'created_at' => $asset->created_at?->format('M d, Y h:i A'),
                ];
            })
            ->all();

        return $assetsDetails;
    }

    /**
     * function to get the liabilities details to be displayed from the create_liabilities table
     */
    public function getLiabilitiesDetails()
    {
        $liabilitiesDetails = CreateLiability::query()
            ->with('company', 'category')
            ->latest()
            ->get()
            ->map(function (CreateLiability $liability) {
                return [
                    'id' => $liability->id,
                    'code' => $liability->code,
                    'name' => $liability->name,
                    'company_name' => $liability->company?->name ?? '-',
                    'category_name' => $liability->category?->category ?? '-',
                    'type' => $liability->type,
                    'term' => $liability->term,
                    'original_amount' => (float) $liability->original_amount,
                    'current_amount' => (float) $liability->current_amount,
                    'creditor' => $liability->creditor ?: '-',
                    'interest_rate' => (float) $liability->interest_rate,
                    'due_date' => $liability->due_date ? Carbon::parse($liability->due_date)->format('M d, Y') : '-',
                    'status' => $liability->status,
                    'created_at' => $liability->created_at?->format('M d, Y h:i A'),
                ];
            })
            ->all();

        return $liabilitiesDetails;
    }


    //get the income items and its categores to be displayed in the items page
    public function getItems()
    {
        $IncomeItems = IncomeItem::with('category')
            ->latest()
            ->get();
        return $IncomeItems;
    }

    //get just the income categories
    public function getIncomeCategories()
    {
        $IncomeCategories = IncomeCategory::query()
            ->latest()
            ->get();
        return $IncomeCategories;
    }



}

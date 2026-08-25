<?php

namespace App\Http\Controllers;

use App\Models\BankTransactions;
use App\Models\CreateAssets;
use App\Models\VirtualAccounts;
use App\Services\AccessControlService;
use App\Services\FAR\CalculateCurrentFar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FAR extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        if (! app(AccessControlService::class)->isCeoOrAdminOrAccountant($currentUser) && ! app(AccessControlService::class)->isManager($currentUser)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to the HRM page.');
        }

        $fixedAssets = app(CalculateCurrentFar::class)->calculateDepreciation();
        $banks = VirtualAccounts::query()
            ->where('status', 'active')
            ->orderBy('account_name')
            ->get(['id', 'account_name', 'company_id', 'balance']);

        return view('far', compact('fixedAssets', 'banks'));
    }

    public function dispose(Request $request, CreateAssets $asset)
    {
        $currentUser = Auth::user();

        if (! app(AccessControlService::class)->isCeoOrAdminOrAccountant($currentUser) && ! app(AccessControlService::class)->isManager($currentUser)) {
            return redirect()->route('far')->with('error', 'You are not authorized to dispose assets.');
        }

        if ($asset->status !== 'Active') {
            return redirect()->route('far')->with('error', 'Only active assets can be disposed.');
        }

        $validated = $request->validate([
            'disposal_date' => 'required|date',
            'disposal_method' => 'required|in:Sold,Disposed,Written Off',
            'disposal_proceeds' => 'nullable|numeric|min:0',
            'disposal_bank_id' => 'nullable|exists:virtual_accounts,id',
            'disposal_notes' => 'nullable|string|max:2000',
        ]);

        $proceeds = (float) ($validated['disposal_proceeds'] ?? 0);

        if ($proceeds > 0 && empty($validated['disposal_bank_id'])) {
            return redirect()->route('far')->with('error', 'Select a bank account when disposal proceeds are greater than zero.');
        }

        try {
            DB::transaction(function () use ($asset, $validated, $proceeds) {
                $carryingValue = (float) ($asset->current_value ?? 0);

                if ($proceeds > 0) {
                    $bank = VirtualAccounts::query()
                        ->whereKey($validated['disposal_bank_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($asset->company_id && (int) $bank->company_id !== (int) $asset->company_id) {
                        throw new \RuntimeException('Bank account must belong to the same company as the asset.');
                    }

                    $bank->balance = (float) $bank->balance + $proceeds;
                    $bank->save();

                    BankTransactions::create([
                        'bank_id' => $bank->id,
                        'company_id' => $asset->company_id,
                        'balance_after' => $bank->balance,
                        'affecting_balance' => $proceeds,
                        'transaction_type' => 'asset_disposal',
                    ]);
                }

                $asset->update([
                    'status' => $validated['disposal_method'],
                    'current_value' => 0,
                    'disposal_date' => $validated['disposal_date'],
                    'disposal_proceeds' => $proceeds,
                    'disposal_carrying_value' => $carryingValue,
                    'disposal_bank_id' => $validated['disposal_bank_id'] ?? null,
                    'disposal_notes' => $validated['disposal_notes'] ?? null,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()->route('far')->with('error', $e->getMessage());
        }

        return redirect()->route('far')->with('success', 'Asset disposed successfully.');
    }
}

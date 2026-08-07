<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loans - Goodness Group</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8e5',
                            100: '#fde6a1',
                            500: '#f0b73a',
                            600: '#eaa106',
                            700: '#c88600'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; }
        h1, h2, h3, nav, button { font-family: Outfit, sans-serif; }
        .mono { font-family: ui-monospace, monospace; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">

        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Loans</h1>
                <p class="text-sm text-slate-500">Loan register, repayment schedules and interest tracking across all companies</p>
            </div>

            <!-- Company Filter -->
            <div class="w-full lg:w-56">
                <select id="loanCompanyFilter" onchange="filterLoansByCompany(this.value)"
                    class="w-full rounded border border-slate-300 px-3 py-2 text-sm bg-white">
                    <option value="all">All Companies</option>
                    @foreach ($companies ?? [] as $company)
                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8 overflow-x-auto">
                <button onclick="switchLoanTab('register', this)" class="loan-tab-btn py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer whitespace-nowrap">Loan Register</button>
                <button onclick="switchLoanTab('schedule', this)" class="loan-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Repayment Schedule</button>
                <button onclick="switchLoanTab('repayments', this)" class="loan-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Repayments</button>
                <button onclick="switchLoanTab('summary', this)" class="loan-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Interest Summary</button>
            </div>
        </div>

        <!-- Action Row -->
        <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
            <h2 id="loanSectionTitle" class="text-lg font-semibold">Loan Register</h2>

            <div class="flex gap-6">
                <div id="loanActionButton" class="w-full lg:w-auto"></div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div id="loanTabContent" class="space-y-6">

                {{-- ============================= LOAN REGISTER ============================= --}}
                <div id="loan-tab-register" class="loan-tab-panel">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Loan Register</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Loan Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Company</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Lender</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Principal</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Interest Rate</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Term</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Outstanding</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($loans ?? [] as $loan)
                                    <tr class="hover:bg-slate-50 loan-row" data-company="{{ $loan->company->name ?? '-' }}">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $loan->code }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-brand-50 text-brand-700 border border-brand-100">{{ $loan->company->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3 font-medium">{{ $loan->lender }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($loan->principal) }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $loan->interest_rate }}% <span class="text-xs text-slate-400">({{ $loan->interest_type }})</span></td>
                                        <td class="px-4 py-3">{{ $loan->term_months }} months</td>
                                        <td class="px-4 py-3 text-right mono font-medium">TZS {{ number_format($loan->outstanding_balance) }}</td>
                                        <td class="px-4 py-3">
                                            @if ($loan->status === 'Active')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Active</span>
                                            @elseif($loan->status === 'Closed')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600 border border-slate-200">Closed</span>
                                            @elseif($loan->status === 'Overdue')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Overdue</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $loan->status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-4">
                                                <button type="button" onclick="toggleLoanDetails('{{ $loan->id }}')"
                                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                                    title="View loan details" aria-label="View loan details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('loans.schedule.regenerate', $loan->id) }}" method="POST"
                                                    onsubmit="return confirm('Regenerate the repayment schedule for {{ $loan->code }}? This replaces any existing installments.');">
                                                    @csrf
                                                    <button type="submit" class="text-brand-600 hover:text-brand-700 transition-colors"
                                                        title="Regenerate repayment schedule" aria-label="Regenerate repayment schedule">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="loan-details-{{ $loan->id }}" class="hidden bg-slate-50/70 loan-row" data-company="{{ $loan->company->name ?? '-' }}">
                                        <td colspan="9" class="px-4 py-4">
                                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Purpose</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->purpose ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Collateral</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->collateral ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Guarantor</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->guarantor ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Approved By</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->approvedBy->name ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Disbursement Date</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->disbursement_date?->format('d M Y') ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Maturity Date</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->maturity_date->format('d M Y') }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Interest Payable: TZS</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ number_format($loan->total_interest_payable) }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Repayable: TZS</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ number_format($loan->total_repayable) }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->created_at?->format('d M Y H:i') ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $loan->updated_at?->format('d M Y H:i') ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-3 text-center text-slate-500">No loans recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= REPAYMENT SCHEDULE ============================= --}}
                <div id="loan-tab-schedule" class="loan-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Repayment Schedule</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Loan Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Company</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Installment #</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Due Date</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Principal</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Interest</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Installment</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($repaymentSchedule ?? [] as $installment)
                                    <tr class="hover:bg-slate-50 loan-row" data-company="{{ $installment->loan->company->name ?? '-' }}">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $installment->loan->code ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-brand-50 text-brand-700 border border-brand-100">{{ $installment->loan->company->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right mono">{{ $installment->installment_number }}</td>
                                        <td class="px-4 py-3">{{ $installment->due_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($installment->principal_portion) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($installment->interest_portion) }}</td>
                                        <td class="px-4 py-3 text-right mono font-medium">TZS {{ number_format($installment->total_installment) }}</td>
                                        <td class="px-4 py-3">
                                            @if ($installment->status === 'Paid')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Paid</span>
                                            @elseif($installment->status === 'Overdue')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Overdue</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($installment->status !== 'Paid')
                                                <form action="{{ route('loans.schedule.mark-paid', $installment->id) }}" method="POST"
                                                    onsubmit="return confirm('Mark installment #{{ $installment->installment_number }} as paid?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">Mark Paid</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-3 text-center text-slate-500">No repayment schedule generated</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= REPAYMENTS ============================= --}}
                <div id="loan-tab-repayments" class="loan-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Repayments Made</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Loan Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Company</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Amount Paid</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Principal</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Interest</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Method</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Balance After</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Recorded By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($repayments ?? [] as $repayment)
                                    <tr class="hover:bg-slate-50 loan-row" data-company="{{ $repayment['company'] }}">
                                        <td class="px-4 py-3">{{ $repayment['date'] }}</td>
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $repayment['loan_code'] }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-brand-50 text-brand-700 border border-brand-100">{{ $repayment['company'] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right mono font-medium">TZS {{ number_format($repayment['amount_paid']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($repayment['principal_paid']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($repayment['interest_paid']) }}</td>
                                        <td class="px-4 py-3">{{ $repayment['payment_method'] }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($repayment['balance_after']) }}</td>
                                        <td class="px-4 py-3">{{ $repayment['recorded_by'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-3 text-center text-slate-500">No repayments recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= INTEREST SUMMARY ============================= --}}
                <div id="loan-tab-summary" class="loan-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Interest Summary by Company</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Company</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Active Loans</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Principal</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Interest Accrued</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Total Paid</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Outstanding Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($interestSummary ?? [] as $summary)
                                    <tr class="hover:bg-slate-50 loan-row" data-company="{{ $summary['company'] }}">
                                        <td class="px-4 py-3 font-medium">{{ $summary['company'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $summary['active_loans'] }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($summary['total_principal']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($summary['interest_accrued']) }}</td>
                                        <td class="px-4 py-3 text-right mono">TZS {{ number_format($summary['total_paid']) }}</td>
                                        <td class="px-4 py-3 text-right mono font-semibold">TZS {{ number_format($summary['outstanding_balance']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No loan data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Modals to build once Repayments and Interest Summary get their own backend --}}
    {{--
    @include('loans.modals.record-repayment')
    --}}
    @include('loans.modals.add-loan')

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <script>
        const loanTabTitles = {
            register: 'Loan Register',
            schedule: 'Repayment Schedule',
            repayments: 'Repayments',
            summary: 'Interest Summary'
        };

        // Only Loan Register has a working backend right now, so it's the
        // only tab that gets a functional Add button. Repayments and
        // Interest Summary stay empty until their controllers exist —
        // add entries here once they do.
        const loanAddButtons = {
            register: { label: 'Add Loan', modal: 'modal-add-loan' }
        };

        function renderLoanActionButton(tab) {
            const container = document.getElementById('loanActionButton');
            if (!container) return;

            const config = loanAddButtons[tab];
            if (!config) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = `
                <button onclick="openLoanModal('${config.modal}')"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-brand-600 text-white rounded hover:bg-brand-700 w-full lg:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    ${config.label}
                </button>
            `;
        }

        function switchLoanTab(tab, btn) {
            document.querySelectorAll('.loan-tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`loan-tab-${tab}`).classList.remove('hidden');

            document.querySelectorAll('.loan-tab-btn').forEach(b => {
                b.classList.remove('text-slate-700', 'border-b-2', 'border-brand-600');
                b.classList.add('text-slate-500');
            });
            btn.classList.remove('text-slate-500');
            btn.classList.add('text-slate-700', 'border-b-2', 'border-brand-600');

            document.getElementById('loanSectionTitle').textContent = loanTabTitles[tab];
            renderLoanActionButton(tab);
        }

        function toggleLoanDetails(id) {
            const detailsRow = document.getElementById(`loan-details-${id}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }

        function filterLoansByCompany(company) {
            document.querySelectorAll('.loan-row').forEach(row => {
                const matches = company === 'all' || row.dataset.company === company;
                row.classList.toggle('hidden', !matches);
            });
        }

        function openLoanModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        function closeLoanModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => renderLoanActionButton('register'));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal-add-"]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>

</body>

</html>
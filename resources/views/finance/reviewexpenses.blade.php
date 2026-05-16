<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Expense Review - Goodness Group</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
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
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Finance</h1>
            <p class="text-sm text-slate-500">Expense review and feedback</p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-lg p-4 space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold font-display">Expense Review</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('finance') }}" class="px-4 py-2 rounded-md border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Back to Finance
                    </a>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $expense->reviewed_at ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $expense->reviewed_at ? 'Reviewed' : 'Pending review' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Expense Number</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $expense->expense_number }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Amount</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">TZS {{ number_format($expense->amount, 2) }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Submitted by</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $expense->creator?->name ?? 'N/A' }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ ucfirst($expense->status) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-6">
                <div class="space-y-6">
                    <div class="rounded-lg border border-slate-200 bg-white p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 items-center justify-center rounded-md bg-brand-50 text-brand-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376A9.014 9.014 0 0 0 21 12a9 9 0 1 0-9 9 9.014 9.014 0 0 0 3.376-.697M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Reminder before you submit</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Use this form to explain how the money was used, what it was spent on, and any notes that help
                                    the finance team understand the report.
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="rounded-md bg-slate-50 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Expense date</p>
                                <p class="mt-1 font-medium text-slate-900">{{ $expense->expense_date?->format('M d, Y') ?? $expense->expense_date }}</p>
                            </div>
                            <div class="rounded-md bg-slate-50 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Company / Department</p>
                                <p class="mt-1 font-medium text-slate-900">{{ $expense->company?->name ?? '-' }} / {{ $expense->department?->name ?? '-' }}</p>
                            </div>
                            <div class="rounded-md bg-slate-50 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Category</p>
                                <p class="mt-1 font-medium text-slate-900">{{ $expense->category }}</p>
                            </div>
                            <div class="rounded-md bg-slate-50 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-500">Payment method</p>
                                <p class="mt-1 font-medium text-slate-900">{{ $expense->payment_method }}</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-lg border border-dashed border-brand-200 bg-brand-50/60 p-4">
                            <p class="text-sm font-semibold text-brand-800">Helpful reminder</p>
                            <div class="mt-3 grid gap-2 sm:grid-cols-2 text-sm text-slate-700">
                                <div class="rounded-md bg-white px-3 py-2">What was bought or paid for</div>
                                <div class="rounded-md bg-white px-3 py-2">Why the spending was necessary</div>
                                <div class="rounded-md bg-white px-3 py-2">Any receipts or reference numbers</div>
                                <div class="rounded-md bg-white px-3 py-2">Anything that was left unused</div>
                            </div>
                        </div>
                    </div>

                    @if ($expense->review_feedback)
                        <div class="rounded-lg border border-slate-200 bg-white p-5">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Previously submitted feedback</p>
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                    Rating: {{ $expense->review_rating }}/5
                                </span>
                            </div>
                            <div class="mt-4 rounded-md bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                                {{ $expense->review_feedback }}
                            </div>
                            @if ($expense->reviewed_at)
                                <p class="mt-3 text-xs text-slate-500">Submitted {{ $expense->reviewed_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Submit your feedback</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Explain how the money was used and what outcome it produced.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('expenses.review.store', $expense) }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                        @csrf
                        @method('PATCH')


                        <div>
                            <label for="review_feedback" class="block text-sm font-medium text-slate-700">Feedback on how the money was used</label>
                            <textarea id="review_feedback" name="review_feedback" rows="10" placeholder="Describe what the money was used for, the key purchases or payments made, and any important notes about the spending." class="mt-2 w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">{{ old('review_feedback', $expense->review_feedback) }}</textarea>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Itemized spending</h4>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">Add one row for each way the money was used. Remove any row you do not need.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">Fill the expenses</span>
                                    <button type="button" id="add-review-item" class="inline-flex items-center rounded-md border border-brand-200 bg-brand-50 px-3 py-2 text-xs font-medium text-brand-700 hover:bg-brand-100 transition-colors">
                                        Add item
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 space-y-3" id="review-items-list">
                                @php
                                    $existingItems = old('review_items', $expense->review_items ?? []);
                                    if (!is_array($existingItems) || count($existingItems) === 0) {
                                        $existingItems = [
                                            ['description' => '', 'amount' => '', 'note' => ''],
                                        ];
                                    }
                                @endphp

                                @foreach ($existingItems as $index => $item)
                                    <div class="review-item rounded-md border border-slate-200 bg-slate-50 p-3">
                                        <div class="grid gap-3 md:grid-cols-12">
                                            <div class="md:col-span-6">
                                                <label class="block text-xs font-medium text-slate-600" for="review_items_{{ $index }}_description">Usage description</label>
                                                <input type="text" name="review_items[{{ $index }}][description]" id="review_items_{{ $index }}_description" value="{{ $item['description'] ?? '' }}" placeholder="E.g. Stationery, transport, lunch for team" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-slate-600" for="review_items_{{ $index }}_amount">Amount spent</label>
                                                <input type="number" step="0.01" min="0" name="review_items[{{ $index }}][amount]" id="review_items_{{ $index }}_amount" value="{{ $item['amount'] ?? '' }}" placeholder="0.00" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-slate-600" for="review_items_{{ $index }}_note">Note</label>
                                                <input type="text" name="review_items[{{ $index }}][note]" id="review_items_{{ $index }}_note" value="{{ $item['note'] ?? '' }}" placeholder="Short note" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                                            </div>
                                        </div>

                                        <div class="mt-3 flex justify-end">
                                            <button type="button" class="remove-review-item inline-flex items-center rounded-md border border-red-200 bg-white px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                                                Remove item
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <p class="mt-3 text-xs text-slate-500">You can leave some rows blank if you only have a few items to report.</p>
                        </div>

                        <div>
                            <label for="review_evidence" class="block text-sm font-medium text-slate-700">Evidence / receipts</label>
                            <input id="review_evidence" name="review_evidence[]" type="file" multiple accept=".pdf,.jpg,.jpeg,.png" class="mt-2 block w-full rounded-md border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-700 hover:file:bg-brand-100">
                            <p class="mt-2 text-xs text-slate-500">You can select more than one file. Upload receipts, screenshots, or supporting evidence for how the money was used.</p>
                            <div id="review-evidence-preview" class="mt-3 space-y-2"></div>
                        </div>

                        @if (!empty($expense->review_evidence_paths))
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm font-semibold text-slate-900">Previously uploaded evidence</p>
                                <div class="mt-3 space-y-2">
                                    @foreach ($expense->review_evidence_paths as $evidence)
                                        <div class="flex items-center justify-between gap-3 rounded-md bg-white px-3 py-2 text-sm text-slate-700">
                                            <span class="truncate">{{ $evidence['name'] ?? 'Receipt file' }}</span>
                                            @if (!empty($evidence['path']))
                                                <a href="{{ asset('storage/' . $evidence['path']) }}" target="_blank" rel="noopener" class="text-brand-700 hover:text-brand-800">View</a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="rounded-md border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            Your feedback helps confirm how the funds were used and gives the finance team a clear record of the report.
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('finance') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                Back to finance
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-brand-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-700">
                                {{ $expense->reviewed_at ? 'Update feedback' : 'Submit feedback' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <template id="review-item-template">
        <div class="review-item rounded-md border border-slate-200 bg-slate-50 p-3">
            <div class="grid gap-3 md:grid-cols-12">
                <div class="md:col-span-6">
                    <label class="block text-xs font-medium text-slate-600">Usage description</label>
                    <input type="text" name="review_items[__INDEX__][description]" placeholder="E.g. Stationery, transport, lunch for team" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-slate-600">Amount spent</label>
                    <input type="number" step="0.01" min="0" name="review_items[__INDEX__][amount]" placeholder="0.00" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-slate-600">Note</label>
                    <input type="text" name="review_items[__INDEX__][note]" placeholder="Short note" class="mt-1 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                </div>
            </div>

            <div class="mt-3 flex justify-end">
                <button type="button" class="remove-review-item inline-flex items-center rounded-md border border-red-200 bg-white px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                    Remove item
                </button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.getElementById('review-items-list');
            const addButton = document.getElementById('add-review-item');
            const template = document.getElementById('review-item-template');
            const evidenceInput = document.getElementById('review_evidence');
            const evidencePreview = document.getElementById('review-evidence-preview');
            const selectedEvidenceFiles = [];

            if (!list || !addButton || !template) {
                return;
            }

            const getNextIndex = () => {
                const currentIndexes = Array.from(list.querySelectorAll('.review-item'))
                    .map((item) => {
                        const input = item.querySelector('input[name*="[description]"]');
                        if (!input) return null;
                        const match = input.name.match(/review_items\[(\d+)\]/);
                        return match ? Number(match[1]) : null;
                    })
                    .filter((value) => value !== null);

                return currentIndexes.length ? Math.max(...currentIndexes) + 1 : 0;
            };

            const bindRemoveButtons = (scope = list) => {
                scope.querySelectorAll('.remove-review-item').forEach((button) => {
                    button.onclick = () => {
                        const items = list.querySelectorAll('.review-item');
                        if (items.length <= 1) {
                            const inputs = list.querySelectorAll('input');
                            inputs.forEach((input) => input.value = '');
                            return;
                        }

                        const item = button.closest('.review-item');
                        if (item) {
                            item.remove();
                        }
                    };
                });
            };

            addButton.addEventListener('click', () => {
                const nextIndex = getNextIndex();
                const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex));
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                const item = wrapper.firstElementChild;

                if (item) {
                    list.appendChild(item);
                    bindRemoveButtons(item);
                }
            });

            bindRemoveButtons();

            if (evidenceInput && evidencePreview) {
                const syncEvidenceInput = () => {
                    const dataTransfer = new DataTransfer();
                    selectedEvidenceFiles.forEach((file) => dataTransfer.items.add(file));
                    evidenceInput.files = dataTransfer.files;
                };

                const renderEvidencePreview = () => {
                    const files = selectedEvidenceFiles;
                    evidencePreview.innerHTML = '';

                    if (!files.length) {
                        const emptyState = document.createElement('div');
                        emptyState.className = 'rounded-md border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-xs text-slate-500';
                        emptyState.textContent = 'No evidence files selected yet.';
                        evidencePreview.appendChild(emptyState);
                        return;
                    }

                    files.forEach((file, index) => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center justify-between gap-3 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700';

                        const info = document.createElement('div');
                        info.className = 'min-w-0';

                        const name = document.createElement('p');
                        name.className = 'truncate font-medium text-slate-800';
                        name.textContent = file.name;

                        const meta = document.createElement('p');
                        meta.className = 'text-xs text-slate-500';
                        meta.textContent = `${(file.size / 1024).toFixed(1)} KB`;

                        info.appendChild(name);
                        info.appendChild(meta);

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'inline-flex items-center rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors';
                        removeButton.textContent = 'Remove';
                        removeButton.addEventListener('click', () => {
                            selectedEvidenceFiles.splice(index, 1);
                            syncEvidenceInput();
                            renderEvidencePreview();
                        });

                        row.appendChild(info);
                        row.appendChild(removeButton);
                        evidencePreview.appendChild(row);
                    });
                };

                evidenceInput.addEventListener('change', () => {
                    const incomingFiles = Array.from(evidenceInput.files || []);

                    incomingFiles.forEach((file) => {
                        const isDuplicate = selectedEvidenceFiles.some((existingFile) => {
                            return existingFile.name === file.name
                                && existingFile.size === file.size
                                && existingFile.lastModified === file.lastModified;
                        });

                        if (!isDuplicate) {
                            selectedEvidenceFiles.push(file);
                        }
                    });

                    syncEvidenceInput();
                    renderEvidencePreview();
                });
                renderEvidencePreview();
            }
        });
    </script>
</body>

</html>
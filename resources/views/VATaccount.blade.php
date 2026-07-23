<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>VAT Account - Goodness ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        nav,
        button {
            font-family: 'Outfit', sans-serif;
        }

        .mono {
            font-family: ui-monospace, monospace;
        }
    </style>
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
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- Topbar -->
    <header class="fixed top-0 left-0 right-0 h-16 bg-white border-b flex items-center px-6 z-20">
        <span class="font-display font-semibold text-lg text-brand-700">Goodness ERP</span>
    </header>

    <!-- Sidebar -->
    <aside class="hidden lg:block fixed top-16 left-0 bottom-0 w-64 bg-white border-r p-4">
        <nav class="text-sm space-y-1">
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-50 text-slate-600">Dashboard</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-50 text-slate-600">Sales</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-50 text-slate-600">Purchases</a>
            <a href="#" class="block px-3 py-2 rounded bg-brand-50 text-brand-700 font-medium">VAT Account</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-50 text-slate-600">Fixed Assets</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-slate-50 text-slate-600">Reports</a>
        </nav>
    </aside>

    <main class="ml-0 lg:ml-64 pt-20 p-6">

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">VAT Account</h1>
            <span class="text-sm text-slate-500">Period: July 2026</span>
        </div>

        <!-- VAT Summary -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">

            <div class="bg-white border rounded shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Output VAT (Sales)</p>
                <p class="mt-1 text-xl font-bold text-slate-800 mono">TZS 18,400,000</p>
                <p class="mt-1 text-xs text-slate-500">VAT collected on sales</p>
            </div>

            <div class="bg-white border rounded shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Input VAT (Purchases)</p>
                <p class="mt-1 text-xl font-bold text-slate-800 mono">TZS 11,650,000</p>
                <p class="mt-1 text-xs text-slate-500">VAT paid on purchases</p>
            </div>

            <div class="bg-white border rounded shadow-sm p-4 border-red-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Payable</p>
                <p class="mt-1 text-xl font-bold mono text-red-600">TZS 6,750,000</p>
                <p class="mt-1 text-xs text-slate-500">Output VAT &minus; Input VAT</p>
            </div>

        </div>

        <!-- Output VAT Table -->
        <div class="bg-white border rounded shadow-sm mb-6">
            <h2 class="text-lg font-semibold px-4 py-3 border-b">Output VAT (Sales Invoices)</h2>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Ref No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Customer</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Taxable Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">VAT Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">VAT Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">INV-2026-0142</td>
                        <td class="px-4 py-3 font-medium">Kilimanjaro Traders Ltd</td>
                        <td class="px-4 py-3 text-right mono">TZS 32,000,000</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 5,760,000</td>
                        <td class="px-4 py-3">2026-07-03</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Filed</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('out-1')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-out-1" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">INV-2026-0142</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Customer:</p>
                                    <p class="mt-1 text-sm text-slate-700">Kilimanjaro Traders Ltd</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">109-284-771</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Filed</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">32,000,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">5,760,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-03</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">INV-2026-0143</td>
                        <td class="px-4 py-3 font-medium">Serengeti Retailers</td>
                        <td class="px-4 py-3 text-right mono">TZS 18,500,000</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 3,330,000</td>
                        <td class="px-4 py-3">2026-07-08</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('out-2')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-out-2" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">INV-2026-0143</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Customer:</p>
                                    <p class="mt-1 text-sm text-slate-700">Serengeti Retailers</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">118-560-902</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Pending</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">18,500,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">3,330,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-08</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">INV-2026-0138</td>
                        <td class="px-4 py-3 font-medium">Coastal Distributors</td>
                        <td class="px-4 py-3 text-right mono">TZS 26,750,000</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 4,815,000</td>
                        <td class="px-4 py-3">2026-07-14</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Reversed</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('out-3')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-out-3" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">INV-2026-0138</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Customer:</p>
                                    <p class="mt-1 text-sm text-slate-700">Coastal Distributors</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">122-940-317</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Reversed</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">26,750,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">4,815,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-14</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-semibold">Total Output VAT</td>
                        <td class="px-4 py-3 text-right font-semibold mono">TZS 18,400,000</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Input VAT Table -->
        <div class="bg-white border rounded shadow-sm mb-6">
            <h2 class="text-lg font-semibold px-4 py-3 border-b">Input VAT (Purchase Invoices)</h2>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Ref No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Supplier</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Taxable Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">VAT Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase">VAT Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">PUR-2026-0071</td>
                        <td class="px-4 py-3 font-medium">Twiga Agro Supplies</td>
                        <td class="px-4 py-3 text-right mono">TZS 22,000,000</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 3,960,000</td>
                        <td class="px-4 py-3">2026-07-02</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Claimed</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('in-1')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-in-1" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">PUR-2026-0071</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier:</p>
                                    <p class="mt-1 text-sm text-slate-700">Twiga Agro Supplies</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">104-772-215</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Claimed</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">22,000,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">3,960,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-02</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">PUR-2026-0075</td>
                        <td class="px-4 py-3 font-medium">Dar Fuel &amp; Logistics</td>
                        <td class="px-4 py-3 text-right mono">TZS 15,500,000</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 2,790,000</td>
                        <td class="px-4 py-3">2026-07-09</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Claimed</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('in-2')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-in-2" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">PUR-2026-0075</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier:</p>
                                    <p class="mt-1 text-sm text-slate-700">Dar Fuel &amp; Logistics</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">131-005-664</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Claimed</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">15,500,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">2,790,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-09</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 mono text-xs text-slate-500">PUR-2026-0080</td>
                        <td class="px-4 py-3 font-medium">Zanzibar Packaging Co.</td>
                        <td class="px-4 py-3 text-right mono">TZS 27,777,778</td>
                        <td class="px-4 py-3">18%</td>
                        <td class="px-4 py-3 text-right mono">TZS 4,900,000</td>
                        <td class="px-4 py-3">2026-07-16</td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Disallowed</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-6">
                                <button type="button" onclick="toggleVatDetails('in-3')"
                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                    title="View VAT entry details" aria-label="View VAT entry details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr id="vat-details-in-3" class="hidden bg-slate-50/70">
                        <td colspan="8" class="px-4 py-4">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ref No.:</p>
                                    <p class="mt-1 text-sm text-slate-700">PUR-2026-0080</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Supplier:</p>
                                    <p class="mt-1 text-sm text-slate-700">Zanzibar Packaging Co.</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">TIN:</p>
                                    <p class="mt-1 text-sm text-slate-700">145-338-092</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status:</p>
                                    <p class="mt-1 text-sm text-slate-700">Disallowed</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Taxable Value: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">27,777,778.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Rate:</p>
                                    <p class="mt-1 text-sm text-slate-700">18%</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">VAT Amount: TZS</p>
                                    <p class="mt-1 text-sm text-slate-700">4,900,000.00</p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date:</p>
                                    <p class="mt-1 text-sm text-slate-700">2026-07-16</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-semibold">Total Input VAT</td>
                        <td class="px-4 py-3 text-right font-semibold mono">TZS 11,650,000</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- VAT Reconciliation -->
        <div class="p-6 bg-white border rounded shadow-sm">
            <h3 class="text-md font-semibold mb-2">VAT Reconciliation</h3>
            <ul class="text-sm space-y-1">
                <li>Total Output VAT: <span class="mono">TZS 18,400,000</span></li>
                <li>Total Input VAT: <span class="mono">TZS 11,650,000</span></li>
                <li class="font-bold">Net VAT Payable: <span class="mono text-red-600">TZS 6,750,000</span></li>
            </ul>
        </div>

    </main>

    <script>
        function toggleVatDetails(id) {
            const detailsRow = document.getElementById(`vat-details-${id}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }
    </script>

</body>

</html>

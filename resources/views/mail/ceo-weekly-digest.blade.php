<x-mail::message>
# Weekly Executive Briefing

Dear CEO,

Please find below the Goodness Group summary for **{{ $digest['week_start'] }} – {{ $digest['week_end'] }}**.

## Cash position
- Receipts: TZS {{ number_format($digest['cash_in']) }}
- Payments: TZS {{ number_format($digest['cash_out']) }}
- Closing cash: TZS {{ number_format($digest['closing_cash']) }}

## Performance
- Collections: TZS {{ number_format($digest['sales_collected']) }}
- Net result (collections less issued expenses): TZS {{ number_format($digest['net_income']) }}

@if ($digest['pending_ceo_count'] > 0 || $digest['overdue_count'] > 0)
<x-mail::panel>
**Items requiring your attention**
- Expenses awaiting your approval: {{ $digest['pending_ceo_count'] }} (TZS {{ number_format($digest['pending_ceo_amount']) }})
- Overdue invoices: {{ $digest['overdue_count'] }} (TZS {{ number_format($digest['overdue_amount']) }})
</x-mail::panel>
@endif

@if (count($pdfs))
The cash flow statement and income statement for the year to date are attached for your review.
@endif

You may open the system at any time for the full picture.

<x-mail::button :url="url('/dashboard')">
Open Goodness ERP
</x-mail::button>

Yours faithfully,<br>
**Goodness Group ERP**
</x-mail::message>

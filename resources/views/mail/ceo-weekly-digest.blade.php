<x-mail::message>
# Weekly briefing

**{{ $digest['week_start'] }} – {{ $digest['week_end'] }}**

## Cash
- In: TZS {{ number_format($digest['cash_in']) }}
- Out: TZS {{ number_format($digest['cash_out']) }}
- Closing: TZS {{ number_format($digest['closing_cash']) }}

## Performance
- Sales collected: TZS {{ number_format($digest['sales_collected']) }}
- Net (sales − issued expenses): TZS {{ number_format($digest['net_income']) }}

@if ($digest['pending_ceo_count'] > 0 || $digest['overdue_count'] > 0)
## Needs your attention
- Expenses waiting approval: {{ $digest['pending_ceo_count'] }} (TZS {{ number_format($digest['pending_ceo_amount']) }})
- Overdue invoices: {{ $digest['overdue_count'] }} (TZS {{ number_format($digest['overdue_amount']) }})
@endif

@if (count($pdfs))
Cash flow and income statement PDFs are attached.
@endif

For more details, please check the dashboard.

<x-mail::button :url="url('/dashboard')">
Open dashboard
</x-mail::button>

</x-mail::message>

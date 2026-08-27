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

## Needs your attention
- Expenses waiting approval: {{ $digest['pending_ceo_count'] }} (TZS {{ number_format($digest['pending_ceo_amount']) }})
- Overdue invoices: {{ $digest['overdue_count'] }} (TZS {{ number_format($digest['overdue_amount']) }})

<x-mail::button :url="url('/dashboard')">
Open dashboard
</x-mail::button>

Goodness ERP
</x-mail::message>

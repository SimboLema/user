{{-- resources/views/emails/quotation/created.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .badge-pending { background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 13px; }
        .btn { display: inline-block; padding: 10px 24px; background: #003153; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        td:first-child { color: #666; width: 45%; }
        td:last-child { font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #003153;">New Quotation Awaiting Approval</h2>
        <p>Hello, a new quotation has been submitted by <strong>KMJ Insurance Brokers</strong> and is awaiting your approval.</p>

        <span class="badge-pending">⏳ Pending Approval</span>

        <table>
            <tr><td>Customer</td><td>{{ ucwords(strtolower($quotation->customer->name)) }}</td></tr>
            <tr><td>Product</td><td>{{ $quotation->coverage->product->name ?? '-' }}</td></tr>
            <tr><td>Coverage</td><td>{{ $quotation->coverage->risk_name ?? '-' }}</td></tr>
            <tr><td>Sum Insured</td><td>{{ number_format($quotation->sum_insured, 2) }} TZS</td></tr>
            <tr><td>Premium (Incl. Tax)</td><td>{{ number_format($quotation->total_premium_including_tax, 2) }} TZS</td></tr>
            <tr><td>Cover Period</td><td>
                {{ \Carbon\Carbon::parse($quotation->cover_note_start_date)->format('d M Y') }} —
                {{ \Carbon\Carbon::parse($quotation->cover_note_end_date)->format('d M Y') }}
            </td></tr>
        </table>

        <a href="{{ url('/insuarer/quotations') }}" class="btn">View & Approve Quotation</a>

        <div class="footer">
            This is an automated notification from KMJ Insurance Brokers System.
        </div>
    </div>
</body>
</html>

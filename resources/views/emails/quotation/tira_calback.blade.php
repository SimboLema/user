{{-- resources/views/emails/quotation/tira_callback.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .badge-success { background: #d1e7dd; color: #0a3622; padding: 4px 12px; border-radius: 20px; font-size: 13px; }
        .badge-failed { background: #f8d7da; color: #842029; padding: 4px 12px; border-radius: 20px; font-size: 13px; }
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
        <h2 style="color: #003153;">TIRA Response Received</h2>
        <p>A TIRA callback has been received for the following quotation:</p>

        @if ($quotation->status === 'success')
            <span class="badge-success">✅ Risknote Issued Successfully</span>
        @else
            <span class="badge-failed">❌ TIRA Response: {{ $quotation->response_status_desc ?? 'Failed' }}</span>
        @endif

        <table>
            <tr><td>Customer</td><td>{{ ucwords(strtolower($quotation->customer->name)) }}</td></tr>
            <tr><td>Cover Note Ref</td><td>{{ $quotation->cover_note_reference ?? '-' }}</td></tr>
            <tr><td>Sticker Number</td><td>{{ $quotation->sticker_number ?? '-' }}</td></tr>
            <tr><td>TIRA Status Code</td><td>{{ $quotation->response_status_code ?? '-' }}</td></tr>
            <tr><td>TIRA Status Desc</td><td>{{ $quotation->response_status_desc ?? '-' }}</td></tr>
            <tr><td>Premium (Incl. Tax)</td><td>{{ number_format($quotation->total_premium_including_tax, 2) }} TZS</td></tr>
        </table>

        <a href="{{ url('/kmj/quotation/' . $quotation->id . '/covernote') }}" class="btn">
            View Quotation
        </a>

        <div class="footer">
            This is an automated notification from KMJ Insurance Brokers System.
        </div>
    </div>
</body>
</html>

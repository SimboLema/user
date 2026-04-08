<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }

        .receipt-container {
            width: 700px;
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #003153;
        }

        .company-info {
            margin-bottom: 30px;
        }

        .company-info h3 {
            margin: 5px 0;
            color: #222;
        }

        .receipt-details {
            text-align: left;
            margin: 30px 0;
        }

        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-details td {
            padding: 8px 0;
        }

        .receipt-details .label {
            font-weight: bold;
            width: 30%;
        }

        .amount-section {
            border-top: 1px dashed #aaa;
            border-bottom: 1px dashed #aaa;
            padding: 15px 0;
            margin: 25px 0;
            font-size: 18px;
        }

        .amount-section span {
            font-weight: bold;
            color: #003153;
        }

        .footer-note {
            font-size: 12px;
            color: #555;
            margin-top: 40px;
        }

        @media print {
            body {
                background: none;
            }
            .page-wrapper {
                height: 100%;
                align-items: center;
                justify-content: center;
            }
            .receipt-container {
                box-shadow: none;
                margin: auto;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="receipt-container">
        <div class="company-info">
            <h2>SURETECH COMPANY</h2>
            <p>P.O Box 123, Dar es Salaam, Tanzania</p>
            <p>Phone: +255 700 000 000 | Email: info@suretech.co.tz</p>
        </div>

        <h3><u>PAYMENT RECEIPT</u></h3>
        <p><strong>Date:</strong> {{ Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i:A') }}</p>

        <div class="receipt-details">
            <table>
                <tr>
                    <td class="label">Receipt No:</td>
                    <td>RCPT-0001</td>
                </tr>
                <tr>
                    <td class="label">Customer Name:</td>
                    <td>{{ ucwords(strtolower($payment->quotation->customer->name )) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td>Airtel Money</td>
                </tr>
                <tr>
                    <td class="label">Reference No:</td>
                    <td>TXN123456789</td>
                </tr>
            </table>
        </div>

        <div class="amount-section">
            Amount Paid: <span>{{$payment->quotation->currency->code?? 'TZS' }} {{ number_format($payment->amount) }}</span>
        </div>

        <p>Received with thanks from the above-named customer for the stated amount.</p>

        <div style="margin-top:60px; text-align:left;">
            <p>__________________________</p>
            <p><strong>Authorized Signature</strong></p>
        </div>

        <div class="footer-note">
            <p>This is a system-generated receipt. No signature is required if printed electronically.</p>
        </div>
    </div>
</div>
</body>
</html>

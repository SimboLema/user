<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── PAGE WRAPPER ── */
        .page {
            width: 100%;
            position: relative;
        }

        /* ── PAGE BREAK ── */
        .page-break {
            break-after: page;
            page-break-after: always;
            height: 0;
            display: block;
        }

        /* ── HEADER / FOOTER IMAGES ── */
        .page-header-img {
            width: 100%;
            display: block;
            margin-bottom: 6px;
        }
        .page-footer-img {
            width: 100%;
            display: block;
            margin-top: 6px;
        }

        /* ── CONTENT WRAPPER ── */
        .content {
            padding: 0 20px;
        }

        /* ── PREVENT KEY BLOCKS FROM SPLITTING ── */
        .quotation-header-table,
        .info-table,
        .fin-table,
        .bank-split-table,
        .issued-by-table,
        .declaration-block,
        .bank-details-placeholder,
        .section-with-bar {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* ── QUOTATION HEADER TABLE ── */
        .quotation-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .quotation-header-table td {
            border: 1px solid #1a3a5c;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .quotation-header-table .label-cell {
            background: #1a3a5c;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            width: 160px;
            letter-spacing: 1px;
        }
        .quotation-header-table .col-header {
            background: #1a3a5c;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }
        .quotation-header-table .col-value {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        /* ── INFO TABLE ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-table td {
            border: 1px solid #555;
            padding: 4px 8px;
            vertical-align: top;
        }
        .field-label {
            background: #e8e8e8;
            font-weight: bold;
            font-size: 9.5px;
            color: #222;
            white-space: nowrap;
        }
        .field-value {
            font-size: 10px;
            color: #111;
        }

        /* ── SECTION HEADING BAR ── */
        .section-bar {
            background: #1a3a5c;
            color: #fff;
            font-weight: bold;
            font-size: 9.5px;
            border: 1px solid #1a3a5c;
            padding: 5px 8px;
            page-break-after: avoid;
            break-after: avoid;
        }

        /* ── QUOTATION DETAILS — line-item style ── */

        /* Column sub-header (Description / Sum Insured / Premium) */
        .detail-col-header {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-col-header td {
            background: #e8e8e8;
            border: 1px solid #bbb;
            border-top: none;
            padding: 3px 8px;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            color: #555;
        }

        /* Each detail row rendered like the screenshot */
        .detail-line {
            width: 100%;
            border-collapse: collapse;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px dotted #ccc;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .detail-line td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 9.5px;
        }
        /* Alternating row shading */
        .detail-line.even td {
            background: #f7f7f7;
        }
        /* Bold label (left, 65%) */
        .dl-label {
            width: 65%;
            font-weight: bold;
            color: #1a1a1a;
        }
        /* Value (right, 35%) */
        .dl-value {
            width: 35%;
            text-align: right;
            white-space: nowrap;
            color: #111;
        }

        /* Wrapper gets a solid bottom border after all rows */
        .detail-lines-wrapper {
            border-bottom: 1px solid #ccc;
        }

        /* ── FINANCIALS SUMMARY ── */
        .fin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .fin-table td {
            border: 1px solid #555;
            padding: 5px 10px;
        }
        .fin-table .fin-label {
            text-align: right;
            font-weight: bold;
            font-size: 10px;
            width: 75%;
        }
        .fin-table .fin-value {
            text-align: right;
            font-size: 10px;
            white-space: nowrap;
        }
        .fin-table .fin-total td {
            font-weight: bold;
            font-size: 11px;
            background: #f0f4f8;
        }

        /* ── BANK DETAILS PLACEHOLDER ── */
        .bank-details-placeholder {
            width: 100%;
            margin-top: 8px;
        }
        .bank-details-placeholder table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ── BANK SPLIT TABLE (Page 2) ── */
        .bank-split-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ── BANK BLOCK ── */
        .bank-block {
            margin-bottom: 6px;
            font-size: 9.5px;
            line-height: 1.6;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .bank-block strong { font-size: 9.5px; }

        /* ── FOOTER TEXT ── */
        .footer {
            margin: 6px 20px 0 20px;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 4px;
        }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { font-size: 8px; color: #888; }

        /* ── SURETECH LOGO ── */
        .suretech-logo {
            padding: 4px 20px 0 20px;
            font-size: 9px;
            color: #1a3a5c;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .suretech-logo span.sys {
            font-weight: normal;
            font-size: 7.5px;
            letter-spacing: 2px;
            display: block;
            color: #888;
        }

        /* ── DECLARATION BLOCK ── */
        .declaration-block {
            border: 1px solid #555;
            border-top: none;
            padding: 12px 10px 80px 10px;
            font-size: 9.5px;
            line-height: 1.9;
        }
    </style>
</head>
<body>

{{-- ============================================================ --}}
{{-- PAGE 1 — QUOTATION DETAILS                                   --}}
{{-- ============================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('img/Header.jpeg') }}" alt="Header" />

    <div class="content">

        {{-- ── Quotation Header ── --}}
        <table class="quotation-header-table">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $quotation->id }}</td>
                <td class="col-value">{{ $quotation->control_number ?? '—' }}</td>
                <td class="col-value">{{ $quotation->created_at->format('d-M-Y') }}</td>
            </tr>
        </table>

        {{-- ── Client / Policy Summary ── --}}
        <table class="info-table" style="margin-bottom:4px;">
            <tr>
                <td class="field-label" style="width:130px;">Client Name</td>
                <td class="field-value" colspan="3">
                    <strong>{{ $quotation->customer->name ?? '—' }}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-label">Address</td>
                <td class="field-value">
                    {{ $quotation->customer->address ?? 'P.O.BOX — DAR ES SALAAM' }}<br>
                    TIN: {{ $quotation->customer->tin ?? '—' }}
                </td>
                <td class="field-label" style="width:100px;">Contacts</td>
                <td class="field-value">
                    Mobile: {{ $quotation->customer->phone ?? '—' }}
                </td>
            </tr>
            <tr>
                <td class="field-label">Intermediary Name</td>
                <td class="field-value">KMJ Insurance Brokers Ltd</td>
                <td class="field-label">Cover Period</td>
                <td class="field-value">
                    {{ optional($quotation->cover_note_start_date)->format('d-M-Y') ?? '—' }}
                    &nbsp;–&nbsp;
                    {{ optional($quotation->cover_note_end_date)->format('d-M-Y') ?? '—' }}
                </td>
            </tr>
            <tr>
                <td class="field-label">Insurance Company</td>
                <td class="field-value" colspan="3">{{ $quotation->insuarer->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Insurance Type</td>
                <td class="field-value" colspan="3">
                    <strong>{{ strtoupper($quotation->coverage->product->name ?? 'PACKAGE POLICY') }}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-label">Coverage / Risk</td>
                <td class="field-value" colspan="3">{{ $quotation->coverage->risk_name ?? '—' }}</td>
            </tr>
        </table>

        {{-- ── Covering Details + Description of Risk ── --}}
        <table class="info-table" style="margin-bottom:5px;">
            <tr>
                <td class="section-bar" style="width:50%;">Covering Details</td>
                <td class="section-bar" style="width:50%;">Description of Risk</td>
            </tr>
            <tr>
                <td class="field-value" style="font-style:italic; font-size:9px; color:#444; line-height:1.6; padding:6px 8px;">
                    ON PACKAGE POLICY TO COVER BELOW MENTIONED RISKS<br>
                    OFFICE LOCATION: {{ strtoupper($quotation->office_location ?? '15TH FLOOR - PSSSF TOWER') }}<br>
                    {{ strtoupper($quotation->office_address ?? 'GARDEN AVENUE / OHIO STREET') }}<br>
                    {{ strtoupper($quotation->office_city ?? 'DAR ES SALAAM, TANZANIA') }}
                </td>
                <td style="border:1px solid #555; padding:6px 8px; vertical-align:top;"></td>
            </tr>
        </table>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- QUOTATION DETAILS — line-item style matching the screenshot --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="section-with-bar">
            <div class="section-bar">Quotation Details</div>

            {{-- Sub-column headers --}}
            <table class="detail-col-header">
                <tr>
                    <td style="width:65%;">Description</td>
                    <td style="width:35%; text-align:right;">Value</td>
                </tr>
            </table>

            {{-- Line items from $quotationDetails (built in controller) --}}
            @php

                $finKeys = [
                    'Total Premium (Excl. Tax)',
                    'Total Premium (Incl. Tax)',
                    'Tax Amount',
                ];
                $rowIndex = 0;
            @endphp

            <div class="detail-lines-wrapper">
                @foreach($quotationDetails as $label => $value)
                    @if(!in_array($label, $finKeys) && $value !== null && $value !== '' && $value !== '0.00')
                        @php $rowClass = ($rowIndex % 2 === 0) ? '' : 'even'; $rowIndex++; @endphp
                        <table class="detail-line {{ $rowClass }}">
                            <tr>
                                <td class="dl-label">{{ $label }}</td>
                                <td class="dl-value">{{ $value }}</td>
                            </tr>
                        </table>
                    @endif
                @endforeach
            </div>
        </div>
        {{-- ══════════════════════════════════════════════════════════ --}}

        {{-- ── Financial Summary ── --}}
        <table class="fin-table">
            <tr>
                <td class="fin-label">Discount</td>
                <td class="fin-value">{{ number_format($quotation->discount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="fin-label">Total Premium (Excl. Tax)</td>
                <td class="fin-value">{{ number_format($quotation->total_premium_excluding_tax ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="fin-label">VAT / Tax Amount</td>
                <td class="fin-value">{{ number_format($quotation->tax_amount ?? 0, 2) }}</td>
            </tr>
            <tr class="fin-total">
                <td class="fin-label">Total Receivable (Incl. Tax)</td>
                <td class="fin-value">{{ number_format($quotation->total_premium_including_tax ?? 0, 2) }}</td>
            </tr>
        </table>


    </div>{{-- end .content --}}



    <img class="page-footer-img" src="{{ public_path('img/Footer.jpeg') }}" alt="Footer" />

</div>

<div class="page-break"></div>

{{-- ============================================================ --}}
{{-- PAGE 2 — BANK DETAILS                                        --}}
{{-- ============================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('img/Header.jpeg') }}" alt="Header" />

    <div class="content">

        <table class="quotation-header-table" style="margin-bottom:10px;">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $quotation->id }}</td>
                <td class="col-value">{{ $quotation->control_number ?? '—' }}</td>
                <td class="col-value">{{ $quotation->created_at->format('d-M-Y') }}</td>
            </tr>
        </table>

        <table class="bank-split-table">
            <tr>
                {{-- Left: Bank details --}}
                <td style="width:50%; padding-right:6px; vertical-align:top; font-size:9.5px; line-height:1.6;">

                    <strong>FOLLOWING ARE OUR BANK DETAILS:</strong><br><br>
                    <strong>Account Name: {{ $quotation->insuarer->name ?? 'Sanlam General Insurance (Tanzania) Limited' }}</strong><br><br>

                    <div class="bank-block">
                        <strong>ECO BANK TANZANIA LIMITED</strong><br>
                        Account Number: USD 0031015400869201 / TZS 0030015400869201<br>
                        Branch: Msimbazi, DSM &nbsp;|&nbsp; Swift: ECOCTZTZ
                    </div>
                    <div class="bank-block">
                        <strong>MKOMBOZI COMMERCIAL BANK</strong><br>
                        Account Number: USD 00451511798001 / TZS 00410611798001<br>
                        Branch: Kariakoo, DSM &nbsp;|&nbsp; Swift: MKCBTZTZ
                    </div>
                    <div class="bank-block">
                        <strong>FIRST NATIONAL BANK</strong><br>
                        Account Number: USD 62470178445 / TZS 62470177679<br>
                        Branch: Main, DSM &nbsp;|&nbsp; Swift: FIRNTZTX
                    </div>
                    <div class="bank-block">
                        <strong>NMB BANK</strong><br>
                        Account Number: USD 20110025007 / TZS 20110007788<br>
                        Branch: Bank House &nbsp;|&nbsp; Swift: NMIBTZTZ
                    </div>
                    <div class="bank-block">
                        <strong>CRDB BANK PLC</strong><br>
                        Account Number: USD 0250439350800 / TZS 0150439350800<br>
                        Branch: Azikiwe &nbsp;|&nbsp; Swift: CORUTZTZ
                    </div>
                    <div class="bank-block">
                        <strong>STANBIC BANK</strong><br>
                        Account Number: USD 9120000864461 / TZS 9120000619467<br>
                        Branch: Kariakoo Sokoni &nbsp;|&nbsp; Swift: SBICTZTX
                    </div>
                    <div class="bank-block">
                        <strong>DIAMOND TRUST BANK</strong><br>
                        Account Number: USD 0400918015 / TZS 0400918023<br>
                        Branch: Main &nbsp;|&nbsp; Swift: DTKETZTZ
                    </div>
                    <div class="bank-block">
                        <strong>NATIONAL BANK OF COMMERCE</strong><br>
                        Account Number: USD 011105016883 / TZS 011103037410<br>
                        Branch: Corporate &nbsp;|&nbsp; Swift: NLCBTZTX
                    </div>
                    <div class="bank-block">
                        <strong>STANDARD CHARTERED BANK</strong><br>
                        Account Number: TZS 0102021445600<br>
                        Branch: International House &nbsp;|&nbsp; Swift: SCBLTZTX<br>
                        <em>Payment should be made in favor of the insurance company.</em>
                    </div>

                </td>

                {{-- Right: Digital Payment --}}
                <td style="width:50%; padding-left:6px; vertical-align:top; font-size:9.5px; line-height:1.75;">
                    <strong>For payment through NMB/CRDB Channels:</strong><br>
                    Your payment reference # is <strong>{{ $quotation->cover_note_reference ?? 'SPXZ2281725' }}</strong>.
                    Your broker shall advise you on the payment guidelines.<br><br>

                    <strong>FOR PAYMENT THROUGH SELCOM PAY:</strong><br>
                    Reference number has not been generated. Kindly click on the
                    'Digital Payment' button on the quotation screen &amp; select
                    'Selcom' option to generate a payment reference number.
                </td>
            </tr>
        </table>

    </div>

    <div class="footer">
        <table class="footer-inner"><tr>
            <td><em>Powered from Smart Policy Insurance System</em><br>
                Printed Date: {{ now()->format('n/j/Y g:i:sA') }} | Printed By: KMJ Insurance</td>
            <td style="text-align:right;">Page 2 of 3</td>
        </tr></table>
    </div>

    <div class="suretech-logo">
        &#9632; <strong style="color:#1a3a5c; letter-spacing:1px;">SURETECH</strong>
        <span class="sys">SYSTEMS</span>
    </div>

    <img class="page-footer-img" src="{{ public_path('img/Footer.jpeg') }}" alt="Footer" />

</div>

<div class="page-break"></div>

{{-- ============================================================ --}}
{{-- PAGE 3 — TERMS & CUSTOMER DECLARATION                        --}}
{{-- ============================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('img/Header.jpeg') }}" alt="Header" />

    <div class="content">

        <table class="quotation-header-table" style="margin-bottom:10px;">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $quotation->id }}</td>
                <td class="col-value">{{ $quotation->control_number ?? '—' }}</td>
                <td class="col-value">{{ $quotation->created_at->format('d-M-Y') }}</td>
            </tr>
        </table>

        <table class="issued-by-table" style="width:100%; border-collapse:collapse; margin-bottom:6px;">
            <tr>
                <td style="font-size:9px; color:#444; line-height:1.8; vertical-align:top; width:70%;">
                    1. When referring to this bill please quote the policy number / Cover note number.<br>
                    2. Cheques should be crossed and made payable to {{ $quotation->insuarer->name ?? 'Sanlam General Insurance (Tanzania) Limited' }}.<br>
                    3. An official receipt should be obtained upon payment.<br>
                    4. An insurance policy will become invalid retroactive to the date of inception if the full premium is not paid.
                </td>
                <td style="text-align:right; font-weight:bold; font-size:9.5px; vertical-align:top;">
                    Issued By, KMJ INSURANCE
                </td>
            </tr>
        </table>

        <div style="border-top:1px dashed #999; padding-top:4px; text-align:right; font-size:9px; color:#555; margin-bottom:8px;">
            For, KMJ Insurance Brokers Ltd
        </div>

        <div class="section-with-bar">
            <div class="section-bar">Customer Declaration</div>
            <div class="declaration-block">
                <ol style="padding-left:16px; margin:0;">
                    <li>I/We declare that the above quote is given to me/us on the information provided by me/us.</li>
                    <li>I/We declare to the best of my/our knowledge and belief that the information given on this quote is true in every respect.</li>
                    <li>I/We agree that this proposal and declaration shall be the basis of the contract between me/us and the Insurer.</li>
                    <li>I/We confirm to have been given adequate pre-sale and post-sale advice relating to coverage, terms and conditions of this insurance product.</li>
                </ol>

                {{-- Watermark --}}
                <div style="text-align:center; margin-top:30px; opacity:0.08;">
                    <span style="font-size:80px; font-weight:900; color:#1a3a5c; letter-spacing:-3px; font-style:italic;">
                        KMJ<span style="color:#c0272d;">ib</span>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="footer">
        <table class="footer-inner"><tr>
            <td><em>Powered from Smart Policy Insurance System</em><br>
                Printed Date: {{ now()->format('n/j/Y g:i:sA') }} | Printed By: KMJ Insurance</td>
            <td style="text-align:right;">Page 3 of 3</td>
        </tr></table>
    </div>

    <div class="suretech-logo">
        &#9632; <strong style="color:#1a3a5c; letter-spacing:1px;">SURETECH</strong>
        <span class="sys">SYSTEMS</span>
    </div>

    <img class="page-footer-img" src="{{ public_path('img/Footer.jpeg') }}" alt="Footer" />

</div>

</body>
</html>

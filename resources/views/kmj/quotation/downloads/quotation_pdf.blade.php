<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            background: #fff;
        }

        .page { width: 100%; position: relative; }

        .page-break {
            break-after: page;
            page-break-after: always;
            height: 0;
            display: block;
        }

        .page-header-img { width: 100%; display: block; margin-bottom: 6px; }
        .page-footer-img { width: 100%; display: block; margin-top: 6px; }

        .content { padding: 0 20px; }

        .quotation-header-table,
        .info-table,
        .bank-split-table,
        .issued-by-table,
        .declaration-block,
        .section-with-bar,
        .motor-items-table,
        .nonmotor-items-table,
        .totals-table {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* ── Quotation header strip ── */
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

        /* ── Info table ── */
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
        .field-value { font-size: 10px; color: #111; }

        /* ── Section bar ── */
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

        /* ── Covering Details ── */
        .covering-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .covering-table .covering-header {
            background: #1a3a5c;
            color: #fff;
            font-weight: bold;
            font-size: 9.5px;
            padding: 5px 8px;
            border: 1px solid #1a3a5c;
            width: 50%;
        }
        .covering-table .covering-cell {
            border: 1px solid #555;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 9px;
            color: #444;
            line-height: 1.6;
            width: 50%;
        }

        /* ── Motor items table ── */
        .motor-items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .motor-items-table th {
            border: 1px solid #1a3a5c;
            padding: 4px 7px;
            background: #e8e8e8;
            font-size: 9.5px;
            font-weight: bold;
            text-align: left;
            color: #222;
        }
        .motor-items-table th.tr { text-align: right; }
        .motor-items-table td {
            border: 1px solid #ccc;
            padding: 4px 7px;
            font-size: 9.5px;
            vertical-align: top;
        }
        .motor-items-table td.tr { text-align: right; }
        .motor-items-table .comp-row td {
            font-weight: bold;
            background: #f0f4f8;
            font-size: 9.5px;
            color: #1a3a5c;
            padding: 3px 7px;
        }
        .addon-dotted {
            border-top: 1px dashed #777;
            border-bottom: 1px dashed #777;
            padding: 4px 7px;
            font-size: 9px;
            line-height: 1.85;
            color: #222;
        }
        .addon-dotted-row td {
            padding: 0 !important;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-top: none;
            border-bottom: none;
        }

        /* ── Non-motor items table ── */
        .nonmotor-items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .nonmotor-items-table th {
            border: 1px solid #1a3a5c;
            padding: 5px 8px;
            background: #e8e8e8;
            font-size: 9.5px;
            font-weight: bold;
            text-align: left;
            color: #222;
        }
        .nonmotor-items-table th.tr { text-align: right; }
        .nonmotor-items-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            font-size: 9.5px;
            vertical-align: top;
        }
        .nonmotor-items-table td.tr { text-align: right; }
        .nonmotor-items-table .item-desc { font-weight: bold; color: #1a1a1a; margin-bottom: 3px; }
        .nonmotor-items-table .item-subdesc { font-size: 8.5px; color: #555; line-height: 1.5; font-style: italic; }
        .nonmotor-items-table tr.even td { background: #f7f7f7; }

        /* ── Totals ── */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }
        .totals-table td {
            border: 1px solid #555;
            padding: 4px 10px;
            font-size: 10px;
        }
        .tt-label { text-align: right; width: 72%; }
        .tt-value { text-align: right; font-weight: bold; }
        .tt-total td { font-weight: bold; font-size: 11px; background: #f0f4f8; }

        .amount-words {
            border: 1px solid #555;
            border-top: none;
            padding: 3px 8px;
            font-size: 9.5px;
            font-style: italic;
            color: #333;
        }

        .bank-footer-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .bank-footer-row td {
            border: 1px solid #555;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 9.5px;
            width: 50%;
        }

        /* ── Bank details ── */
        .bank-split-table { width: 100%; border-collapse: collapse; }
        .bank-block { margin-bottom: 8px; font-size: 9.5px; line-height: 1.6; page-break-inside: avoid; break-inside: avoid; }

        /* ── Declaration ── */
        .declaration-block {
            border: 1px solid #555;
            border-top: none;
            padding: 12px 10px 80px 10px;
            font-size: 9.5px;
            line-height: 1.9;
        }

        /* ── Footer ── */
        .footer { margin: 6px 20px 0 20px; font-size: 8px; color: #888; border-top: 1px solid #ddd; padding-top: 4px; }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { font-size: 8px; color: #888; }
        .suretech-logo { padding: 4px 20px 0 20px; font-size: 9px; color: #1a3a5c; font-weight: bold; letter-spacing: 1px; }
        .suretech-logo span.sys { font-weight: normal; font-size: 7.5px; letter-spacing: 2px; display: block; color: #888; }
    </style>
</head>
<body>

@php
    $q        = $quotation;
    $customer = $q->customer;

    // $motor is passed explicitly from the controller as a stdClass or null
    // $isMotor: true if motor object exists OR registration_number is on the quotation
    $isMotor  = !is_null($motor) || !empty($q->registration_number);

    $addons    = $q->quotationAddons ?? collect();
    $hasAddons = $isMotor && $addons->count() > 0;

    $issueDate = \Carbon\Carbon::parse($q->created_at)->format('d-M-Y');
    $startDate = $q->cover_note_start_date
        ? \Carbon\Carbon::parse($q->cover_note_start_date)->format('d-M-Y') : '—';
    $endDate = $q->cover_note_end_date
        ? \Carbon\Carbon::parse($q->cover_note_end_date)->format('d-M-Y') : '—';
    $printDate = now()->format('n/j/Y g:i:sA');
    $printedBy = auth()->user()->name ?? 'KMJ Insurance';

    $premium         = floatval($q->total_premium_excluding_tax ?? 0);
    $discount        = floatval($q->discount ?? 0);
    $vat             = floatval($q->tax_amount ?? 0);
    $totalReceivable = floatval($q->total_premium_including_tax ?? 0);
    $sumInsured      = floatval($q->sum_insured ?? 0);

    $totalPages  = $isMotor ? 2 : 3;

    $addressParts = array_filter([
        $customer->postal_address ?? null,
        $customer->street ?? null,
        $customer->district->name ?? null,
    ]);
    $address = implode(",\n", $addressParts);

    $insurerName = $q->insuarer->name ?? $q->coverage->product->insurance->name ?? 'KMJ Insurance Brokers Ltd';

    $coveringDetails   = $q->cover_note_desc ?? ($isMotor
        ? 'COMPREHENSIVE MOTOR VEHICLE INSURANCE POLICY COVERING THE INSURED VEHICLE AGAINST LOSS OR DAMAGE AND THIRD PARTY LIABILITY AS SPECIFIED UNDER THE POLICY AGREEMENT.'
        : 'PROVIDES PROTECTION AGAINST ANY INSURED PERILS AS SPECIFIED UNDER THE COVER NOTE AGREEMENT.');

    $descriptionOfRisk = $q->operative_clause ?? ($isMotor
        ? 'Cover applies to accidental damage, fire, theft, and third-party bodily injury and property damage within the geographical limits specified in this policy.'
        : '');
@endphp

{{-- ================================================================ --}}
{{--  PAGE 1                                                           --}}
{{-- ================================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('assets/img/Header.jpeg') }}" alt="Header"/>

    <div class="content">

        {{-- Quotation Header Strip --}}
        <table class="quotation-header-table">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $q->id }}</td>
                <td class="col-value">{{ $q->control_number ?? '—' }}</td>
                <td class="col-value">{{ $issueDate }}</td>
            </tr>
        </table>

        {{-- Client / Policy Info --}}
        <table class="info-table">
            <tr>
                <td class="field-label" style="width:130px;">Client Name</td>
                <td class="field-value" colspan="3">
                    <strong>{{ strtoupper($customer->name ?? '—') }}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-label">Address</td>
                <td class="field-value" style="white-space:pre-line;">{{ $address }}
TIN: {{ $customer->tin_number ?? '—' }}{{ !empty($customer->vrn) ? '   VRN: '.$customer->vrn : '' }}</td>
                <td class="field-label" style="width:100px;">Contacts</td>
                <td class="field-value">Mobile: {{ $customer->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Intermediary Name</td>
                <td class="field-value">KMJ Insurance Brokers Ltd</td>
                <td class="field-label">Cover Period</td>
                <td class="field-value">{{ $startDate }} &nbsp;–&nbsp; {{ $endDate }}</td>
            </tr>
            <tr>
                <td class="field-label">Insurance Company</td>
                <td class="field-value" colspan="3">{{ $insurerName }}</td>
            </tr>
            <tr>
                <td class="field-label">Insurance Type</td>
                <td class="field-value" colspan="3">
                    <strong>{{ $isMotor ? 'MOTOR' : strtoupper($q->coverage->product->insurance->type ?? 'NON-MOTOR') }}</strong>
                </td>
            </tr>
        </table>

        {{-- Covering Details + Description of Risk (ALL types) --}}
        <table class="covering-table">
            <tr>
                <td class="covering-header">Covering Details</td>
                <td class="covering-header">Description of Risk</td>
            </tr>
            <tr>
                <td class="covering-cell" style="font-style:italic;">
                    {{ strtoupper($coveringDetails) }}
                </td>
                <td class="covering-cell">
                    {{ $descriptionOfRisk }}
                </td>
            </tr>
        </table>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- MOTOR LAYOUT                                                    --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($isMotor)

        <div class="section-with-bar" style="margin-top:5px;">
            <div class="section-bar">Quotation Details</div>

            <table class="motor-items-table">
                <thead>
                    <tr>
                        <th style="width:5%;">S/N</th>
                        <th style="width:28%;">Insured Name</th>
                        <th style="width:37%;">Vehicle Description</th>
                        <th class="tr" style="width:15%;">Sum Insured</th>
                        <th class="tr" style="width:15%;">Premium (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="comp-row">
                        <td colspan="5">Comprehensive</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td style="font-weight:bold;">{{ strtoupper($customer->name ?? '—') }}</td>
                        <td>
                            Reg No : <strong>{{ strtoupper($motor->registration_number ?? '—') }}</strong><br>
                            Make : <strong>{{ $motor->make ?? '—' }}</strong><br>
                            Model : <strong>{{ $motor->model ?? '—' }}</strong><br>
                            Chasis No : <strong>{{ strtoupper($motor->chassis_number ?? '—') }}</strong><br>
                            Body Type : <strong>{{ strtoupper($motor->body_type ?? '—') }}</strong><br>
                            Colour : <strong>{{ $motor->color ?? '—' }}</strong><br>
                            Reg Year : <strong>{{ $motor->year_of_manufacture ?? '—' }}</strong>
                        </td>
                        <td class="tr">
                            {{ number_format($sumInsured, 2) }}<br>
                            <span style="font-size:8.5px;">Other Fees : 0.00</span>
                        </td>
                        <td class="tr">{{ number_format($premium, 2) }}</td>
                    </tr>

                    @if($hasAddons)
                    <tr class="addon-dotted-row">
                        <td colspan="5">
                            <div class="addon-dotted">
                                @foreach($addons as $addon)
                                    @php
                                        $addonName = $addon->addonProduct->name ?? $addon->addon_desc ?? ('Addon #' . $addon->addon_product_id);
                                        $addonAmt  = floatval($addon->addon_amount ?? $addon->amount ?? 0);
                                    @endphp
                                    <span style="display:inline-block;width:64%;">{{ $addonName }}</span>
                                    @if($addonAmt > 0)
                                        <span style="display:inline-block;width:35%;text-align:right;">TZS {{ number_format($addonAmt, 2) }}</span>
                                    @endif
                                    <br>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endif

                    <tr><td colspan="5" style="height:4px;border:none;"></td></tr>
                </tbody>
            </table>
        </div>

        <table class="totals-table">
            <tr>
                <td class="tt-label">Discount :</td>
                <td class="tt-value">{{ number_format($discount, 2) }}</td>
            </tr>
            <tr>
                <td class="tt-label" style="font-weight:bold;">Total Premium:</td>
                <td class="tt-value">{{ number_format($premium, 2) }}</td>
            </tr>
            <tr>
                <td class="tt-label">VAT :</td>
                <td class="tt-value">{{ number_format($vat, 2) }}</td>
            </tr>
            <tr class="tt-total">
                <td class="tt-label">Total Receivable:</td>
                <td class="tt-value">{{ number_format($totalReceivable, 2) }}</td>
            </tr>
        </table>

        <div class="amount-words">
            TZS {{ number_format($totalReceivable, 2) }} Only
        </div>

        <table class="bank-footer-row">
            <tr>
                <td>Bank Details</td>
                <td style="text-align:center;">Digital Payment</td>
            </tr>
        </table>

        @endif
        {{-- /MOTOR --}}

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- NON-MOTOR LAYOUT                                               --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if(!$isMotor)

        <div class="section-with-bar" style="margin-top:5px;">
            <div class="section-bar">Items Covered</div>

            <table class="nonmotor-items-table">
                <thead>
                    <tr>
                        <th style="width:55%;">Items Covered</th>
                        <th class="tr" style="width:22%;">Sum Insured (in TZS)</th>
                        <th class="tr" style="width:23%;">Premium (in TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowIdx = 0; @endphp
                    <tr class="{{ $rowIdx % 2 === 1 ? 'even' : '' }}">
                        <td>
                            <div class="item-desc">{{ $q->coverage->name ?? '—' }}</div>
                            @if(!empty($q->cover_note_desc))
                                <div class="item-subdesc">{{ $q->cover_note_desc }}</div>
                            @elseif(!empty($q->coverage->description))
                                <div class="item-subdesc">{{ $q->coverage->description }}</div>
                            @endif
                        </td>
                        <td class="tr">{{ number_format($sumInsured, 2) }}</td>
                        <td class="tr">{{ number_format($premium, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="totals-table">
            <tr>
                <td class="tt-label">Discount :</td>
                <td class="tt-value">{{ number_format($discount, 2) }}</td>
            </tr>
            <tr>
                <td class="tt-label" style="font-weight:bold;">Total Premium:</td>
                <td class="tt-value">{{ number_format($premium, 2) }}</td>
            </tr>
            <tr>
                <td class="tt-label">VAT :</td>
                <td class="tt-value">{{ number_format($vat, 2) }}</td>
            </tr>
            <tr class="tt-total">
                <td class="tt-label">Total Receivable:</td>
                <td class="tt-value">{{ number_format($totalReceivable, 2) }}</td>
            </tr>
        </table>

        <div class="amount-words">
            TZS {{ number_format($totalReceivable, 2) }} Only
        </div>

        <table class="bank-footer-row">
            <tr>
                <td>Bank Details</td>
                <td style="text-align:center;">Digital Payment</td>
            </tr>
        </table>

        @endif
        {{-- /NON-MOTOR --}}

    </div>{{-- /.content --}}

    <div class="footer">
        <table class="footer-inner"><tr>
            <td><em>Powered from Smart Policy Insurance System</em><br>
                Printed Date: {{ $printDate }} | Printed By: {{ $printedBy }}</td>
            <td style="text-align:right;">Page 1 of {{ $totalPages }}</td>
        </tr></table>
    </div>
    <div class="suretech-logo">
        &#9632; <strong style="color:#1a3a5c;letter-spacing:1px;">SURETECH</strong>
        <span class="sys">SYSTEMS</span>
    </div>
    <img class="page-footer-img" src="{{ public_path('assets/img/Footer.jpeg') }}" alt="Footer"/>

</div>
{{-- /PAGE 1 --}}

<div class="page-break"></div>

{{-- ================================================================ --}}
{{--  PAGE 2 — BANK DETAILS                                            --}}
{{-- ================================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('assets/img/Header.jpeg') }}" alt="Header"/>

    <div class="content">

        <table class="quotation-header-table" style="margin-bottom:10px;">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $q->id }}</td>
                <td class="col-value">{{ $q->control_number ?? '—' }}</td>
                <td class="col-value">{{ $issueDate }}</td>
            </tr>
        </table>

        <table class="bank-split-table">
            <tr>
                <td style="width:50%;padding-right:6px;vertical-align:top;font-size:9.5px;line-height:1.6;">
                    <strong>FOLLOWING ARE OUR BANK DETAILS FOR PREMIUM PAYMENTS:</strong><br><br>
                    <strong>Account Name: {{ $insurerName }}</strong><br><br>

                    <div class="bank-block">
                        <strong>Bank Name: National Bank of Commerce Limited</strong><br>
                        TZS Account Number: 011103037639 | USD Account Number: 011105017048<br>
                        Bank swift code: NLCBTZTX | Branch: Corporate Branch
                    </div>
                    <div class="bank-block">
                        <strong>Bank Name: CRDB BANK PLC</strong><br>
                        TZS Account Number: 01J1027050200 | 0150439350800<br>
                        Bank swift code: CORUTZTZ | Sort code: 00003314
                    </div>
                    <div class="bank-block">
                        <strong>Bank Name: Stanbic Bank Tanzania</strong><br>
                        TZS Account Number: 9120001733166 | USD Account Number: 9120001733174<br>
                        Bank swift code: SBICTZTX | Bank Branch Code: 121011
                    </div>
                    <div class="bank-block">
                        <strong>Bank Name: Diamond Trust Bank Tanzania PLC</strong><br>
                        TZS Account Number: 0400242046 | USD Account Number: 0400242011<br>
                        Bank swift code: DTKETZTZ | Bank Branch Code: 001 | Branch: Main
                    </div>
                    <div class="bank-block">
                        <strong>Bank Name: NMB BANK</strong><br>
                        TZS Account Number: 24110002021 | TZS Account Number: 20110007788<br>
                        USD Account Number: 20110025007 | USD Account Number: 24110006388<br>
                        Bank swift code: NMBTZTZ | Branch: NMB Ohio
                    </div>
                    <div class="bank-block" style="margin-top:4px;font-size:9px;font-style:italic;">
                        Notes: The payment should be made in favor of the insurance company.
                    </div>
                </td>

                <td style="width:50%;padding-left:6px;vertical-align:top;font-size:9.5px;line-height:1.75;">
                    <div class="bank-block">
                        <strong>For payment through NMB/CRDB Channels:</strong><br>
                        Your payment reference # is
                        <strong>{{ $q->control_number ?? $q->cover_note_reference ?? '—' }}</strong>.
                        Your broker shall advise you on the payment guidelines.
                    </div>
                    <div class="bank-block" style="margin-top:8px;">
                        <strong>FOR PAYMENT THROUGH SELCOM PAY:</strong><br>
                        Reference number has not been generated, kindly click on 'Digital Payment'
                        button on quotation screen &amp; select 'Selcom' option to generate payment
                        reference number.
                    </div>
                </td>
            </tr>
        </table>

        <table class="issued-by-table" style="width:100%;border-collapse:collapse;margin-top:10px;margin-bottom:6px;">
            <tr>
                <td style="font-size:9px;color:#444;line-height:1.8;vertical-align:top;width:70%;">
                    1. When referring to this bill please quote the policy number / Cover note number.<br>
                    2. Cheques should be crossed and made payable to {{ $insurerName }}.<br>
                    3. An official receipt should be obtained upon payment.<br>
                    4. An insurance policy will become invalid retroactive to the date of inception if the full premium is not paid.
                </td>
                <td style="text-align:right;font-weight:bold;font-size:9.5px;vertical-align:top;">
                    Issued By, KMJ INSURANCE
                </td>
            </tr>
        </table>
        <div style="border-top:1px dashed #999;padding-top:4px;text-align:right;font-size:9px;color:#555;margin-bottom:8px;">
            For, KMJ Insurance Brokers Ltd
        </div>

        {{-- Declaration on page 2 for MOTOR --}}
        @if($isMotor)
        <div class="section-with-bar">
            <div class="section-bar">Customer Declaration</div>
            <div class="declaration-block">
                <ol style="padding-left:16px;margin:0;">
                    <li>I/We declare that the above quote is given to me/us on the information provided by me/us.</li>
                    <li>I/We declare to the best of my/our knowledge and belief that the information given on this quote is true in every respect.</li>
                    <li>I/We agree that this proposal and declaration shall be the basis of the contract between me/us and the Insurer.</li>
                    <li>I/We confirm to have been given adequate pre-sale and post-sale advice relating to coverage, terms and conditions of this insurance product.</li>
                </ol>
                <div style="text-align:center;margin-top:30px;opacity:0.06;">
                    <span style="font-size:80px;font-weight:900;color:#1a3a5c;letter-spacing:-3px;font-style:italic;">KMJ<span style="color:#c0272d;">ib</span></span>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /.content --}}

    <div class="footer">
        <table class="footer-inner"><tr>
            <td><em>Powered from Smart Policy Insurance System</em><br>
                Printed Date: {{ $printDate }} | Printed By: {{ $printedBy }}</td>
            <td style="text-align:right;">Page 2 of {{ $totalPages }}</td>
        </tr></table>
    </div>
    <div class="suretech-logo">
        &#9632; <strong style="color:#1a3a5c;letter-spacing:1px;">SURETECH</strong>
        <span class="sys">SYSTEMS</span>
    </div>
    <img class="page-footer-img" src="{{ public_path('assets/img/Footer.jpeg') }}" alt="Footer"/>

</div>
{{-- /PAGE 2 --}}

{{-- ================================================================ --}}
{{--  PAGE 3 — NON-MOTOR ONLY: Declaration                             --}}
{{-- ================================================================ --}}
@if(!$isMotor)
<div class="page-break"></div>
<div class="page">

    <img class="page-header-img" src="{{ public_path('assets/img/Header.jpeg') }}" alt="Header"/>

    <div class="content">

        <table class="quotation-header-table" style="margin-bottom:10px;">
            <tr>
                <td class="label-cell" rowspan="2">QUOTATION</td>
                <td class="col-header">Quotation Number:</td>
                <td class="col-header">Control Number:</td>
                <td class="col-header">Issue Date</td>
            </tr>
            <tr>
                <td class="col-value">{{ $q->id }}</td>
                <td class="col-value">{{ $q->control_number ?? '—' }}</td>
                <td class="col-value">{{ $issueDate }}</td>
            </tr>
        </table>

        <table class="issued-by-table" style="width:100%;border-collapse:collapse;margin-bottom:6px;">
            <tr>
                <td style="font-size:9px;color:#444;line-height:1.8;vertical-align:top;width:70%;">
                    1. When referring to this bill please quote the policy number / Cover note number.<br>
                    2. Cheques should be crossed and made payable to {{ $insurerName }}.<br>
                    3. An official receipt should be obtained upon payment.<br>
                    4. An insurance policy will become invalid retroactive to the date of inception if the full premium is not paid.
                </td>
                <td style="text-align:right;font-weight:bold;font-size:9.5px;vertical-align:top;">
                    Issued By, KMJ INSURANCE
                </td>
            </tr>
        </table>

        <div style="border-top:1px dashed #999;padding-top:4px;text-align:right;font-size:9px;color:#555;margin-bottom:8px;">
            For, KMJ Insurance Brokers Ltd
        </div>

        <div class="section-with-bar">
            <div class="section-bar">Customer Declaration</div>
            <div class="declaration-block">
                <ol style="padding-left:16px;margin:0;">
                    <li>I/We declare that the above quote is given to me/us on the information provided by me/us.</li>
                    <li>I/We declare to the best of my/our knowledge and belief that the information given on this quote is true in every respect.</li>
                    <li>I/We agree that this proposal and declaration shall be the basis of the contract between me/us and the Insurer.</li>
                    <li>I/We confirm to have been given adequate pre-sale and post-sale advice relating to coverage, terms and conditions of this insurance product.</li>
                </ol>
                <div style="text-align:center;margin-top:30px;opacity:0.06;">
                    <span style="font-size:80px;font-weight:900;color:#1a3a5c;letter-spacing:-3px;font-style:italic;">KMJ<span style="color:#c0272d;">ib</span></span>
                </div>
            </div>
        </div>

    </div>{{-- /.content --}}

    <div class="footer">
        <table class="footer-inner"><tr>
            <td><em>Powered from Smart Policy Insurance System</em><br>
                Printed Date: {{ $printDate }} | Printed By: {{ $printedBy }}</td>
            <td style="text-align:right;">Page 3 of 3</td>
        </tr></table>
    </div>
    <div class="suretech-logo">
        &#9632; <strong style="color:#1a3a5c;letter-spacing:1px;">SURETECH</strong>
        <span class="sys">SYSTEMS</span>
    </div>
    <img class="page-footer-img" src="{{ public_path('assets/img/Footer.jpeg') }}" alt="Footer"/>

</div>
@endif
{{-- /PAGE 3 --}}

</body>
</html>

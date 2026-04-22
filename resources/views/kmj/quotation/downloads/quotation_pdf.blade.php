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

        /* ── Prevent splits ── */
        .quotation-header-table,
        .info-table,
        .fin-table,
        .bank-split-table,
        .issued-by-table,
        .declaration-block,
        .section-with-bar,
        .motor-items-table,
        .totals-table-motor {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* ══════════════════════════════════════════════
           SHARED — Quotation header strip
        ══════════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════════
           SHARED — Info rows (client, policy)
        ══════════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════════
           SHARED — Section heading bar
        ══════════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════════
           NON-MOTOR — line-item detail rows
        ══════════════════════════════════════════════ */
        .detail-col-header { width: 100%; border-collapse: collapse; }
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
        .detail-line {
            width: 100%;
            border-collapse: collapse;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px dotted #ccc;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .detail-line td { padding: 5px 8px; vertical-align: top; font-size: 9.5px; }
        .detail-line.even td { background: #f7f7f7; }
        .dl-label { width: 65%; font-weight: bold; color: #1a1a1a; }
        .dl-value { width: 35%; text-align: right; white-space: nowrap; color: #111; }
        .detail-lines-wrapper { border-bottom: 1px solid #ccc; }

        /* ══════════════════════════════════════════════
           NON-MOTOR — financial summary
        ══════════════════════════════════════════════ */
        .fin-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .fin-table td { border: 1px solid #555; padding: 5px 10px; }
        .fin-table .fin-label { text-align: right; font-weight: bold; font-size: 10px; width: 75%; }
        .fin-table .fin-value { text-align: right; font-size: 10px; white-space: nowrap; }
        .fin-table .fin-total td { font-weight: bold; font-size: 11px; background: #f0f4f8; }

        /* ══════════════════════════════════════════════
           MOTOR — main items table
        ══════════════════════════════════════════════ */
        .motor-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
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
        .motor-items-table td.tc { text-align: center; }

        /* Comprehensive sub-heading row */
        .motor-items-table .comp-row td {
            font-weight: bold;
            background: #f0f4f8;
            font-size: 9.5px;
            color: #1a3a5c;
            padding: 3px 7px;
        }

        /* Dotted addon section inside the table */
        .addon-dotted {
            border-top: 1px dashed #777;
            border-bottom: 1px dashed #777;
            padding: 3px 7px;
            font-size: 9px;
            line-height: 1.75;
            color: #222;
        }
        .addon-dotted-row td { padding: 0 !important; border-left: 1px solid #ccc; border-right: 1px solid #ccc; }

        /* ══════════════════════════════════════════════
           MOTOR — totals block
        ══════════════════════════════════════════════ */
        .totals-table-motor {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }
        .totals-table-motor td {
            border: 1px solid #555;
            padding: 4px 10px;
            font-size: 10px;
        }
        .tt-label { text-align: right; width: 72%; }
        .tt-value { text-align: right; font-weight: bold; }
        .tt-total td { font-weight: bold; font-size: 11px; background: #f0f4f8; }

        /* Amount in words */
        .amount-words {
            border: 1px solid #555;
            border-top: none;
            padding: 3px 8px;
            font-size: 9.5px;
            font-style: italic;
            color: #333;
        }

        /* Bank / Digital row */
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

        /* ══════════════════════════════════════════════
           SHARED — Bank details (page 2)
        ══════════════════════════════════════════════ */
        .bank-split-table { width: 100%; border-collapse: collapse; }
        .bank-block { margin-bottom: 6px; font-size: 9.5px; line-height: 1.6; page-break-inside: avoid; break-inside: avoid; }
        .bank-block strong { font-size: 9.5px; }

        /* ══════════════════════════════════════════════
           SHARED — Declaration
        ══════════════════════════════════════════════ */
        .declaration-block {
            border: 1px solid #555;
            border-top: none;
            padding: 12px 10px 80px 10px;
            font-size: 9.5px;
            line-height: 1.9;
        }

        /* ══════════════════════════════════════════════
           SHARED — Footer
        ══════════════════════════════════════════════ */
        .footer { margin: 6px 20px 0 20px; font-size: 8px; color: #888; border-top: 1px solid #ddd; padding-top: 4px; }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { font-size: 8px; color: #888; }

        .suretech-logo { padding: 4px 20px 0 20px; font-size: 9px; color: #1a3a5c; font-weight: bold; letter-spacing: 1px; }
        .suretech-logo span.sys { font-weight: normal; font-size: 7.5px; letter-spacing: 2px; display: block; color: #888; }

        /* ══════════════════════════════════════════════
           NON-MOTOR only extras
        ══════════════════════════════════════════════ */
        .covering-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .covering-table td { border: 1px solid #555; padding: 6px 8px; vertical-align: top; font-size: 9.5px; }
    </style>
</head>
<body>

@php
    $q         = $quotation;
    $customer  = $q->customer;
    $motor     = $q->motor ?? null;
    $isMotor   = intval($q->insurance_id) === 2 || !is_null($motor);
    $addons    = $q->quotationAddons ?? collect();
    $hasAddons = $isMotor && $addons->count() > 0;

    $issueDate = \Carbon\Carbon::parse($q->created_at)->format('d-M-Y');
    $startDate = $q->cover_note_start_date
        ? \Carbon\Carbon::parse($q->cover_note_start_date)->format('d-M-Y') : '—';
    $endDate   = $q->cover_note_end_date
        ? \Carbon\Carbon::parse($q->cover_note_end_date)->format('d-M-Y') : '—';
    $printDate = now()->format('n/j/Y g:i:sA');
    $printedBy = auth()->user()->name ?? 'KMJ Insurance';

    $premium         = floatval($q->total_premium_excluding_tax ?? 0);
    $discount        = floatval($q->discount ?? 0);
    $vat             = floatval($q->tax_amount ?? 0);
    $totalReceivable = floatval($q->total_premium_including_tax ?? 0);
    $sumInsured      = floatval($q->sum_insured ?? 0);
    $taxRate         = floatval($q->tax_rate ?? 0.18);

    // Total pages: motor = 2, non-motor = 3
    $totalPages = $isMotor ? 2 : 3;

    // Address
    $addressParts = array_filter([
        $customer->postal_address ?? null,
        $customer->street ?? null,
        $customer->district->name ?? null,
        $customer->region->name ?? null,
    ]);
    $address = implode(",\n", $addressParts);

    // Insurer name
    $insurerName = $q->coverage->product->insurance->name ?? ($q->insuarer->name ?? 'KMJ Insurance Brokers Ltd');
@endphp

{{-- ================================================================ --}}
{{--  PAGE 1                                                           --}}
{{-- ================================================================ --}}
<div class="page">

    <img class="page-header-img" src="{{ public_path('assets/img/Header.jpeg') }}" alt="Header"/>

    <div class="content">

        {{-- ── Quotation Header Strip ── --}}
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

        {{-- ── Client / Policy Summary ── --}}
        <table class="info-table">
            <tr>
                <td class="field-label" style="width:130px;">Client Name</td>
                <td class="field-value" colspan="3">
                    <strong>{{ strtoupper($customer->name ?? $q->customer_name ?? '—') }}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-label">Address</td>
                <td class="field-value" style="white-space:pre-line;">{{ $address }}
TIN: {{ $customer->tin_number ?? '—' }}{{ isset($customer->vrn) && $customer->vrn ? '   VRN: '.$customer->vrn : '' }}</td>
                <td class="field-label" style="width:100px;">Contacts</td>
                <td class="field-value">Mobile: {{ $customer->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Intermediary Name</td>
                <td class="field-value">{{ $q->insuarer->name ?? 'KMJ Insurance Brokers Ltd' }}</td>
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
                    <strong>{{ strtoupper($q->coverage->product->insurance->type ?? ($isMotor ? 'MOTOR' : 'NON-MOTOR')) }}</strong>
                </td>
            </tr>
            @if(!$isMotor)
            <tr>
                <td class="field-label">Coverage / Risk</td>
                <td class="field-value" colspan="3">{{ $q->coverage->name ?? '—' }}</td>
            </tr>
            @endif
        </table>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- NON-MOTOR LAYOUT                                               --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if(!$isMotor)

            {{-- Covering Details + Description of Risk --}}
            <table class="covering-table" style="margin-bottom:5px;">
                <tr>
                    <td class="section-bar" style="width:50%;">Covering Details</td>
                    <td class="section-bar" style="width:50%;">Description of Risk</td>
                </tr>
                <tr>
                    <td class="field-value" style="font-style:italic;font-size:9px;color:#444;line-height:1.6;padding:6px 8px;">
                        {{ strtoupper($q->cover_note_desc ?? 'PROVIDES PROTECTION AGAINST ANY INSURED PERILS AS SPECIFIED UNDER THE COVER NOTE AGREEMENT.') }}
                    </td>
                    <td style="border:1px solid #555;padding:6px 8px;vertical-align:top;">
                        {{ $q->operative_clause ?? '' }}
                    </td>
                </tr>
            </table>

            {{-- Quotation Details — line-item style --}}
            <div class="section-with-bar">
                <div class="section-bar">Quotation Details</div>

                <table class="detail-col-header">
                    <tr>
                        <td style="width:65%;">Description</td>
                        <td style="width:35%;text-align:right;">Value</td>
                    </tr>
                </table>

                @php
                    $finKeys  = ['Total Premium (Excl. Tax)', 'Total Premium (Incl. Tax)', 'Tax Amount'];
                    $rowIndex = 0;
                    // Build detail rows — add/remove fields to match your model
                    $quotationDetails = array_filter([
                        'Coverage'           => $q->coverage->name ?? null,
                        'Risk Code'          => $q->risk_code ?? null,
                        'Product Code'       => $q->product_code ?? null,
                        'Sum Insured'        => $sumInsured > 0 ? number_format($sumInsured, 2) : null,
                        'Premium Rate'       => $q->premium_rate ? number_format(floatval($q->premium_rate) * 100, 2) . '%' : null,
                        'Currency'           => $q->currency->name ?? null,
                        'Exchange Rate'      => $q->exchange_rate ?? null,
                        'Commission Rate'    => $q->commission_rate ? $q->commission_rate . '%' : null,
                        'Is Tax Exempted'    => ($q->is_tax_exempted === 'Y') ? 'Yes' : 'No',
                    ], fn($v) => $v !== null && $v !== '' && $v !== '0.00' && $v !== '0');
                @endphp

                <div class="detail-lines-wrapper">
                    @foreach($quotationDetails as $label => $value)
                        @if(!in_array($label, $finKeys))
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

            {{-- Financial Summary --}}
            <table class="fin-table">
                <tr>
                    <td class="fin-label">Discount</td>
                    <td class="fin-value">{{ number_format($discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="fin-label">Total Premium (Excl. Tax)</td>
                    <td class="fin-value">{{ number_format($premium, 2) }}</td>
                </tr>
                <tr>
                    <td class="fin-label">VAT / Tax Amount</td>
                    <td class="fin-value">{{ number_format($vat, 2) }}</td>
                </tr>
                <tr class="fin-total">
                    <td class="fin-label">Total Receivable (Incl. Tax)</td>
                    <td class="fin-value">{{ number_format($totalReceivable, 2) }}</td>
                </tr>
            </table>

        @endif
        {{-- /NON-MOTOR --}}

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- MOTOR LAYOUT (with OR without addons)                          --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        @if($isMotor)

            {{-- Items table --}}
            <div class="section-with-bar" style="margin-top:5px;">
                <div class="section-bar">Quotation Details</div>

                <table class="motor-items-table">
                    <thead>
                        <tr>
                            <th style="width:5%;">S/N</th>
                            <th style="width:28%;">Insured Name</th>
                            <th style="width:35%;">Vehicle Description</th>
                            <th class="tr" style="width:16%;">Sum Insured</th>
                            <th class="tr" style="width:16%;">Premium (TZS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Comprehensive sub-heading --}}
                        <tr class="comp-row">
                            <td colspan="5">Comprehensive</td>
                        </tr>

                        {{-- Main vehicle row --}}
                        <tr>
                            <td>1</td>
                            <td style="font-weight:bold;">
                                {{ strtoupper($customer->name ?? $q->customer_name ?? '—') }}
                            </td>
                            <td>
                                @if($motor)
                                    Reg No : <strong>{{ strtoupper($motor->registration_number ?? '—') }}</strong><br>
                                    Make : <strong>{{ $motor->make ?? '—' }}</strong><br>
                                    Model : <strong>{{ $motor->model ?? '—' }}</strong><br>
                                    Chasis No : <strong>{{ strtoupper($motor->chassis_number ?? '—') }}</strong><br>
                                    Body Type : <strong>{{ strtoupper($motor->body_type ?? '—') }}</strong><br>
                                    Colour : <strong>{{ $motor->color ?? '—' }}</strong><br>
                                    Reg Year : <strong>{{ $motor->year_of_manufacture ?? '—' }}</strong>
                                @else
                                    {{ $q->coverage->name ?? '—' }}
                                @endif
                            </td>
                            <td class="tr">
                                {{ number_format($sumInsured, 2) }}<br>
                                <span style="font-size:8.5px;">Other Fees : 0.00</span>
                            </td>
                            <td class="tr">{{ number_format($premium, 2) }}</td>
                        </tr>

                        {{-- ── ADDONS (only when motor has addons) ── --}}
                        @if($hasAddons)
                        <tr class="addon-dotted-row">
                            <td colspan="5">
                                <div class="addon-dotted">
                                    @foreach($addons as $addon)
                                        @php
                                            $addonName = $addon->addonProduct->name ?? ('Addon #'.$addon->addon_product_id);
                                            $addonAmt  = floatval($addon->amount ?? 0);
                                        @endphp
                                        <span style="display:inline-block;width:65%;">{{ $addonName }}</span>
                                        @if($addonAmt > 0)
                                            <span style="display:inline-block;width:34%;text-align:right;">
                                                TZS {{ number_format($addonAmt, 2) }}
                                            </span>
                                        @endif
                                        <br>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                        {{-- /ADDONS --}}

                        {{-- Spacer --}}
                        <tr>
                            <td colspan="5" style="height:5px;border:none;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Motor totals --}}
            <table class="totals-table-motor">
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

            {{-- Amount in words --}}
            <div class="amount-words">
                TZS {{ number_format($totalReceivable, 2) }} Only
            </div>

            {{-- Bank / Digital footer bar --}}
            <table class="bank-footer-row">
                <tr>
                    <td>Bank Details</td>
                    <td style="text-align:center;">Digital Payment</td>
                </tr>
            </table>

        @endif
        {{-- /MOTOR --}}

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

        {{-- Header strip repeat --}}
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

        {{-- Bank details two-column --}}
        <table class="bank-split-table">
            <tr>
                {{-- LEFT — bank list --}}
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

                {{-- RIGHT — digital / NMB/CRDB / Selcom --}}
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

        {{-- Notes + Issued By (shown on page 2 for MOTOR, on page 3 for non-motor) --}}
        @if($isMotor)
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

        {{-- Customer Declaration on page 2 for motor --}}
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
{{--  PAGE 3 — NON-MOTOR ONLY: Terms & Declaration                     --}}
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

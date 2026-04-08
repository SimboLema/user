<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Cover Note</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            border: 1px solid #000;
            /* Outer border as seen in the image */
            padding: 10px 15px;
            box-sizing: border-box;
        }

        /*
         * Header styling
         */
        .header-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .header-cell {
            display: table-cell;
            vertical-align: top;
            padding: 0;
        }

        .header-left {
            width: 20%;
            text-align: left;
        }

        .header-right {
            width: 80%;
            text-align: center;
            /* Kurekebisha mpangilio wa anwani katikati ya sehemu yake */
            padding-left: 10px;
            font-size: 9px;
            /*border-bottom: 3px solid #000; */
            padding-bottom: 5px;
        }

        .header-right h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #17375d;
        }

        .header-right p {
            margin: 1px 0;
            line-height: 1.2;
        }

        /*
         * Motor Cover Note Bar
         */
        .motor-cover {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            background-color: #f2f2f2;
            border-top: 2px solid #000;
            /* Kuiga muundo wa picha */
            border-bottom: 2px solid #000;
            /* Kuiga muundo wa picha */
            margin: 5px 0;
            padding: 5px;
        }

        /*
         * General Table Styling (kwa sections zote)
         */
        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 10px;
        }

        .section-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            height: auto;
        }

        .section-table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        /*
         * Sehemu ya RISK NOTE / STICKER NO
         */
        .risk-note-table {
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 10px;
        }

        .risk-note-table td {
            border: none;
            padding: 3px 6px;
            vertical-align: top;
        }

        .risk-note-cell {
            border: 1px solid #000;
            padding: 3px 5px;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            display: inline-block;
            vertical-align: middle;
            margin-top: 4px;
        }

        /*
         * Text Section (The policyholder described...)
         */
        .policy-text-section td {
            border: 1px solid #000;
            /* Border surrounding the text */
            padding: 8px 6px;
            font-size: 10px;
            line-height: 1.4;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: justify;
            hyphens: auto;
        }

        .policy-text-section td span {
            display: inline-block;
            font-size: clamp(8px, 1vw, 10px);
            /* itapunguza font size kama text ni refu, lakini isiwe chini ya 8px */
        }



        /* Vehicle Details - No background color on data rows */
        .vehicle-details-table .data-row td {
            background-color: transparent;
            font-weight: normal;
        }

        /*
         * QR Code and Signature Section
         */
        .scan-signature-table {
            margin-top: 0;
        }

        .scan-signature-table td {
            height: 110px;
            /* Kuweka urefu sawa */
            vertical-align: middle;
        }

        .qr-cell {
            width: 30%;
            text-align: center;
            font-size: 10px;
            line-height: 1.5;
        }

        .signature-cell {
            width: 70%;
            font-size: 10px;
            line-height: 1.5;
        }

        .signature-cell-content {
            padding-left: 10px;
        }

        .qr-code-box {
            display: inline-block;
            margin-top: 5px;
        }

        /*
         * Footer Section
         */
        .important-note {
            font-size: 8px;
            padding: 5px 0;
            margin-top: 0;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        /* .kmj-logo-container {
            text-align: right;
            margin: 15px 0 5px 0;
        } */

        .footer-info {
            font-size: 8px;
            text-align: center;
            margin-top: 5px;
        }

        .footer-info p {
            margin: 1px 0;
            line-height: 1.2;
        }

        .powered-by-grid {
            border-top: 1px solid #000;
            padding-top: 2px;
            margin-top: 5px;
            font-size: 7px;
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .powered-by-cell {
            display: table-cell;
            text-align: left;
            width: 33.33%;
        }

        /* Utility classes */
        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .small-text {
            font-size: 9px;
        }

        .data-text {
            font-weight: normal;
        }
    </style>


<body>

    <div class="container">
        <div class="header-grid">
            <div class="header-cell header-left">
                <img src="data:image/png;base64,{{ $relianceLogo }}" alt="Reliance Logo"
                    style="max-width: 120px; height: auto; margin-right: 10px;">
            </div>
            <div class="header-cell header-right">
                <h2>RELIANCE INSURANCE COMPANY (T) LIMITED</h2>
                <p>10th Floor, TAN House, Plot No 34/1, Victoria Area, New Bagamoyo Road, Kinondoni</p>
                <p>P.O. BOX 9826, Dar es Salaam, Tanzania. Tel: 222972876-98. Toll-free: 0800 750 271</p>
                <p>W: http://www.reliancetz.com | E: insure@reliance.co.tz | Instagram: reliancetanzania</p>
            </div>
        </div>
        <div class="motor-cover bold">MOTOR COVER NOTE</div>

        <table class="risk-note-table">
            <tr>
                <td style="width: 50%;">
                    <span class="bold">RISK NOTE NO :</span>
                    <span class="risk-note-cell" style="width: 100px; margin-left: 5px;">14474</span>
                </td>
                <td class="right" style="width: 50%;">
                    <span class="bold">STICKER NO :</span>
                    <span class="risk-note-cell"
                        style="width: 140px; margin-left: 5px;">{{ $quotation->sticker_number ?? '' }}</span>
                </td>
            </tr>
        </table>

        <table class="section-table" style="margin-top:0;">
            <tr>
                <td colspan="2" class="policy-text-section">
                    The policyholder described in the Certificate below having proposed for insurance in respect of the
                    Motor Vehicle described in the Certificate and having paid the sum of <span
                        class="bold">177,000.00 TZS (Incl. VAT)</span>,
                    <span class="bold uppercase">ONE HUNDRED SEVENTY-SEVEN THOUSAND TANZANIAN SHILLINGS</span> as
                    premium. <br />
                    The risk is hereby held covered in terms of the company's usual form of <span class="bold">Third
                        Party Premium Buses-Daladala within City</span>
                    Policy applicable thereto for the period between the dates specified in the Certificate unless the
                    cover
                    be terminated by the Company by notice in writing in which case the insurance will thereupon cease
                    and a
                    proportionate part of the annual premium otherwise payable for such insurance will be charged for
                    the
                    time the Company has been on risk. The Policyholder warrants that the Motor Vehicle is only used for
                    the
                    purpose of <span class="bold">Passenger-Third Party Premium Buses.</span>
                </td>
            </tr>
        </table>

        <table
            style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif; font-size:11px; border:1px solid black;">
            <!-- Row 1 -->
            <tr>
                <td style="width:20%; font-weight:bold; border:1px solid black; padding:4px 6px;">Insured Name</td>
                <td style="width:35%; font-weight:bold; border:1px solid black; padding:4px 6px;">
                    {{ strtoupper($quotation->owner_name) ?? '' }}</td>
                <td style="width:19.4%; font-weight:bold; border:1px solid black; padding:4px 6px;">TIRA Cover Note</td>
                <td style="width:25.6%; border:1px solid black; padding:4px 6px;">
                    {{ $quotation->cover_note_reference ?? '' }}</td>
            </tr>

            <!-- Row 2 -->
            <tr>
                <td style="width:20%; font-weight:bold; border:1px solid black; padding:4px 6px;">Account <br><br>
                    Address</td>
                <td style="width:35%; border:1px solid black; padding:4px 6px;">
                    {{ strtoupper($quotation->owner_name) ?? '' }}<br><br>
                    {{ strtoupper($quotation->customer->postal_address) ?? '' }}<br>
                    TIN: 120428128
                </td>
                <td colspan="2" style="border:1px solid black; padding:0;">
                    <!-- Nested Table -->
                    <table style="width:100%; border-collapse:collapse; font-size:11px;">
                        <tr>
                            <td style="width:44%; font-weight:bold; border:1px solid black; padding:4px 6px;">Policy No
                            </td>
                            <td style="width:56%; border:1px solid black; padding:4px 6px;">P00/015/3176/2025/07/101404
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="width:44%; font-weight:bold;font-size:10px; border:1px solid black; padding:4px 6px;">
                                Customer
                                Tax Invoice</td>
                            <td style="width:56%; border:1px solid black; padding:4px 6px;">RICL264844</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding:0; border:none;">
                                <table style="width:100%; border-collapse:collapse; font-size:11px;">
                                    <tr>
                                        <td
                                            style="width:25%; font-weight:bold; border:1px solid black; padding:4px 6px;">
                                            Debit No</td>
                                        <td style="width:25%; border:1px solid black; padding:4px 6px;">10952</td>
                                        <td
                                            style="width:25%; font-weight:bold; border:1px solid black; padding:4px 6px;">
                                            File No</td>
                                        <td style="width:25%; border:1px solid black; padding:4px 6px;"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Row 3 -->
            <tr>
                <td style="width:20%; font-weight:bold; border:1px solid black; padding:4px 6px;">Insurer Name</td>
                <td style="width:35%; border:1px solid black; padding:4px 6px;">Reliance Insurance Company (Tanzania)
                    Limited</td>
                <td style="width:20%; font-weight:bold; border:1px solid black; padding:4px 6px;">Intermediary</td>
                <td style="width:25%; border:1px solid black; padding:4px 6px;">KMJ Insurance Brokers Ltd</td>
            </tr>

            <!-- Row 4 -->
            <tr>
                <td style="width:20%; font-weight:bold; border:1px solid black; padding:4px 6px;">Cover Period From</td>
                <td colspan="3" style="border:1px solid black; padding:4px 6px;">
                    {{ \Carbon\Carbon::parse($quotation->cover_note_start_date)->format('d-M-Y h:iA') ?? '' }}
                    To
                    {{ \Carbon\Carbon::parse($quotation->cover_note_end_date)->format('d-M-Y') ?? '' }}

                </td>
            </tr>
        </table>




        <table class="section-table small-text">
            <tr>
                <td style="width: 50%;" class="bold">
                    THE MOTOR VEHICLES INSURANCE ACT,1961(CAP 169 R.E. 2020)(SECTION-7) AND THE MOTOR VEHICLES (THIRD
                    PARTY
                    RISKS) DECREE 1953 (ZANZIBAR) SECTION 6
                </td>
                <td style="width: 50%;" class="bold">
                    VALIDITY OF THIS RISK NOTE IS SUBJECT TO RECEIPT OF PREMIUM BY PRIOR INSURER TO INCEPTION OF RISK &
                    SUBJECT TO REALIZATION OF CHEQUE WHEREVER APPLICABLE.
                </td>
            </tr>
            <tr>
                <td colspan="2" class="bold" style="background-color: #f2f2f2;">
                    CERTIFICATE OF INSURANCE: We hereby certify that a Policy of Insurance covering the liabilities
                    required
                    to be covered by the above mentioned legislations has been issued as follows:
                </td>
            </tr>
        </table>

        <table class="section-table vehicle-details-table" style="font-size: 85%;">
            <tr class="center bold">
                <td style="width: 15%;">Vehicle Registration No.</td>
                <td style="width: 15%;">Make/Model</td>
                <td style="width: 15%;">Type/Color</td>
                <td style="width: 20%;">Engine No.</td>
                <td style="width: 20%;">Chassis No.</td>
                <td style="width: 15%;">Seating Capacity</td>
            </tr>
            <tr class="center data-row">
                <td>{{ $quotation->registration_number }}</td>
                <td>{{ $quotation->make }} {{ $quotation->model }}</td>
                <td>{{ $quotation->color }}</td>
                <td>{{ $quotation->engine_number }}</td>
                <td>{{ $quotation->chassis_number }}</td>
                <td>{{ $quotation->sitting_capacity }}</td>
            </tr>
            <tr class="center bold">
                <td>CC</td>
                <td>Year of Manufacture</td>
                <td>Vehicle Sum Insured (in TZS)</td>
                <td>Net Premium</td>
                <td>VAT Amount</td>
                <td>Premium (Incl. VAT) (in TZS)</td>
            </tr>
            <tr class="center data-row">
                <td>2980</td>
                <td>{{ $quotation->year_of_manufacture }}</td>
                <td>{{ number_format($quotation->sum_insured) }}</td>
                <td>{{ number_format($quotation->total_premium_excluding_tax) }}</td>
                <td>{{ number_format($quotation->tax_amount) }}</td>
                <td>{{ number_format($quotation->total_premium_including_tax) }}</td>
            </tr>
        </table>

        <table
            style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif; font-size:9px; border:1px solid black;">
            <tr>

                <td style="width:15%; padding:2px; vertical-align:middle; text-align:center; line-height:1.2;">
                    <span
                        style="border:1px solid black; display:inline-block; padding:14px;font-weight:bold;font-size:14px">Scan
                        QR
                        <br> code to <br> Validate</span>
                </td>


                <td style="width:15%; padding:2px; vertical-align:middle; text-align:center;">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" height="75"
                        style="border:1px solid black; display:inline-block; padding:2px;"
                        style="display:block; margin:auto;">
                </td>


                <td style="width:45%; padding:4px; vertical-align:top;">
                    <div style="padding-left:10px;">
                        <span style="font-weight:bold;">Date of Issue :</span>
                        <br>
                        <br>
                        {{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-Y') ?? '' }}
                        <div style="border-bottom:1px solid black; width:150px; margin-top:2px;"></div>
                    </div>
                </td>


                <td style="width:25%; padding:4px; vertical-align:top; text-align:right;">
                    <div style="padding-top:5px;">
                        <span style="font-weight:bold; font-size:8px;">ISSUED BY, KMJ INSURANCE THREE</span>
                    </div>
                    <div style="padding-top:40px;">
                        <span style="font-weight:bold; font-size:8px;">AUTHORIZED SIGNATORY</span>
                    </div>
                </td>
            </tr>
        </table>



        <p class="important-note bold" style="border-bottom: 1px solid #000;">
            <span style="text-decoration: underline; font-weight: bold;">IMPORTANT</span>: In the event of any change of
            vehicle or ownership, this certificate must be returned to the
            company
            within 7 days from the date of change.
        </p>
        <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <tr>
                <td style="width:50%; text-align:left; vertical-align:middle;">
                    <img src="data:image/png;base64,{{ $suretechLogo }}" alt="SureTech Logo" height="50">
                </td>
                <td style="width:50%; text-align:right; vertical-align:middle;">
                    <img src="data:image/png;base64,{{ $kmjLogo }}" alt="KMJ Logo" height="50">
                </td>
            </tr>
        </table>


        <div class="footer-info" style="border-top: 1px solid #000;">
            <p>KMJ Insurance Brokers Ltd, No 51, Plot 1595 Jamhuri St, P.O Box 20139, Dar es Salaam, Tanzania, City:
                DarEsSalaam</p>
            <p>Tel: +255 22 2120432 | +255 712 467873 Email: admin@kmjinsurance.co.tz</p>

            <div class="powered-by-grid">
                <div class="powered-by-cell" style="text-align: left;">
                    Powered from KMJib Insurance System
                </div>
                <div class="powered-by-cell" style="text-align: center;">
                    UIN #: KMJI14474 | Receipt No: 162730
                </div>
                <div class="powered-by-cell" style="text-align: right;">
                    KMJ Issue {{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-Y h:iA') ?? '' }}
                </div>
            </div>
        </div>
    </div>

</body>

</html>

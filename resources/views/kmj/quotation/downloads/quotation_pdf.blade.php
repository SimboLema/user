<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Policy Schedule - Page 2</title>
    <style>
        /*
         * GLOBAL STYLING
         */
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
            width: 100%;
            border: 1px solid #000;
            padding: 10px 15px;
            box-sizing: border-box;
        }

        /*
         * HEADER (Mini Details Bar)
         */
        .mini-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
            table-layout: fixed; /* Muhimu kwa upana wa seli */
        }

        .mini-header-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            text-align: left;
            background-color: #f2f2f2;
            height: 15px;
        }

        .mini-header-table .label {
            font-weight: bold;
            margin-right: 5px;
        }

        .col-25 { width: 25%; }

        /*
         * MAIN SCHEDULE TABLE
         */
        .schedule-title {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 0;
            font-size: 10px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: -1px;
            margin-bottom: 10px;
            table-layout: fixed; /* Muhimu */
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            height: 15px;
        }

        .schedule-table th {
            text-align: center;
            background-color: #d9e1f2;
            font-weight: bold;
        }

        /* Upana wa Schedule I */
        .schedule-table.schedule-i-table .item-col { width: 5%; text-align: center; font-weight: bold;}
        .schedule-table.schedule-i-table .cover-col { width: 35%; text-align: left;}
        .schedule-table.schedule-i-table .motor-col { width: 20%; text-align: right;}
        .schedule-table.schedule-i-table .commercial-col { width: 20%; text-align: right;}
        .schedule-table.schedule-i-table .cycle-col { width: 20%; text-align: right;}

        /* Upana wa Schedule II A */
        .schedule-table.schedule-iia-table .item-col { width: 5%; text-align: center; font-weight: bold;}
        .schedule-table.schedule-iia-table .cover-col { width: 40%; text-align: left;}
        .schedule-table.schedule-iia-table .motor-private-col { width: 30%; text-align: center;}
        .schedule-table.schedule-iia-table .motor-cycle-col { width: 25%; text-align: center;}


        .bold { font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bg-lightgrey { background-color: #f2f2f2; }


        /*
         * SCHEDULE B TABLE (Deductibles) - CRITICAL ADJUSTMENTS
         */
        .schedule-b-table {
            table-layout: fixed; /* Muhimu */
            margin-bottom: 5px;
        }

        .schedule-b-table th {
            text-align: center;
            font-size: 9px;
            height: 35px;
            padding: 3px;
        }

        .schedule-b-table td {
            font-size: 9px;
            padding: 4px;
            vertical-align: top;
        }

        /* Upana wa Schedule II B */
        .schedule-b-table .item-col { width: 5%; }
        .schedule-b-table .category-col { width: 18%; }
        .schedule-b-table .cargo-col { width: 27%; }
        .schedule-b-table .passenger-row-2 { width: 15%; } /* Nusu ya 30% */
        .schedule-b-table .special-col { width: 25%; }

        .schedule-b-table .data-align-left { text-align: left; }
        .schedule-b-table .data-align-right { text-align: right; }


        /*
         * FOOTER INFO
         */
        .footer-disclaimer {
            font-size: 9px;
            margin: 10px 0;
            line-height: 1.4;
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
            width: 25%;
            padding: 0 5px;
        }
        .pb-center-uin { width: 17%; text-align: center !important; }
        .pb-center-receipt { width: 17%; text-align: center !important; }
        .pb-right-issue { width: 33%; text-align: right !important; }
        .pb-page-no { width: 13%; text-align: right !important; border-left: 1px solid #000; padding-left: 5px;}
    </style>
</head>

<body>

    <div class="container">

        <table class="mini-header-table">
            <tr>
                <td class="col-25"><span class="label">Risk Note No.</span> 14474</td>
                <td class="col-25"><span class="label">Sticker No.</span> 25002-20945-36811</td>
                <td class="col-25"><span class="label">Insured Name</span> HAMDU SAID MSAMI</td>
                <td class="col-25"><span class="label">Issuing Intermediary Name</span> KMJ Insurance Brokers Ltd</td>
            </tr>
        </table>

        <p class="schedule-title bold" style="background-color: #d9e1f2;">LIMIT OF LIABILITY ATTACHING TO AND FORMING PART OF MOTOR VEHICLE INSURANCE POLICY</p>

        <table class="schedule-table schedule-i-table">
            <tr>
                <th class="item-col">Item</th>
                <th class="cover-col">Scope of Cover/Limit of Liability</th>
                <th class="motor-col">Motor Private</th>
                <th class="commercial-col">Motor Commercial</th>
                <th class="cycle-col">Motor Cycle</th>
            </tr>
            <tr>
                <td class="item-col">1.0</td>
                <td class="cover-col">Third party Injury/Death</td>
                <td class="motor-col right">Unlimited</td>
                <td class="commercial-col right">Unlimited</td>
                <td class="cycle-col right">Unlimited</td>
            </tr>
            <tr>
                <td class="item-col">2.0</td>
                <td class="cover-col">Third Party Property Damage</td>
                <td class="motor-col right">100,000,000</td>
                <td class="commercial-col right">100,000,000</td>
                <td class="cycle-col right">50,000,000</td>
            </tr>
            <tr>
                <td class="item-col">3.0</td>
                <td class="cover-col">Passenger Liability Per Person</td>
                <td class="motor-col right">30,000,000</td>
                <td class="commercial-col right">30,000,000</td>
                <td class="cycle-col right">20,000,000</td>
            </tr>
            <tr>
                <td class="item-col"></td>
                <td class="cover-col">Passenger Liability Per Occurrence</td>
                <td class="motor-col right">100,000,000</td>
                <td class="commercial-col right">100,000,000</td>
                <td class="cycle-col right">N/A</td>
            </tr>
            <tr>
                <td class="item-col">4.0</td>
                <td class="cover-col">Medical Expenses Per Person</td>
                <td class="motor-col right">2,500,000</td>
                <td class="commercial-col right">N/A</td>
                <td class="cycle-col right">250,000</td>
            </tr>
            <tr>
                <td class="item-col">5.0</td>
                <td class="cover-col">Personal Accident Benefit</td>
                <td class="motor-col right" style="font-size: 9px;">2,000,000 (any one accident)</td>
                <td class="commercial-col right">N/A</td>
                <td class="cycle-col right">N/A</td>
            </tr>
            <tr>
                <td class="item-col">6.0</td>
                <td class="cover-col">Windscreen Cover</td>
                <td class="motor-col right" style="font-size: 9px;">500,000 (unless stated otherwise)</td>
                <td class="commercial-col right" style="font-size: 9px;">1,000,000 (unless stated otherwise)</td>
                <td class="cycle-col right">N/A</td>
            </tr>
            <tr>
                <td class="item-col">7.0 (i)</td>
                <td class="cover-col">Towing Charges within DSM</td>
                <td class="motor-col right">500,000</td>
                <td class="commercial-col right">500,000</td>
                <td class="cycle-col right">100,000</td>
            </tr>
            <tr>
                <td class="item-col">(ii)</td>
                <td class="cover-col">Towing charges outside Dsm</td>
                <td class="motor-col right">1,000,000</td>
                <td class="commercial-col right">1,500,000</td>
                <td class="cycle-col right">N/A</td>
            </tr>
            <tr>
                <td class="item-col">8.0</td>
                <td class="cover-col">Riots/Strikes (nonpolitical)</td>
                <td class="motor-col right">Included</td>
                <td class="commercial-col right">Included</td>
                <td class="cycle-col right">Included</td>
            </tr>
            <tr>
                <td class="item-col">9.0</td>
                <td class="cover-col">Geographical Limit</td>
                <td class="motor-col center" style="font-size: 9px;">East Africa (unless stated otherwise)</td>
                <td class="commercial-col center" style="font-size: 9px;">East Africa (unless stated otherwise)</td>
                <td class="cycle-col center">East Africa</td>
            </tr>
        </table>

        <p class="schedule-title bold" style="background-color: #d9e1f2;">SCHEDULE II: DEDUCTIBLES APPLICABLE (Unless stated otherwise in schedule)</p>

        <p class="schedule-title bold bg-lightgrey" style="font-size: 10px; margin-top: -1px;">A: APPLICABLE TO MOTOR PRIVATE CAR AND MOTOR CYCLES</p>
        <table class="schedule-table schedule-iia-table">
            <tr>
                <th class="item-col">Item</th>
                <th class="cover-col">Scope of cover/Limit of Liability</th>
                <th class="motor-private-col">Motor Private</th>
                <th class="motor-cycle-col">Motor Cycle</th>
            </tr>
            <tr>
                <td class="item-col">10.1</td>
                <td class="cover-col">Own damage - Within Tanzania</td>
                <td class="motor-private-col center" style="font-size: 9px;">5% of claim min. 350,000 (double the excess in case of total theft claim)</td>
                <td class="motor-cycle-col center" style="font-size: 9px;">5% of claim min. 100,000 (double the excess in case of total theft claim)</td>
            </tr>
            <tr>
                <td class="item-col"></td>
                <td class="cover-col">Own damage - Outside Tanzania</td>
                <td class="motor-private-col center">Twice of Item 10.1 above</td>
                <td class="motor-cycle-col center">Twice of Item 10.1 above</td>
            </tr>
            <tr>
                <td class="item-col">10.2</td>
                <td class="cover-col">Young and Inexperienced Driver</td>
                <td class="motor-private-col center">5% of claim min. 500,000</td>
                <td class="motor-cycle-col center">5% of claim min. 250,000</td>
            </tr>
            <tr>
                <td class="item-col">10.3</td>
                <td class="cover-col">Third Party Property Damage</td>
                <td class="motor-private-col center">250,000</td>
                <td class="motor-cycle-col center">50,000</td>
            </tr>
        </table>

        <p class="schedule-title bold bg-lightgrey" style="font-size: 10px; margin-top: -1px;">B: APPLICABLE TO MOTOR COMMERCIAL VEHICLE</p>
        <table class="schedule-table schedule-b-table">
            <tr>
                <th rowspan="2" class="item-col">Item</th>
                <th rowspan="2" class="category-col">Scope of cover/Limit of Liability</th>
                <th class="cargo-col">General Goods Carrying</th>
                <th colspan="2" class="passenger-col">Passenger Carrying Vehicles</th>
                <th rowspan="2" class="special-col">Special Type Vehicle</th>
            </tr>
            <tr>
                <th class="cargo-col" style="font-weight: normal; font-size: 8px;">Trucks, Tractors, Pickups, canters etc</th>
                <th class="passenger-row-2" style="font-weight: normal; font-size: 8px;">Public tax, private hire, tour operators</th>
                <th class="passenger-row-2" style="font-weight: normal; font-size: 8px;">Buses (Daladala within city, upcountry, private & school)</th>
            </tr>
            <tr>
                <td class="item-col center">10.1</td>
                <td class="category-col bold data-align-left">Own Damage - Within Tanzania</td>
                <td class="cargo-col data-align-left">7.5% of claim min. 500,000 (30% of claim, minimum 750,000 in case of total theft claim) 5% of sum insured for Tankers</td>
                <td class="passenger-row-2 data-align-left">7.5% of claim min. 500,000 (10% of claim, minimum 750,000 in case of total theft claim</td>
                <td class="passenger-row-2 data-align-left">10% of claim min. 1,000,000 (10% of claim, minimum 750,000 in case of total theft claim</td>
                <td class="special-col data-align-left">10% of claim min. 1,000,000</td>
            </tr>
            <tr>
                <td class="item-col center"></td>
                <td class="category-col bold data-align-left">Own Damage - Outside Tanzania</td>
                <td class="cargo-col data-align-left">Twice of Item 10.1 above</td>
                <td class="passenger-row-2 data-align-left">Twice of Item 10.1 above</td>
                <td class="passenger-row-2 data-align-left">Twice of Item 10.1 above</td>
                <td class="special-col data-align-left">Twice of Item 10.1 above</td>
            </tr>
            <tr>
                <td class="item-col center">10.2</td>
                <td class="category-col bold data-align-left">Young and Inexperienced Driver</td>
                <td class="cargo-col data-align-left">10% of claim min. 750,000</td>
                <td class="passenger-row-2 data-align-left">10% of claim min. 750,000</td>
                <td class="passenger-row-2 data-align-left">10% of claim min. 1,000,000</td>
                <td class="special-col data-align-left">10% of claim min. 1,000,000</td>
            </tr>
            <tr>
                <td class="item-col center">10.3</td>
                <td class="category-col bold data-align-left">Third Party Property Damage</td>
                <td class="cargo-col data-align-left">500,000</td>
                <td class="passenger-row-2 data-align-left">500,000</td>
                <td class="passenger-row-2 data-align-left">500,000</td>
                <td class="special-col data-align-left">500,000</td>
            </tr>
        </table>

        <div class="footer-disclaimer">
            <p><span class="bold">Damage:</span> Item No 4 to 8 and 10.1 do not apply in case of Third-Party Cover</p>
            <p>Windscreen cover if stated in the schedule is subjected to deductibles /excess of Tshs. 50,000 for each and every loss</p>
            <p>TP cover is restricted to United Republic of Tanzania</p>
            <p><span class="bold">East Africa means,</span> Tanzania, Kenya, Uganda, Rwanda, South Sudan and Burundi</p>
        </div>

        <div class="powered-by-grid" style="font-size: 8px;">
            <div class="powered-by-cell" style="width: 33.33%; text-align: left;">
                Powered from Smart Policy Insurance System
            </div>
            <div class="powered-by-cell" style="width: 16.67%; text-align: center;">
                UIN #: KMJI14474
            </div>
            <div class="powered-by-cell" style="width: 16.67%; text-align: center;">
                Receipt No: 162730
            </div>
            <div class="powered-by-cell" style="width: 20%; text-align: right;">
                 JJ Issue three-28-Jul-2025 11:58:02 AM
            </div>
            <div class="powered-by-cell" style="width: 13.33%; text-align: right; border-left: 1px solid #000; padding-left: 5px;">
                Page 2 of 2
            </div>
        </div>
    </div>

</body>

</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>

    <style>
        @page {
            size: A4;
            margin: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
            font-size: 11px;
            background: #ffffff;
        }

        .invoice-container {
            width: 100%;
            padding: 15px;
            background: #fff;
        }

        /* HEADER */

        .header {
            width: 100%;
            border-bottom: 3px solid #078447;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-box {
            width: 65px;
            height: 65px;
            border: 2px solid #078447;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
            color: #078447;
            font-size: 25px;
            font-weight: bold;
        }

        .company-info {
            padding-left: 15px;
            vertical-align: middle;
        }

        .company-info h1 {
            font-size: 23px;
            color: #075b38;
            margin: 0 0 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-info p {
            font-size: 10px;
            color: #64748b;
            margin: 4px 0;
        }

        .invoice-title {
            text-align: right;
            vertical-align: middle;
        }

        .invoice-title h2 {
            font-size: 31px;
            color: #111827;
            margin: 0 0 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .invoice-title p {
            margin: 5px 0;
            font-size: 10px;
            color: #475569;
        }

        .invoice-title strong {
            color: #111827;
        }

        /* CLIENT INFORMATION */

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
            margin: 0 -12px 25px;
        }

        .info-card {
            width: 50%;
            vertical-align: top;
            border: 1px solid #d1e5d9;
            border-radius: 9px;
            background: #f7fbf8;
            padding: 0;
        }

        .info-heading {
            background: #078447;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            padding: 11px 14px;
            border-radius: 7px 7px 0 0;
        }

        .info-content {
            padding: 13px 15px;
            min-height: 105px;
        }

        .info-content p {
            margin: 5px 0;
            font-size: 10px;
            line-height: 1.6;
            color: #475569;
        }

        .info-content .client-name {
            font-size: 13px;
            color: #111827;
            font-weight: bold;
        }

        /* SECTION TITLE */

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #075b38;
            margin-bottom: 12px;
        }

        /* ITEMS TABLE */

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 22px;
        }

        .items-table thead th {
            background: #078447;
            color: #ffffff;
            padding: 12px 10px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }

        .items-table thead th:first-child {
            border-radius: 7px 0 0 0;
        }

        .items-table thead th:last-child {
            border-radius: 0 7px 0 0;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            color: #334155;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right !important;
        }

        /* TOTALS */

        .totals-wrapper {
            width: 100%;
            margin-bottom: 25px;
        }

        .totals-table {
            width: 55%;
            margin-left: auto;
            border-collapse: collapse;
            border: 1px solid #d1e5d9;
        }

        .totals-table td {
            padding: 10px 12px;
            font-size: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals-table td:first-child {
            font-weight: bold;
            color: #475569;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #1f2937;
        }

        .total-row td {
            background: #078447;
            color: #ffffff !important;
            font-size: 13px !important;
            padding: 13px 12px !important;
            border: none !important;
        }

        .paid-row td {
            background: #effaf3;
            color: #078447 !important;
            font-size: 11px !important;
        }

        .outstanding-row td {
            background: #fff0f0;
            color: #dc2626 !important;
            font-size: 11px !important;
            border-bottom: none !important;
        }

        /* NOTES */

        .notes-box {
            background: #f0f8f2;
            border-left: 4px solid #078447;
            padding: 15px 18px;
            margin-top: 30px;
            border-radius: 5px;
        }

        .notes-box strong {
            color: #075b38;
            font-size: 12px;
        }

        .notes-box p {
            color: #475569;
            font-size: 10px;
            margin: 8px 0 0;
            line-height: 1.6;
        }

        /* FOOTER */

        .footer {
            margin-top: 35px;
            border-top: 2px solid #078447;
            padding-top: 15px;
            text-align: center;
        }

        .footer .thank-you {
            font-size: 13px;
            color: #075b38;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .footer p {
            color: #64748b;
            font-size: 9px;
            margin: 5px 0;
        }

        .footer-line {
            margin-top: 12px;
            background: #078447;
            height: 4px;
            width: 100%;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .invoice-container {
                padding: 10px;
            }

            .items-table,
            .totals-table,
            .info-table,
            .notes-box {
                page-break-inside: avoid;
            }

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

<div class="invoice-container">

    <!-- HEADER -->

    <div class="header">

        <table class="header-table">
            <tr>

                <td style="width: 65px;">
                    <div class="logo-box">
                        ⚖
                    </div>
                </td>

                <td class="company-info">

                    <h1>LEGALPRO FIRM</h1>

                    <p>123 Legal Street, Kathmandu, Nepal</p>

                    <p>Phone: +977-1-4000000</p>

                    <p>Email: billing@legalpro.com</p>

                </td>

                <td class="invoice-title">

                    <h2>INVOICE</h2>

                    <p>
                        <strong>Invoice #:</strong>
                        {{ $invoice->invoice_number }}
                    </p>

                    <p>
                        <strong>Date:</strong>
                        {{ $invoice->invoice_date->format('d M Y') }}
                    </p>

                    <p>
                        <strong>Due Date:</strong>
                        {{ $invoice->due_date->format('d M Y') }}
                    </p>

                </td>

            </tr>
        </table>

    </div>


    <!-- CLIENT AND CASE INFORMATION -->

    <table class="info-table">
        <tr>

            <!-- BILL TO -->

            <td class="info-card">

                <div class="info-heading">
                    👤 &nbsp; Bill To:
                </div>

                <div class="info-content">

                    <p class="client-name">
                        {{ $invoice->client->name }}
                    </p>

                    <p>
                        {{ $invoice->client->address ?? 'N/A' }}
                    </p>

                    <p>
                        {{ $invoice->client->phone ?? '' }}
                    </p>

                    <p>
                        {{ $invoice->client->email ?? '' }}
                    </p>

                </div>

            </td>


            <!-- RELATED CASE -->

            @if($invoice->case)

            <td class="info-card">

                <div class="info-heading">
                    ⚖ &nbsp; Related Case:
                </div>

                <div class="info-content">

                    <p class="client-name">
                        {{ $invoice->case->case_number }}
                    </p>

                    <p>
                        {{ $invoice->case->title }}
                    </p>

                </div>

            </td>

            @endif

        </tr>
    </table>


    <!-- INVOICE ITEMS -->

    <table class="items-table">

        <thead>
            <tr>

                <th style="width: 50%;">
                    Description
                </th>

                <th style="width: 15%; text-align: right;">
                    Qty
                </th>

                <th style="width: 15%; text-align: right;">
                    Rate
                </th>

                <th style="width: 20%; text-align: right;">
                    Amount
                </th>

            </tr>
        </thead>

        <tbody>

            @foreach($invoice->items as $item)

            <tr>

                <td>
                    {{ $item->description }}
                </td>

                <td class="text-right">
                    {{ number_format($item->quantity, 2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->rate, 2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->amount, 2) }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>


    <!-- TOTALS -->

    <div class="totals-wrapper">

        <table class="totals-table">

            <tr>

                <td>Subtotal:</td>

                <td>
                    {{ number_format($invoice->subtotal, 2) }}
                </td>

            </tr>

            <tr>

                <td>
                    Tax ({{ $invoice->tax_rate }}%):
                </td>

                <td>
                    {{ number_format($invoice->tax_amount, 2) }}
                </td>

            </tr>

            @if($invoice->discount > 0)

            <tr>

                <td>Discount:</td>

                <td>
                    - {{ number_format($invoice->discount, 2) }}
                </td>

            </tr>

            @endif


            <tr class="total-row">

                <td>Total Amount:</td>

                <td>
                    NPR {{ number_format($invoice->total_amount, 2) }}
                </td>

            </tr>


            <tr class="paid-row">

                <td>Paid Amount:</td>

                <td>
                    NPR {{ number_format($invoice->paid_amount, 2) }}
                </td>

            </tr>


            <tr class="outstanding-row">

                <td>Outstanding:</td>

                <td>
                    NPR {{ number_format(
                        $invoice->total_amount - $invoice->paid_amount, 2
                    ) }}
                </td>

            </tr>

        </table>

    </div>


    <!-- NOTES -->

    @if($invoice->notes)

    <div class="notes-box">

        <strong>
            ✎ &nbsp; Notes / Terms:
        </strong>

        <p>
            {{ $invoice->notes }}
        </p>

    </div>

    @endif


    <!-- FOOTER -->

    <div class="footer">

        <p class="thank-you">
            Thank you for your business!
        </p>

        <p>
            This is a computer-generated invoice.
        </p>

        <p>
            LEGALPRO FIRM | Kathmandu, Nepal
        </p>

        <div class="footer-line"></div>

    </div>

</div>

</body>
</html>

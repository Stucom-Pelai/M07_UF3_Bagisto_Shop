<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">

<head>
    <!-- meta tags -->
    <meta http-equiv="Cache-control" content="no-cache">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    @php
        $fontPath = [];

        // Get the default locale code.
        $getLocale = app()->getLocale();

        // Get the current currency code.
        $currencyCode = core()->getBaseCurrencyCode();

        if ($getLocale == 'en' && $currencyCode == 'INR') {
            $fontFamily = [
                'regular' => 'DejaVu Sans',
                'bold' => 'DejaVu Sans',
            ];
        } else {
            $fontFamily = [
                'regular' => 'Arial, sans-serif',
                'bold' => 'Arial, sans-serif',
            ];
        }

        if (in_array($getLocale, ['ar', 'he', 'fa', 'tr', 'ru', 'uk'])) {
            $fontFamily = [
                'regular' => 'DejaVu Sans',
                'bold' => 'DejaVu Sans',
            ];
        } elseif ($getLocale == 'zh_CN') {
            $fontPath = [
                'regular' => asset('fonts/NotoSansSC-Regular.ttf'),
                'bold' => asset('fonts/NotoSansSC-Bold.ttf'),
            ];

            $fontFamily = [
                'regular' => 'Noto Sans SC',
                'bold' => 'Noto Sans SC Bold',
            ];
        } elseif ($getLocale == 'ja') {
            $fontPath = [
                'regular' => asset('fonts/NotoSansJP-Regular.ttf'),
                'bold' => asset('fonts/NotoSansJP-Bold.ttf'),
            ];

            $fontFamily = [
                'regular' => 'Noto Sans JP',
                'bold' => 'Noto Sans JP Bold',
            ];
        } elseif ($getLocale == 'hi_IN') {
            $fontPath = [
                'regular' => asset('fonts/Hind-Regular.ttf'),
                'bold' => asset('fonts/Hind-Bold.ttf'),
            ];

            $fontFamily = [
                'regular' => 'Hind',
                'bold' => 'Hind Bold',
            ];
        } elseif ($getLocale == 'bn') {
            $fontPath = [
                'regular' => asset('fonts/NotoSansBengali-Regular.ttf'),
                'bold' => asset('fonts/NotoSansBengali-Bold.ttf'),
            ];

            $fontFamily = [
                'regular' => 'Noto Sans Bengali',
                'bold' => 'Noto Sans Bengali Bold',
            ];
        } elseif ($getLocale == 'sin') {
            $fontPath = [
                'regular' => asset('fonts/NotoSansSinhala-Regular.ttf'),
                'bold' => asset('fonts/NotoSansSinhala-Bold.ttf'),
            ];

            $fontFamily = [
                'regular' => 'Noto Sans Sinhala',
                'bold' => 'Noto Sans Sinhala Bold',
            ];
        }
    @endphp

    <!-- lang supports inclusion -->
    <style type="text/css">
        @if (!empty($fontPath['regular']))
            @font-face {
                src: url({{ $fontPath['regular'] }}) format('truetype');
                font-family: {{ $fontFamily['regular'] }};
            }
        @endif

        @if (!empty($fontPath['bold']))
            @font-face {
                src: url({{ $fontPath['bold'] }}) format('truetype');
                font-family: {{ $fontFamily['bold'] }};
                font-style: bold;
            }
        @endif

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: {{ $fontFamily['regular'] }};
        }

        body {
            font-size: 10px;
            color: #091341;
            font-family: "{{ $fontFamily['regular'] }}";
        }

        b,
        th {
            font-family: "{{ $fontFamily['bold'] }}";
        }

        .page-content {
            padding: 12px;
        }

        .page-header {
            border-bottom: 1px solid #E9EFFC;
            text-align: center;
            font-size: 24px;
            text-transform: uppercase;
            color: #000DBB;
            padding: 24px 0;
            margin: 0;
        }

        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .logo-container.rtl {
            left: auto;
            right: 20px;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
        }

        .page-header b {
            display: inline-block;
            vertical-align: middle;
        }

        .small-text {
            font-size: 7px;
        }

        table {
            width: 100%;
            border-spacing: 1px 0;
            border-collapse: separate;
            margin-bottom: 16px;
        }

        table thead th {
            background-color: #E9EFFC;
            color: #000DBB;
            padding: 6px 18px;
            text-align: left;
        }

        table.rtl thead tr th {
            text-align: right;
        }

        table tbody td {
            padding: 9px 18px;
            border-bottom: 1px solid #E9EFFC;
            text-align: left;
            vertical-align: top;
        }

        table.rtl tbody tr td {
            text-align: right;
        }

        .summary {
            width: 100%;
            display: inline-block;
        }

        .summary table {
            float: right;
            width: 250px;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #E9EFFC;
            white-space: nowrap;
        }

        .summary table.rtl {
            width: 280px;
        }

        .summary table.rtl {
            margin-right: 480px;
        }

        .summary table td {
            padding: 5px 10px;
        }

        .summary table td:nth-child(2) {
            text-align: center;
        }

        .summary table td:nth-child(3) {
            text-align: right;
        }
    </style>
</head>

<body dir="{{ core()->getCurrentLocale()->direction }}">
    <div class="logo-container {{ core()->getCurrentLocale()->direction }}">
        @if (core()->getConfigData('sales.invoice_settings.pdf_print_outs.logo'))
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(Storage::url(core()->getConfigData('sales.invoice_settings.pdf_print_outs.logo')))) }}" />
        @else
            <img
                src="data:image/png;base64,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" />
        @endif
    </div>

    <div class="page">
        <!-- Header -->
        <div class="page-header">
            <b>@lang('admin::app.sales.orders.orders-pdf.orders')</b>
        </div>

        <div class="page-content">
            <!-- Items -->
            <div class="items">
                <table class="{{ core()->getCurrentLocale()->direction }}">
                    <thead>
                        <tr>
                            <th>
                                Order ID
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Channel
                            </th>

                            <th>
                                Customer Email
                            </th>

                            <th>
                                Total Items
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{ $order->id }}
                                </td>
                                <td>
                                    {{ $order->status }}
                                </td>
                                <td>
                                    {{ $order->channel_name }}
                                </td>
                                <td>
                                    {{ $order->customer_email }}
                                </td>
                                <td>
                                    {{ $order->total_item_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody> 
                </table>
            </div>
        </div>
    </div>
</body>

</html>

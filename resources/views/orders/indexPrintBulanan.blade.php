<!-- resources/views/reports/pdf/daily_report_pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Report PDF</title>
    <style>
        /* Tambahkan CSS sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Monthly Report</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Total Revenue</th>
                <th>Total Quantity</th>
                <th>Month</th>

            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            $total_quantity = 0;
            $total_revenue = 0;
        @endphp
        @if ($data != null && count($data) > 0)
            @foreach ($data as $order)
                @php
                    $total_quantity += $order->total_quantity;
                    $total_revenue += $order->total_revenue;
                @endphp
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$order->product_name}}</td>
                    <td>{{$order->total_quantity}}</td>
                    <td>{{number_format($order->total_revenue)}}</td>
                    <td>{{$order->order_month}}</td>
                </tr>
            @endforeach
            <!-- Total Row -->
            <tr>
                <th colspan="2">{{ __('Total') }}</th>
                <th>{{$total_quantity}}</th>
                <th>{{number_format($total_revenue)}}</th>
                <th></th>
            </tr>
        @else
            <tr>
                <td colspan="5" class="text-center">{{ __('Not Found') }}</td>
            </tr>
        @endif        </tbody>
    </table>
    
</body>
</html>

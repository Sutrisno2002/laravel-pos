<!-- resources/views/reports/pdf/daily_report_pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Report PDF</title>
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
    <h1 style="text-align: center;">Daily Report</h1>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Product Name</th>
                <th>Total Revenue</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_revenue_sum = 0;
                $total_quantity_sum = 0;
            @endphp
            @foreach ($data as $report)
                @php
                    $total_revenue_sum += $report->total_revenue;
                    $total_quantity_sum += $report->total_quantity;
                @endphp
                <tr>
                    <td>{{ $report->order_date }}</td>
                    <td>{{ $report->product_name }}</td>
                    <td>{{ number_format($report->total_revenue) }}</td>
                    <td>{{ $report->total_quantity }}</td>
                </tr>
            @endforeach
            <!-- Summary Row -->
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ number_format($total_revenue_sum) }}</strong></td>
                <td><strong>{{ $total_quantity_sum }}</strong></td>
            </tr>
        </tbody>
    </table>
    
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $data ->id }}</title>
    <style>
        /* CSS styling untuk invoice */
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-body {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-items th, .invoice-items td {
            border: 1px solid #ccc;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <div>
                <h2>Invoice #{{ $data->id }}</h2>
                <p>Date: {{ $data->created_at }}</p>
            </div>
            <div>
                <p>Customer: {{ $data->customer_name }}</p>
                <p>Email: {{ $data->customer_email }}</p>
            </div>
        </div>

        <div class="invoice-body">
            <h3>Invoice Details</h3>
            <table class="invoice-items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price) }}</td>
                            <td>${{ number_format($item->quantity * $item->unit_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="invoice-footer">
            <p><strong>Total Amount:</strong> ${{ $data->total_amount }}</p>
        </div>
    </div>
</body>
</html>

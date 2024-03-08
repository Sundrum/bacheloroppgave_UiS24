<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            margin: 5px 0;
        }

        h4 {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <h1>Payment receipt</h1>
    <p>Receipt ID: {{ $invoice_number }}</p>
    <p>Date: {{ $netsResponse->payment->created }}</p>
    <br>
    <h4>Supplier</h4>
    <p>7sense Agritech AS</p>
    <p>Moloveien 14, 3187 Horten, NOR</p>
    <p>Org. nr: 913 036 999</p>
    <br>
    <h4>Customer</h4>
    <p>{{ $netsResponse->payment->consumer->company->contactDetails->firstName }} {{ $netsResponse->payment->consumer->company->contactDetails->lastName }}</p>
    <p>{{ $netsResponse->payment->consumer->company->name }}</p>
    <p>{{ $netsResponse->payment->consumer->billingAddress->addressLine1 }}, {{ $netsResponse->payment->consumer->billingAddress->postalCode}} {{ $netsResponse->payment->consumer->billingAddress->city }} {{ $netsResponse->payment->consumer->billingAddress->country }}</p>
    <br>
    <h4>Order lines</h4>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit price</th>
            <th>VAT%</th>
            <th>Total Excl VAT</th>
            <th>Total VAT</th>
            <th>Total Incl VAT</th>
            <th>Currency</th>
        </tr>
        @if (!$is_subscription)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $amount }}</td>
                <td>{{ $price_ex_vat }}</td>
                <td>25%</td>
                <td>{{ $price_ex_vat}}</td>
                <td>{{ $vat }}</td>
                <td>{{ $product->product_price }}</td>
                <td>{{ $netsResponse->payment->orderDetails->currency }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $product->product_name }} subscription</td>
                <td>{{ $amount }}</td>
                <td>{{ $subscription_ex_vat }}</td>
                <td>25%</td>
                <td>{{ $subscription_ex_vat}}</td>
                <td>{{ $subscription_vat }}</td>
                <td>{{ $product->subscription_price }}</td>
                <td>{{ $netsResponse->payment->orderDetails->currency }}</td>
            </tr>
        @endif
    </table>
</body>
</html>

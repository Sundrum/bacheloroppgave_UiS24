<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Invoice</h1>
    <p>Invoice ID: {{ $invoice_number }}</p>
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
        <tr>
            <td>{{ $product->product_name }}</td>
            <td>{{ $amount }}</td>
            <td>{{ $product->product_price }}</td>
            <td>25%</td>
            <td>{{ $product->product_price / 1.25 }}</td>
            <td>{{ $product->product_price * 0.25 }}</td>
            <td>{{ $product->product_price }}</td>
            <td>{{ $netsResponse->payment->orderDetails->currency }}</td>
        </tr>
    </table>
</body>
</html>
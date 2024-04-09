<h3>Daily subscription payment status {{ $currentDate }} </h3>

<h4>Charged subscriptions</h4>
<p>All new subscription payments that happened overnight</p>
@if(count($charge[0]) == 0)
    <p>No subscriptions were charged today</p>
@else
<table>
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Product Name</th>
            <th>Serialnumber</th>
            <th>Subscription Price</th>
        </tr>
    </thead>
    <tbody>
    @php
    $totalSubscriptionPrice = 0;
    @endphp
    @foreach ($charge[0] as $subscription)
        <tr>
            <td>{{ $subscription->customer_id_ref }}</td>
            <td>{{ $subscription->product_name }}</td>
            <td>{{ $subscription->serialnumber }}</td>
            <td>{{ $subscription->subscription_price }}</td>
        </tr>
        @php
        $totalSubscriptionPrice += $subscription->subscription_price;
        @endphp
    @endforeach
    <tr>
        <td>Total sum:</td>
        <td></td>
        <td></td>
        <td>{{$totalSubscriptionPrice}}</td>
    </tr>
    </tbody>
</table>
@endif

<h4>Cancelled subscriptions</h4>
<p>All subscriptions that were cancelled today</p>
@if(count($cancelled[0]) == 0)
    <p>No subscriptions were cancelled today</p>
@else
<table>
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Product Name</th>
            <th>Serialnumber</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($cancelled[0] as $subscription)
        <tr>
            <td>{{ $subscription->customer_id_ref }}</td>
            <td>{{ $subscription->product_name }}</td>
            <td>{{ $subscription->serialnumber }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif

<h4>Outdated subscriptions</h4>
<p>Cancelled subscriptions that have been outdated</p>
@if(count($outdated[0]) == 0)
    <p>No subscriptions were outdated today</p>
@else
<table>
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Product Name</th>
            <th>Serialnumber</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($outdated[0] as $subscription)
        <tr>
            <td>{{ $subscription->customer_id_ref }}</td>
            <td>{{ $subscription->product_name }}</td>
            <td>{{ $subscription->serialnumber }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif

<h4>Errors</h4>
<p>Charge errors:</p>
@foreach ($charge[1] as $error)
    <p>{{ $error }}</p>
@endforeach
<br>
<p>Cancelled errors:</p>
@foreach ($cancelled[1] as $error)
    <p>{{ $error }}</p>
@endforeach
<br>
<p>Outdated errors:</p>
@foreach ($outdated[1] as $error)
    <p>{{ $error }}</p>
@endforeach
<br>
<p>Other errors:</p>
@foreach ($errors as $error)
    <p>{{ $error }}</p>
@endforeach

<style>
    body {
        color : #00265a;
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }

    /* Table header styles */
    th {
        background-color: #f2f2f2;
        font-weight: bold;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #ddd;
    }

    /* Table row styles */
    tr {
        border-bottom: 1px solid #ddd;
    }

    /* Table cell styles */
    td {
        padding: 10px;
    }
</style>
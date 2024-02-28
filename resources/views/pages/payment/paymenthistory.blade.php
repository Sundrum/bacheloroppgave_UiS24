@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Payment history for {{ $userData['customer_name'] ?? ''}}</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Company</th>
                        <th>Method</th>
                        <th>Satus</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentData as $payment)
                    <tr>
                        <form action="{{ route('invoice') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}">
                        @php
                            $dateTimeParts = explode(' ', $payment->created_at);
                            $date = $dateTimeParts[0];
                            $time = substr($dateTimeParts[1], 0, 5); // Extracting time HH:MM
                        @endphp
                        <td>{{ $date }}</td>
                        <td>{{ $time }}</td>
                        <td>{{ $payment->nets->orderDetails->reference ?? 'N/A' }}</td>
                        <td>{{ $payment->nets->orderDetails->amount/100 ?? 'N/A' }} {{ $payment->nets->orderDetails->currency }}</td>
                        <td>{{ $payment->nets->consumer->company->name ?? 'N/A' }}</td> 
                        <td>{{ $payment->nets->paymentDetails->paymentMethod ?? 'N/A'}}</td>
                        <td>{{ $payment->payment_status ?? 'N/A'}}</td>
                        <td><button type="submit" value="{{$payment->payment_id}}"><i id="button" class="fa fa-2x fa-download"></i></button></td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    console.log(@json($paymentData));
</script>
<style>
    .link-button {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    }

    .link-button:hover {
        color: lightskyblue; /* Change the color for the hover effect */
        text-decoration: underline;
    }

    #button {
        background: none;
        border: none;
    }
    thead {
        font-weight: 900;
    }

</style>
@endsection
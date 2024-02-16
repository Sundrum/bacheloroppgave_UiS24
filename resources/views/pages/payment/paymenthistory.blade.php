@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Payment history for {{ $userData['customer_name'] ?? ''}}</h4>
            @foreach ($paymentData as $row)
                {{-- Display content of each payment data --}}
                <div class="row">
                    <div class="col-12">
                        <p>Payment ID: {{ $row->payment->paymentId ??  'N/A' }}</p>
                        {{-- Add more fields as needed --}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
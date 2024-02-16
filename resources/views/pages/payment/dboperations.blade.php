@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Edit and delete database tables</h4>
        </div>
    </div>
    <h4>Payments table</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Created At</th>
                <th>Payment Status</th>
                <th>Customer ID</th>
                <th>Save</th>
                <th>Remove</th>
            </tr>
            @foreach($payments as $payment)
            <form method="POST" action="{{ route('dboperationsupdated') }}">
                @csrf
                <tr>
                    <td>
                        {{$payment->payment_id}}
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}" />
                    </td>
                    <td>{{$payment->created_at}}</td>
                    <td><input type="text" name="payment_status" value="{{$payment->payment_status}}"></td>
                    <td><input type="text" name="customer_id_ref" value="{{$payment->customer_id_ref}}"></td>
                    <td><button class="btn-7g" type="submit"><i class="fa fa-lg fa-check"></i></button></td>
                </form>
                <td>
                    <form action="{{ route('dboperationsdeleted') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{$payment->payment_id}}" />
                        <button type="submit" class="btn-7r">
                            <i class="fa fa-lg fa-trash"></i>
                        </button>
                    </form>
                </td>
                </tr>
            @endforeach
        </thead>
    </table>
</section>
@endsection

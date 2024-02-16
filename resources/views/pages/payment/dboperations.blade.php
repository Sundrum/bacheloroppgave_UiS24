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
                <th>Remove Entry</th>
                <th>Save</th>
            </tr>
            @foreach($payments as $payment)
            <form action="POST">
                <tr>
                    <td><input type="text" value="{{$payment->payment_id}}" size="40"></td>
                    <td><input type="text" value="{{$payment->created_at}}"></td>
                    <td><input type="text" value="{{$payment->payment_status}}"></td>
                    <td><input type="text" value="{{$payment->customer_id_ref}}"></td>
                    <td>
                        <a class="fas fa-2x fa-trash" onclick="loadContent('{{route('dboperationsdeleted')}}')" href="{{route('dboperationsdeleted')}}""></a>
                    </td>
                    <td><button class="btn btn-primary" type="submit">Save</button></td>
                </tr>
            </form>
            @endforeach
        </thead>
    </table>
</section>
@endsection

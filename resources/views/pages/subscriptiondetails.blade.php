@extends('layouts.app')
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Details for {{$sensorUnit->product_name}}</h4>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Serial Number</th>
                <th>Description</th>
                <th>Installation Date</th>
                <th>Status</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$sensorUnit->product_name}}</td>
                <td>{{$sensorUnit->serialnumber}}</td>
                <td>{{$sensorUnit->product_description}}</td>
                <td>{{$sensorUnit->sensorunit_installdate}}</td>
                @if ($sensorUnit->paid_subscription)
                <td>Paid</td>
                @else
                <td>Not Paid</td>
                @endif
                <td>N/A</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection
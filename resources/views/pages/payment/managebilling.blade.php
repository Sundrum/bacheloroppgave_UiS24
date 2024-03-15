@extends('layouts.app')
@section('content')

<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row mt-5 text-center">
        <div class="col-md-12">
            <h4>Manage payment method</h4>
            <p>Manage how you pay for your membership</p>
        </div>
        <div class="row">
            @if($cardtype == 'visa')
            <div class="col-md-4">
                <img src="" alt="">
            </div>
            @elseif($cardtype == 'mastercard')
            <div class="col-md-4">
                <img src="" alt="">
            </div>
            @elseif($cardtype == 'amex')
            <div class="col-md-4">
                <img src="" alt="">
            </div>
            @endif
            <div class="col-md-4">
                {{$cardtype}} {{$cardnumber}}
            </div>
            <div class="col-md-4">
                <a href="{{route('editbilling')}}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</section>
@endsection
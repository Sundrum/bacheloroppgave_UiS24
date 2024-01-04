@extends('layouts.app')

@section('content')

<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h1>Subscription status: {{request()->responseCode}}</h1>
        </div>
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <span>Transaction ID: {{$response['sale'] ?? ''}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <h4></h4>
        </div>
    </div>
    <div class="row  text-center mt-2">
        <div class="col-12">
            <p></p>
        </div>
    </div>

@endsection
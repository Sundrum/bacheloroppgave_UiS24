@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
    <h4>Payment completed!</h4>
    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}" class="btn btn-primary">
    Back to cart
    </a>
</section>
@endsection
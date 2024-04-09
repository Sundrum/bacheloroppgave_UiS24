@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <h4> Checkout success </h4> <!-- Move the heading here -->
    </div>
    <form action="{{ route('invoice') }}" method="POST">
        @csrf
        <input type="hidden" name="payment_id" value="{{$payment_id}}">
        <button type="submit" class="btn-7g"> Download PDF</button>
    </form>
    <button onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}" class="btn-7g">
        View subscriptions
    </button>
</section>

<style>
    /* Center the content */
    .bg-white.card-rounded {
        margin: auto;
        padding: 20px;
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        flex-direction: column; /* Stack items vertically */
    }

    /* Style the heading */
    .bg-white.card-rounded h4 {
        font-size: 24px;
        color: #00265a;
        margin-bottom: 20px;
    }
</style>
@endsection

@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
    </div>
    <div class="col-12">
        <h4> @lang('dashboard.paymentcompleted') </h4>
    </div>
    <h4>Bilag</h4>
    <form action="{{ route('invoice') }}" method="POST">
        @csrf
    <input type="hidden" name="payment_id" value="{{$payment_id}}">
    <button type="submit"> Last ned bilag PDF </button>
    </form>
    <br>
    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}" class="btn btn-primary">
    Tilbake til abonnementer
    </a>


</section>
@endsection
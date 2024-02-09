@extends('layouts.app')

@section('content')
<div class="rcorners2" style="background: #ffcccb">
    <div class="row mt-5 mb-5">
        <div class="col">
            <a onclick="loadContent('{{route('payment')}}')" href="{{route('payment')}}">
            {{-- @lang('dashboard.subscription_failed') --}}
            </a>
        </div>
    </div>
</div>
@endsection


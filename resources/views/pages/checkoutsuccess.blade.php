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
    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}" class="btn btn-primary">
    Back to subscriptions page
    </a>
</section>
<script>
    let text = @json(__('dashboard.paymentcompleted'));
    console.log(text)
</script>
@endsection
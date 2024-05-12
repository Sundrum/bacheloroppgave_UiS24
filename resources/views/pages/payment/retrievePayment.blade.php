@extends('layouts.app')

@section('content')
<h1>Retrive Payment</h1>

<script>
    let paymentInfo = @json($response);
    console.log(JSON.parse(paymentInfo));
</script>
@endsection
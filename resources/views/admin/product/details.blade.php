@extends('layouts.admin')

@section('content')
<section class="container-fluid">
    <div class="row mt-4 mb-3 justify-content-center">       
        <div class="col text-center">
            <h2>{{$product->product_name ?? 'New Product'}}</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('admin.product.product')
        </div>
        @if(isset($product))
            <div class="col-md-6">
                @include('admin.product.sensorprobe')
            </div>
        @endif
    </div>

</section>

@endsection
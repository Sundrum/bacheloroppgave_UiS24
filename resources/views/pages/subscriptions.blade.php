@extends('layouts.app')

@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Manage your subscriptions</h4>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subscription Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Irrigation Sensor</td>
                                <td>Paid/Active</td>
                                <td>
                                    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}">
                                        <i class="fa fa-2x fa-bars"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Portal Access Subscription</td>
                                <td>Not Paid/Inactive</td>
                                <td>
                                    <a onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}">
                                        <i class="fa fa-2x fa-bars"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                <h4>Payment</h4>
                <button id="checkout-button" class="btn btn-primary">Proceed to Checkout</button>

                {{-- Blade Routes --}}
                <script>
                    var checkoutRoute = "{{ route('checkout') }}";
                </script>                
                <script type="text/javascript" src="{{asset('js/subscription.js')}}"></script>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
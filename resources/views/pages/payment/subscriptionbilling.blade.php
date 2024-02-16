@extends('layouts.app')
@section('content')

<div id="header">
    <section class="bg-white card-rounded">
        <div class="row text-center mt-5">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                            <td onclick="loadContent('{{route('subscriptions')}}')" href="{{route('subscriptions')}}">
                                @lang('navbar.subscriptions')
                            </td>
                            <td onclick="loadContent('{{route('paymenthistory')}}')" href="{{route('paymenthistory')}}">
                                Payment History
                            </td>
                            <td>
                                Manage Billing Details
                            </td>
                      </tr>
                    </thead>
                  </table>
            </div>
        </div>
    </section>
</div>

<div id="body">
    <h6 style="color: gray;">Payment Info<h6>
    <section class="bg-white card-rounded">
        <div class="row mt-3 text-center" id="title">
            <h4>Next Payment</h4>
            <p>{{$Sub}}</p>
        </div>
    </section>
</div>
@endsection
<style>
    tr {
        text-align: center;
    }
    td:hover {
        cursor: pointer;
        color: lightskyblue;
    }
    #body {
        padding-top: 20px;
        margin-top: 20px;
    }
    #title {
        padding-top: 20px;
    }
</style>
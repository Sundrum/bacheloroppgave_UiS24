@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request()->message)
            <div class="alert alert-success">{{ request()->message }}</div>
        @endif
        @if (request()->errormessage)
            <div class="alert alert-danger">{{ request()->errormessage }}</div>
        @endif
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">
                <div class="card card-rounded">
                    <div class="m-2">
                        <div class="row">
                            <div class="col-4 font-weight-bold">
                                Name
                            </div>
                            <div class="col-8">
                                {{$customer->customer_name ?? ''}}
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="row">
                            <div class="col-4 font-weight-bold my-auto">
                                Maincontact
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_maincontact ?? ''}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_phone ?? ''}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_email ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="row">
                            <div class="col-4 font-weight-bold my-auto">
                                Address
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_visitaddr1 ?? ''}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_visitaddr2 ?? ''}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        {{$customer->customer_visitpostcode ?? ''}} {{$customer->customer_visitcity ?? ''}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        @foreach ($countries as $row)
                                            @if($row['country_id'] == $customer->customer_visitcountry)
                                                {{$row['name']}}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="row">
                            <div class="col-4 font-weight-bold my-auto">
                                Alerts
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col">
                                        Email alert
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        SMS alert
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Irrigation Email alert
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Irrigation SMS alert
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="row">
                                    <div class="col">
                                        :{{$customer->customer_variables_email ?? '-'}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        @if($customer->customer_variables_sms_enable == 0)
                                            :Not paid for service
                                        @else
                                            :{{$customer->customer_variables_sms ?? ''}}
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        :{{$customer->customer_variables_irrigation_email ?? '-'}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        @if($customer->customer_variables_irrigation_sms_enable == 0)
                                            :Turned off
                                        @else
                                            :{{$customer->customer_variables_irrigation_sms ?? '-'}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="">
                        <div class="row">
                            <div class="col-4 font-weight-bold">
                                Subscription paid
                            </div>
                            <div class="col-8">
                                <label class="switch">
                                    <input type="checkbox" id="paid_subscription" name="paid_subscription" onclick="toggleSubscription()" @if(isset($customer->paid_subscription)) @if($customer->paid_subscription) checked @endif @else checked @endif>
                                    <span class="slider round"></span>
                                    </label>
                            </div>
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->customer_id}}">
                        </div>
                        <hr class="mb-2">
                        <div class="row mt-1">
                            <div class="col-6">
                                <button class="btn-7s col-12" data-toggle="modal" data-target="#sendOrderConfirmation">Send Order Confirmation</button>
                            </div>
                            <div class="col-6">
                                <a class="" href="/admin/customer/edit/{{$customer->customer_id ?? ''}}"><button class="btn-7g col-12">Edit</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>
<div class="row justify-content-center mt-2">
    <div class="col-md-6">
        <h3>Sensorunits</h3>
        <div class="card card-rounded">
            <div class="m-2">
                <div class="row mb-1">
                    <div class="col text-center">
                        <button class="btn-7g" data-toggle="modal" data-target="#updateUnit">Add sensorunit</button>
                    </div>
                </div>
                <table id="sensorunit" class="display" width="100%">
                    <thead>
                        <tr>
                            <td>Serialnumber</td>
                            <td>Name</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($customer->sensorunits) && count($customer->sensorunits) > 0)
                            @foreach ($customer->sensorunits as $unit)
                                <tr>
                                    <td>{{$unit->serialnumber ?? ''}}</td>
                                    <td>{{$unit->sensorunit_location ?? ''}}</td>
                                    <td class="text-center"><a href="/admin/sensorunit/{{$unit->sensorunit_id ?? ''}}"><button class="btn-7s">Open</button></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No sensorunits connected to this customer</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Users</h3>
        <div class="card card-rounded">
            <div class="m-2">
                <div class="row mb-1">
                    <div class="col text-center">
                        <a href="/admin/account?customer_id={{$customer->customer_id}}"><button class="btn-7g">Add new user</button></a>
                    </div>
                </div>
                <table id="usertable" class="display" width="100%">
                    <thead>
                        <tr>
                            {{-- <td>#</td> --}}
                            <td>Name</td>
                            <td>Email</td>
                            <td>Roletype</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($customer->users) && count($customer->users) > 0)
                            @foreach ($customer->users as $user)
                                <tr>
                                    {{-- <td>{{$user->user_id ?? ''}}</td> --}}
                                    <td>{{$user->user_name ?? ''}}</td>
                                    <td>{{$user->user_email ?? ''}}</td>
                                    <td>{{$user->roletype ?? ''}}
                                    <td class="text-center"><a href="/admin/account/{{$user->user_id ?? ''}}"><button class="btn-7s">Open</button></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No users connected to this customer</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.customer.modalOrderMail')
@include('admin.customer.addunits')
<script>
setTitle(@json( __('admin.customer')));

$(document).ready(function () {
    var table = $('#sensorunit').DataTable({
        pageLength: 10, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 4,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
    });

    var table2 = $('#usertable').DataTable({
        pageLength: 10, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 4,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
    });
});

function toggleSubscription() {
    var value = document.getElementById("paid_subscription").checked;
    var id = document.getElementById("customer_id").value;
    console.log(id);
    $.ajax({
        url: "/admin/subscription",
        type: 'POST',
        data: { 
            "value": value,
            "id": id,
            "_token": token,
        },
        success: function(msg) {
            console.log(msg);
        },   
        error:function(msg) {
            console.log('Something went wrong.');
        }
    });
}
</script>
@endsection
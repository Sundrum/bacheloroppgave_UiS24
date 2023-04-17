@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/css/slider.css') }}">

@section('content')
<h1><i class="fa fa-user" aria-hidden="true"></i> {{$user['user_name']}}</h1>

<div class="row bg-dark text-white text-center">
        <div class="col"><strong>Serialnumber</strong></div>
        <div class="col-2"><strong>Changes</strong></div>
        <div class="col-2"><strong>Action<strong></div>
</div>
@php $i=0; @endphp
<div id="myTable">
    @csrf
    @foreach($accesslist as $access)
        <div class="object">
            <div class="row border-bottom align-items-center text-center" id="0{{trim($access['serialnumber'])}}">
                <div class="col" scope="row" data-toggle="collapse" data-target="#collapse{{trim($access['serialnumber'])}}" aria-expanded="false" aria-controls="collapseExample">
                    <input type="text" value="{{$access['serialnumber']}}" disabled>
                </div>
                <div class="col-2">
                    <label class="switch">
                        <input type="checkbox" @if(isset($access['changeallowed']) && $access['changeallowed'] == '1') checked @endif class="btn btn-primary" id="[{{$i}}][access]" name="[{{$i}}][access]">
                            <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-2">
                    <i class="fa fa-times" onclick="deleteRow(0,'{{$user['user_id']}}','{{trim($access['serialnumber'])}}','{{trim($user['user_email'])}}')" style="color:red" aria-hidden="true"></i>
                </div>
            </div>
            <div class="collapse collapse-local text-left" id="collapse{{trim($access['serialnumber'])}}">
                <div class="card card-body">
                    <div class="row bg-dark text-white">
                        <div class="col text-center">
                            <strong>User</strong>
                        </div>
                        <div class="col text-center">
                            <strong>Changes</strong>
                        </div>
                        <div class="col text-center">
                            <strong>Action</strong>
                        </div>
                    </div>
                    @php $n=1; @endphp
                    @foreach($access['others'] as $users)
                        @if($users['user_id'] !== $user['user_id'])
                            <div class="row border-bottom align-items-center hover" id="{{$n}}{{trim($users['serialnumber'])}}">
                                <div class="col text-center">
                                    <strong>{{$users['user_email']}}</strong>
                                </div>
                                <div class="col text-center">
                                    <label class="switch">
                                        <input type="checkbox" @if(isset($users['changeallowed']) && $users['changeallowed'] == '1') checked @endif class="btn btn-primary" id="[others][access][{{$users['serialnumber']}}]">
                                            <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col text-center">
                                    <i class="fa fa-times" onclick="deleteRow('{{$n}}','{{$user['user_id']}}','{{trim($users['serialnumber'])}}','{{trim($users['user_email'])}}')" style="color:red" aria-hidden="true"></i>
                                </div>
                            </div>
                        @endif
                        @php $n++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
        @php $i++; @endphp
    @endforeach
</div>
<br>
<div class="col">
    <div class="text-center">
        <button class="btn" onclick="addField();"><i class="fa fa-plus" style="color:green" aria-hidden="true"></i></button>
    </div>
    <div class="text-right">        
        <button class="btn btn-primary" onclick="addNewSensor();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
    <div>
</div>

<script>
    var counter = $('#myTable').find('.object').length;
    var code2 = '<option value="">Select a Serialnumber</option>';

    //getData();
    function addField() {
        counter++;
        console.log(counter);
        var code = '<div class="row border-bottom align-items-center hover" id="'+counter+'" style="background: #d2f8d2";>';
            code += '<div class="col text-center"><select class="seriallist" id="['+counter+'][serialnumber][new]" name="['+counter+'][serialnumber][new]">';
            code += code2;
            code += '</select></div>';
            code += '<div class="col-2 text-center"><label class="switch"><input type="checkbox" checked id="['+counter+'][access] name="['+counter+'][access] class="btn btn-primary" id="probe" name="probe"><span class="slider round"></span></label></div>';
            code += '<div class="col-2 text-center"><i onclick="removeRow('+counter+')" class="fa fa-minus" style="color:red" aria-hidden="true"></i></div></div>';
        $('#myTable .object:last').after(code);
        makeSearchable();
    }

    function makeSearchable(){
        $('.seriallist').select2();
    }

    function getData(){
        $.ajax({
            url: '/admin/getSensorunits',
            dataType: 'json',      
            success: function(data) {
                for (var i in data) {
                    code2 += '<option value="'+data[i].serialnumber.trim()+'">'+data[i].serialnumber.trim()+'</option>';
                }
            }
        });
    }

    function deleteRow(id, userid,serialnumber,email) {
        var token = "{{ csrf_token() }}";
        var confirmed = confirm('Do you want remove this unit?');

        if(confirmed) {
            $.ajax({
                url: "/admin/connect/user/delete",
                type: 'POST',
                data: { 
                    "userid": userid,
                    "serialnumber": serialnumber,
                    "email": email,
                    "_token": token,
                },
                success: function(msg) {
                    console.log(msg);
                    $('#'+id+serialnumber+'').remove();
                },   
                error: function(msg) {
                    alert("Failed - Please try again")
                }
            });
        }
    }

    function removeRow(index) {
        $('#'+index+'').remove();
    }
    
</script>

@endsection
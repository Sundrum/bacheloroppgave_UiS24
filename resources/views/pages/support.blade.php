@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/dot.css') }}">

@section('content')
  <h2>@lang('support.header')</h2><br>
    @foreach(Session::get('productnumbers') as $product)
      <div class="card bg-white mb-3">
        <div class="card-header">
          <h5 class="panel-title"><strong>{{trim($product['product_name'])}}</strong></h5> 
        </div>
        <div class="card-body">
            <div class="row">
              @if(isset($product['product_image_url']))
              <div class="col-md-2">
                <img class="img-fluid img-thumbnail" src="{{ trim($product['product_image_url']) }}" width="200" height="200" alt="">
              </div>
              @endif

              <div class="col-md-10"><br>
                <table class="tableTechnical" style="padding-left: 20px;">
                  <tbody>
                    <tr>
                      <td  class="tecnicalinfo tmp">@lang('support.proddesc')</td>
                      <td> <span class="tecnicalinfotmp"><b>{{ trim($product['product_description']) }}</b></span></td>
                    </tr>
                    <tr>
                      <td class="tecnicalinfo tmp">@lang('support.prodnumb')</td>
                      <td> <span class="tecnicalinfotmp"><b>{{ trim($product['productnumber']) }}</b></span></td>
                    </tr>

                    <tr>
                      <td colspan="2" class="tecnicalinfo tmp">
                        <a class="card-link" href="{{ $product['document_url'] }}" target="_blank"><img src="{{asset('img/pdf-64.png')}}" alt=""> {{ $product['document_name'] }}</a>
                        <a class="card-link" data-toggle="modal" data-target="#basicModal" href="#" target="_blank"><img src="{{asset('img/support.png')}}" alt="">@lang('support.support')</a>  
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div><br>
            @foreach ($units as $unit)
              @if(trim($unit['productnumber']) == trim($product['productnumber']))
                <p><a href='/unit/{{$unit['serialnumber']}}'> 
                  <span class="dot align-middle" style="background-color: {{$unit['color']}};"></span> {{ trim($unit['serialnumber']) }} ->  {{ trim($unit['sensorunit_location']) ?? 'No name given' }}: {{$unit['date_time'] ?? ''}} 
                </a></p>
              @endif
            @endforeach
        </div>
      </div>

      {{-- Modal for viewing support --}}
      <div id ="basicModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">@lang('support.support')</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table>
                <tr>
                  <td><img src="{{asset('img/support_email.png')}}"></td>
                  <td style="padding-top: 5px;">
                    <a href="mailto:{{trim($product['helpdesk_email'])}}?Subject=7Sense Support Request"> @lang('support.emailinfo')</a>
                  </td>
                </tr>  
                <tr>
                  <td><img src="{{asset('img/support_phone.png')}}"></td>
                  <td style="padding-top: 5px;">@lang('support.phoneinfo'){{ $product['helpdesk_phone'] }}</td>
                </tr>  
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('support.close')</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach          


    {{-- @if (Auth::user()->roletype_id_ref > 80)
        <div class= "card bg-white mb-3">
            <p class="card-header">Admin Analyse</p>
            <p class ="text-center">Firstname: {{ Auth::user()->user_name }} </p>
            <p class ="text-center">Lastname: {{ Auth::user()->user_surname }} </p>
            <p class ="text-center">Customernumber: {{ Auth::user()->customernumber }} </p>
            <p class ="text-center">Phonenumber: {{ Auth::user()->user_phone_work }} </p>
            <p class ="text-center">Email: {{ Auth::user()->user_email }} </p>
            <p class ="text-center">Alternative Email: {{ Auth::user()->user_alternative_email }} </p>
            <p class ="text-center">ID: {{ Auth::user()->user_id }} </p>
            <p class ="text-center">Roletype: {{ Auth::user()->roletype_id_ref }} </p>
            <p class ="text-center">Customer ID: {{ Auth::user()->customer_id_ref }} </p>
        </div>
    @endif --}}

@endsection
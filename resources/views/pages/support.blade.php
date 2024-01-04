@extends('layouts.app')


@section('content')
    @foreach(Session::get('productnumbers') as $product)


      <div class="row">
        <div class="col-sx-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3">
          <div class="card-rounded bg-white p-3 my-5">
            <div class="row justify-content-center mb-2">
              <div class="icon-card icon-color myaccount">
                <i class="fa fa-microchip fa-3x icon-color"></i>
              </div>
            </div>
            <div class="row text-center mt-3">
              <h1>{{trim($product['product_name']) ?? ''}}</h1>
            </div>
            @if(isset($product['product_image_url']))
            <div class="row">
              <div class="col-md-4 offset-4">
                <img class="img-fluid" src="{{ trim($product['product_image_url']) }}" width="200" height="200" alt="">
              </div>
            </div>
            @endif
            <div class="row">
              <div class="col-12">
                <span>@lang('support.prodnumb')</span>
                <span>{{ trim($product['productnumber']) ?? ''}}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <span>@lang('support.proddesc')</span>
                <span>{{ trim($product['product_description']) ?? ''}}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <hr class="mt-2 mb-1 mx-5">
              </div>
              @foreach ($units as $unit)
                @if(trim($unit['productnumber']) == trim($product['productnumber']))
                <div class="row px-4 my-1">
                  <span class="text-white @if($unit['status']) bg-7g @else bg-7r @endif" @if($unit['product_name'] == 'Gateway' && Auth::user()->user_roletype_id < 90) onClick="getGateway('{{$unit['serialnumber']}}')" @endif style="cursor: pointer;font-size: 14px; border-radius: 100%; width: 30px; height:30px; padding: 0; display:inline-flex; align-items:center; justify-content:center;">@if(Auth::user()->roletype_id < 80)<i class="fa fa-ellipsis"></i>@endif</span>
                  <div class="col-10 col-md-11" id="{{$unit['serialnumber']}}">
                    <div class="col-12 col-md-12" style="border-radius: 5px 25px 25px 25px; padding-top: 4px; ">
                      <a href='/unit/{{$unit['serialnumber']}}' class="text-b"> 
                        {{ trim($unit['serialnumber']) }} ->  {{ trim($unit['sensorunit_location']) ?? 'No name given' }}: {{$unit['date_time'] ?? ''}}
                      </a>                      
                    </div>
                  </div>
                </div>
                @endif
              @endforeach
            </div>
            <div class="row">
              <div class="col-12">
                <hr class="mt-2 mx-5">
                {{-- <a class="card-link" href="{{ $product['document_url'] }}" target="_blank"><img src="{{asset('img/pdf-64.png')}}" alt=""> {{ $product['document_name'] }}</a> --}}
                <div class="col-12 py-1">
                  <span class="text-white bg-7s" style="cursor: pointer;font-size: 14px; border-radius: 100%; width: 40px; height:40px; padding: 0; display:inline-flex; align-items:center; justify-content:center;">
                    <i class="fa fa-envelope fa-lg icon-color"></i>
                  </span>
                  <span>
                    <a class="text-b" href="mailto:{{trim($product['helpdesk_email'])}}?Subject=7Sense Support Request"> @lang('support.emailinfo')</a>
                  </span>
                </div>
                <div class="col-12 py-1">
                  <span class="text-white bg-7s" style="cursor: pointer;font-size: 14px; border-radius: 100%; width: 40px; height:40px; padding: 0; display:inline-flex; align-items:center; justify-content:center;">
                    <i class="fa fa-phone fa-lg icon-color"></i>
                  </span>
                  @lang('support.phoneinfo'){{ $product['helpdesk_phone']}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach          
<script>
  setTitle(@json(__('support.header')));
  
  function getGateway(serial) {
    console.log(serial);
    addLoadingSpinner();
    $.ajax({
      url: "/admin/gateway?gateway="+serial,
      type: 'GET',
      data: {
          "_token": token,
      },
      success: function (data) {
        if(data.length < 1) {
          errorMessage(@json(__('general.somethingwentwrong')))
        }
        let objects = Array();
        let text = document.createElement('div');
        let lines = data.split('\n')
        console.log(lines);        
        for (let i=0;i<lines.length;i++) {
          let line = lines[i];
          if(line.length > 10) {
            objects[i] = processLines(line);
          }
        }
        objects.map((el, i) => {
          text.innerHTML += `
                  <div class="row mt-2 py-2 bg-7g card-rounded" style="pointer: cursor;" onClick="getGatewayEvent('${el[8]}')">
                    <div class="col-5">${serial}</div>
                    <div class="col-5">${el[5]} ${el[6]} ${el[7]}</div>
                    <div class="col-2">${el[9]}</div>
                    <div id="${el[8]}"></div>
                  </div>
                `;
        })
        let gateway = document.getElementById(serial);
        insertAfter(gateway, text);
        removeLoadingSpinner();

      },
      error: function (data) {
        console.log('Error');
        errorMessage(@json(__('general.somethingwentwrong')))
      },
    });
  }

  function processLines(line) {
    let parts = line.split(" ");
    let m = 0;
    let tempArray = Array();
    for (let n = 0; n < parts.length; n++) {
      if(parts[n]) {
        tempArray[m] = parts[n];
        if(m == 8) tempArray[9] = parts[n].slice(-2);
        m++;
      }
    }
    return tempArray;
  }

  function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.appendChild(newNode, referenceNode.nextSibling);
  }

  function getGatewayEvent(event) {
    $.ajax({
      url: "/admin/loggateway",
      type: 'POST',
      data: {
          "path": event,
          "_token": token,
      },
      success: function (data) {
        console.log(data);
        let text = document.createElement('div');
        text.innerHTML += "<hr>";
        let lines = data.split('\n')
        for (let i = 0; i < lines.length; i++) {
          let line = lines[i];
          text.innerHTML += `<div class="row">
                              <div class="col-12">
                                ${line}
                              </div>
                            </div>`;
        }
        let eventPath = document.getElementById(event);
        insertAfter(eventPath, text);
      },
      error: function (data) {
        console.log('Error');
      },
    });
  }
</script>

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
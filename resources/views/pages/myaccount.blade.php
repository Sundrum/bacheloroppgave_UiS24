@extends('layouts.app')

@section('content')

<style>

    .iti-flag {
        background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/img/flags.png");
    }
    
    @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
        .iti-flag {
            background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/img/flags@2x.png");
        }
    }

</style>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css">
    

    <div class="row">
        <div class="col-sx-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3">
            <div class="card-rounded bg-white p-3 my-5">
                <div class="row justify-content-center mb-2">
                    <div class="icon-card icon-color myaccount">
                        <i class="far fa-address-card fa-3x icon-color"></i>
                    </div>
                </div>
                <div class="row text-center mt-5">
                    <h1>@lang('myaccount.myaccount')</h1>
                </div>
                <div class="row text-center">
                    <div class="col-12">
                        {{-- <p>@lang('myaccount.myaccount_info')</p> --}}
                    </div>
                </div>
                <div class="card-body justify-content-center">
                    <form method="POST" action="{{ route('changeaccount') }}">
                        @csrf
                        <div class="form-group">
                            <span id="email_helptext" class="mx-5">@lang('myaccount.username')</span>
                            <div class="input-group pb-2">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                        <i class="fa fa-user fa-at icon-color"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" placeholder="@lang('myaccount.username')" value="{{trim(Auth::user()->user_email)}}" required>
                            </div>
                            {{-- <div class="card-rounded bg-7r mx-4 mt-0 mb-2 px-4 py-2">
                                <span class="">Not validated. You need to verify your email adress</span>
                            </div> --}}
                        </div>
    
                        <div class="form-group pb-2">
                            <span id="name_helptext" class="mx-5">@lang('myaccount.name')</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                        <i class="fa fa-user fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="name" name="name" placeholder="@lang('myaccount.name')" value="{{trim(Auth::user()->user_name)}}" maxlength="50" required>
                            </div>
                        </div>
    
                        <div class="form-group ">
                            <span id="phone_helptext" class="mx-5">@lang('myaccount.phonenumber')
                            </span>
                            <div class="input-group pb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-7s h-100">
                                        <i class="fa fa-phone fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <input type="tel" placeholder="@lang('myaccount.phonenumber')" value="{{trim(Auth::user()->user_phone_work)}}" id="phone_work" class="form-control" required>
                            </div>
                        </div>
    
                        <div class="form-group pb-4">
                            <span id="language_helptext" class="mx-5" >@lang('myaccount.language')
                            </span>
                            <div class="input-group"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-7s h-100">
                                        <i class="fa fa-globe fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <select class="custom-select form-control" id="language" name="language">
                                    
                                    <option value="2" @if(Auth::user()->user_language == 2) selected="selected" @endif> English </option>
                                    <option value="1" @if(Auth::user()->user_language == 1) selected="selected" @endif> Norwegian </option>
                                    <option value="3" @if(Auth::user()->user_language == 3) selected="selected" @endif> French </option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col text-center">
                                <button type="submit" class="btn-7s" onclick="updatephoneprefix();">
                                    @lang('myaccount.savesettings')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>
    let telworkInput = $("#phone_work");
    let telhomeinput = $("#phone_home");
    let prefixhome = 47;
    let prefixwork = 47;
    setTitle(@json(__('myaccount.myaccount')));
     // initialize
    intlTelInput(telworkInput.get(0), {
        hiddenInput: 'phone_work',
        // separateDialCode: true,
        initialCountry: 'no',
        preferredCountries: ['no','se','dk','fr','us','gb'],
        autoPlaceholder: 'aggressive',
        formatOnDisplay: true,
        autoHideDialCode: true,
        nationalMode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.min.js",
        geoIpLookup: function(callback) {
            fetch('https://ipinfo.io/json', {
              cache: 'reload'
            }).then(response => {
                if ( response.ok ) {
                    return response.json()
                }
                throw new Error('Failed: ' + response.status)
            }).then(ipjson => {
                callback(ipjson.country)

            }).catch(e => {
                callback('no')
            })
        }
    })

    // initialize
    intlTelInput(telhomeinput.get(0), {
        hiddenInput: 'phone_home',
        separateDialCode: true,
        initialCountry: 'no',
        preferredCountries: ['no','se','dk','fr','us','gb'],
        autoPlaceholder: 'aggressive',
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.min.js",
        geoIpLookup: function(callback) {
            fetch('https://ipinfo.io/json', {
                cache: 'reload'
            }).then(response => {
                if ( response.ok ) {
                     return response.json()

                }
                throw new Error('Failed: ' + response.status)
            }).then(ipjson => {
                callback(ipjson.country)

            }).catch(e => {
                callback('no')
            })
        }
    })

    function updatephoneprefix() {
        prefixwork = telworkInput.intlTelInput('getSelectedCountryData').dialCode;
        prefixhome = telhomeinput.intlTelInput('getSelectedCountryData').dialCode;

        document.getElementById('prefixphonework').value=telworkInput.intlTelInput('getSelectedCountryData').dialCode;
        document.getElementById('prefixphonehome').value=telhomeinput.intlTelInput('getSelectedCountryData').dialCode;

        console.log("Prefix work : " + prefixwork);
        console.log("Prefix home : " + prefixhome);
    }
</script>
@endsection
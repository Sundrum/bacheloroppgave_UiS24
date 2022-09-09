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
    

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="far fa-address-card fa-lg"></i> @lang('myaccount.myaccount') </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('changeaccount') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="input-group">
                                <label for="email" class="col-md-4 col-form-label text-md-right"> @lang('myaccount.username') </label>
                                <div class="input-group-prepend"><span name="prefixproduct" value="email" class="input-group-text"><i class="fa fa-at fa-fw"></i></span></div>
                                <input type="email" class="col-md-5 form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ trim(Auth::user()->user_email) }}" required autocomplete="email">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#altem"><i class="fa fa-plus"></i></button>
                                </div>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div id="altem" class="collapse">
                            <div class="form-group row">
                               <div class="input-group">
                                   <label for="altemail" class="col-md-4 col-form-label text-md-right">@lang('myaccount.altemail')</label>
                                   <div class="input-group-prepend"><span name="prefixproduct" value="altemail" class="input-group-text"><i class="fa fa-at fa-fw"></i></span></div>
                                   <input type="altemail" class="col-md-5 form-control" id="altemail" name="altemail" value="{{ trim(Auth::user()->user_alternative_email) }}" autocomplete="altemail">

                                   {{-- @error('altemail')
                                       <span class="invalid-feedback" role="alert">
                                           <strong>{{ $message }}</strong>
                                       </span>
                                   @enderror --}}
                               </div>
                           </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group">
                                <label for="name" class="col-md-4 col-form-label text-md-right">@lang('myaccount.name')</label>

                                <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fa fa-user fa-fw"></i></span></div>
                                <input type="text" class="col-md-5 form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ trim(Auth::user()->user_name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="input-group">
                                <label for="phone_work" class="col-md-4 col-form-label text-md-right">@lang('myaccount.phonenumber')</label>
                                 <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                                <input type="tel" class="col-md-10 form-control" id="phone_work" maxlength="15" value="{{trim(Auth::user()->user_phone_work)}}">
                                {{-- <div class="input-group-append">
                                      <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#phw"><i class="fa fa-plus"></i></button>
                                </div> --}}
                            </div>
                        </div>

                        {{-- <div id="phw" class="collapse"> --}}
                            {{-- <div class="form-group row">
                                <div class="input-group">
                                    <label for="phone_home" class="col-md-4 col-form-label text-md-right">{{ __('Phone Home') }}</label>
                                    <div class="input-group-prepend"><span name="prefixproduct" value="phone_home" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                                    <input type="tel" class="col-md-10 form-control" id="phone_home" name="phone_home" value="{{trim(Auth::user()->user_phone_home)}}">
                                </div>
                            </div> --}}
                        {{-- </div> --}}
                        <div class="form-group row">
                            <div class="input-group"> 
                                <label for="language" class="col-md-4 col-form-label text-md-right">@lang('myaccount.language')</label>
                                    <div class="input-group-prepend"><label class="input-group-text" for="language"><i class="fa fa-globe fa-fw"></i></label></div>
                                    <select class="custom-select col-md-4 form-control" id="language" name="language">
                          
                                        <option value="2" @if(Auth::user()->user_language == 2) selected="selected" @endif> English </option>
                                        <option value="1" @if(Auth::user()->user_language == 1) selected="selected" @endif> Norwegian </option>
                                        <option value="3" @if(Auth::user()->user_language == 3) selected="selected" @endif> French </option>
                         
                                    </select>
                            </div>
                        </div>                

                        <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" onclick="updatephoneprefix();">
                                    @lang('myaccount.savesettings')
                                </button>
                            </div>
                        </div>
                            {{-- <input type="hidden" class="form-control" id="prefixphonework" name="prefixphonework" value="47">
                            <input type="hidden" class="form-control" id="prefixphonehome" name="prefixphonehome" value="47"> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    let telworkInput = $("#phone_work");
    let telhomeinput = $("#phone_home");
    let prefixhome = 47;
    let prefixwork = 47;

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
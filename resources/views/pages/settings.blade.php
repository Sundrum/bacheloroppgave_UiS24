@extends('layouts.app')

@section('content')

<style>
    .iti-flag {
        background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/img/flags.png");
    }

    @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
        .iti-flag {
            background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/img/flags@2x.png");
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/intlTelInput.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @if(isset($page))
            <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">@lang('settings.generalsettings')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(isset($page) && $page == 1) active @endif" id="profile-tab" data-toggle="tab" href="#sensors" role="tab" aria-controls="profile" aria-selected="false">@lang('settings.sensorsettings')</a>
            </li>
            @if (Auth::user()->roletype_id_ref > 14)
                <li class="nav-item">
                    <a class="nav-link @if(isset($page) && $page == 2) active @endif" id="users-tab" data-toggle="tab" href="#useradmin" role="tab" aria-controls="profile" aria-selected="false">@lang('settings.accountssettings')</a>
                </li>
            @endif
        @else
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">@lang('settings.generalsettings')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sensors" role="tab" aria-controls="profile" aria-selected="false">@lang('settings.sensorsettings')</a>
            </li>
            @if (Auth::user()->roletype_id_ref > 14)
                <li class="nav-item">
                    <a class="nav-link" id="users-tab" data-toggle="tab" href="#useradmin" role="tab" aria-controls="profile" aria-selected="false">@lang('settings.accountssettings')</a>
                </li>
            @endif
        @endif
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if(!isset($page)) show active @endif" id="home" role="tabpanel" aria-labelledby="home-tab"><br>
            @include('pages.settings.customerinfo')
        </div>

        <div class="tab-pane fade @if(isset($page) && $page == 1) show active @endif" id="sensors" role="tabpanel" aria-labelledby="profile-tab">
           @include('pages.settings.sensorsettings')
        </div>

        @if (Auth::user()->roletype_id_ref > 14)
            <div class="tab-pane fade @if(isset($page) && $page == 2) show active @endif" id="useradmin" role="tabpanel" aria-labelledby="users-tab">
                @include('pages.settings.accountsettings')
            </div>
        @endif
    </div>

    <div id ="AllUnitsModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('settings.shareallunits')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('shareunit') }}">
                    @csrf
                    <input type="hidden" name="serialnumber" value="all">

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">@lang('settings.email')</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="name@example.com" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="access" class="col-md-4 col-form-label text-md-right">@lang('settings.premissions')</label>
                        <select id="access" class="" name="access">
                            <option value="false">@lang('settings.no')</option>
                            <option value="true">@lang('settings.yes')</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('settings.close')</button>
                <button type="submit" class="btn btn-primary">@lang('settings.share')</button>
            </form>
                
            </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });

    $('.popover-dismiss').popover({
        trigger: 'focus'
    });

    let customer_sms = $("#customer_variables_sms");
    let customer_sms_1 = $("#customer_variables_sms_1");
    let customer_sms_irr = $("#customer_variables_irrigation_sms");
    let customer_sms_irr_1 = $("#customer_variables_irrigation_sms_1");

     // initialize
     customer_sms.intlTelInput({
      initialCountry: 'no',
      preferredCountries: ['no','us','gb','se','dk','fi','fr','it'],
      autoPlaceholder: 'aggressive',
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
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
    customer_sms_1.intlTelInput({
        initialCountry: 'no',
        preferredCountries: ['no','us','gb','se','dk','fi','fr','it'],
        autoPlaceholder: 'aggressive',
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
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
    customer_sms_irr.intlTelInput({
      initialCountry: 'no',
      preferredCountries: ['no','us','gb','se','dk','fi','fr','it'],
      autoPlaceholder: 'aggressive',
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
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
    customer_sms_irr_1.intlTelInput({
        initialCountry: 'no',
        preferredCountries: ['no','us','gb','se','dk','fi','fr','it'],
        autoPlaceholder: 'aggressive',
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
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
        let prefix_customer_variables_sms = customer_sms.intlTelInput('getSelectedCountryData').dialCode;
        let prefix_customer_variables_sms_1 = customer_sms_1.intlTelInput('getSelectedCountryData').dialCode;
        let prefix_customer_variables_irrigation_sms = customer_sms_irr.intlTelInput('getSelectedCountryData').dialCode;
        let prefix_customer_variables_irrigation_sms_1 = customer_sms_irr_1.intlTelInput('getSelectedCountryData').dialCode;
        
        document.getElementById('prefix_customer_variables_sms').value=prefix_customer_variables_sms; 
        document.getElementById('prefix_customer_variables_sms_1').value=prefix_customer_variables_sms_1; 
        document.getElementById('prefix_customer_variables_irrigation_sms').value=prefix_customer_variables_irrigation_sms; 
        document.getElementById('prefix_customer_variables_irrigation_sms_1').value=prefix_customer_variables_irrigation_sms_1; 
        
    }
</script>
@endsection
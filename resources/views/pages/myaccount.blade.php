@extends('layouts.app')

@section('content')

<style>

    .intl-tel-input {
        padding: 6px 12px !important;
    }

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
                <div class="row text-center mt-3">
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
                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->user_id}}">
                        <div class="form-group">
                            <span id="email_helptext" class="mx-5">@lang('myaccount.username')</span>
                            <div class="input-group pb-2">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                        <i class="fa fa-user fa-at icon-color"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" placeholder="@lang('myaccount.username')" value="{{trim(Auth::user()->user_email)}}" required>
                                <div class="input-group-append" id="append-email">
                                    <div class="btn-7r m-0 p-2" style="border-radius: 0 15px 15px 0;"><i class="fa fa-x"></i></div>
                                </div>
                            </div>
                            <div id="email_append"></div>
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
                            <span id="phone_helptext" class="mx-5">@lang('myaccount.phonenumber')</span>
                            <div class="input-group pb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-7s h-100">
                                        <i class="fa fa-phone fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <input type="tel" placeholder="@lang('myaccount.phonenumber')" value="{{trim(Auth::user()->user_phone_work)}}" id="phone_work" class="form-control" required>
                                <div class="input-group-append" id="tel-append">
                                    <div class="btn-7r m-0 p-2" style="border-radius: 0 15px 15px 0;"><i class="fa fa-x"></i></div>
                                </div>
                            </div>
                            <div id="sms_append"></div>
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

                        <div class="form-group pb-4">
                            <span id="measurement_helptext" class="mx-5" >@lang('myaccount.measurement')
                            </span>
                            <div class="input-group"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-7s h-100">
                                        <i class="fa fa-globe fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <select class="custom-select form-control" id="measurement" name="measurement">
                                    <option value="1" @if(Auth::user()->measurement == 1) selected="selected" @endif> Metric System </option>
                                    <option value="2" @if(Auth::user()->measurement == 2) selected="selected" @endif> Imperial System </option>
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
    $(document).ready(function () {
        checkVerifyStatus();
    });

    function checkVerifyStatus() {
        $.ajax({
            url: "/myaccount/validation",
            type: 'GET',
            success: function (data) {
                console.log(data)
                if(typeof data.mail != 'undefined' && data.mail && data.mail.email_verified) {
                    if(data.mail.verify_email == @json(Auth::user()->user_email)) {
                        const verifiedmail = `<div class="btn-7g m-0 p-2" style="border-radius: 0 15px 15px 0;"><i class="fa fa-check"></i></div>`;
                        document.getElementById('append-email').innerHTML = verifiedmail;
                    } else {
                        const notverifiedmail = `<div class="card-rounded bg-7r mx-5 mt-0 mb-2 px-3 py-2">
                                                    <i class="fa fa-info-circle fa-lg"></i><a style="text-decoration: underline;" onclick="sendVerificationMail()"> Click here to verify your email</a> 
                                                </div>`;
                        document.getElementById('email_append').innerHTML = notverifiedmail;
                    }
                } else {
                    if(typeof data.mail !='undefined' && data.mail && data.mail.id) {
                        document.getElementById('email_append').innerHTML = `<div class="mx-md-5">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-8 col-md-7 col-lg-6">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control" placeholder="Verification Code E.g 1234" name="verification_code_email" id="verification_code_email" maxlength="4">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <button type="button" class="btn-7r" onclick="sendVerificationMail()">Resend</button>
                                                                        <button type="button" class="btn-7g" onclick="verifyMail()"><i class="fa fa-arrow-right"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>`;
                    } else {
                        const notverifiedmail = `<div class="card-rounded bg-7r mx-5 mt-0 mb-2 px-3 py-2">
                                                    <i class="fa fa-info-circle fa-lg"></i><a style="text-decoration: underline;" onclick="sendVerificationMail()"> Click here to verify your email</a> 
                                                </div>`;
                        document.getElementById('email_append').innerHTML = notverifiedmail;
                    }
                }
                if(typeof data.sms != 'undefined' && data.sms && data.sms.phonenumber_verified) {
                    if(data.sms.verify_phonenumber == @json(Auth::user()->user_phone_work)) {
                        const verifiedsms = `<div class="btn-7g m-0 p-2" style="border-radius: 0 15px 15px 0;"><i class="fa fa-check"></i></div>`;
                        document.getElementById('tel-append').innerHTML = verifiedsms;
                    } else {
                        const notverifiedsms = `<div class="card-rounded bg-7r mx-5 mt-0 mb-2 px-3 py-2">
                                                    <i class="fa fa-info-circle fa-lg"></i><a style="text-decoration: underline;" onclick="sendVerificationSMS()"> Click here to verify your phonenumber</a>
                                                </div>`;
                        document.getElementById('sms_append').innerHTML = notverifiedsms;
                    }
                } else {
                    if(typeof data.sms !='undefined' && data.sms && data.sms.id) {
                        document.getElementById('sms_append').innerHTML = `<div class="mx-md-5">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-8 col-md-7 col-lg-6">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control" placeholder="Verification Code E.g 1234" name="verification_code" id="verification_code" maxlength="4">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <button type="button" class="btn-7r" onclick="sendVerificationSMS()">Resend</button>
                                                                        <button type="button" class="btn-7g" onclick="verifyPhone()"><i class="fa fa-arrow-right"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>`;
                    } else {
                        const notverifiedsms = `<div class="card-rounded bg-7r mx-5 mt-0 mb-2 px-3 py-2">
                                                    <i class="fa fa-info-circle fa-lg"></i><a style="text-decoration: underline;" onclick="sendVerificationSMS()"> Click here to verify your phonenumber</a>
                                                </div>`;
                        document.getElementById('sms_append').innerHTML = notverifiedsms;
                    }
                }
            },
        })
    }

    let telworkInput = $("#phone_work");
    let prefixwork = 47;
    setTitle(@json(__('myaccount.myaccount')));
     // initialize
    intlTelInput(telworkInput.get(0), {
        hiddenInput: 'phone_work',
        separateDialCode: true,
        initialCountry: 'no',
        preferredCountries: ['no','us','fr','gb','dk','se'],
        autoPlaceholder: 'off',
        formatOnDisplay: false,
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

    function sendVerificationMail() {
        $.ajax({
            url: "/verify/email",
            type: 'POST',
            dataType: 'json',
            data: {
                'user_id': @json(Auth::user()->user_id),
                '_token': token
            },
            success: function (data) {
                console.log(data) 
                successMessage("An mail has been sent to your email.");
                document.getElementById('email_append').innerHTML = `<div class="mx-md-5">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-8 col-md-7 col-lg-6">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control" placeholder="Verification Code E.g 1234" name="verification_code_email" id="verification_code_email" maxlength="4">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <button type="button" class="btn-7r" onclick="sendVerificationMail()">Resend</button>
                                                                        <button type="button" class="btn-7g" onclick="verifyMail()"><i class="fa fa-arrow-right"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>`;                    
            },
            error: function (data) {
                errorMessage('Something went wrong. Please try again later');
            }
        })

    }

    function sendVerificationSMS() {
        $.ajax({
            url: "/verify/phone",
            type: 'POST',
            dataType: 'json',
            data: {
                'user_id': @json(Auth::user()->user_id),
                '_token': token
            },
            success: function (data) {
                console.log(data)
                successMessage("A text has been sent to your phonenumber.");
                document.getElementById('sms_append').innerHTML = `<div class="mx-md-5">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-8 col-md-7 col-lg-6">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control" placeholder="Verification Code E.g 1234" name="verification_code" id="verification_code" maxlength="4">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <button type="button" class="btn-7r" onclick="sendVerificationSMS()">Resend</button>
                                                                        <button type="button" class="btn-7g" onclick="verifyPhone()"><i class="fa fa-arrow-right"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>`;
            },
            error: function (data) {
                errorMessage('Something went wrong. Please try again later');
            }
        })
    }

    function verifyPhone() {
        let code = document.getElementById('verification_code').value;
        if(code.length < 4) {
            return errorMessage("Verification Code needs to be 4 digits");
        }
        $.ajax({
            url: "/validate/phone",
            type: 'POST',
            dataType: 'json',
            data: { 
                "code": document.getElementById('verification_code').value,
                'user_id': @json(Auth::user()->user_id),
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                if(data == 2) {
                    errorMessage('Please click resend to get a new verification code.')
                } else if (data == 3) {
                    errorMessage('Your input is not defined. Please type in the code once more.')
                } else if (data == 4) {
                    errorMessage('Your input does not match our verification code.')
                } else if (data == 1) {
                    successMessage('Your phonenumber has been successfully verified.')
                    checkVerifyStatus();
                    document.getElementById('sms_append').innerHTML = " ";
                } else {
                    errorMessage('Something went wrong. Please try again later.')
                }
                
            },   
            error: function(data) {
                console.log(data);
            }
        });
    }

    function verifyMail() {
        let code = document.getElementById('verification_code_email').value;
        if(code.length < 4) {
            return errorMessage("Verification Code needs to be 4 digits");
        }
        $.ajax({
            url: "/validate/email",
            type: 'POST',
            dataType: 'json',
            data: { 
                "code": document.getElementById('verification_code_email').value,
                'user_id': @json(Auth::user()->user_id),
                "_token": token,
            },
            success: function(data) {
                console.log(data);
                if(data == 2) {
                    errorMessage('Please click resend to get a new verification code.')
                } else if (data == 3) {
                    errorMessage('Your input is not defined. Please type in the code once more.')
                } else if (data == 4) {
                    errorMessage('Your input does not match our verification code.')
                } else if (data == 1) {
                    successMessage('Your email has been successfully verified.')
                    checkVerifyStatus();
                    document.getElementById('email_append').innerHTML = " ";
                } else {
                    errorMessage('Something went wrong. Please try again later.')
                }
                
            },   
            error: function(data) {
                console.log(data);
            }
        });
    }

    function updatephoneprefix() {
        prefixwork = telworkInput.intlTelInput('getSelectedCountryData').dialCode;
        document.getElementById('prefixphonework').value=telworkInput.intlTelInput('getSelectedCountryData').dialCode;
        console.log("Prefix work : " + prefixwork);
    }
</script>
@endsection
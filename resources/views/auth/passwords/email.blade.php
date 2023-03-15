@extends('layouts.app')

@section('content')

{{-- ForgotPasswordController flashes a message to session if e-mail is incorrect --}}
@if (Session::has('message'))
<div class="alert alert-danger">{{ Session::get('message') }}</div>
@endif
    <div class="row">
    <div class="col-12 my-login">
        <div class="col-md-6 col-lg-5 col-xl-4 offset-md-5 offset-lg-6 offset-xl-7">
            <div class="card card-rounded">
                <div class="row justify-content-center mb-2">
                    <div class="icon-card icon-color reset">
                        <i class="fa fa-4x fa-lock"></i>
                    </div>
                </div>
                <div class="row text-center mt-5">
                    <h1>Reset Password</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row mt-2">
                            <div class="col-sm-10 offset-sm-1">
                                <span id="email_helptext" class="mx-5" style="display: none">Email</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span name="prefixproduct" value="email" class="input-group-text bg-7s icon-color h-100">
                                            <i class="fa fa-1x fa-at"></i>
                                        </span>
                                    </div>
                                    <input placeholder="Email" id="email" class="form-control input-login" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4 mb-0 text-center">
                            <div class="col-sd-8">
                                <button type="submit" class="btn-7s">
                                    {{ __('Send Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    email.addEventListener("input", showHelptext);
</script>
@endsection

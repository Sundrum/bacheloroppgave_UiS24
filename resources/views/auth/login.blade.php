@extends('layouts.app')

@section('content')
<div class="row mx-0">
    <div class="col-12 my-login">
        <div class="col-md-6 col-lg-5 col-xl-4 offset-md-5 offset-lg-6 offset-xl-7">
            <div class="card card-rounded mb-5">
                <div class="row justify-content-center">
                    <div class="icon-card icon-color">
                        <i class="fa fa-4x fa-user"></i>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <h1>Login</h1>
                </div>
                <p class="text-center px-md-3 px-2">When you login you will get access to your units.</p>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-1">
                                <span id="email_helptext" class="mx-5" style="display: none">Email</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span name="prefixproduct" value="email" class="input-group-text bg-7s icon-color h-100">
                                            <i class="fa fa-1x fa-at"></i>
                                        </span>
                                    </div>
                                    <input placeholder="Email" id="email" class="form-control form-control-7s input-login" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            
                            <div class="col-sm-10 offset-sm-1">
                                <span id="password_helptext" class="mx-5" style="display: none">Password</span>
                                <div class="input-group">
                                    <div class="input-group-prepend
                                    ">
                                        <span name="prefixproduct" value="password" class="input-group-text bg-7s icon-color h-100">
                                            <i class="fa fa-1x fa-lock"></i>
                                        </span>
                                    </div>
                                    <input placeholder="Password" id="password" type="password" class="input-login form-control form-control-7s @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row text-center my-3">
                            <div class="col-7 px-md-5">
                                <div class="text-start">
                                    <input class="form-check-input checkbox-7s" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label ml-0" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-5 text-right">
                                @if (Route::has('password.request'))
                                    <a class="text-b" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0 text-center">
                            <div class="col-sd-8">
                                <button type="submit" class="btn-7s" id="loginbutton">
                                    {{ __('LOGIN') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br><br>
<script>
    password.addEventListener("input", showHelptext);
    email.addEventListener("input", showHelptext);
</script>
@endsection

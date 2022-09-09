@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center welcome-bg" id="welcome-bg">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <!--<div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-dark">{{ __('LOGIN') }}</div>
        
                        <div class="card-body">
                            <form action="{{url('post-login')}}" method="POST" id="logForm">
                                @csrf
                                <div class="form-label-group">
                                    <label for="inputEmail" class="text-dark text-left">Email</label>
                                    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" >
                                    
                                    @if ($errors->has('email'))
                                    <span class="error">{{ $errors->first('email') }}</span>
                                    @endif    
                                  </div> 
                   
                                  <div class="form-label-group">
                                    <label for="inputPassword" class="text-dark text-left">Password</label>
                                    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                                    
                                     
                                    @if ($errors->has('password'))
                                    <span class="error">{{ $errors->first('password') }}</span>
                                    @endif  
                                  </div>
        
                                  <button class="btn btn-primary" type="submit">Login</button>
                                  <div class="text-center text-dark">If you have an account?
                                    <a class="small" href="{{url('register')}}">Sign Up</a></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
@endsection   
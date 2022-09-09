

@extends('layouts.admin')

@section('content')

<section class="container">
    <div class="mt-3 mb-3">
        <div class="row">
            <div class="@if(isset($user))col-md-6 @else col-md-12 @endif card card-rounded">
                <h5 class="m-4 text-center">User info</h5>
                @if(isset(request()->customer_id))
                    <div class="alert alert-secondary">Denne brukeren opprettes via en kunde-side. Vennligst verifiser at "Kunde" stemmer med ønsket kunde.</div>
                @endif
                <div class="mt-3 mb-3">
                    <form method="POST" name="userupdate" id="userupdate" action="{{route('updateUser')}}">
                        @csrf
                        <div class="form-group row">
                            <label for="user_email" class="col-md-4 col-form-label">{{ __('E-mail') }}</label>
                            <div class="col-md-8">
                                <input id="user_email" type="email" class="form-control @error('email') is-invalid @enderror" name="user_email" value="{{$user->user_email ?? ''}}" placeholder="Username / E-mail" required autocomplete="email" autofocus onkeyup="this.value=this.value.toLowerCase()">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label for="user_name" class="col-md-4 col-form-label">{{ __('Name') }}</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Full name" value="{{$user->user_name ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user_phone_work" class="col-md-4 col-form-label">{{ __('Phone') }}</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="user_phone_work" name="user_phone_work" placeholder="+47 000 00 000" value="{{$user->user_phone_work ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user_alternative_email" class="col-md-4 col-form-label">{{ __('Alternative E-mail') }}</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <input type="email" class="form-control" id="user_alternative_email" name="user_alternative_email" placeholder="Alternative E-mail" value="{{$user->user_alternative_email ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user_language" class="col-md-4 col-form-label">{{ __('Language') }}</label>
                            <div class="input-group col-md-8">
                                <select class="custom-select col-md-12 form-control" id="user_language" name="user_language" required>
                                    <option value="1" @if(isset($user) && $user->user_language == 1) selected="selected" @endif> Norwegian </option>
                                    <option value="2" @if(isset($user) && $user->user_language == 2) selected="selected" @endif> English </option>
                                    <option value="3" @if(isset($user) && $user->user_language == 3) selected="selected" @endif> French </option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="roletype_id_ref" class="col-md-4 col-form-label">Usertype</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <select class="custom-select col-md-12 form-control" id="roletype_id_ref" name="roletype_id_ref" required>
                                        @foreach($table['roletype'] as $roletype)
                                            <option @if(isset($user) && $roletype->roletype_id == $user->roletype_id_ref) selected @endif value="{{$roletype->roletype_id}}"> {{$roletype->roletype}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customer_id" class="col-md-4 col-form-label">Customer</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <select class="custom-select col-md-12 form-control" id="customer_id" name="customer_id" required>
                                        @foreach($table['customer'] as $customer)
                                            @if(isset(request()->customer_id))
                                                <option @if($customer->customer_id == request()->customer_id) selected @endif value="{{$customer->customer_id}}"> {{$customer->customer_name}}</option>    
                                            @else
                                                <option @if(isset($user) && $customer->customer_id == $user->customer_id_ref) selected @endif value="{{$customer->customer_id}}"> {{$customer->customer_name}}</option>    
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <div class="form-group row">
                            <label for="user_password" class="col-md-4 col-form-label">{{ __('Password') }}</label>
                            <div class="input-group col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="user_password" name="user_password" minlength="5" placeholder="Password" @if(!isset($user)) required @endif>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="{{$user->user_id ?? ''}}">
                        <div class="form-row justify-content-center">
                            <div class="col-lg-4">
                                <button type="submit" id="userform" class="btn-primary-filled"> Save </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @include('admin.user.units')
        </div>
    </div>
    @if(isset($user))
    <div class="col-md-12 mt-4 mb-4">
        <hr>
        <div class="row mb-4 justify-content-center">
            <a class="nav-pills btn-secondary-rounded-outline m-1" href="/select/{{$user->user_id}}/{{$user->customernumber}}">Logg inn som</a>
            <a class="nav-pills btn-secondary-rounded-outline m-1" href="/admin/customer/{{$user->customer_id_ref}}">@lang('admin.customer')</a>
            <a class="nav-pills btn-secondary-rounded-outline m-1" href="#">@lang('admin.delete')</a>
        </div>
    </div>
 
    @endif
</section>

<script>
$( document ).ready(function() {
    $('#customer_id').select2();
});
</script>

@endsection
<div class="row">
    <div class="col-sx-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3">
        <div class="card-rounded bg-white p-3 my-5">
            <div class="row justify-content-center mb-2">
                <div class="icon-card" style="padding: 20px;">
                    <i class="fas fa-cog fa-3x icon-color"></i>
                </div>
            </div>
            <div class="row text-center">
                <h3>@lang('settings.generalsettings')</h3>
            </div>
            <div class="row text-center">
                {{-- <div class="col-12">
                    <p>@lang('settings.generalsettingstext')</p>
                </div> --}}
            </div>
            <form method="POST" action="{{ route('updatecustomersettings') }}">
                @csrf
                <input type="hidden" class="form-control" id="prefix_customer_variables_sms" name="prefix_customer_variables_sms" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_sms_1" name="prefix_customer_variables_sms_1" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_irrigation_sms" name="prefix_customer_variables_irrigation_sms" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_irrigation_sms_1" name="prefix_customer_variables_irrigation_sms_1" value="47">

            {{-- <div class="alert alert-danger">In Progress - Please do not update at the moment ||  {{$customersettings['customer_variables_sms']}}, {{$customersettings['customer_variables_sms_1']}}, {{$customersettings['customer_variables_irrigation_sms']}} , {{$customersettings['customer_variables_irrigation_sms_1']}}</div> --}}
                <input type="hidden" name="customernumber" value="{{ $customersettings['customernumber'] }}">
                @if((isset($customersettings['customer_variables_sms_enable']) && $customersettings['customer_variables_sms_enable'] == 1 ))
                    <div class="form-group">
                        <span class="mx-5" for="customer_variables_sms">@lang('settings.smsalerts')</span>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-7s h-100">
                                    <i class="fa fa-phone fa-fw icon-color"></i>
                                </span>
                            </div>
                            <input type="tel" class="form-control" id="customer_variables_sms" name="customer_variables_sms" value="{{ $customersettings['customer_variables_sms'] ?? '' }}" autofocus>
                        </div>
                    </div>
                    
                    <div id="sms-alert" class="@if(!trim($customersettings['customer_variables_sms_1'])) collapse @endif">
                        <div class="form-group row">
                            <div class="input-group">
                                <label for="customer_variables_sms_1" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                                <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                                <input type="tel" class="col-md-10 form-control" id="customer_variables_sms_1" name="customer_variables_sms_1" value="{{ $customersettings['customer_variables_sms_1'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                @else
                <div class="form-group">
                    <span class="mx-5" for="customer_variables_sms">@lang('settings.smsalerts')</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-7s h-100">
                                <i class="fa fa-phone fa-fw icon-color"></i>
                            </span>
                        </div>
                        <input type="tel" class="form-control" id="sms_info" name="sms_info" placeholder="@lang('settings.paid')" disabled>
                        <div class="input-group-append">
                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.smsalertsinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group mt-2">
                    <span class="mx-5" for="customer_variables_email">@lang('settings.emailalerts')</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                <i class="fas fa-at fa-fw icon-color"></i>
                            </span>
                        </div>
                        <input type="email" class="form-control @error('email_1') is-invalid @enderror" id="customer_variables_email" name="customer_variables_email" value="{{ $customersettings['customer_variables_email'] ?? '' }}" autofocus>
                        @if(!trim($customersettings['customer_variables_email_1']))
                            <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#email-alert"><i class="fa fa-plus"></i></button>
                        @endif
                        @error('email_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                    </div>
                </div>
                <div id="email-alert" class="@if(!trim($customersettings['customer_variables_email_1'])) collapse @endif">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                    <i class="fas fa-at fa-fw icon-color"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control @error('email_2') is-invalid @enderror" id="customer_variables_email_1" name="customer_variables_email_1" value="{{ $customersettings['customer_variables_email_1'] ?? '' }}" autofocus>
                            @error('email_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label for="user_defined_title" class="mx-5">@lang('settings.title')</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                <i class="fas fa-signature fa-fw icon-color"></i>
                            </span>
                        </div>
                        <input type="text" class="col-md-5 form-control" id="user_defined_title" name="user_defined_title" value="{{ Session::get('customer_site_title') }}">
                    </div>
                </div>
                
                @if (Session::get('irrigation'))
                <hr>
                    <h3 class="mt-3">@lang('settings.irrigationsetup')</h3>
                    <div class="form-group mt-2">
                        <span for="customer_variables_irrigation_sms" class="mx-5">@lang('settings.smsalerts')</span>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                    <i class="fa fa-phone fa-fw icon-color"></i>
                                </span>
                            </div>
                            <input type="tel" class="col-md-10 form-control" id="customer_variables_irrigation_sms" name="customer_variables_irrigation_sms" value="{{ $customersettings['customer_variables_irrigation_sms'] ?? '' }}" autofocus>
                            {{-- @if(!trim($customersettings['customer_variables_irrigation_sms_1']))
                                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#sms-alert-irr"><i class="fa fa-plus"></i></button>
                            @endif --}}
                        </div>
                    </div>
                    
                    <div id="sms-alert-irr" class="@if(!trim($customersettings['customer_variables_irrigation_sms_1'])) collapse @endif">
                        <div class="form-group row">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                        <i class="fa fa-phone fa-fw icon-color"></i>
                                    </span>
                                </div>
                                <input type="tel" class="col-md-10 form-control" id="customer_variables_irrigation_sms_1" name="customer_variables_irrigation_sms_1" value="{{ $customersettings['customer_variables_irrigation_sms_1'] ?? '' }}" autofocus>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label for="customer_variables_irrigation_email" class="mx-5">@lang('settings.emailalerts')</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span name="prefixproduct" class="input-group-text bg-7s h-100">
                                    <i class="fas fa-at fa-fw icon-color"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control @error('email_irr') is-invalid @enderror" id="customer_variables_irrigation_email" name="customer_variables_irrigation_email" value="{{ $customersettings['customer_variables_irrigation_email'] ?? '' }}" autofocus>
                            {{-- @if(!trim($customersettings['customer_variables_irrigation_email_1']))
                                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#email-alert-irr"><i class="fa fa-plus"></i></button>
                            @endif --}}
                            @error('email_irr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div id="email-alert-irr" class="@if(!trim($customersettings['customer_variables_irrigation_email_1'])) collapse @endif">
                        <div class="form-group row">
                            <div class="input-group">
                                <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-at fa-fw"></i></span></div>
                                <input type="email" class="col-md-5 form-control @error('email') is-invalid @enderror" id="customer_variables_irrigation_email_1" name="customer_variables_irrigation_email_1" value="{{ $customersettings['customer_variables_irrigation_email_1'] ?? '' }}" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn-7s" onclick="updatephoneprefix();">
                            @lang('settings.savegeneralsettings')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    setTitle(@json( __('settings.customersetup')));
</script>
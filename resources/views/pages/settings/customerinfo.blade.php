<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-cogs fa-lg"></i> @lang('settings.generalsetup') </h4>    
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('updatecustomersettings') }}">
                @csrf
                <input type="hidden" class="form-control" id="prefix_customer_variables_sms" name="prefix_customer_variables_sms" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_sms_1" name="prefix_customer_variables_sms_1" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_irrigation_sms" name="prefix_customer_variables_irrigation_sms" value="47">
                <input type="hidden" class="form-control" id="prefix_customer_variables_irrigation_sms_1" name="prefix_customer_variables_irrigation_sms_1" value="47">

            {{-- <div class="alert alert-danger">In Progress - Please do not update at the moment ||  {{$customersettings['customer_variables_sms']}}, {{$customersettings['customer_variables_sms_1']}}, {{$customersettings['customer_variables_irrigation_sms']}} , {{$customersettings['customer_variables_irrigation_sms_1']}}</div> --}}
                <input type="hidden" name="customernumber" value="{{ $customersettings['customernumber'] }}">
                @if((isset($customersettings['customer_variables_sms_enable']) && $customersettings['customer_variables_sms_enable'] == 1 ))
                <div class="form-group row">
                        <div class="input-group">
                            <label for="customer_variables_sms" class="col-md-4 col-form-label text-md-right">@lang('settings.smsalerts')</label>
                            <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                            <input type="tel" class="col-md-10 form-control" id="customer_variables_sms" name="customer_variables_sms" value="{{ $customersettings['customer_variables_sms'] ?? '' }}" autofocus>
                            @if(!trim($customersettings['customer_variables_sms_1']))
                                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#sms-alert"><i class="fa fa-plus"></i></button>
                            @endif
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
                    <div class="form-group row">
                        <div class="input-group">
                            <label for="sms_info" class="col-md-4 col-form-label text-md-right">@lang('settings.smsalerts')</label>
                            <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                            <input type="text" class="col-md-5 form-control" id="sms_info" name="sms_info" value="" placeholder="@lang('settings.paid')" disabled>
                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.smsalertsinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="input-group">
                        <label for="customer_variables_email" class="col-md-4 col-form-label text-md-right">@lang('settings.emailalerts')</label>
                        <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-at fa-fw"></i></span></div>
                        <input type="email" class="col-md-5 form-control @error('email_1') is-invalid @enderror" id="customer_variables_email" name="customer_variables_email" value="{{ $customersettings['customer_variables_email'] ?? '' }}" autofocus>
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
                    <div class="form-group row">
                        <div class="input-group">
                            <label for="customer_variables_email_1" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                            <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-at fa-fw"></i></span></div>
                            <input type="email" class="col-md-5 form-control @error('email_2') is-invalid @enderror" id="customer_variables_email_1" name="customer_variables_email_1" value="{{ $customersettings['customer_variables_email_1'] ?? '' }}" autofocus>
                            @error('email_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label for="user_defined_title" class="col-md-4 col-form-label text-md-right">@lang('settings.title')</label>
                        <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-signature fa-fw"></i></span></div>
                        <input type="text" class="col-md-5 form-control" id="user_defined_title" name="user_defined_title" value="{{ Session::get('customer_site_title') }}">
                    </div>
                </div>
                
                @if (Session::get('irrigation'))
                    <br>
                    <h4>@lang('settings.irrigationsetup')</h4>
                    <div class="form-group row">
                        <div class="input-group">
                            <label for="customer_variables_irrigation_sms" class="col-md-4 col-form-label text-md-right">@lang('settings.smsalerts')</label>
                            <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                            <input type="tel" class="col-md-10 form-control" id="customer_variables_irrigation_sms" name="customer_variables_irrigation_sms" value="{{ $customersettings['customer_variables_irrigation_sms'] ?? '' }}" autofocus>
                            @if(!trim($customersettings['customer_variables_irrigation_sms_1']))
                                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#sms-alert-irr"><i class="fa fa-plus"></i></button>
                            @endif
                        </div>
                    </div>
                    
                    <div id="sms-alert-irr" class="@if(!trim($customersettings['customer_variables_irrigation_sms_1'])) collapse @endif">
                        <div class="form-group row">
                            <div class="input-group">
                                <label for="customer_variables_irrigation_sms_1" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
                                <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fa fa-phone fa-fw"></i></span></div>
                                <input type="tel" class="col-md-10 form-control" id="customer_variables_irrigation_sms_1" name="customer_variables_irrigation_sms_1" value="{{ $customersettings['customer_variables_irrigation_sms_1'] ?? '' }}" autofocus>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="input-group">
                            <label for="customer_variables_irrigation_email" class="col-md-4 col-form-label text-md-right">@lang('settings.emailalerts')</label>
                            <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-at fa-fw"></i></span></div>
                            <input type="text" class="col-md-5 form-control @error('email_irr') is-invalid @enderror" id="customer_variables_irrigation_email" name="customer_variables_irrigation_email" value="{{ $customersettings['customer_variables_irrigation_email'] ?? '' }}" autofocus>
                            @if(!trim($customersettings['customer_variables_irrigation_email_1']))
                                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#email-alert-irr"><i class="fa fa-plus"></i></button>
                            @endif
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
                                <label for="customer_variables_irrigation_email_1" class="col-md-4 col-form-label text-md-right">{{ __('') }}</label>
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
                        <button type="submit" class="btn btn-primary" onclick="updatephoneprefix();">
                            @lang('settings.savegeneralsettings')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
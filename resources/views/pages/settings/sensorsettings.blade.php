<p>
    <button class="btn btn-secondary float-right" type="button" data-toggle="modal" data-target="#AllUnitsModal">
        @lang('settings.shareallunits')
    </button>
</p>
<br>
<br>
<div id="list">
    @php
        $units = Session::get('units');
    @endphp
    @foreach ($units as $unit)
        <div class="card bg-light mb-1">
            <div class="card-header">
                <table align="left" style="position: static; text-align:left; width:50%;">
                    <tr>
                        @if( trim($unit['sensorunit_location']))
                            <td><h5>{{ trim($unit['sensorunit_location']) }}</h5></td>
                        @else
                            <td><h5>{{ trim($unit['serialnumber']) }}</h5></td>
                        @endif
                {{-- <div class="float-right"> --}}
                </table>
                <table align="right" style="position: static; text-align:right; width:30%;">
                    <tr>
                        @if ($unit['changeallowed'] == 1)
                            <td><i class="fas fa-2x fa-share-square" style="margin-right: 1em;" aria-hidden="true" aria-controls="collapse" data-toggle="collapse" data-target="#collapse{{trim($unit['serialnumber'])}}"></i></td>
                        @endif
                        <td><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#changesettings{{trim($unit['serialnumber'])}}">
                            @lang('settings.changesettings')
                        </button></td>
                    </tr>
                </table>
                {{-- </div> --}}
            </div>
            <div class="collapse collapse-local" id="collapse{{trim($unit['serialnumber'])}}">                        
                <div class="card-body">
                    @if ($unit['changeallowed'] == 1)
                        <form method="POST" action="{{ route('shareunit') }}">
                            @csrf

                            <input type="hidden" name="serialnumber" value="{{ $unit['serialnumber'] }}">

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

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('settings.share')
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p> You don't have premissions to share this unit </p>
                    @endif
                </div> 
            </div>
        </div>

        <div id ="changesettings{{trim($unit['serialnumber'])}}" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('settings.settingsfor') {{ $unit['serialnumber'] }} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $unittype = substr($unit['serialnumber'],0,7);
                        @endphp
                        @if(strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0)
                        <form id="irrsettings" method="POST" action="{{ route('irrigationsettings') }}">
                            @csrf
                            @foreach ($irrigationunits as $irrigationunit)
                                @if(trim($unit['serialnumber']) == trim($irrigationunit['serialnumber']))
                                    <input type="hidden" name="serialnumber" value="{{$unit['serialnumber']}}">
                                    @if($unit['changeallowed'] == 1)
                                        <div class="alert alert-secondary">@lang('settings.remember')</div>
                                    @else
                                        <div class="alert alert-secondary">@lang('settings.nopremissions')</div>
                                    @endif
                                    
                                    <div class="form-group row">
                                        <div class="input-group">
                                            <label for="unitname" class="col-md-4 col-form-label text-md-right">@lang('settings.unitname')</label>
                                            <div class="input-group-prepend"><span name="prefixproduct" value="name" class="input-group-text"><i class="fas fa-signature"></i></span></div>
                                            <input type="text" class="col-md-4 form-control" id="unitname" name="unitname" value="{{ trim($unit['sensorunit_location']) }}" @if($unit['changeallowed'] == 0) disabled @endif>
                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.unitnameinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="input-group">
                                            <label for="irrigation_tilt" class="col-md-4 col-form-label text-md-right">@lang('settings.tilt')</label>
                                            {{-- <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-mobile-alt"></i></span></div> --}}
                                            <input type="int" class="col-md-5 form-control" id="irrigation_tilt" name="irrigation_tilt" value="@if(isset($irrigationunit['irrigation_tilt'])) {{trim($irrigationunit['irrigation_tilt'])}} @else 100 @endif" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                            <div class="input-group-append"> <span class="input-group-text">&deg;</span> </div>
                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.tiltinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="input-group">
                                            <label for="radius" class="col-md-4 col-form-label text-md-right">@lang('settings.meterfromtarget')</label>
                                            {{-- <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-mobile-alt"></i></span></div> --}}
                                            <input type="int" class="col-md-5 form-control" id="radius" name="radius" value="@if(isset($irrigationunit['irrigation_endpoint_radius'])) {{trim($irrigationunit['irrigation_endpoint_radius'])}} @else 10 @endif" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                            <div class="input-group-append"> <span class="input-group-text">m</span> </div>
                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.meterfromtargetinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="input-group">
                                            <label for="irrigation_nozzlewidth" class="col-md-4 col-form-label text-md-right">@lang('settings.nozzlewidth')</label>
                                            {{-- <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-mobile-alt"></i></span></div> --}}
                                            <input type="int" class="col-md-5 form-control" id="irrigation_nozzlewidth" name="irrigation_nozzlewidth" value="@if(isset($irrigationunit['irrigation_nozzlewidth'])){{trim($irrigationunit['irrigation_nozzlewidth'])}} @else 30 @endif" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                            <div class="input-group-append"> <span class="input-group-text">mm</span> </div>
                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.nozzlewidthinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                        </div>
                                    </div>

                                    @if(strcmp($unit['serialnumber'],'21-1020-AA-00197') === 0 )
                                        <div class="form-group row">
                                            <div class="input-group">
                                                <label for="irrigation_pressure_bar" class="col-md-4 col-form-label text-md-right">Varsling trykk</label>
                                                <input type="text" class="col-md-5 form-control" pattern = "[0-9].[0-9]" maxlength="3" id="irrigation_pressure_bar" name="irrigation_pressure_bar" value="@if(isset($irrigationunit['irrigation_pressure_bar']->value)){{substr($irrigationunit['irrigation_pressure_bar']->value,0,3)}}@endif" placeholder="F.eks: 2.0" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                                <div class="input-group-append"><span class="input-group-text">Bar</span> </div>
                                                <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="Den nedre grenseverdien for varsling for trykk"><i class="fa fa-info-circle fa-lg"></i></button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group row">
                                            <div class="input-group">
                                                <label for="irrigation_nozzlebar" class="col-md-4 col-form-label text-md-right">@lang('settings.nozzlebar')</label>
                                                <input type="int" class="col-md-5 form-control" id="irrigation_nozzlebar" name="irrigation_nozzlebar" value="@if(isset($irrigationunit['irrigation_nozzlebar'])){{trim($irrigationunit['irrigation_nozzlebar'])}} @else 5 @endif" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                                <div class="input-group-append"> <span class="input-group-text">Bar</span> </div>
                                                <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.nozzlebarinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @else
                        <form method="POST" action="{{ route('sensorsettings') }}">
                            @csrf 
                            <input type="hidden" name="serialnumber" value="{{$unit['serialnumber']}}">
                            @if($unit['changeallowed'] == 1)
                                <div class="alert alert-secondary">@lang('settings.remember')</div>
                            @else
                                <div class="alert alert-secondary">@lang('settings.nopremissions')</div>
                            @endif

                            
                            <div class="form-group row">
                                <label for="unitname" class="col-md-4 col-form-label text-md-right">@lang('settings.unitname')</label>
                                <div class="col-md-6">
                                    <input id="unitname" type="text" class="form-control" name="unitname" value="{{ trim($unit['sensorunit_location']) }}" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                </div>
                            </div>

                            @foreach ($sensorunits as $sensorunit)
                                @if(trim($sensorunit['serialnumber']) == trim($unit['serialnumber']))
                                    @if(strcmp($unittype,'21-1057') === 0)
                                        <div class="form-group row">
                                            <label for="sensorunit_tree_species" class="col-md-4 col-form-label text-md-right">Treslag</label>
                                            <div class="col-md-6">
                                                <select class="form-control" id="sensorunit_tree_species" name="sensorunit_tree_species" @if($unit['changeallowed'] == 0) disabled @endif>
                                                    @foreach($treespecies as $row)
                                                        @if(isset($sensorunit['tree_specie']))
                                                            <option value="{{$row->specie_id}}" @if(trim($row->specie_id) === $sensorunit['tree_specie']->value) selected @endif> {{$row->specie_name}}</option>
                                                        @else
                                                            <option value="{{$row->specie_id}}" @if($row->specie_id == 1) selected @endif> {{$row->specie_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- {{dd($sensorunit)}} --}}
                                    @foreach($sensorunit as $probe)
                                        @if(is_array($probe))
                                            @if(!$probe['sensorprobes_alert_hidden'])
                                            <div class="card mb-1">
                                                <div class="card-header">
                                                    <table align="left" style="position: static; text-align:left;">
                                                        <tr>
                                                            @isset($probe['unittype_icon'])
                                                                <td><img class="image-responsive" src="{{ $probe['unittype_icon'] }}" width="30" height="30" title="{{ $probe['unittype_description'] }}" rel="tooltip" alt=""> </td>
                                                            @endisset
                                                            <td>{{$probe['unittype_description']}}</td>
                                                        </tr>
                                                    </table>

                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <input type="hidden" name="probe[{{$probe['probenumber']}}][serialnumber]" value="{{$unit['serialnumber']}}">
                                                        <input type="hidden" name="probe[{{$probe['probenumber']}}][probenumber]" value="{{$probe['probenumber']}}">
                                                        
                                                        @if(((isset($customersettings['customer_variables_sms_enable']) && $customersettings['customer_variables_sms_enable'] == 1 )&& trim(Session::get('customernumber')) == trim($unit['customernumber'])))
                                                            <div class="col">
                                                                <label for="unitname" class="col-xs-6 col-form-label ">@lang('settings.smsalert')</label>
                                                                    <label class="switch">
                                                                        <input type="checkbox" @if(isset($probe['sms_enabled']) && $probe['sms_enabled'] == '1') checked @endif class="btn btn-primary" id="probe[{{$probe['probenumber']}}][sms_enabled]" name="probe[{{$probe['probenumber']}}][sms_enabled]" @if($unit['changeallowed'] == 0) disabled @endif>
                                                                            <span class="slider round"></span>
                                                                    </label>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="col">
                                                            <label for="emailalert" class="col-xs-6 col-form-label ">@lang('settings.emailalert')</label>
                                                            <label class="switch">
                                                            <input type="checkbox" @if(isset($probe['email_enabled']) && $probe['email_enabled'] == '1') checked @endif class="btn btn-primary" id="probe[{{$probe['probenumber']}}][email_enabled]" name="probe[{{$probe['probenumber']}}][email_enabled]" @if($unit['changeallowed'] == 0) disabled @endif>
                                                                    <span class="slider round"></span>
                                                            </label>
                                                        </div>

                                                        <div class="input-group">
                                                            <label for="probe[{{$probe['probenumber']}}][repeats]" class="col-md-4 col-form-label text-md-right">@lang('settings.repeats')</label>
                                                            {{-- <div class="input-group-prepend"><span name="prefixproduct" value="" class="input-group-text"><i class="fas fa-mobile-alt"></i></span></div> --}}
                                                            <input type="text" class="form-control col-xs-2" id="probe[{{$probe['probenumber']}}][repeats]" name="probe[{{$probe['probenumber']}}][repeats]" value="@if(isset($probe['repeats'])) {{trim($probe['repeats'])}} @endif" placeholder="" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.repeatsinfo')"><i class="fa fa-info-circle fa-lg"></i></button>
                                                        </div>

                                                        <div class="input-group">
                                                            <label for="probe[{{$probe['probenumber']}}][upper_thersholds]" class="col-md-4 col-form-label text-md-right">@lang('settings.upper')</label>
                                                            <input type="text" class="form-control col-xs-2" id="probe[{{$probe['probenumber']}}][upper_thersholds]" name="probe[{{$probe['probenumber']}}][upper_thersholds]" value="@if(isset($probe['upper_thersholds'])) {{trim($probe['upper_thersholds'])}} @endif" placeholder="" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                                            <div class="input-group-append"> <span class="input-group-text">{{$probe['unittype_shortlabel']}}</span> </div>
                                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.upperinfo') {{$probe['unittype_shortlabel']}}"><i class="fa fa-info-circle fa-lg"></i></button>
                                                        </div>
                                                        <div class="input-group">
                                                            <label for="probe[{{$probe['probenumber']}}][lower_thersholds]" class="col-md-4 col-form-label text-md-right">@lang('settings.lower')</label>
                                                            <input type="text" class="form-control col-xs-2" id="probe[{{$probe['probenumber']}}][lower_thersholds]" name="probe[{{$probe['probenumber']}}][lower_thersholds]" value="@if(isset($probe['lower_thersholds'])) {{trim($probe['lower_thersholds'])}} @endif" placeholder="" @if($unit['changeallowed'] == 0) disabled @endif autofocus>
                                                            <div class="input-group-append"> <span class="input-group-text">{{$probe['unittype_shortlabel']}}</span> </div>
                                                            <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="@lang('settings.lowerinfo') {{$probe['unittype_shortlabel']}}"><i class="fa fa-info-circle fa-lg"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        <div class="card mb-1">
                            <div class="card-header">
                                <table align="left" style="position: static; text-align:left;">
                                    <tr>
                                        <td>@lang('settings.notes')</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <textarea id="sensorunit_note" type="text" rows="3" class="form-control" name="sensorunit_note" value="@if(isset($unit['sensorunit_note'])) {{ trim($unit['sensorunit_note']) }} @endif" placeholder="Your notes" @if($unit['changeallowed'] == 0) disabled @endif autofocus>@if(isset($unit['sensorunit_note'])) {{ trim($unit['sensorunit_note']) }} @endif </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('settings.close')</button>
                        @if($unit['changeallowed'] == 1)
                            <button type="submit" class="btn btn-primary">@lang('settings.updatesettings')</button>
                        @endif
                    </div>
                        </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@extends('layouts.app')

@section('content')

<script> 
    setTitle(@json( __('navbar.dashboard')));
</script>
    @if (Session::has('settingserror') && Session::get('settingserror') == 1)
        <div class="alert alert-danger">
            Your account has no alarms defined <a href="/settings/1"> Click here to update</a>
        </div>
    @endif
    @if (Session::has('errormessage'))
        <div class="alert alert-danger">{{ Session::get('errormessage') }}</div>
    @endif
    <input type="hidden" id="customerNumber" value="{{ Session::get('customernumber') }}">
    @if(count(Session::get('irrigation')) > 0)
        @include('pages.dashboard.infobutton')
        @include('pages.dashboard.irrigationunits')
    @endif

    @if (count(Session::get('customerunits')) > 0)
        @include('pages.dashboard.sensorunits')
        @include('pages.dashboard.addgroup')
        @include('pages.dashboard.changegroup')
        @include('pages.dashboard.sensorsettings')
    @endif

    @if (count(Session::get('sharedunits')) > 0)
        @include('pages.dashboard.sharedunits')
    @endif

    <script>
        function caretRotation(obj) {
            var id = obj.id;
            var element = document.getElementById(id);
            if (element.classList.contains("fa-caret-down")) {
                element.classList.remove("fa-caret-down");
                element.classList.add("fa-caret-left");
            } else {
                element.classList.remove("fa-caret-left");
                element.classList.add("fa-caret-down");
            }
        }
        
        </script>
@endsection


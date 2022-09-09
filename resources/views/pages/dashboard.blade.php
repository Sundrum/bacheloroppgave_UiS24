@extends('layouts.app')

{{-- Sortable CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js" SameSite="none Secure"></script>
{{-- CSS for irrigation slider --}}
<link rel="stylesheet" type="text/css" href="{{ url('/css/slider.css') }}">
{{-- CSS for sensorunit live dot (circle) --}}
<link rel="stylesheet" type="text/css" href="{{ url('/css/dot.css') }}">

@section('content')
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
        <br><br>
        @include('pages.dashboard.irrigationunits')
    @endif

    @if (count(Session::get('customerunits')) > 0)

        @include('pages.dashboard.sensorunits')
        @include('pages.dashboard.addgroup')
        @include('pages.dashboard.changegroup')
        <br>
        @include('pages.dashboard.sensorsettings')
        <br>
    @endif

    @if (count(Session::get('sharedunits')) > 0)
        <br>
        @include('pages.dashboard.sharedunits')
    @endif
@endsection

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

// $(document).ready(function () {
//     if (sessionStorage.scrollTop != "undefined") {
//         $(window).scrollTop(sessionStorage.scrollTop);
//     }
//     setInterval(function() {
//     cache_clear()
//   }, 10000);
// });

// $(window).scroll(function () {
//     sessionStorage.scrollTop = $(this).scrollTop();
// });

// function cache_clear() {
//   window.location.reload(true);
//   // window.location.reload(); use this if you do not remove cache
// }
</script>
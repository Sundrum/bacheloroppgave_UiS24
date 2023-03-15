<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" defer></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('/js/map/utilities.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/snaptoroute.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/markers.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/oldruns.js') }}"></script>

<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="{{ asset('js/placeholder.js') }}"></script>

@auth
    <script>
        var token = "{{ csrf_token() }}";
        var timezone = moment.tz.guess();
        $.ajax({
            url: "/timezone",
            type: 'POST',
            data: { 
                "timezone": timezone,
                "_token": token,
            },
            success: function(msg) {
                console.log(msg);
            },   
            error:function(msg) {
                console.log("Problems setting your local timezone");
            }
        });
    </script>
@endauth

<script src="{{ asset('js/app.js') }}"></script>
@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<title>{{ config('APP_NAME', '7Sense Portal') }}</title>
</head>
<body>
@auth
    <main class="wrapper">
        <section class="sidebar">
            @include('layouts.components.sidebar')
        </section>
        <section class="content-wrapper">
            <div class="topbar">
                @include('layouts.components.topbar')
            </div>
            <div class="content-main">
                @yield('content')
            </div>
        </section>
    </main>
@else
    <main class="bg-img" id="main">
        @include('layouts.components.guestheader')
        @yield('content')
        @include('layouts.components.cookies')
    </main>
@endauth
@include('layouts.components.userfeedback')
</body>
</html>
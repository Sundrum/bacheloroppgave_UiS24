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
{{-- <script src="https://code.jquery.com/jquery-3.3.1.js" defer></script> --}}

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}"></script>

<title>{{ config('APP_NAME', '7Sense Portal') }}</title>
</head>
<body>
@include('layouts.navbar')
<main id="app" class="py-4 bg-white">
    <div class="container">
        @auth
            @if (Session::has('updateemail') && Session::get('updateemail') == 1)
                <div class="alert alert-danger">
                    Please update your Username to a valid Email. <a href="/myaccount"> Click here to update</a>
                </div>
            @endif
        @endauth
            @yield('content')

        
    </div>
</main>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<script src="{{ asset('js/jquery-3-3-1.js') }}"></script>
<script src="{{ asset('js/sortable.min.js') }}"></script>
<script src="{{ asset('js/fontawesome-5-13-0.min.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" defer></script>

<link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://nightly.datatables.net/js/jquery.dataTables.js" defer></script>
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.js" defer></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<script src="{{ asset('js/placeholder.js') }}"></script>

@auth
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>
    <script>
        const token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/timezone.js') }}"></script>

@endauth

@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>

<title>{{ config('APP_NAME', '7Sense Portal') }}</title>
</head>
<body>
@auth
    <section id="app">
        <main class="wrapper">
            <section class="sidebar">
                @include('layouts.components.sidebar')
            </section>
            <section class="content-wrapper">
                <div class="mt-4">
                    @include('layouts.components.topbar')
                </div>
                <div id="content-main" class="content-main">
                    @if(env('API_URL') !== 'localhost:46000/v1/')
                        <div class="row mb-2 mx-2">
                            <div class="col-12 card-rounded bg-7r py-1 pb-2 ">
                                <h3>{{env('API_URL')}}</h3>
                                Database connection are connected to Production. Please do not make any changes.
                            </div>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </section>
        </main>
    </section>
    <script>
        $( window ).on( "unload", function() {
            $(".message-g").remove();
        });
    </script>
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
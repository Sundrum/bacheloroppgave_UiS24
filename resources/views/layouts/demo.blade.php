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

<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://nightly.datatables.net/js/jquery.dataTables.js" defer></script>
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.js" defer></script>

{{-- <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>

<link href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script> --}}

{{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js" defer></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css"> --}}

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

@vite(['resources/sass/app.scss', 'resources/js/app.js'])


<title>{{ config('APP_NAME', '7Sense Portal') }}</title>
</head>
<body class="bg-grey">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/demo_uk') }}"><img src="{{asset('img/7sense-logo-white.png')}}" alt="brand" style="max-height: 50px; width: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul class="navbar-nav mr-auto">
              <li class="nav-item"><a class="nav-link @if(Request::is('dashboard')) active @endif" href="/demo_uk"><i class="fas fa-lg fa-desktop"></i> @lang('navbar.dashboard')</a></li>
              <li class="nav-item"><a class="nav-link disabled @if(Request::is('graph')) active @endif" href="/demo_uk"><i class="fas fa-lg fa-chart-bar"></i> @lang('navbar.graph')</a></li>
              <li class="nav-item">
                  <span class="badge badge-pill badge-primary" style="float:right;margin-bottom:-18px;">1</span> 
                <a class="nav-link disabled @if(Request::is('messages')) active @endif" href="/demo_uk"> <i class="fas fa-lg fa-envelope"></i>  @lang('navbar.messages')</a>
              </li>
              <li><a class="nav-link disabled @if(Request::is('settings')) active @endif" href="/demo_uk"><i class="fas fa-lg fa-cogs"></i>  @lang('navbar.settings')</a></li>
    
            </ul>
            @endauth
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              @guest
                <li class="nav-item">
                  <a class="nav-link @if(Request::is('login')) active @endif" href="/login"> {{ __('Login') }}</a>
                </li>
                
                {{-- <li class="nav-item">
                  <a class="nav-link" href="/register">{{ __('REGISTER') }}</a>
                </li> --}}
              @else
                @if (Auth::user()->roletype_id_ref > 80)
                    <li class="nav-item"><a class="nav-link @if(Request::is('select')) active @endif" href="/select"> <i class="fas fa-lg fa-sync-alt"></i> @lang('navbar.selectuser')</a></li>
                    <li class="nav-item"><a class="nav-link @if(Request::is('admin', 'admin/*')) active @endif" href="/admin"><i class="fa fa-lg fa-lock" aria-hidden="true"></i> @lang('navbar.admin')</a></li>
                @endif
                  <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> <i class="fa fa-lg fa-user"></i>
                      {{ Auth::user()->user_name }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="/myaccount"><i class="fas fa-lg fa-user"></i> @lang('navbar.myaccount')</a>
                      <a class="dropdown-item" href="/demo_uk"><i class="fas fa fa-laptop"></i> @lang('navbar.demo')</a>
                      <a class="dropdown-item disabled" href="/support"><i class="fas fa-lg fa-wrench"></i>  @lang('navbar.support')</a>
    
                      <a class="dropdown-item" href="/logout"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-lg fa-sign-out-alt"></i>
                        @lang('navbar.logout')
                      </a>
                      <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        @csrf
                      </form>
                    </div>
                  </li>
              @endguest
            </ul>
          </div>
        </div>
      </nav>
    
    <script>
      function dashboard() { 
        window.location = "/dashboard";
      }
      function locationreload() { 
        location.reload();            
      }
    </script>
    
<main class="py-4">
    @yield('content')
</main>
</body>
</html>
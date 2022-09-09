<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">
    @auth
      @if(Auth::user()->user_id == '275')
        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{asset('img/tekzence_logo.png')}}" alt="brand" style="max-height: 50px; width: auto;"></a>
        <a class="navbar-toggler" href="/"><i class="fas fa-lg fa-desktop"></i></a>
        <a class="navbar-toggler" onclick="locationreload()"><i class="fas fa-lg fa-redo"></i></a>
      @else
        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{asset('img/7sense-logo-white.png')}}" alt="brand" style="max-height: 50px; width: auto;"></a>
        <a class="navbar-toggler" onclick="dashboard()"><i class="fas fa-lg fa-desktop"></i></a>
        <a class="navbar-toggler" onclick="locationreload()"><i class="fas fa-lg fa-redo"></i></a>
      @endif
    @else
      <a class="navbar-brand" href="{{ url('/') }}"><img src="{{asset('img/7sense-logo-white.png')}}" alt="brand" style="max-height: 50px; width: auto;"></a>
    @endauth
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        @auth
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a class="nav-link @if(Request::is('dashboard')) active @endif" href="/dashboard"><i class="fas fa-lg fa-desktop"></i> @lang('navbar.dashboard')</a></li>
          <li class="nav-item"><a class="nav-link @if(Request::is('graph')) active @endif" href="/graph"><i class="fas fa-lg fa-chart-bar"></i> @lang('navbar.graph')</a></li>
          <li class="nav-item">
            <!-- NOTIFICATIONS -->
            @if(Session::get('newMessages'))
              <span class="badge badge-pill badge-primary" style="float:right;margin-bottom:-10px;">{{ Session::get('newMessages') }}</span> 
            @endif
            <a class="nav-link @if(Request::is('messages')) active @endif" href="/messages"> <i class="fas fa-lg fa-envelope"></i>  @lang('navbar.messages')</a>
          </li>
          <li><a class="nav-link @if(Request::is('settings')) active @endif" href="/settings"><i class="fas fa-lg fa-cogs"></i>  @lang('navbar.settings')</a></li>

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
                  <a class="dropdown-item" href="/support"><i class="fas fa-lg fa-wrench"></i>  @lang('navbar.support')</a>

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
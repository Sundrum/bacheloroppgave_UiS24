<div class="top-3">
    <h2 class="top-title" id="top-title"></h2>
    <span class="topbar-label" id="dashboard-modules">
        <i class="fas fa-lg fa-qrcode"></i>
    </span>
</div>
<div class="top-3" id="search-container">
  {{-- <span class="search-label">
      <i class="fas fa-search"></i>
    </span>
  <input type="search" placeholder="Search..."> --}}
</div>
    {{-- <button class="navbar-toggler bg-7s" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button> --}}


    <div class="top-3">
      {{-- <ul class="navbar-nav"> --}}
        <div class="toplabel-3-text">
          <h2 class="topnav-name" id="top-name">{{$data['user_name'] ?? Auth::user()->user_name ?? ''}}</h2>
          <h5 class="topnav-job" id="top-customer">{{$data['user_id'] ?? Auth::user()->customernumber ?? 'Customer'}}</h5>
        </div>
          <span class="topbar-label" href="#" role="button" data-bs-toggle="dropdown"> 
           <i class="fa fa-lg fa-bars"></i>
          </span>
          <ul class="dropdown-menu">
            <a href="{{route('myaccount')}}" class="dropdown-item">
              <i class="fas fa-user"></i> @lang('navbar.myaccount')
            </a>
            <a class="dropdown-item" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> @lang('navbar.logout')
            </a>

            <form id="logout-form" action="/logout" method="POST" style="display: none;"> @csrf </form>
          </ul>
        {{-- </li> --}}
          {{-- <li class="nav-item ">
            <a href="{{route('myaccount')}}" class="topbar-label-user">
              <i class="fas fa-user"></i>
            </a>
          </li> --}}
      {{-- </ul> --}}
  </div>



{{-- <div class="top-3">
    <h2 class="top-title" id="top-title"></h2>
    <span class="topbar-label" id="dashboard-modules">
        <i class="fas fa-lg fa-qrcode"></i>
    </span>
</div>
<div class="top-3" id="search-container">
  <span class="search-label">
        <i class="fas fa-search"></i>
      </span>
  <input type="search" placeholder="Search...">
</div>
<div class="top-3">
    <div class="toplabel-3-text">
        <h2 class="topnav-name" id="top-name">{{$data['user_name'] ?? Auth::user()->user_name ?? ''}}</h2>
        <h5 class="topnav-job" id="top-customer">{{$data['user_id'] ?? Auth::user()->customernumber ?? 'Customer'}}</h5>
    </div>
    <div class="nav-item dropdown topbar-label-user">
        <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre> 
            <i class="fa fa-lg fa-bars"></i>
            <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="{{route('myaccount')}}" class="dropdown-item">
                <i class="fas fa-user"></i> @lang('navbar.myaccount')
            <a class="dropdown-item" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-lg fa-sign-out"></i> @lang('navbar.logout')
            </a>
            <form id="logout-form" action="/logout" method="POST" style="display: none;"> @csrf </form>
        </div>
    </div>
    <a href="{{route('myaccount')}}" class="topbar-label-user">
        <i class="fas fa-user"></i>
    </a>
</div> --}}
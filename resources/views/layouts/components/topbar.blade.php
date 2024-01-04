<div class="row px-3">
  <div class="col-xl-8 col-md-8 col-sm-10 col-10">
    <h2 class="top-title" id="top-title"></h2>
  </div>
  <div id="user-text-info" class="col-xl-3 col-md-3 col-sm-3 col-4 float-end">
    <div class="row">
      <h2 class="topnav-name" id="top-name">{{Session::get('customer_site_title') ?? Auth::user()->customernumber ?? 'Customer'}}</h2>
    </div>
    <div class="row">
      <h5 class="topnav-job" id="top-customer">{{$data['user_name'] ?? Auth::user()->user_name ?? ''}}</h5>
    </div>
  </div>
  <div class="col-xl-1 col-md-1 col-sm-2 col-2">
    <span class="float-end" href="#" role="button" data-bs-toggle="dropdown">
      <span class="d-flex justify-content-center align-items-center rounded-circle bg-7s" style="height: 50px; width: 50px;">
        <i class="fa fa-lg fa-bars icon-color"></i>
      </span>
    </span>
    @auth
    <ul class="dropdown-menu bg-7g">
      <a onclick="loadContent('{{route('dashboard')}}')" href="{{route('dashboard')}}" class="dropdown-item">
        <i class="fas fa-desktop"></i> @lang('navbar.dashboard')
      </a>
      <a onclick="loadContent('{{route('getGraph')}}')" href="{{route('getGraph')}}"  class="dropdown-item">
        <i class="fas fa-chart-bar"></i> @lang('navbar.graph')
      </a>
      <a onclick="loadContent('{{route('messages')}}')" href="{{route('messages')}}"  class="dropdown-item">
        <i class="fas fa-envelope"></i> @lang('navbar.messages')
      </a>
      <a onclick="loadContent('{{route('myaccount')}}')" href="{{route('myaccount')}}"  class="dropdown-item">
        <i class="fas fa-user"></i> @lang('navbar.myaccount')
      </a>
      <a onclick="loadContent('{{route('settings')}}')" href="{{route('settings')}}"  class="dropdown-item">
        <i class="fas fa-cog"></i> @lang('navbar.settings')
      </a>
      <a onclick="loadContent('{{route('support')}}')" href="{{route('support')}}"  class="dropdown-item">
        <i class="fas fa-info-circle"></i> @lang('navbar.support')
      </a>
      <a onclick="loadContent('https://portal.7sense.no/demo_uk')" href="https://portal.7sense.no/demo_uk"  class="dropdown-item">
        <i class="fas fa-list"></i> @lang('Demo')
      </a>
      <hr class="my-0 mx-3">
      <a class="dropdown-item" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> @lang('navbar.logout')
      </a>
      
      <form id="logout-form" action="/logout" method="POST" style="display: none;"> @csrf </form>
      @if (Auth::user()->roletype_id_ref > 80)
        <hr class="my-0 mx-3">
        <a onclick="loadContent('{{route('selectuser')}}')" href="{{route('selectuser')}}" class="dropdown-item">
          <i class="fas fa-sync-alt"></i> @lang('navbar.selectuser')
        </a>
        <a onclick="loadContent('{{route('admin')}}')" href="{{route('admin')}}" class="dropdown-item">
          <i class="fas fa-lock"></i> @lang('navbar.admin')
        </a>
      @endif
    </ul>
    @endauth
  </div>
</div>
<img class="logo-small" src="{{asset('img/7sense-7-white.png')}}" alt="icon" id="btn" style="max-height:100px; width: auto;">
<img class="logo-full" src="{{asset('img/7sense-logo-white.png')}}" alt="icon" id="sidebar_close" style="max-height:100px; width: auto;">
<hr class="divider">
<ul class="sidebar-list">
    <li class="sidebar-item">
        <a onclick="loadContent('{{route('dashboard')}}')" href="{{route('dashboard')}}">
            <i class="fa fa-2x fa-desktop"></i>
            <span class="links_name">@lang('navbar.dashboard')</span>
        </a>
        <span class="tooltip-right">@lang('navbar.dashboard')</span>
    </li>
    <li class="sidebar-item">
        <a onclick="loadContent('{{route('getGraph')}}')" href="{{route('getGraph')}}">
            <i class="fa fa-2x fa-chart-bar"></i>
            <span class="links_name">@lang('navbar.graph')</span>
        </a>
        <span class="tooltip-right">@lang('navbar.graph')</span>
    </li>
    <li class="sidebar-item">
        <a onclick="loadContent('{{route('messages')}}')" href="{{route('messages')}}">
            <i class="fa fa-2x fa-envelope"></i>
            <span class="links_name">@lang('navbar.messages')</span>
        </a>
        <span class="tooltip-right">@lang('navbar.messages')</span>
    </li>
    <li class="sidebar-item">
        <a onclick="loadContent('{{route('settings')}}')" href="{{route('settings')}}">
            <i class="fas fa-2x fa-cog"></i>
            <span class="links_name">@lang('navbar.settings')</span>
        </a>
        <span class="tooltip-right">@lang('navbar.settings')</span>
    </li>
    @auth
        @if (Auth::user()->roletype_id_ref > 80)
            <hr class="divider-2">
            {{-- <li class="sidebar-item">
                <a href="/select">
                    <i class="fas fa-2x fa-sync-alt"></i>
                    <span class="links_name">@lang('navbar.selectuser')</span>
                </a>
                <span class="tooltip-right">@lang('navbar.selectuser')</span>
            </li> --}}
            <li class="sidebar-item">
                <a onclick="loadContent('{{route('admin')}}')" href="{{route('admin')}}">
                    <i class="fa fa-2x fa-lock"></i>
                    <span class="links_name">@lang('navbar.admin')</span>
                </a>
                <span class="tooltip-right">@lang('navbar.admin')</span>
            </li>
            {{-- <li class="sidebar-item">
                <a href="/admin/customer">
                    <i class="fa fa-2x fa-address-card"></i>
                    <span class="links_name">Customer</span>
                </a>
                <span class="tooltip-right">Customer</span>
            </li>
            <li class="sidebar-item">
                <a href="/admin/proxy">
                    <i class="fa fa-2x fa-server"></i>
                    <span class="links_name">Tek-Zence</span>
                </a>
                <span class="tooltip-right">Tek-Zence</span>
            </li> --}}
        @endif
    @endauth
</ul>

    
<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let close_Btn = document.querySelector("#sidebar_close");

    closeBtn.addEventListener("click", ()=>{
        sidebar.classList.toggle("open");
        menuBtnChange();//calling the function(optional)
    });

    close_Btn.addEventListener("click", ()=>{
        sidebar.classList.toggle("open");
        menuBtnChange();//calling the function(optional)
    });
    

    // following are the code to change sidebar button(optional)
    function menuBtnChange() {
        if(sidebar.classList.contains("open")){
            closeBtn.classList.replace("navbar-brand", "logo_name");//replacing the iocns class
        }else {
            closeBtn.classList.replace("logo_name","navbar-brand");//replacing the iocns class
        }
    }

</script>
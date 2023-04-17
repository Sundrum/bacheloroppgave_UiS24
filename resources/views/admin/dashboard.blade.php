@extends('layouts.app')

@section('content')

<section class="">
  <div id="admin" onclick="rotateImg('arrowadmin')" class="row card card-rounded " data-toggle="collapse" data-target="#collapseadmin" aria-expanded="true" aria-controls="collapseadmin">
      <div class="row mt-3 mb-3">
          <div class="pl-5 pt-2 col-11">
            <h4 class="v-align"><strong>@lang('admin.administrator')</strong></h4>
          </div>
          <div class="col-1 align-self-center text-end">
              <img id="arrowadmin" data-toggle="collapse" data-target="#collapseadmin" aria-expanded="true" aria-controls="collapseadmin" src="{{ asset('img/expand.svg') }}">
          </div>
      </div>
  </div>
  <div id="collapseadmin" class="collapse show" aria-labelledby="headingOne" data-parent="#admin">
    @include('admin.dashboard.portal')
</section>

<section class="">
  <div id="installer" onclick="rotateImg('arrowinstaller')" class="row card card-rounded mt-4" data-toggle="collapse" data-target="#collapseinstaller" aria-expanded="true" aria-controls="collapseinstaller">
      <div class="row mt-3 mb-3">
          <div class="pl-5 pt-2 col-11">
            <h4 class="v-align"><strong>Resources</strong></h4>
          </div>
          <div class="col-1 align-self-center text-end">
              <img id="arrowinstaller" data-toggle="collapse" data-target="#collapseinstaller" aria-expanded="true" aria-controls="collapseinstaller" src="{{ asset('img/expand.svg') }}">
          </div>
      </div>
  </div>
  <div id="collapseinstaller" class="collapse show" aria-labelledby="headingOne" data-parent="#installer">
    @include('admin.dashboard.resource')
  </div>
</section>

<section class="">
    <div id="development" onclick="rotateImg('arrowdev')" class="row card card-rounded mt-4" data-toggle="collapse" data-target="#collapsedev" aria-expanded="true" aria-controls="collapsedev">
        <div class="row mt-3 mb-3">
            <div class="pl-5 pt-2 col-11">
              <h4 class="v-align"><strong>Development</strong></h4>
            </div>
            <div class="col-1 align-self-center text-end">
                <img id="arrowdev" data-toggle="collapse" data-target="#collapsedev" aria-expanded="true" aria-controls="collapsedev" src="{{ asset('img/expand.svg') }}">
            </div>
        </div>
    </div>
    <div id="collapsedev" class="collapse show" aria-labelledby="headingOne" data-parent="#development">
      @include('admin.dashboard.development')
    </div>
  </section>
  @include('admin.firmware.upload')
<script>
  setTitle('Admin Dashboard');
</script>
@endsection
<div class="row justify-content-end">

    <div class="col mb-1">
        <i  class="fa fa-3x fa-info-circle fa-fw float-end"
            style="margin-right: 10px;"
            data-toggle="modal" onclick="showInfoBox();" data-target="#myInfowindow">
        </i>
        @if(count(Session::get('irrigation')) > 1)
            <a onclick="loadContent('{{route('fleetmanagment')}}')" href="{{route('fleetmanagment')}}"><button class="btn-7g float-end" href=""><strong>@lang('general.fleetmanagment')</strong></button></a>
        @endif
    </div>
</div>

<div class="modal fade" id="myInfowindow"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('dashboard.irrinfo')</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_0.png" class="float-left" width="50"> </td>
                        <td class="tdspace"> @lang('dashboard.nocontact')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_1.png" class="float-left"  width="50"> </td>
                        <td class="tdspace"> @lang('dashboard.sleepmode')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_4.png" class="float-left"  width="50"> </td>
                        <td class="tdspace"> @lang('dashboard.waitingforwater')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_5.png" class="float-left"  width="50"> </td>
                        <td class="tdspace"> @lang('dashboard.irrigationmode')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="50"></td>
                        <td class="tdspace"> @lang('dashboard.angle')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="50"></td>
                        <td class="tdspace"> @lang('dashboard.eta')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="50"></td>
                        <td class="tdspace"> @lang('dashboard.speed')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="50"></td>
                        <td class="tdspace"> @lang('dashboard.remaining')</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="50"></td>
                        <td class="tdspace"> @lang('dashboard.pressure')</td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('dashboard.close')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showInfoBox(){
        $('#myInfowindow').toggle();
    }
</script>
@extends('layouts.app')

@section('content')
<div class="row justify-content-end">
    <div class="col-12 float-end">
        <svg class="info-btn svg-inline--fa fa-info-circle fa-w-16 fa-3x fa-fw float-end" style="margin-right: 10px;" data-toggle="modal" data-target="#myInfowindow" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
            <path fill="#212529" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
        </svg>
        <img class="btn-flag image-responsive float-end m-1" width="50" src="https://storage.portal.7sense.no/images/dashboardicons/uk-flag.png">

        <img class="btn-flag image-responsive float-end m-1"width="50" src="https://storage.portal.7sense.no/images/dashboardicons/nor-flag.png" onclick="window.location='demo_norway';">
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
                        <td class="tdspace"> No contact</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_1.png" class="float-left"  width="50"> </td>
                        <td class="tdspace"> Sleep mode</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_4.png" class="float-left"  width="50"> </td>
                        <td class="tdspace">Waiting for water</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td> <img src="../img/irrigation/state_5.png" class="float-left"  width="50"> </td>
                        <td class="tdspace"> Irrigation mode</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/tilt.png" width="50"></td>
                        <td class="tdspace"> Tilt angle</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/eta.png" width="50"></td>
                        <td class="tdspace"> Estimated time of arrival</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/speed.png" width="50"></td>
                        <td class="tdspace"> Speed</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/distance.png" width="50"></td>
                        <td class="tdspace"> Remaining meters</td>
                    </tr>
                    <tr class="spaceUnder">
                        <td><img class="image-responsive" src="https://storage.portal.7sense.no/images/dashboardicons/gas.png" width="50"></td>
                        <td class="tdspace"> Pressure</td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn-7g" data-dismiss="modal">Close</button>
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

<div class="row">
    <div class="col-6">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
</div>
@endsection

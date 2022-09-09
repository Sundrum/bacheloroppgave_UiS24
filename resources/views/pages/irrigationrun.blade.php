@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry"> type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('/js/map/utilities.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/snaptoroute.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/markers.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/oldruns.js') }}"></script>


@section('content')
 <div class="container" style="position: relative;">
    @if (isset($message))
    <div class="alert alert-secondary">{{ $message }}</div>
    @endif
    @if (isset($errormessage))
    <div class="alert alert-danger">{{ $errormessage }}</div>
    @endif
    <div id="map"></div>
    @if(is_array($data) && count($data) > 0)
        <div class="overlap" style="position: absolute; top:65px; margin-left:10px; width:50px; z-index:10; background: white;">
            <img class="image-responsive" onclick="$( '#infowindow' ).toggle();" src="../img/info_60x60.png" width="40" alt="" style="margin-left: 5px;">
        </div>
        <div id="infowindow" class="overlap" style="position: absolute; top:70px; width:250px; margin-left:60px; z-index:2; background: white; display:none;">
            <table>
                <tr class="spaceUnder">
                    <td><img class="image-responsive" src="../img/irr_icon_start.png" width="50"></td>
                    <td class="tdspace"> @lang('map.startpoint')</td>
                </tr>
                <tr class="spaceUnder">
                    <td><img class="image-responsive" src="../img/irr_icon_present.png" width="50"></td>
                    <td class="tdspace"> @lang('map.current')</td>
                </tr>
                <tr class="spaceUnder">
                    <td><img class="image-responsive" src="../img/irr_icon_poi.png" width="50"></td>
                    <td class="tdspace"> @lang('map.poi')</td>
                </tr>
                <tr class="spaceUnder">
                    <td><img class="image-responsive" src="../img/irr_icon_final.png" width="50"></td>
                    <td class="tdspace"> @lang('map.endpoint')</td>
                </tr>
            </table>
        </div>
    @endif
</div>
@endsection

<script>
    var token = "{{ csrf_token() }}";
    var positions = Array();
    var serial = "{{ $serial }}";

        function initMap() {
            var mapDiv = document.getElementById('map');
            map = new google.maps.Map(mapDiv, {
                // center: center,
                mapTypeId: 'satellite',
                mapTypeControl: true,
                zoom: 17,
                streetViewControl: false,
                tilt: 0
            });
        
            getOldRuns(serial, 380);
            
            map.addListener('zoom_changed', function() {
                setZoomValues();
            });
        }	// end initMap

        google.maps.event.addDomListener(window, "load", initMap);

        var infowindow = new google.maps.InfoWindow();
        function infoCallback(infowindow) {
            return function() {
                infowindow.open(map);
            };
        }
</script>
@extends('layouts.app')

@section('content')

 <div class="container" style="position: relative;">
    {{-- @if (isset($message))
    <div class="alert alert-secondary">{{ $message }}</div>
    @endif
    @if (isset($errormessage))
    <div class="alert alert-danger">{{ $errormessage }}</div>
    @endif --}}

    <div id="map"></div>
    @if(isset($irrigationrun['irrigation_startpoint']))
        <div class="overlap" style="position: absolute; top:90px; margin-left:10px; width:50px; z-index:10; background: white;">
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
        <div class="overlap" style="position: absolute; top:50px; margin-left:9px; z-index:10;">
            <div class="form-group">
                <input class="col-5 col-md-6 col-form-label" type="number" placeholder="Distance" id="meter" name="meter">
            </div>
        </div>
    @endif
    @if(isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0' )
            <div class="overlap" style="position: absolute; top:135px; margin-left:10px; width:50px; z-index:2; background: white;">
                <img class="image-responsive" onclick="addPOI(0,0,1);" src="../img/poi1_btn.png" width="40" alt="" style="margin-left: 5px;">
            </div>
            <div class="overlap" style="position: absolute; top:160px; margin-left:10px; width:50px; z-index:2; background: white;">
                <img class="image-responsive" onclick="addPOI(0,0,2);" src="../img/poi2_btn.png" width="40" alt="" style="margin-left: 5px;">
            </div>
    @endif
</div>

<script>
    var token = "{{ csrf_token() }}";
    var positions = Array();
    var serial = "{{ $serial }}";
    var setEndpoint = "@lang('map.setend')";
    var delEndpoint = "@lang('map.delend')";
    var setPOI = "@lang('map.setpoi')";
    var delPOI = "@lang('map.delpoi')";
    var days = 60;
    var user_id = @json(Auth::user()->user_id);
    if (user_id == 22) {
        days = 3;
    }
    @if(isset($data))
        var count = "<?php echo count($data); ?>";
    @else
        var count = 0;
    @endif


    @if(isset($irrigationrun['irrigation_startpoint']))
        var firstpoint = new google.maps.LatLng(<?php echo trim($irrigationrun['irrigation_startpoint']); ?>);
        var center = new google.maps.LatLng(<?php echo trim($irrigationrun['irrigation_startpoint']); ?>);
    @elseif(isset($phone_lat_lng))
        var center = new google.maps.LatLng(<?php echo trim($phone_lat_lng); ?>);
    @endif
    @if(isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0')
        var endpoint = new google.maps.LatLng(<?php echo trim($variables['irrigation_endpoint']); ?>);
        var center = new google.maps.LatLng(<?php echo trim($variables['irrigation_endpoint']); ?>);
        console.log(endpoint);
    @endif
    console.log(count);
    
    @if((isset($irrigationrun['irrigation_startpoint'])) || (isset($phone_lat_lng)) || (isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0'))
        function initMap() {
            var mapDiv = document.getElementById('map');
            map = new google.maps.Map(mapDiv, {
                center: center,
                mapTypeId: 'satellite',
                mapTypeControl: true,
                zoom: 17,
                streetViewControl: false,
                tilt: 0
            });
            
            @php
                echo 'google.maps.event.addListenerOnce(map, "idle", function(){';	// do something only the first time the map is loaded
                    if (isset($phone_lat_lng)) {
                        echo 'map.panTo(new google.maps.LatLng('.$phone_lat_lng.'));';
                        echo 'showContextMenu(new google.maps.LatLng('.$phone_lat_lng.'));';
                    } else if (isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0') {
                        echo "map.panTo(new google.maps.LatLng(".$variables['irrigation_endpoint']."));";
                        //echo 'showContextMenu(new google.maps.LatLng('.$phone_lat_lng.'));';
                    }
                echo '});';

                if (isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0') {
                    echo "addEndMarker(new google.maps.LatLng(".$variables['irrigation_endpoint']."));";
                }

                echo 'google.maps.event.addListenerOnce(map, "projection_changed", function(){';
                    if (isset($variables['irrigation_poi_1']) && $variables['irrigation_poi_1'] != '0,0') {
                        echo "addPOI(".$variables['irrigation_poi_1'].", 1);";
                    }
                    if (isset($variables['irrigation_poi_2']) && $variables['irrigation_poi_2'] != '0,0') {
                        echo "addPOI(".$variables['irrigation_poi_2'].", 2);";
                    }
                echo '});';	
            @endphp
            
            getLatestRun(serial);
            getOldRuns(serial, days);
            getDistance();
            map.addListener("click", function(e){
                if (!endMarker) {
                    showContextMenu(e.latLng);
                }
            });

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
    @endif
</script>
@endsection
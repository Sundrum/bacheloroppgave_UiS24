@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('/js/map/utilities.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/snaptoroute.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/markers.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/map/oldruns.js') }}"></script>
@section('content')


<div class="row bg-white card-rounded">
    @if(isset($irrigationrun['irrigation_startpoint']))
        <div class="col-3 col-md">
            <span>
                Distance: <span id="meter" name="meter"></span>
            </span>
        </div>
        @if(isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0' )
            <div class="col">
                <button class="btn-7s" onclick="addPOI(0,0,1);">SMS<sub>1</sub></button>
            </div>
            <div class="col">
                <button class="btn-7s" onclick="addPOI(0,0,2);">SMS<sub>2</sub></button>
            </div>
        @endif
        <div class="col">
            <div class="float-end">
                <img class="image-responsive float-right" role="button" href="#" data-bs-toggle="dropdown" src="../img/info_60x60.png" width="40" alt="">
                <ul class="dropdown-menu bg-white">
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/start.png" width="40"> @lang('map.startpoint')
                    </li>
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/current.svg" width="40"> @lang('map.current')
                    </li>
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/sms.png" width="40"> @lang('map.poi')
                    </li class="px-3">
                    <li class="px-3">
                        <img class="image-responsive" src="../img/irrigation/finish.png" width="40"> @lang('map.endpoint')
                    </li>
                </ul>
            </div>
        </div>
    @endif
</div>
<div id="map"></div>

<script>
    setTitle(@json($serial));
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
        console.log(firstpoint);
        var center = new google.maps.LatLng(<?php echo trim($irrigationrun['irrigation_startpoint']); ?>);
    @elseif(isset($phone_lat_lng))
        var center = new google.maps.LatLng(<?php echo trim($phone_lat_lng); ?>);
    @endif
    @if(isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0')
        var endpoint = new google.maps.LatLng(<?php echo trim($irrigationrun['irrigation_endpoint']); ?>);
        var center = new google.maps.LatLng(<?php echo trim($irrigationrun['irrigation_endpoint']); ?>);
        console.log(endpoint);
    @endif
    
    @if((isset($irrigationrun['irrigation_startpoint'])) || (isset($phone_lat_lng)) || (isset($variables['irrigation_endpoint']) && $variables['irrigation_endpoint'] != '0,0'))
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
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

        function getPointOfInterest() {
            @php
                echo 'google.maps.event.addListenerOnce(map, "idle", function(){';
                    if (isset($variables['irrigation_poi_1']) && $variables['irrigation_poi_1'] != '0,0') {
                        echo "addPOI(".$variables['irrigation_poi_1'].", 1);";
                    }
                    if (isset($variables['irrigation_poi_2']) && $variables['irrigation_poi_2'] != '0,0') {
                        echo "addPOI(".$variables['irrigation_poi_2'].", 2);";
                    }
                echo '});';	
            @endphp
        }
    @endif
</script>

@endsection

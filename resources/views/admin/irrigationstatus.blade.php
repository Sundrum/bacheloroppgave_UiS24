@extends('layouts.app')

@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<section class="bg-white card-rounded">
    <div class="m-3">
        <div class="col-12 pt-3">
            <div id="map" style="height: 580px; border-radius: 25px;"></div>
        </div>
        <div class="col-12 mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                        Off Season
                    </div>
                  </div>
                  <div class="row justify-content-center">
                      <div class="col">
                          <img src="{{asset('/img/irrigation/state_7.png')}}" onclick="setSearch('state7');">
                      </div>
                  </div>
                  <div class="row justify-content-center" id="off_season">
                      <div class="col text-center">
                          {{$variable['off_season'] ?? '0'}}
                      </div>
                  </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                        Post Settling
                    </div>
                  </div>
                  <div class="row justify-content-center">
                      <div class="col">
                          <img src="{{asset('/img/irrigation/state_6.png')}}" onclick="setSearch('state6');">
                      </div>
                  </div>
                  <div class="row justify-content-center" id="post_settling">
                      <div class="col text-center">
                          {{$variable['post_settling'] ?? '0'}}
                      </div>
                  </div>
                </div>

                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Irrigation
                    </div>
                  </div>
                  <div class="row justify-content-center">
                      <div class="col">
                          <img src="{{asset('/img/irrigation/state_5.png')}}" onclick="setSearch('state5');">
                      </div>
                  </div>
                  <div class="row justify-content-center" id="irrigation">
                      <div class="col">
                          {{$variable['irrigation'] ?? '0'}}
                      </div>
                  </div>
                </div>

                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Pre Settling
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_4.png')}}" onclick="setSearch('state4');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="settling">
                        <div class="col">
                            {{$variable['settling'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Idle Activity
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_3.png')}}" onclick="setSearch('state3');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_activity">
                        <div class="col">
                            {{$variable['idle_activity'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Idle Clock Wait
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_2.png')}}" onclick="setSearch('state2');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_clock_wait">
                        <div class="col">
                            {{$variable['idle_clock_wait'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Idle
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_1.png')}}" onclick="setSearch('state1');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle_green">
                        <div class="col">
                            {{$variable['idle_green'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Idle
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state_0.png')}}" onclick="setSearch('state0');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="idle">
                        <div class="col">
                            {{$variable['idle'] ?? '0'}}
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                  <div class="row justify-content-center">
                    <div class="col text-center">
                      Production
                    </div>
                  </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <img src="{{asset('/img/irrigation/state.png')}}" onclick="setSearch('state-1');">
                        </div>
                    </div>
                    <div class="row justify-content-center" id="notused">
                        <div class="col">
                            {{$variable['notused'] ?? 0}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-3">
        <div id="table-section">
            <table id="irrtable" class="display" width="100%"></table>
        </div>
    </div>
</section>

<!-- Datatables script  -->
<script type="module">
    setTitle('Irrigation Overview');
    var table; 
    $(document).ready(function () {
        let dataSet = @php echo $data; @endphp;
        table = $('#irrtable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            data: dataSet,
            pageLength: 25, // Number of entries
            responsive: true, // For mobile devices
            sorting: [ [0,'ASC'],[5,'ASC']],
            columnDefs : [{ 
                responsivePriority: 1, targets: 5,
                'targets': 0,
                'checboxes': {
                    'selectRow': true
                },
            }],
            'select': {
                style: 'multi'
            },
            columns: [
                { 
                    title: "Status",
                    width: "5%"
                },
                { title: "Serienummer" },
                { title: "Navn" },
                { title: "Kunde" },
                { title: "Seq" },
                { title: "Resetcode" },
                { title: "Reset count" },
                { title: "Siste levert" },
                { title: "", orderable: false, searchable: false },
            ],
        });
        $('#irrtable tbody').on( 'click', 'tr', function () {
            var datarow = table.row(this).data();
            var id = datarow[1];
            console.log(id);
            window.location='/admin/irrigationstatus/'+id;
        });
    });

    function setSearch(text){
        $('html, body').animate({
            scrollTop: $("#table-section").offset().top
        }, 1000);
        var search = table.search();
        search += ' ' + text;
        table.search(search).draw();
    }
    setTimeout(function(){updateStatus();}, 180000);

    function updateStatus() {
        $.ajax({
            url: '/admin/irrigationstatusupdate',
            dataType: 'json',      
            data: {
                "_token": token,
            }, 
            success: function( data ) {
                $('#notused').html(data.variable.notused);
                $('#idle').html(data.variable.idle);
                $('#idle_green').html(data.variable.idle_green);
                $('#settling').html(data.variable.settling);
                $('#irrigation').html(data.variable.irrigation);
                $('#post_settling').html(data.variable.post_settling);
                $('#off_season').html(data.variable.off_season);
                console.log(data);
                $('#irrtable').DataTable().clear().destroy();
                table = $('#irrtable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ],
                    data: data.data,
                    pageLength: 25, // Number of entries
                    responsive: true, // For mobile devices
                    sorting: [ [0,'ASC'],[5,'ASC']],
                    columnDefs : [{ 
                        responsivePriority: 1, targets: 5,
                        'targets': 0,
                        'checboxes': {
                            'selectRow': true
                        },
                    }],
                    'select': {
                        style: 'multi'
                    },
                    columns: [
                        { 
                            title: "Status",
                            width: "5%"
                        },
                        { title: "Serienummer" },
                        { title: "Navn" },
                        { title: "Kunde" },
                        { title: "Seq" },
                        { title: "Resetcode" },
                        { title: "Reset count" },
                        { title: "Siste levert" },
                        { title: "", orderable: false, searchable: false },
                    ],
                });
                getActive();
                setTimeout(function(){updateStatus();}, 180000);
            },
            error: function( data ) {
                errorMessage('Auto-refresh error');
                setTimeout(function(){updateStatus();}, 120000);
            }
        });
    }

    import { MarkerClusterer } from "https://cdn.skypack.dev/@googlemaps/markerclusterer@2.0.3";
    
    let markers;
    let map;
    let infoWindow;
    let cluster;
    
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 59.390982, lng: 10.460590},
            mapTypeControl: false,
            zoom: 16,
            streetViewControl: false,
            tilt: 0,
            styles: styleArray
        });
    
        infoWindow = new google.maps.InfoWindow({
            content: "",
            disableAutoPan: true,
        });
    
        getActive();
    }
    
    window.initMap = initMap;
    
    function getActive() {
        $.ajax({
            url: '/admin/map/irrigationstatus',
            dataType: 'json',      
            data: {
                "_token": token,
            }, 
            success: function( data ) {
                addMarkers(data);
            },
            error: function( data ) {
                errorMessage("Unable to load map");
            }
        });
    }
    
    function addMarkers(activeLatLng){
      let bounds = new google.maps.LatLngBounds();
      let markers = activeLatLng.map((data, i) => {
        if(data['lat'] && data['lng']) {
            let label = data['serialnumber'];
            let marker = new google.maps.Marker({
                position: new google.maps.LatLng(data['lat'], data['lng']),
                icon: new google.maps.MarkerImage("/img/irrigation/marker_state_"+data['state']+".png", null, null, null, new google.maps.Size(27,42.75)),
                map: map
            });
            bounds.extend(marker.position);
            
            marker.addListener("click", () => {
                infoWindow.setContent(label);
                infoWindow.open(map, marker);
            });
            return marker;
        }
      });
      // // Add a marker clusterer to manage the markers.
      if (cluster) {
        cluster.clearMarkers();
        cluster = null;
      } else {
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
      }

      cluster = new MarkerClusterer({ map, markers });
    
    }
    
    let styleArray = [
      {
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#f5f5f5"
          }
        ]
      },
      // {
      //   "elementType": "labels",
      //   "stylers": [
      //     {
      //       "visibility": "off"
      //     }
      //   ]
      // },
      {
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#616161"
          }
        ]
      },
      {
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "color": "#f5f5f5"
          }
        ]
      },
      {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "administrative.land_parcel",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#bdbdbd"
          }
        ]
      },
      {
        "featureType": "administrative.neighborhood",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#eeeeee"
          }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#757575"
          }
        ]
      },
      {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#e5e5e5"
          }
        ]
      },
      {
        "featureType": "poi.park",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#9e9e9e"
          }
        ]
      },
      // {
      //   "featureType": "road",
      //   "stylers": [
      //     {
      //       "visibility": "off"
      //     }
      //   ]
      // },
      {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      },
      {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road.arterial",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#757575"
          }
        ]
      },
      {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#dadada"
          }
        ]
      },
      {
        "featureType": "road.highway",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#616161"
          }
        ]
      },
      {
        "featureType": "road.local",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#9e9e9e"
          }
        ]
      },
      {
        "featureType": "transit",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "transit.line",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#e5e5e5"
          }
        ]
      },
      {
        "featureType": "transit.station",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#eeeeee"
          }
        ]
      },
      {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#c9c9c9"
          }
        ]
      },
      {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#9e9e9e"
          }
        ]
      }
    ];
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5ES3cEEeVcDzibri1eYEUHIOIrOewcCs&language=en&libraries=geometry&callback=initMap" defer></script>

@endsection
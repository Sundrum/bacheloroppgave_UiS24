@extends('layouts.app')

<script src="{{ asset('js/three/three.js') }}"></script>
<script src="{{ asset('js/three/loaders/STLLoader.js') }}"></script>

@section('content')
<section class="">
    <h1 class="text-center"> XYZ - Development </h1>
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-3 mb-3">
                @foreach ($data as $row)
                    <div class="col-md-6 mt-2 mb-2">
                        <div class="card card-rounded" style="background: {{$row['color'] ?? ''}}; color: {{$row['text-color'] ?? ''}};">
                            <h3 class="text-center">{{$row['serial']}}</h3>
                            <p class="text-center"> Tid: {{$row['time']}}</p>
                            
                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <h5>X = {{$row['xdeg']}}</h5>
                                        <h5 class="text-center">( {{$row['xorg']}} )</h5>
                                        <h5 class="text-center">{{round($row['x'],4)}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Y = {{$row['ydeg']}}</h5>
                                        <h5 class="text-center">( {{$row['yorg']}} )</h5>
                                        <h5 class="text-center">{{round($row['y'],4)}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Z = {{$row['zdeg']}}</h5>
                                        <h5 class="text-center">( {{$row['zorg']}} )</h5>
                                        <h5 class="text-center">{{round($row['z'],4)}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="">
        <div class="col-md-12" id="sizing">
            <div class="row justify-content-center">
                <div id="xyz" class="card card-rounded"></div>
            </div>
        </div>
    </div>
</section>

<script>
var container;
var camera, cameraTarget, scene, renderer;

var x_data = "{{ $data[0]['x'] }}";
var y_data = "{{ $data[0]['y'] }}";
var z_data = "{{ $data[0]['z'] }}";

init();

function init() {
    container = document.getElementById('xyz');
    camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 5000 );
    // camera.position.set( -2.4, -0.1, -0.1 );
    camera.position.set( -5, 0, 0 );

    cameraTarget = new THREE.Vector3( 0, -0.25, 0 );

    scene = new THREE.Scene();
    scene.fog = new THREE.Fog( 0xFFFFFF, 2, 15 );
    renderer = new THREE.WebGLRenderer( { antialias: true } );
    renderer.setClearColor( scene.fog.color );
    renderer.setPixelRatio( window.devicePixelRatio );
    //renderer.setSize( window.innerWidth - 300, window.innerHeight - 300 );
    renderer.setSize(700, 500);

    renderer.gammaInput = true;
    renderer.gammaOutput = true;

    renderer.shadowMap.enabled = true;
    renderer.shadowMap.renderReverseSided = false;


    var loader = new THREE.STLLoader();
    loader.load( '../img/wmcsensor.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0x228B22, specular: 0x808080, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0, 0, 0 );
        mesh.rotation.set( x_data , y_data , z_data );
        mesh.scale.set( 0.006, 0.006, 0.006 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add( mesh );
        camera.lookAt( cameraTarget );
        renderer.render( scene, camera );

    } );


    // Lights
    addShadowedLight( -1, 1, 1, 0x808080, 0.7 );
    addShadowedLight( 0, 1, -1, 0x808080, 1 );

    container.appendChild( renderer.domElement );


    window.addEventListener( 'resize', onWindowResize, false );


}

function addShadowedLight( x, y, z, color, intensity ) {

    var directionalLight = new THREE.DirectionalLight( color, intensity );
    directionalLight.position.set( x, y, z );
    scene.add( directionalLight );

    // directionalLight.castShadow = true;

    var d = 1;
    directionalLight.shadow.camera.left = -d;
    directionalLight.shadow.camera.right = d;
    directionalLight.shadow.camera.top = d;
    directionalLight.shadow.camera.bottom = -d;

    directionalLight.shadow.camera.near = 1;
    directionalLight.shadow.camera.far = 4;


    // directionalLight.shadow.bias = -0.005;

}

// $(document).ready(function() {
//   setInterval(function() {
//     cache_clear()
//   }, 9000);
// });

// function cache_clear() {
//   window.location.reload(true);
//   // window.location.reload(); use this if you do not remove cache
// }

function onWindowResize() {
    console.log(document.getElementById('sizing').offsetHeight);
    console.log(document.getElementById('sizing').offsetWidth);
    var divHeight = document.getElementById('sizing').offsetHeight;
    var divWidth = document.getElementById('sizing').offsetWidth

    // camera.aspect = window.innerWidth / window.innerHeight;
    camera.aspect = divHeight / divWidth;

    camera.updateProjectionMatrix();

    // renderer.setSize( window.innerWidth, window.innerHeight );
    renderer.setSize( divHeight, divWidth );


}
</script>
@endsection
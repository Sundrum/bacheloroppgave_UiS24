@extends('layouts.app')

@section('content')
<script src="{{ asset('js/three/three.js') }}"></script>
<script src="{{ asset('js/three/loaders/STLLoader.js') }}"></script>
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

var x_data_1 = "{{ $data[1]['x'] }}";
var y_data_1 = "{{ $data[1]['y'] }}";
var z_data_1 = "{{ $data[1]['z'] }}";

var x_data_2 = "{{ $data[2]['x'] }}";
var y_data_2 = "{{ $data[2]['y'] }}";
var z_data_2 = "{{ $data[2]['z'] }}";

var x_data_3 = "{{ $data[3]['x'] }}";
var y_data_3 = "{{ $data[3]['y'] }}";
var z_data_3 = "{{ $data[3]['z'] }}";

// var x_data_3 = 91;
// var y_data_3 = 40;
// var z_data_3 = 3.14;

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

        mesh.position.set( 0, 0, -1.7 );
        mesh.rotation.set( y_data , z_data , x_data  );
        mesh.scale.set( 0.006, 0.006, 0.006 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add( mesh );
        camera.lookAt( cameraTarget );
        renderer.render( scene, camera );

    } );
    loader.load( '../img/wmcsensor.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0x4682B4, specular: 0x808080, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0, 0, -0.6 );
        mesh.rotation.set( y_data_1, z_data_1,  x_data_1);
        mesh.scale.set( 0.006, 0.006, 0.006 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add( mesh );
        camera.lookAt( cameraTarget );
        renderer.render( scene, camera );

    } );
    loader.load( '../img/wmcsensor.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0xFED16D, specular: 0xFFFFFF, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0, 0, 0.6 );
        mesh.rotation.set( y_data_2 , z_data_2 , x_data_2  );
        mesh.scale.set( 0.006, 0.006, 0.006 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add( mesh );
        camera.lookAt( cameraTarget );
        renderer.render( scene, camera );

    } );
    loader.load( '../img/wmcsensor.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0xFFFFff, specular: 0x808080, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0, 0, 1.7 );
        mesh.rotation.set( y_data_3 , z_data_3 , x_data_3  );
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
<section class="">
    <h1 class="text-center">{{$row['serial']}}</h1>
    <p class="text-center"> Tid: {{$row['time']}}</p>

    <div class="col-md-12">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <h3>X = {{$row['xdeg']}}</h3>
            </div>
            <div class="col-md-3">
                <h3>Y = {{$row['ydeg']}}</h3>
            </div>
            <div class="col-md-3">
                <h3>Z = {{$row['zdeg']}}</h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="col-md-12" id="sizing">
            <div class="row justify-content-center">
                <div id="xyz"></div>
            </div>
        </div>
    </div>

<script>
var container;
var camera, cameraTarget, scene, renderer;

var x_data = "{{ $row['x'] }}";
var y_data = "{{ $row['y'] }}";
var z_data = "{{ $row['z'] }}";


init();
animate();

function init() {
    container = document.getElementById('xyz');
    camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 15 );
    camera.position.set( -2.5, -0.4, -0.1 );

    cameraTarget = new THREE.Vector3( 0, -0.25, 0 );

    scene = new THREE.Scene();
    scene.fog = new THREE.Fog( 0xFFFFFF, 2, 15 );

    // Ground

    // var plane = new THREE.Mesh(
    //     new THREE.PlaneBufferGeometry( 40, 40 ),
    //     new THREE.MeshPhongMaterial( { color: 0x999999, specular: 0x101010 } )
    // );
    // plane.rotation.x = 0;
    // plane.position.y = -0.8;
    // scene.add( plane );

    // plane.receiveShadow = true;


    var loader = new THREE.STLLoader();
    loader.load( '../img/wmcsensor.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0xFFFFff, specular: 0x808080, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0.7, 0, 0 );
        mesh.rotation.set( x_data , y_data , z_data  );
        mesh.scale.set( 0.006, 0.006, 0.006 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add( mesh );

    } );


    // Lights

    // scene.add( new THREE.HemisphereLight( 0x443333, 0x111122 ) );

    addShadowedLight( -1, 1, 1, 0x808080, 0.7 );
    addShadowedLight( 0, 1, -1, 0x808080, 1 );
    // renderer

    renderer = new THREE.WebGLRenderer( { antialias: true } );
    renderer.setClearColor( scene.fog.color );
    renderer.setPixelRatio( window.devicePixelRatio );
    //renderer.setSize( window.innerWidth - 300, window.innerHeight - 300 );
    renderer.setSize(700, 500);

    renderer.gammaInput = true;
    renderer.gammaOutput = true;

    renderer.shadowMap.enabled = true;
    renderer.shadowMap.renderReverseSided = false;

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

function animate() {

    requestAnimationFrame( animate );

    render();

}

function render() {

    // var timer = Date.now() * 0.0005;
    // camera.position.x = Math.cos( timer ) * 3;
    // camera.position.z = Math.sin( timer ) * 3;
    var timer = Date.now() * 0.0000005;

    // console.log(Math.cos( timer ) * 3)
    // console.log(Math.sin( timer ) * 3)
    // camera.position.x = -2.5;
    // camera.position.y = -0.4;
    // camera.position.z = 0.1;

    camera.position.x = -2.5;
    camera.position.y = -0.4;
    camera.position.z = 0.1;

    camera.lookAt( cameraTarget );

    renderer.render( scene, camera );

}
</script>
</section>
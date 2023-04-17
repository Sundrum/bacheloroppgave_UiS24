<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<script src="https://code.jquery.com/jquery-3.5.1.js" defer></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>

<link  href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link  href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<style>
.wrapper{
    overflow-x: hidden;
}
.sidebar{
    position: fixed;
    width: 100px;
    height: 98%;
    margin-left: 30px;
    margin-right: 30px;
    margin-top: 1%;
    background: #032350;
    box-shadow: 0px 2px 8px rgba(196, 202, 212, 0.24);
    border-radius: 24px;
}

.sidebar-list{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 0px;
    gap: 20px;
    position: absolute;
    width: 56px;
    left: 20px;
    margin-top: 30px;
    color: white;
}

.sidebar a{
    color:white;
    background: #032350;
    padding:12px;
    width: 60px;
    height: 60px;
    border-radius: 99px;
    justify-content: center;
    align-items: center;
}

.sidebar-list a:hover{
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    color:#032350;
    background: #F3F4F6;
    border-radius: 99px;
}

.divider {
    margin: auto;
    width: 65px;
    border-radius: 24px;
    border: 1px solid #F3F4F6;
}

.sidebar img{
    margin: auto;
    padding: 25px;
}

.content-wrapper {
    margin-left: 160px;
    margin-right: 30px;
}
.topbar {
    margin-top: 0;
    height: 120px;
    text-align: center;
    display: flex;
    flex-direction: row;
    width: 100%;
}

.topbar-h1{
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 33%;
    gap: 200px;
    position: relative;
    height: 80px;
    top: 32px;
}

.topbar-h2{
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    width: 33%;
    position: relative;
    height: 80px;
    top: 32px;
}

.topbar-h3 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    height: 80px;
    width: 33%;
    top: 32px;
}

.topbar-h1 h2 {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 400;
    font-size: 32px;
    line-height: 40px;
    letter-spacing: -0.02em;
    color: #22272F;
    flex: none;
    order: 0;
    flex-grow: 0;
}

.content-main{

}
</style>


<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}"></script>

<title>{{ config('APP_NAME', '7Sense Portal') }}</title>
</head>
<body class="bg-grey">
    <main class="wrapper">
        <section class="sidebar">
            <img src="{{asset('img/7sense-7-white.png')}}" alt="icon" id="btn" style="max-height:100px; width: auto;">
            <hr class="divider">
            <div class="sidebar-list">
                <a href="/admin/customer" class=""><i class="fa fa-2x fa-address-card"></i></a>
                <a href="/admin/customer" class=""><i class="fa fa-2x fa-address-card"></i></a>
            </div>

            {{-- <div class="sidebar-heading"><a class="navbar-brand" href="{{ url('/') }}"><img src="{{asset('img/7-.jpg')}}" alt="brand" style="max-height: 50px; width: auto;"></a></div>
            <div class="list-group">
              <a href="/admin/customer" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-address-card"></i> Kundekort</a>
              <a href="/admin/user" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-user"></i> Bruker</a>
              <a href="/admin/building" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-building"></i> Eiendommer</a>
              <a href="/admin/sensorunit" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-microchip"></i> Sensorenheter</a>
              <a href="/admin/maintenance" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-calendar-alt"></i> Vedlikeholdsplan</a>
              <hr>
              <a href="/admin/dashboard" class="list-group-item list-group-item-action bg-white"><i class="fa fa-lg fa-cogs"></i> Innstillinger</a> --}}
              {{-- <a href="/admin/dashboard" class="list-group-item list-group-item-action bg-light">Innstillinger</a> --}}
        
              {{-- <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button> --}}
        
            {{-- </div> --}}
        </section>
        <section class="content-wrapper">
            <div class="topbar">
                <div class="topbar-h1">
                    <h2>Heading</h2>
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="topbar-h2">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="topbar-h3">
                    <div class="name_job">
                        <div class="topnav-name">{{$data['user_name'] ?? 'Name'}}</div>
                        <div class="topnav-job">{{$data['user_id'] ?? 'Customer'}}</div>
                    </div>
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="content-main">
                <button id="button" class="btn btn-success card-rounded">Update w/release</button>
                <button id="delete" class="btn btn-warning card-rounded">Remove from Queue</button>
                <table id="proxy" class="display" width="100%"></table>
            </div>
        </section>

        
        <script>
        $(document).ready(function () {
            var dataSet = @php echo $data; @endphp;
            var table = $('#proxy').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                data: dataSet,
                pageLength: 100, // Number of entries
                responsive: true, // For mobile devices
                columnDefs : [{ 
                    responsivePriority: 1, targets: 4,
                    'targets': 0,
                    'checboxes': {
                        'selectRow': true
                    },
                }],
                'select': {
                    style: 'multi'
                },
                columns: [
                    { title: "SERIALNUMBER",
                        data:"serialnumber" },
                    { title: "RSSI",
                        data:"rssi" },
                    { title: "FW",
                        data: "swversion" },
                    { title: "LAST CONNECT",
                        data: "lastconnect" },
                    { title: "LAST FOTA IN QUEUE",
                        data: "queue_at" },
                    { title: "QUEUE LAST UPDATE",
                        data: "queue_updated_at" },    
                    { title: "ID Q",
                        data: "fota_in_queue" },
                    // { title: "COUNT Q",
                    //     data: "fota_in_queue_count" },
                    { title: "IMEI",
                        data: "imei" },
                    { title: "IMSI",
                        data: "imsi" },
                    // { title: "ICCID",
                    //     data: "iccid" },
                ],
            });
        
            $('#table tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
            } );
        
            $('#button').click( function () {
                counter = table.rows('.selected').data().length;
                alert( counter );
                for (i = 0; i < counter; i++) {
                    $.ajax({
                        url: "/admin/proxy/fota",
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                            "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                            "_token": token,
                        },
                        success: function(data) {
                            console.log(data);
                        },   
                        error: function(data) {
                            console.log(data);
                            alert('Something went wrong')
        
                        }
                    });
                }
            });
        
            $('#delete').click( function () {
                counter = table.rows('.selected').data().length;
                alert( counter );
                for (i = 0; i < counter; i++) {
                    $.ajax({
                        url: "/admin/proxy/queue/delete",
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                            "serialnumber": table.rows('.selected').data()[i]['serialnumber'],
                            "_token": token,
                        },
                        success: function(data) {
                            console.log(data);
                        },   
                        error: function(data) {
                            console.log(data);
                            alert('Something went wrong')
        
                        }
                    });
                }
            });
        });
        
        </script>
    </main>
    </body>
    </html>
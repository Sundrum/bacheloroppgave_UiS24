@extends('layouts.admin')

@section('content')
    <h1><i class="fa fa-random" aria-hidden="true"></i> Select User</h1>
    <table align="left" style="position: static; text-align:left; width:100%;">
        <tr>
            <td>
                <p>Click user ID to expand user on mobile</p>
            </td>
        </tr>
    </table>
    <br>
    <br>
       
    <!-- DataTables Extension for mobile support -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

    <!-- Datatables script  -->
    <script>
        $(document).ready(function () {
            var dataSet = @php echo $data; @endphp;
            $('#selectusertable').DataTable({
                data: dataSet,
                pageLength: 10, // Number of entries
                responsive: true, // For mobile devices
                columnDefs : [
                    { 
                        responsivePriority: 1, targets: 5 }
                    ],
                columns: [
                    { title: "User ID" },
                    { title: "Name" },
                    { title: "Username" },
                    { title: "Customer Name" },
                    { title: "Customer Number" },
                    { title: "Action", orderable: false, searchable: false },
                ],
            });
        });
    </script>

    <table id="selectusertable" class="display" width="100%"></table>
    
@endsection
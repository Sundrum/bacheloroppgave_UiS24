@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-8 my-auto">
            <h1><i class="fa fa-tag" aria-hidden="true"></i> Unittypes</h1>
        </div>
        <div class="col-4 my-auto">
            <div class="text-right">
                <button class="btn btn-primary" onclick="location.href='{{route('newunittype')}}'">Add new unittype</button>
            </div>
        </div>
    </div>
    <div class="row card card-rounded mt-2 mb-2">
        <div class="col-12 mt-2 mb-2">
            <table id="unittypetable" class="display" width="100%"></table>
        </div>
    </div>
</div>


    <!-- Datatables script  -->
    <script>
        $(document).ready(function () {
            var dataSet = @php echo $data; @endphp;
            $('#unittypetable').DataTable({
                data: dataSet,
                pageLength: 10, // Number of entries
                responsive: true, // For mobile devices
                columnDefs : [
                    { 
                        responsivePriority: 1, targets: 4 }
                    ],
                columns: [
                    { title: "ID" },
                    { title: "Description" },
                    { title: "Shortlabel" },
                    { title: "Decimals" },
                    { title: "Action", orderable: false, searchable: false },
                ],
            });
        });
    </script>    
@endsection
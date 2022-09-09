@extends('layouts.admin')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><b>Product</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <a onclick="window.location='{{ route('newproduct') }}'" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <table id="producttable" class="display" width="100%">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Number</td>
                <td>Description</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $row)
                <tr>
                    <td>{{$row->product_id ?? ''}}</td>
                    <td>{{$row->product_name ?? ''}}</td>
                    <td>{{$row->productnumber ?? ''}}</td>
                    <td>{{$row->product_description ?? ''}}</td>
                    <td><a href="/admin/product/{{$row->product_id}}"><button class="btn-primary-filled">Open</button></a></td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Number</td>
                <td>Description</td>
                <td></td>
            </tr>
        </tfoot>

    </table>
</section>

<script>
$(document).ready(function () {
    var table = $('#producttable').DataTable({
        pageLength: 25, // Number of entries
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 3,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
        'select': {
            style: 'multi'
        },
    });
    
    $('#producttable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var id = datarow[0];
        window.location='product/'+id;
    });
});

</script>
@endsection
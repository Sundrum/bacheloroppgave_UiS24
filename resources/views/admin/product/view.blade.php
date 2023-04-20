@extends('layouts.app')

@section('content')
<section class="">
    <div class="card card-rounded p-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="col-12">
                    <a onclick="window.location='{{ route('newproduct') }}'" class="btn-7g float-end" id="button"><i></i><span> @lang('admin.new')</span></a>
                </div>
                <div class="col-12">
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
                                    <td><a href="/admin/product/{{$row->product_id}}"><button class="btn-7s">Open</button></a></td>
                
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
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById("top-title").innerHTML = 'Products';

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
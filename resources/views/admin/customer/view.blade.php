@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row mt-3 mb-3">
        <div class="col-sm-5">
            <h2><b>Customer</b> </h2>
            <span class="text-muted">Management</span>
        </div>
        <div class="col-sm-7">
            <a onclick="window.location='newcustomer'" class="btn btn-primary-filled float-right" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <table id="customertable" class="display" width="100%">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Customernumber</td>
                <td>Maincontact</td>
                <td>E-mail</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $row)
                <tr>
                    <td>{{$row->customer_id ?? ''}}</td>
                    <td>{{$row->customer_name ?? ''}}</td>
                    <td>{{$row->customernumber ?? ''}}</td>
                    <td>{{$row->customer_maincontact ?? ''}}</td>
                    <td>{{$row->customer_email ?? ''}}</td>
                    <td><a href="/admin/customer/{{$row->customer_id}}"><button class="btn-primary-filled">Open</button></a></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Customernumber</td>
                <td>Maincontact</td>
                <td>E-mail</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</section>

<script>
$(document).ready(function () {
    var table = $('#customertable').DataTable({
        pageLength: 25, // Number of entries
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
    });
    
    $('#customertable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var userid = datarow[0];
        window.location='customer/'+userid;
    });
});

</script>
@endsection
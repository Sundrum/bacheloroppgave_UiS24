@extends('layouts.app')

@section('content')
<section class="card-rounded bg-white p-3">
    <div class="row">
        <div class="col-sm-12">
            <a onclick="window.location='newcustomer'" class="btn-7g float-end" id="button"><i></i><span> @lang('admin.new')</span></a>
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
                    <td><a href="/admin/customer/{{$row->customer_id}}"><button class="btn-7s">Open</button></a></td>
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
setTitle(@json( __('admin.customer')));
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
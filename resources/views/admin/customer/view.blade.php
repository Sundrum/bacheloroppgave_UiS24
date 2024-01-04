<!-- DataTables Extension for mobile support -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

<section class="card-rounded bg-white p-3">
    <div class="row">
        <div class="col-8">
            <h3>Customers</h3>
        </div>
        <div class="col-4">
            <a onclick="window.location='/admin/newcustomer'" class="btn-7g float-end" id="button"><i></i><span> @lang('admin.new')</span></a>
        </div>
    </div>
    <table id="customertable" class="display" width="100%"></table>
</section>

<script>
$(document).ready(function () {
    var dataSet = @php echo $customers; @endphp;
    var table = $('#customertable').DataTable({
        pageLength: 10, // Number of entries
        data: dataSet,
        stateSave: true,
        responsive: true, // For mobile devices
        columnDefs : [{ 
            responsivePriority: 1, targets: 4,
            'targets': 0,
            'checboxes': {
                'selectRow': true
            },
        }],
        columns: [
            { 
                title: "#",
                data: "customer_id"
            },
            { 
                title: "Name",
                data: "customer_name"
            },
            { 
                title: "Customernumber",
                data: "customernumber"
            },
            { 
                title: "Maincontact",
                data: "customer_maincontact"
            },
            { 
                title: "Email", 
                data: "customer_email"
            }
        ],
        'select': {
            style: 'multi'
        },
    });
    
    $('#customertable tbody').on( 'click', 'tr', function () {
        var datarow = table.row(this).data();
        var userid = datarow['customer_id'];
        window.location='/admin/customer/'+userid;
    });
});

</script>
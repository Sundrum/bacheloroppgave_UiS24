@extends('layouts.admin')

@section('content')
<section class="container">
    <h1>API Proxy Billing Service</h1>

    <div class="card mb-3">
        <div class="card-body target">
            <div class="form-group row">
                <label for="customer" class="col-md-3 col-form-label text-left"><h5>Select Customer</h5></label>
                <select class="col-md-4" name="customer" id="customer">
                    @foreach ($customers as $customer)
                        <option value="{{$customer->customernumber}}">{{$customer->customername}}</option>
                    @endforeach
                </select>    
            </div>
            <div class="form-group row">
                <label for="year" class="col-md-3 col-form-label text-left"><h5>Select Year</h5></label>
                <select class="col-md-4"name="year" id="year">
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022" selected>2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>    
            </div>
            <div class="form-group row">
                <label for="quarter" class="col-md-3 col-form-label text-left"><h5>Select Quarter</h5></label>
                <select class="col-md-4"name="quarter" id="quarter">
                    <option value="1">Q1</option>
                    <option value="2">Q2</option>
                    <option value="3" selected>Q3</option>
                    <option value="4">Q4</option>
                </select>    
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 id="summary" style="display: hidden;"><h5>
                </div>
            </div>
        </div>
    </div>
    <div>
        <table id="billingresult" class="display" width="100%"></table>
    
    </div>
</section>


<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">

<script>
    $(document).ready(function () {
        var customer = document.getElementById("customer").value;
        var quarter = document.getElementById("quarter").value;
        var year = document.getElementById("year").value;

        $.ajax({ 
            url: '/admin/billing/summary/'+customer+'/'+quarter+'/'+year,
            dataType: 'json',      
            success: function( data ) {
                document.getElementById("summary").innerHTML = data;

            },
            error: function( data ) {
                console.log(data)
            }
        });

        $.ajax({ 
            url: '/admin/billing/'+customer+'/'+quarter+'/'+year,
            dataType: 'json',      
            success: function( data ) {
                $('#billingresult').DataTable({
                    data: data,
                    async: true,
                    pageLength: 20, // Number of entries
                    responsive: true, // For mobile devices
                    dom: 'Bfrtip',
                    buttons: [ 'copyHtml5', 'csvHtml5', 'pdfHtml5' ],
                    columnDefs : [
                        { 
                            responsivePriority: 1, targets: 3 }
                        ],
                    columns: [
                        { title: "Serialnumber" },
                        { title: "Connections" },
                        { title: "First Connection" },
                        { title: "Last Connection" },
                    ],
                });
            },
            error: function( data ) {
            }
        });
    });

    $('.target').change(function() {
        $('#billingresult').DataTable().clear().destroy();
        var customer = document.getElementById("customer").value;
        var quarter = document.getElementById("quarter").value;
        var year = document.getElementById("year").value;

        $.ajax({ 
            url: '/admin/billing/summary/'+customer+'/'+quarter+'/'+year,
            dataType: 'json',      
            success: function( data ) {
                document.getElementById("summary").innerHTML = data;

            },
            error: function( data ) {
            }
        });

        $.ajax({ 
            url: '/admin/billing/'+customer+'/'+quarter+'/'+year,
            dataType: 'json',      
            success: function( data ) {
                $('#billingresult').DataTable({
                    data: data,
                    async: true,
                    pageLength: 20, // Number of entries
                    responsive: true, // For mobile devices
                    dom: 'Bfrtip',
                    buttons: [ 'copyHtml5', 'csvHtml5', 'pdfHtml5' ],
                    columnDefs : [
                        { 
                            responsivePriority: 1, targets: 3 }
                        ],
                    columns: [
                        { title: "Serialnumber" },
                        { title: "Connections" },
                        { title: "First Connection" },
                        { title: "Last Connection" },
                    ],
                });
            },
            error: function( data ) {
            }
        });
    });
</script>

@endsection
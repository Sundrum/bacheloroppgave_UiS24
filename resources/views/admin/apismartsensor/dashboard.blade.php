@extends('layouts.dev')

@section('content')
<section class="">
    <div class="bg-white card-rounded p-2 mb-3">
        <div class="card-body target">
            <div class="form-group row">
                <label for="customer" class="col-md-3 col-form-label text-left"><h5>Select Customer</h5></label>
                <select class="col-md-4 form-control" name="customer" id="customer">
                    @foreach ($customers as $customer)
                        <option value="{{$customer->customernumber}}">{{$customer->customername}}</option>
                    @endforeach
                </select>    
            </div>
            <div class="form-group row">
                <label for="year" class="col-md-3 col-form-label text-left"><h5>Select Year</h5></label>
                <select class="col-md-4 form-control"name="year" id="year">
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023"selected>2023</option>
                    <option value="2024">2024</option>
                </select>    
            </div>
            <div class="form-group row">
                <label for="quarter" class="col-md-3 col-form-label text-left"><h5>Select Quarter</h5></label>
                <select class="col-md-4 form-control"name="quarter" id="quarter">
                    <option value="1" selected>Q1</option>
                    <option value="2">Q2</option>
                    <option value="3">Q3</option>
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

<script>
    document.getElementById("top-title").innerHTML = 'API Proxy Billing Service';

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
                    pageLength: 100, // Number of entries
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
                    pageLength: 100, // Number of entries
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
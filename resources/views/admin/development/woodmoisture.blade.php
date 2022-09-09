@extends('layouts.admin')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
@section('content')
<section class="container">
    <h1 class="text-center"> Wood Moisture Content Calculator</h1>
    <div class="row">
        <div class="col-md-12 mt-2 mb-2">
            <div class="form-group row">
                <label for="treespecie" class="col-md-3 col-form-label text-left"><h5>Select Treespecie</h5></label>
                <select class="col-md-4"name="treespecie" id="treespecie" style="border-radius: 25px">
                    @foreach ($data['treespecies'] as $item)
                        <option value="{{$item->specie_id ?? ''}}">{{$item->specie_name ?? ''}}</option>
                    @endforeach
                </select>    
            </div>
            <div class="form-group row">
                <label for="temperature" class="col-md-3 col-form-label text-left"><h5>Temperature</h5></label>
                <input class="col-md-4" value="20.5" type="number" step="0.1" name="temperature" id="temperature" style="border-radius: 25px"> 
            </div>
            <div class="form-group row">
                <label for="ohm" class="col-md-3 col-form-label text-left"><h5>Ohm</h5></label>
                <input class="col-md-4" type="number" value="101000" name="ohm" id="ohm" style="border-radius: 25px"> 
            </div>
            <div class="btn btn-primary-filled" onclick="calculate()">Calculate</div>
        </div>

            <div class="card-body mt-2">
                <h5 id="summary" style="display: hidden;"><h5>
            </div>
    </div>
</section>

<script>
    function calculate() {
        var id = document.getElementById("treespecie").value;
        var temperature = document.getElementById("temperature").value;
        var ohm = document.getElementById("ohm").value;
        $.ajax({ 
            url: '/dev/calculate/woodmoisture/'+id+'/'+temperature+'/'+ohm,
            dataType: 'json',      
            success: function( data ) {
                document.getElementById("summary").innerHTML = data;

            },
            error: function( data ) {
            }
        });
    }
</script>
@endsection
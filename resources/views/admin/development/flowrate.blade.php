@extends('layouts.admin')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
@section('content')
<section class="container">
    <h1 class="text-center"> Flowrate Calculator</h1>
    <div class="row">
        <div class="col-md-12 mt-2 mb-2">
            <div class="form-group row">
                <label for="serialnumber" class="col-md-3 col-form-label text-left"><h5>Select sensorunit</h5></label>
                <select class="col-md-4"name="serialnumber" id="serialnumber" style="border-radius: 25px">
                    @foreach ($sensorunit as $unit)
                        <option value="{{$unit->serialnumber ?? ''}}">{{$unit->serialnumber ?? ''}}</option>
                    @endforeach
                </select>    
            </div>
            <div class="form-group row">
                <label for="run" class="col-md-3 col-form-label text-left"><h5>Run</h5></label>
                <input class="col-md-4" type="number" value="10" name="run" id="run" style="border-radius: 25px"> 
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
        var serialnumber = document.getElementById("serialnumber").value;
        var run = document.getElementById("run").value;
        console.log(serialnumber + run)
        $.ajax({ 
            url: '/dev/calculaterun/'+serialnumber+'/'+run,
            dataType: 'json',      
            success: function( data ) {
                console.log(data);
                document.getElementById("summary").innerHTML = data;

            },
            error: function( data ) {
                //console.log(data);
            }
        });
    }
</script>

@endsection
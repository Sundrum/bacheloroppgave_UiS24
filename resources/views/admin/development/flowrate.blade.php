@extends('layouts.app')

@section('content')
<section class="">
    <div class="row">
        <div class="col-md-6 offset-md-3 my-2 card-rounded bg-white p-3">
            <div class="form-group row">
                <label for="serialnumber" class="col-md-3 col-form-label text-left"><h5>Select sensorunit</h5></label>
                <select class="col-md-6 " name="serialnumber" id="serialnumber">
                    @foreach ($sensorunit as $unit)
                        <option value="{{$unit->serialnumber ?? ''}}">{{$unit->serialnumber ?? ''}}</option>
                    @endforeach
                </select>    
            </div>
            <div class="form-group row">
                <label for="run" class="col-md-3 col-form-label text-left"><h5>Run</h5></label>
                <input class="col-md-2" type="number" value="10" name="run" id="run"> 
            </div>
            
            <div class="btn-7s" onclick="calculate()">Calculate</div>
        </div>

            <div class="card-body mt-2">
                <h5 id="summary" style="display: hidden;"><h5>
            </div>
    </div>
</section>

<script>
    setTitle("Flowrate Calculator");
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
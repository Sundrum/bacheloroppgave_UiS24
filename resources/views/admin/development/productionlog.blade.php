@extends('layouts.app')

@section('content')

{{-- <section class="bg-grey mt-2"> --}}
    {{-- <div class="row"> --}}
        <div class="card-rounded bg-white col-12">
            <div class="row justify-content-center">
                <div class="col-md-12 mt-2 px-5">
                    <div class="form-group">
                        <label for="production_log" class="col-md-12"><h5>Production Log</h5></label>
                        <textarea class="form-control" type="text" rows="4" name="production_log" id="production_log" ></textarea>
                    </div>
                    <div class="form-group mt-2">
                        <label for="serial_imei" class="col-md-12"><h5>IMEI list</h5></label>
                        <textarea class="form-control" type="text" rows="4" name="serial_imei" id="serial_imei"></textarea>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="ohm" class="col-md-3 col-form-label text-left"><h5>Process 0</h5></label>
                        <textarea class="form-control" type="text" rows="3" name="proccess" id="proccess"></textarea>
                    </div> --}}
                    <div class="btn-7s mt-2" onclick="generate()">Generate</div>
                </div>
        
                    <div class="card-body mt-2 mb-5 px-5">
                        <span id="summary" style="display: hidden;"><span>
                    </div>
            </div>
        </div>
    {{-- </div> --}}
{{-- </section> --}}
<script>

    function generate() {
        $.ajax({ 
            url: '/dev/process/productionlog',
            type: 'POST',
            data: { 
                "production_log": document.getElementById("production_log").value,
                "serial_imei": document.getElementById("serial_imei").value,
                "_token": token,
            },      
            success: function( data ) {
                let notFound = data.not_found;
                let log = data.result;
                let duplicates = data.duplicates_imeilist;
                let text = "";
                console.log("Dups = " + duplicates.length);
                if(duplicates.length > 0) {
                    text += `<h5 class="mt-2">Duplicates from IMEI list</h5>
                                <div class="card-rounded bg-7r p-3">
                            `;
                    
                    
                    duplicates.forEach((element) => {
                        text += `<p class="p-0 m-0 text-7r">
                                ${element}
                                </p>`;
                    });

                    text += `</div>`;
                }

                console.log("Not found = " + notFound.length);
                if(notFound.length > 0) {
                    text += `<h5 class="mt-2">Not found</h5>
                            <div class="card-rounded bg-7r p-3">
                        `;
                    notFound.forEach((element) => {
                        text += `<p class="p-0 m-0 text-7r">
                                ${element}
                                </p>`;
                    });
                    text += `</div>`;
                }

                if(log.length > 0) {
                    text += `<h5 class="mt-2">IMEI, IMSI, ICCID, SERIAL ;; CHECKED</h5>`;
                    log.forEach((element) => {
                        text += `<p class="p-0 m-0">
                                    ${element.imei},${element.imsi},${element.iccid},${element.serial};;${element.checked}
                                </p>`;
                    });
                }

                //$feedback .= ''.$unit['imei'].','.(string)$unit['imsi'].','.(string)$unit['iccid'].','.(string)$unit['serial'].';;'.$unit['checked'].'<br>';

                document.getElementById("summary").innerHTML = text;
                successMessage("Processed");
            },
            error: function( data ) {
                errorMessage("Something went wrong");
            }
        });
    }
</script>
@endsection
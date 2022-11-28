@extends('layouts.app')

<style>
@media print {
    .body * {
        visibility: hidden;
    }
    .printContainer, .printContainer * {
        visibility: visible;
    }
    .printContainer {
        position: absolute;
        left: 0px;
        top: 0px;
    }
    .noPrint{
        display: none;
        visibility: hidden;
    }
    .img { 
        display: show;
    }
}

</style>


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11">
            <div class="row mt-2 mb-2" id="noPrint">
                <div class="col">
                    <div class="row p-2">
                        <a href="{{route('cases')}}" id="noPrint" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}">@lang('back to cases')</a>
                    </div>
                </div>
            </div>
            @if (isset($data) && isset($data->status) && ($data->status == 3))
                <div class="col-md-12 my-auto">
                    <div class="row justify-content-center" id="noPrint">
                        {{-- <button class="btn btn-secondary-filled ml-2" onclick="setPrint('{{$data->case_id}}')">@lang('Print skjema')</button> --}}
                        <button class="btn btn-secondary-filled ml-2" onclick="printDiv('printContainer')">Print</button>
                    </div>
                </div>
            @endif
            <div class="card card-rounded mt-2" id="printContainer">
            
            <form class="row mt-2 justify-content-center" id="servicedetails">
                @csrf

                    <img class="avatar-picture" src="{{asset('img/7sense_logo.png')}}" id="img" alt="7sense_logo" style="max-height: 120px; width: auto;"></a>
                    <div class="col-12">
                        <h3 class="text-center"><strong>Serviceskjema</strong></h3>
                    </div>
                
                <?php 
                if (isset($data)) {
                    // echo "<div>".print_r($data)."</div>";
                    // echo "rediger";
                }
                
                else {
                    // echo "Ny";
                }
                ?>
                   
                   
                   <div class="mt-3 col-12">
                       
                        <div class="col-12 my-auto">
                            <p class="text-center text-muted">Dette er et serviceskjema for å gi en oversikt over reperasjoner, som er gjort på sensorer til kunder.
                        </div>


                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-12 my-auto">
                                <h4><strong>Kundeopplysninger</strong></h4>  
                            </div>
                        </div>

                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Navn/Kundenavn</strong>
                            </div>
                            {{-- {{ isset($data) ? "disabled" : ""  242, 250}} --}}
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    @if(isset($data) && isset($customer))
                                        {{$customer->customer_name}}
                                        <input type="hidden" name="customer_id_ref" value="{{$data->customer_id_ref}}">
                                    @elseif(isset($customers))
                                        <select class="custom-select form-control" id="customer_id_ref" name="customer_id_ref" >
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->customer_id}}" required @if(isset($data) && ($customer->customer_id == $data->customer_id_ref)) selected @endif> {{$customer->customer_name}}, ({{$customer->customernumber}})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        No data / an error has occurred
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Adresse og gate nr</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customer_address" maxlength ="25" name="customer_address" placeholder="Skriv inn kundens addresse her (dette vil bli lagret)" value="{{$customer->customer_visitaddr1 ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Postkode</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customer_postcode" maxlength ="8" name="customer_postcode" placeholder="Skriv inn kundens postnr her (dette vil bli lagret)" value="{{$customer->customer_visitpostcode ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Postadresse</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customer_city" maxlength ="25" name="customer_city" placeholder="Skriv inn kundens postadr her (dette vil bli lagret)" value="{{$customer->customer_visitcity ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <hr>

                        <hr class="m-1">
                        <div class="row px-4 mt-2">
                            <div class="col-12">
                                <h4><strong>Produktopplysninger</strong></h4>
                            </div>
                        </div>

                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Serienummer</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    @if(isset($data) && isset($customer))
                                        {{$data->serialnumber ?? "-"}}
                                        {{-- <input type="hidden" name="serialnumber" value="{{$data->serialnumber}}"> --}}
                                    @elseif(isset($customer))
                                        <select class="custom-select form-control" id="serialnumber" name="serialnumber" onclick="getserial()">
                                            <option value="" required @if(isset($data) && ($customer->serialnumber == $data->serialnumber)) selected @endif>
                                        </select>
                                    @else
                                    No data / an error has occurred
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6" id="noPrint">
                                <strong>Serienummer 2 (valgfritt)</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    @if(isset($data) && isset($customer))
                                        {{$data->serialnumber_2 ?? ""}}
                                    @elseif(isset($customer))
                                        <select class="custom-select form-control" id="serialnumber_2" name="serialnumber_2">
                                            <option value=""></option>
                                        </select>
                                    @else
                                    No data / an error has occurred
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                        <hr>

                        
                        <hr class="m-1">
                        <div class="row px-4 mt-2">
                            <div class="col-8">
                                <h4><strong>Reparasjonsopplysninger</strong></h4>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Produkt mottat dato</strong>
                            </div>
                            <div class="input-group col-md-5">
                                @if(isset($data) && isset($data->date_recived))
                                    {{substr($data->date_recived ?? '', 0, 10)}}
                                @else
                                    <div class="input-group">
                                        <input type="date" name="date_recived" value="{{substr($data->date_recived ?? '', 0, 10)}}" required >
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Service-ID</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" maxlength ="5" id="" name="service_id" placeholder="000" value="{{$data->service_id ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Beskrivelse av feilen</strong>
                            </div>
                            <div class="col-md-5">
                                <textarea type="text" rows="5" cols="15" maxlength ="250" class="form-control" id="fault_comment" name="fault_comment" placeholder="Skriv problemet i denne tekstboksen" autofocus>{{$data->fault_description ?? ''}}</textarea>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Breskrivelse av reparasjonen</strong>
                            </div>
                      
                        <div class="col-md-5">
                            <textarea type="text" rows="5" cols="15" maxlength ="250" class="form-control" id="rep_comment" name="rep_comment" placeholder="Skriv problemet i denne tekstboksen" autofocus>{{$data->repair_description ?? ''}}</textarea>
                        </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Er produktet funksjonstestet etter reparasjon?</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <select class="custom-select form-control" id="test_ok" name="test_ok">
                                        <option value="1">Ja</option>
                                        <option value="0">Nei</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <hr class="m-1">
                        <div class="row px-4 mt-2">
                            <div class="col-12">
                                <h4><strong>Ansvarlig</strong></h4>
                            </div>
                            <div class="col-6"></div>   
                        </div>


                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Ansvarlig for sak</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <select class="custom-select form-control" id="service_person" name="service_person">
                                        @foreach($service_persons as $service_person)
                                            <option value="{{$service_person->service_person_id}}" required @if(isset($data) && ($service_person->service_persons_id == $data->status)) selected @endif> {{$service_person->service_person_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Sist oppdatert</strong>
                            </div>
                            <div class="input-group col-md-5">
                                @if(isset($data) && isset($data->updated_at))
                                {{substr($data->updated_at ?? '', 0, 10)}}
                                @endif
                            </div>
                        </div>
                        <hr class="m-1">
                        <div class="row pl-4">
                            <div class="col-6">
                                <strong>Status på servicen</strong>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group">
                                    <select class="custom-select form-control" id="service_status" name="service_status">
                                        @foreach($service_status as $service_stat)
                                            <option value="{{$service_stat->service_status_id}}" required @if(isset($data) && ($service_stat->service_status_id == $data->status)) selected @endif> {{$service_stat->service_status_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>                    
                        <div class="row justify-content-center">
                                7Sense Agritech AS | Moloveien 14 | 3187 Horten
                        </div>
                        <div class="row justify-content-center">
                            www.7sense.no
                        </div>
                        <hr>
                        <br>
                        <br>
                        <br>
                        <br>
                        <u>
                        <hr>
                    </div>
                </div>   
                    <div class="form-row justify-content-center">
                        <div>
                            <button type="submit" class="btn-primary-filled" id="noPrint"> <strong>Lagre</strong></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="message-bottom"></div>
</div>


<script>

    
    const customers = <?php echo json_encode($customers ?? '') ?>;

    function updateCustomer(value) {
        const customer = customers.find((c) => c.customer_id === value);

        if (customer == null) return;
        console.log(customer.customer_id);
        $('#customer_address').val(customer.customer_visitaddr1);
        $('#customer_postcode').val(customer.customer_visitpostcode);
        $('#customer_city').val(customer.customer_visitcity);

        $.each(customer.sensorunits, function(index, sensorunit) {
            $('#serialnumber').append($("<option></option>").attr("value", sensorunit.serialnumber).text(sensorunit.serialnumber));
            $('#serialnumber_2').append($("<option></option>").attr("value", sensorunit.serialnumber).text(sensorunit.serialnumber));
        });
    }

    function getserial() {
        var se = $('#serialnumber').val();
        console.log("serialnumberonchange" + se);
        console.log($('#customer_id_ref').val());
    }
    
    $(document).ready(function(){
        const customerSelect = $('#customer_id_ref');
        $('[data-toggle="popover"]').popover();

        customerSelect
            .on('change', ({ target }) => {
                const value = Number(target.value);
                updateCustomer(value);
            });

        if (customerSelect.val()) {
            const value = Number(customerSelect.val());
            updateCustomer(value);
        }
    });

    $('.popover-dismiss').popover({
        trigger: 'focus'
    });

    $( "#servicedetails" ).on( "submit", function(e) {
        const caseId = <?php echo isset($data) ? $data->case_id : -1 ?>;
        // console.log(('#serialnumber').val());
        e.preventDefault();
        console.log('sent');
        var dataString = $(this).serialize();
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: caseId != -1 ? `/admin/sensorunit/cases/${caseId}` : "/admin/sensorunit/cases/new",
            data: dataString,
            success: function (msg) {
                console.log(msg);
                if (msg == 1) {
                    $('#message-bottom').html('<div id="success-alert" class="alert alert-success fade show text-center" role="alert"><p>Oppdatert</p></div>');
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
                        $("#success-alert").slideUp(500);
                    });
                    window.location.href = '/admin/sensorunit/cases';
                } else {
                    $('#message-bottom').html('<div id="danger-alert" class="alert alert-danger fade show text-center" role="alert"><p>Noe gikk galt!</p></div>');
                    $("#danger-alert").fadeTo(2000, 500).slideUp(500, function() {
                        $("#danger-alert").slideUp(500);
                    });
                }
            }
        });
    });

    function printDiv(printContainer) {
        var printContents = document.getElementById(printContainer).innerHTML;
        var originalContents = document.body.innerHTML;
     

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
    }

</script>

@endsection
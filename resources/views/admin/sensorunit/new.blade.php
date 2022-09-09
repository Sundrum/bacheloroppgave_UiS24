@extends('layouts.admin')

@section('content')

<section class="container-fluid">
    <div class="col-12">
        <div class="mt-3 mb-3">
            <div class="row justify-content-center">
                <div class="col-md-6 card card-rounded">
                    <h5 class="m-4 text-center">Add new sensor devices</h5>
                    <p>Serial numbers must be registered in order to connect with customers. This functionality is used to register a device for the first time. <br> <strong>Are you unsure what this is used for? Then you shouldnt register new devices.</strong></p>
                    <p>The devices will be added to the customer: 7Sense OnStock</p>
                    <div class="mt-1 mb-3">
                        <form method="POST" id="update" action="{{route('adminnewunits')}}">
                            @csrf
                            <hr>
                            <div class="form-group row">
                                <label for="product" class="col-md-4 col-form-label">{{ __('Product') }}</label>
                                <div class="input-group col-md-8">
                                    <select class="custom-select" id="product" name="product" required>
                                        @foreach($product as $row)
                                            <option value="{{$row->product_id}}"> {{$row->productnumber}}, {{$row->product_name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="sensorunit_position" class="col-md-4 col-form-label my-auto">{{ __('Serialnumber') }}</label>
                                <div class="input-group col-md-8">
                                    <div class="input-group">
                                        <input type="int" class="form-control" id="serialnumber" name="serialnumber" placeholder="F.eks. 00001" minlength="5" maxlength="5" required>
                                    </div>
                                    <span class="text-muted">Enter the last 5 digits of the serial number of the first new device</span>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="amount" class="col-md-4 col-form-label my-auto">{{ __('Amount') }}</label>
                                <div class="input-group col-md-8">
                                    <div class="input-group">
                                        <input type="int" class="form-control" id="amount" name="amount" placeholder="F.eks. 20" required>
                                    </div>
                                    <span class="text-muted">Enter the number of new devices</span>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row justify-content-center">
                                <div>
                                    <button type="submit" class="btn-primary-filled"> <strong>Create sensor devices</strong></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
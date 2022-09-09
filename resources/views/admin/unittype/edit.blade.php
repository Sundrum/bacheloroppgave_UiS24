@extends('layouts.admin')

@section('content')
<div class="container">
    <h4><i class="fa fa-tag fa-lg"></i> Unittype</h4>
    <div class="row mt-3 mb-1">
        <a class="btn-primary-outline" href="/admin/unittypes" style="color: black; text-decoration: none;"><img class="" src="{{ asset('/img/back.svg') }}"> <strong>Back to Unittypes</strong></a>
    </div>
    <div class="card card-rounded">
        <div class="card-body mt-2">
            <div class="row justify-content-center">
                <p>The sum of all products that use this unittype is <strong>{{$counter ?? '0'}}</strong>. Changes will effect all products</p>
            </div>
            <form method="POST" action="{{route('updateunittype')}}">
                @csrf
                <div class="form-group row">
                    <div class="input-group">
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description<button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="The unittype Temperature has Temperature as description"><i class="fa fa-info-circle fa-lg"></i></button></label>
                        <input type="text" class="col-md-5 form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{$unittype->unittype_description ?? ''}}" required>
                        

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label for="label" class="col-md-4 col-form-label text-md-right my-auto">Label<button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="The unittype Temperature has Celsius as label"><i class="fa fa-info-circle fa-lg"></i></button></label>
                        <input type="text" class="col-md-5 form-control @error('label') is-invalid @enderror" id="label"  value="{{$unittype->unittype_label ?? ''}}" name="label" required>
                        @error('label')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label for="shortlabel" class="col-md-4 col-form-label text-md-right my-auto">Shortlabel<button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="The unittype Temperature has °C as shortlabel"><i class="fa fa-info-circle fa-lg"></i></button></label>
                        <input type="text" class="col-md-5 form-control @error('shortlabel') is-invalid @enderror" id="shortlabel" name="shortlabel" value="{{$unittype->unittype_shortlabel ?? ''}}" required>

                        @error('shortlabel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label for="decimals" class="col-md-4 col-form-label text-md-right">Decimals <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="The unittype Temperature has 1 as decimals"><i class="fa fa-info-circle fa-lg"></i></button></label>
                        <input type="number" class="col-md-5 form-control @error('decimals') is-invalid @enderror" id="decimals" name="decimals" value="{{$unittype->unittype_decimals ?? ''}}" required>
                        @error('decimals')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="input-group">
                        <label for="url" class="col-md-4 col-form-label text-md-right">Picture Url <button type="button" class="btn" data-toggle="popover" data-placement="top" data-trigger="focus" data-content="The unittype Temperature has 1 as decimals"><i class="fa fa-info-circle fa-lg"></i></button></label>
                        <input type="text" class="col-md-5 form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{$unittype->unittype_url ?? ''}}" required>
                        @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                @if(isset($unittype->unittype_id))
                    <input type="hidden" name="id" value="{{$unittype->unittype_id ?? ''}}">
                    <input type="hidden" name="old" value="1">
                @else
                    <input type="hidden" name="old" value="0">
                @endif
                <div class="row justify-content-center">
                    <button type="submit" class="btn btn-primary-filled"><strong>Save</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>
@endsection
<div class="row mt-3 mb-3">
    <div class="col-md-6">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.user')</h4>
                </div>           
            </div>
            <div class="row m-1 mt-1 mb-3" >
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('newuser') }}'">
                        @lang('admin.new')
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3" >
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('user') }}'">
                        @lang('admin.viewall') ({{$count['users'] ?? ''}})
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.customer')</h4>
                </div>                
            </div>
            <div class="row m-1 mt-1 mb-3" >
                <div class="col-md-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('newcustomer') }}'">
                        @lang('admin.new')
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3" >
                <div class="col-md-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('customeradmin') }}'">
                        @lang('admin.viewall') ({{$count['customers'] ?? ''}})
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3 mb-3">
    <div class="col-md-4">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.product')</h4>
                </div>               
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-md-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('newproduct') }}'">
                        @lang('admin.new')
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-md-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('unittype') }}'">
                        Unittypes
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-md-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('product') }}'">
                        @lang('admin.viewall') ({{$count['products'] ?? ''}})
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.sensorunit')</h4>
                </div>           
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('adminaddunit') }}'">
                        @lang('admin.new')
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='/admin/irrigationstatus'">
                       Show Irrigation Overview
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('sensorunit') }}'">
                        @lang('admin.viewall') ({{$count['units'] ?? ''}})
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{ route('cases') }}'">
                        Cases ({{$count['cases'] ?? ''}})
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>Firmware</h4>
                </div>                
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled"  data-toggle="modal" data-target="#uploadModal">
                        Upload new FW
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{route('firmwarelist')}}'">
                        View Firmware
                    </div>
                </div>
            </div>
            <div class="row m-1 mt-1 mb-3">
                <div class="col-12">
                    <div class="btn-primary-filled" onclick="window.location='{{route('showFirmware')}}'">
                        Show Queue
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
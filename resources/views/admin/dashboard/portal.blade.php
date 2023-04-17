<div class="row mt-3 mb-3">
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.user')</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center" >
                <button class="btn-7s" onclick="loadContent('{{ route('newuser') }}')">
                    @lang('admin.new')
                </button>
            </div>
            <div class="row m-1 justify-content-center" >
                <button class="btn-7s" onclick="loadContent('{{ route('user') }}')">
                    @lang('admin.viewall') ({{$count['users'] ?? ''}})
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.customer')</h4>
                </div>                
            </div>
            <div class="row m-1 justify-content-center" >
                <div class="btn-7s" onclick="loadContent('{{ route('newcustomer') }}')">
                    @lang('admin.new')
                </div>
            </div>
            <div class="row m-1 justify-content-center" >
                <div class="btn-7s" onclick="loadContent('{{ route('customeradmin') }}')">
                    @lang('admin.viewall') ({{$count['customers'] ?? ''}})
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.product')</h4>
                </div>               
            </div>
            <div class="row m-1 justify-content-center">
                <div class="btn-7s" onclick="loadContent('{{ route('newproduct') }}')">
                    @lang('admin.new')
                </div>
            </div>
            <div class="row m-1 justify-content-center">
                <div class="btn-7s" onclick="loadContent('{{ route('unittype') }}')">
                    Unittypes
                </div>
            </div>
            <div class="row m-1 justify-content-center">
                <div class="btn-7s" onclick="loadContent('{{ route('product') }}')">
                    @lang('admin.viewall') ({{$count['products'] ?? ''}})
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.sensorunit')</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center">
                <div class="btn-7s" onclick="loadContent('{{ route('adminaddunit') }}')">
                    @lang('admin.new')
                </div>
            </div>
            <div class="row m-1 justify-content-center">
                <div class="btn-7s" onclick="loadContent('{{ route('irrigationstatus') }}')">
                    Show Irrigation Overview
                </div>
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s" onclick="loadContent('{{ route('sensorunit') }}')">
                    @lang('admin.viewall') ({{$count['units'] ?? ''}})
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 mb-2">
                <div class="col-md-12 text-center">
                    <h4>Firmware</h4>
                </div>                
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s"  data-toggle="modal" data-target="#uploadModal">
                    Upload new FW
                </button>
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s" onclick="loadContent('{{route('firmwarelist')}}')">
                    View Firmware
                </button>
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s" onclick="loadContent('{{route('showFirmware')}}')">
                    Show Queue
                </button>
            </div>
        </div>
    </div>
</div>
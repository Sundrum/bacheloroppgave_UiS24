<div class="row mt-3 mb-3">
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3 ">
                <div class="col-md-12 text-center">
                    <h4>@lang('admin.apiproxy')</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s" onclick="window.location='{{ route('billing') }}'">
                    @lang('admin.billing')
                </button>
            </div>
            <div class="row m-1 justify-content-center">
                <button class="btn-7s" onclick="window.location='{{ route('proxyvariables') }}'">
                    Variables
                </button>
            </div>
            
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3">
                <div class="col-md-12 text-center">
                    <h4>Telenor Portal</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center">
                <a href="https://www.telenor.no/bedrift/minbedrift/#/" target="_blank" style="text-decoration:none;">
                    <div class="row">
                        <button class="btn-7s">
                            Go to
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3">
                <div class="col-md-12 text-center">
                    <h4>ICE Bedrift</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center">
                <a href="https://minside.ice.no/minbedrift/3772189/abonnement" target="_blank" style="text-decoration:none;">
                    <div class="row">
                        <button class="btn-7s">
                            Go to
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="col-md-12 card card-rounded">
            <div class="row m-1 mt-3">
                <div class="col-md-12 text-center">
                    <h4>Development Portal</h4>
                </div>           
            </div>
            <div class="row m-1 justify-content-center">
                <a href="http://development.portal.7sense.no" target="_blank" style="text-decoration:none;">
                    <div class="row">
                        <button class="btn-7s">
                            Go to
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
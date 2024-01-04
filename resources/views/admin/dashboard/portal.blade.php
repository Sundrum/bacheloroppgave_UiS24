<div class="row mt-3 mb-3">
    <div class="col-md-12 col-lg-6">
        <div class="col-md-12 card card-rounded">
            @include('admin.customer.view')
        </div>

        {{-- <div class="row m-1 mt-3 mb-2">
            <div class="col-md-12 text-center">
                <h4>@lang('admin.customer')</h4>
            </div>                
        </div>
        <div class="row m-1 justify-content-center" >
            <div class="btn-7s" onclick="loadContent('{{ route('newcustomer') }}')">
                @lang('admin.new') @lang('admin.customer')
            </div>
        </div>
        <div class="row m-1 justify-content-center" >
            <button class="btn-7s" onclick="loadContent('{{ route('user') }}')">
                @lang('admin.viewall') @lang('admin.user') ({{$count['users'] ?? ''}})
            </button>
        </div>
        <div class="row m-1 justify-content-center" >
            <div class="btn-7s" onclick="loadContent('{{ route('customeradmin') }}')">
                @lang('admin.viewall') @lang('admin.customer') ({{$count['customers'] ?? ''}})
            </div>
        </div> --}}
        <div class="row p-3">     
            <div class="col-md-12 col-lg-4 card-rounded bg-white">
                <div class="row m-1 mt-3 mb-2 p-2">
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
            <div class="col-md-12 col-lg-4 card card-rounded">
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
            <div class="col-md-12 col-lg-4 card card-rounded">
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
    <div class="col-md-6">
            <div class="col-md-12 card card-rounded" id="container">
            </div>
            <div class="col-md-12  mt-2 card-rounded bg-white">
                <div class="row p-3">
                    <h4 class="text-center">Activity Feed</h4>
                </div>
                <div id="activityfeed" style="overflow-y:scroll; height: 300px;">
                    
                </div>
            </div>
    </div>
</div>

<script>

    function getPayement() {
        $.ajax({
            url: "/admin/payment/all",
            type: 'GET',
            data: {
                "_token": token,
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                errorMessage('Could not load payments');
            }
        })
    }
    getPayement();
    
    let dataSeries = [];
    function getActivity() {
        $.ajax({
            url: "/admin/activity/daily",
            type: 'GET',
            data: {
                "_token": token,
            },
            success: function (data) {
                let dashboard = data.dashboard;
                let settings = data.settings;
                let all = data.all;
                let acticityfeed = "";
                let admin = data.admin;
                let countArray = [null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null];
                let users = [];
                let uniqeUsers = 0;
                dashboard.map((el, i) => {
                    let index = new Date(el.created_at).getHours();
                    if(countArray[index] === null) countArray[index] = 1;
                    else countArray[index] += 1;

                    if(users[el.userId]) {
                        users[el.userId] += 1;
                    } else {
                        uniqeUsers += 1;
                        users[el.userId] = 1;
                    }
                })

                all.map((el, i) => {
                    let date = moment(el.created_at).calendar();
                    if (el.roletype_id_ref > 50) {
                        acticityfeed += `<div class="row px-5 py-1">
                                            <div class="col-9 col-lg-10 offset-lg-1">
                                                <div class="col-12 offset-2 col-lg-8 bg-7r offset-lg-4" style="border-radius: 25px 5px 25px 25px; padding: 15px;">
                                                    ${el.description}
                                                </div>
                                                <div class="col-12 offset-2 col-lg-8 offset-lg-4 text-start">
                                                    ${date}
                                                </div>
                                            </div>
                                            <div class="col-3 col-lg-1">
                                                <div class="row">
                                                    <span onclick="userPage(${el.userId})" class="text-white" style="cursor: pointer;font-size: 14px; border-radius: 100%; width: 40px; height:40px; background-color: #efa6a5 !important; padding: 0; display:inline-flex; align-items:center; justify-content:center;">${el.userId}</span>
                                                </div>
                                            </div>    
                                        </div>`;
                    } else {
                        acticityfeed += `<div class="row px-5 py-1">
                                            <span onclick="userPage(${el.userId})" class="text-white" style="cursor: pointer;font-size: 14px; border-radius: 100%; width: 40px; height:40px; background-color: #a7c49d !important; padding: 0; display:inline-flex; align-items:center; justify-content:center;">${el.userId}</span>
                                            <div class="col-10">
                                                <div class="col-8 bg-7g" style="border-radius: 5px 25px 25px 25px; padding: 15px;">
                                                    ${el.description}
                                                </div>
                                                <div class="col-8 text-end">
                                                    ${date}
                                                </div>
                                            </div>
                                        </div>`;
                    }
                })


                document.getElementById('activityfeed').innerHTML = acticityfeed;
                let countAdmin = [null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null];
                admin.map((el, i) => {
                    let index = new Date(el.created_at).getHours();
                    if(countAdmin[index] === null) countAdmin[index] = 1;
                    else countAdmin[index] += 1;
                })

                let settingsData = [null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null];
                settings.map((el, i) => {
                    let index = new Date(el.created_at).getHours();
                    if(settingsData[index] === null) settingsData[index] = 1;
                    else settingsData[index] += 1;
                })
                dataSeries = [{
                    'name': 'Dashboard',
                    'data': countArray,
                    'color': '#a7c49d'
                },{
                    'name': 'Dashboard (Admin)',
                    'data': countAdmin,
                    'color': '#efa6a5'
                },{
                    'name': 'Settings',
                    'data': settingsData,
                    'color': '#00265a'
            }];
                initChart(dataSeries);
            },
        })
    }
    getActivity();

    function initChart(dataSeries){
        Highcharts.chart('container', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'Tracking of page loadings'
            },
            subtitle: {
                text: 'Last reset @ 2023-10-31',
                align: 'right',
                verticalAlign: 'bottom'
            },
            legend: {
                layout: 'horizontal',
                align: 'left',
                verticalAlign: 'bottom',
                x: 20,
                y: 0,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF'
            },
            yAxis: {
                title: {
                    text: 'Loadings'
                }
            },
            xAxis: {
                title: {
                    text: 'Time (hour)'
                }
            },
            plotOptions: {
                series: {
                    pointStart: 0,
                    pointEnd: 25
                },
                area: {
                    fillOpacity: 0.5
                }
            },
            credits: {
                enabled: false
            },
            series: dataSeries
        });
    }

    function userPage(id) {
        window.location = '/admin/account/'+id;
    }
</script>
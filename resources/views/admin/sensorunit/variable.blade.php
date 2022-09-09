<div class="col-md-6 card card-rounded">
    <h5 class="mt-4 mb-2">Status</h5>
    <div class="row">
        <div class="col-md-6">
            <hr class="m-0">
            @if($unit->imei)
                <div class="row">
                    <div class="col-6">
                        IMEI
                    </div>
                    <div class="col-6">
                        {{$unit->imei ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
            @if($unit->imsi)
                <div class="row">
                    <div class="col-6">
                        IMSI
                    </div>
                    <div class="col-6">
                        {{$unit->imsi ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
    
            @if($unit->swversion)
                <div class="row">
                    <div class="col-6">
                        Firmware
                    </div>
                    <div class="col-6">
                        {{$unit->swversion ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
    
            @if($unit->gnss)
                <div class="row">
                    <div class="col-6">
                        GNSS
                    </div>
                    <div class="col-6">
                        {{$unit->gnss ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
            @if($unit->timegnss)
                <div class="row">
                    <div class="col-6">
                        Time GNSS
                    </div>
                    <div class="col-6">
                        {{$unit->timegnss ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
            @if($unit->cellid)
            <div class="row">
                <div class="col-6">
                    Cell ID
                </div>
                <div class="col-6">
                    {{$unit->cellid ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->lac)
            <div class="row">
                <div class="col-6">
                    LAC/TAC
                </div>
                <div class="col-6">
                    {{$unit->lac ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
    
            @if($unit->iccid)
                <div class="row">
                    <div class="col-6">
                        ICCID
                    </div>
                    <div class="col-6">
                        {{$unit->iccid ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
    
            @if($unit->dip_switch)
                <div class="row">
                    <div class="col-6">
                        DIP Switch
                    </div>
                    <div class="col-6">
                        {{$unit->dip_switch ?? ''}}
                    </div>
                </div>
                <hr class="m-0">
            @endif
    
            @if($unit->mdmfwver)
            <div class="row">
                <div class="col-6">
                    Modem Firmware
                </div>
                <div class="col-6">
                    {{$unit->mdmfwver ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
    
            @if($unit->mccmnc)
            <div class="row">
                <div class="col-6">
                    MCCMNC
                </div>
                <div class="col-6">
                    {{$unit->mccmnc ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
    
        </div>
        <div class="col-md-6">
            <hr class="m-0">
            @if($unit->connectcounter)
            <div class="row">
                <div class="col-6">
                    Connections
                </div>
                <div class="col-6">
                    {{$unit->connectcounter ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->datausagein)
            <div class="row">
                <div class="col-6">
                    Data Usage In
                </div>
                <div class="col-6">
                    {{$unit->datausagein ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->datausageout)
            <div class="row">
                <div class="col-6">
                    Data Usage Out
                </div>
                <div class="col-6">
                    {{$unit->datausageout ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->packageslost)
            <div class="row">
                <div class="col-6">
                    Packageslost
                </div>
                <div class="col-6">
                    {{$unit->packageslost ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->rebootcounter)
            <div class="row">
                <div class="col-6">
                    Reboots
                </div>
                <div class="col-6">
                    {{$unit->rebootcounter ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->lastackcommand)
            <div class="row">
                <div class="col-6">
                    Last Ack Sent
                </div>
                <div class="col-6">
                    {{$unit->lastackcommand ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->rssi)
            <div class="row">
                <div class="col-6">
                    RSSI
                </div>
                <div class="col-6">
                    {{$unit->rssi ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
            @if($unit->sequencenumber)
            <div class="row">
                <div class="col-6">
                    Sequencenumber
                </div>
                <div class="col-6">
                    {{$unit->sequencenumber ?? ''}}
                </div>
            </div>
            <hr class="m-0">
            @endif
        </div>
    </div>
    <div class="ml-2 mr-2">

    </div>

</div>
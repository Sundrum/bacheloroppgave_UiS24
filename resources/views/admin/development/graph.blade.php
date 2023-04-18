@extends('layouts.app')

@section('content')

    <div class="card-rounded bg-white col-12 p-3">
        <div class="form-row">
            <span class="mt-3"> Title of Graph </span>
            <input type="text" id="graph_name" value="Title" class="form-control">
        </div>
        <div class="form-row">
            <span class="mt-2"> Data (seperated by , ) </span>
            <input type="text" id="graph_input" placeholder="E.g. 12.3,1,12.123,-1.2,-8.9" class="form-control">
        </div>
        <div class="row mt-2">
            <div class="col text-center">
                <h4 id="titlegraph" class="mt-5">Title</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card-body">        
                    <div id="container" style="height: 600px; min-width: 310px"></div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById("top-title").innerHTML = 'Graph @ Dev';

    // graph_input.addEventListner("oninput", handleString());
    graph_input.addEventListener("input", handleString);
    graph_name.addEventListener("input", changeTitle);

    let data = [];
    let seriesOptions = [];
    var strDateTimeStart = Date.now();
    console.log(strDateTimeStart)

    function handleString() {
        if(this.value.includes(",")) {
            let tempArray = this.value.split(',');
            let data = [];
            for(var i = 0; i < tempArray.length-1; i++) {
                data[i] = convert(tempArray[i]);
            }
            seriesOptions[0] = {
                name: 'Input',
                data: data,
                visible: true,
                pointStart: 1
            };
            createChart();
            console.log(data);  
        }
    }

    function convert(x) {
        var floatValue = +(x);
        return floatValue;
    }

    function changeTitle() {
        document.getElementById("titlegraph").innerHTML = this.value;
    }

    
    function createChart() {
        Highcharts.chart('container', {
            title: {
                text: ''
            },

            xAxis: {
                tickInterval: 1,
                type: '',
                accessibility: {
                    rangeDescription: 'Range: 1 to 10'
                }
            },

            yAxis: {
                type: '',
                minorTickInterval: 1,
                accessibility: {
                    rangeDescription: 'Range: -150 to 150'
                }
            },

            tooltip: {
                headerFormat: '<b>{series.name}</b><br />',
                pointFormat: 'x = {point.x}, y = {point.y}'
            },
            
            credits: {
                enabled: false
            },

            plotOptions: {
                series: {
                    marker: {
                        enabled: false,
                        states: {
                            hover: {
                                enabled: false
                            }
                        }
                    }
                }
            },

            series: seriesOptions
        });
    }
</script>
@endsection
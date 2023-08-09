@extends('layouts.app')

@section('content')

<main id="app" class="bg-grey">
    <div class="container-fluid"></div>
</main>

<script>
/* 2022 | Anne-Stine Gjervoldstad */

/* Storage for all html to be rendered */

let html = ""

/* Time stamps */

d = new Date()
yyyy = d.getFullYear()
mm = d.getMonth() + 1
dd = d.getDate()
hours = d.getHours()
minutes = d.getMinutes()
if (mm < 10) mm = "0" + mm
if (dd < 10) dd = "0" + dd
if (hours < 10) hours = "0" + hours
if (minutes < 10) minutes = "0" + minutes

const newTime = "I dag " + hours + ":" + minutes
const oldTime = dd + "." + mm + "." + yyyy + " " + "06:00"
const futureTime = "14" + ":" + minutes;
const oldIrrTime = "23" + ":" + minutes;
/* Sensor status colors */

const colors =
{
    green: "#a7c49d",
    red: "#efa6a5",
    blue: "#00265a"
}

/* Button texts - Displayed in dropdown section of a sensor's element */

const groupSensorBtn = "Se detaljer"
const irrigationSensorBtn = "Åpne kart"
const modalBtn = "Lukk"

/* Icons and unit details - Remember to add else-if statements in the for-loop in renderGroupSensors so that it corresponds with these objects */

const url = "https://storage.portal.7sense.no/images/dashboardicons/"

const icons =
{
    eta:
    {
        src: url + "eta.png",
        title: "ETA"
    },

    speed:
    {
        src: url + "speed.png",
        title: "Fart"
    },

    tilt:
    {
        src: url + "tilt.png",
        title: "Tilt"
    },

    distance:
    {
        src: url + "distance.png",
        title: "Meter igjen"
    },

    flowrate:
    {
        src: url + "moisture.png",
        title: "Strømning"
    },

    vibration:
    {
        src: url + "vibration.png",
        title: "Vibrasjon"
    },

    temperature:
    {
        src: url + "temperature.png",
        title: "Temperatur",
        unit: "°C"
    },

    humidity:
    {
        src: url + "humidity.png",
        title: "Fuktighet",
        unit: "% RH"
    },

    co2:
    {
        src: url + "carbondioxide.png",
        title: "CO2",
        unit: "PPM"
    },

    battery:
    {
        src: url + "battery_icons/battery_100.png",
        title: "Batteri",
        unit: "V"
    },

    signal:
    {
        src: [url + "rssi_icons/0.png", url + "rssi_icons/1.png", url + "rssi_icons/2.png", url + "rssi_icons/3.png"],
        title: "RSSI",
        unit: "dBm"
    },

    airPressure:
    {
        src: url + "gas.png",
        title: "Trykk",
        unit: "hPa"
    },

    moistUpper:
    {
        src: url + "humidity.png",
        title: "Jordfuktighet øvre",
        unit: "%"
    },

    moistLower:
    {
        src: url + "humidity.png",
        title: "Jordfuktighet nedre",
        unit: "%"
    },

    soilTemperature:
    {
        src: url + "temperature.png",
        title: "Jordtemperatur",
        unit: "%"
    },

    flagNorway:
    {
        src: url + "nor-flag.png",
        title: "NOR",
    },

    flagUK:
    {
        src: url + "uk-flag.png",
        title: "UK",
    }
}

/* Irrigation sensors */

const irrigationSensors =
[
    {
        name: "Ocmis",
        serialnumber: "21-1020-AC-99999",
        isActive: true
    },
    {
        name: "Sovehjelpen",
        serialnumber: "21-1020-AC-99998",
        isActive: true
    },
    {
        name: "Bauer",
        serialnumber: "21-1020-AC-99997",
        isActive: false
    }
    ,
    {
        name: "Ocmis",
        serialnumber: "21-1020-AC-99996",
        isActive: false
    }
]

/* Groups and sensors data */

const groups =
[
    {
        name: "Jordspyd",
        sensors:
        [
            {
                name: "Skifte 1",
                isActive: true,
                serialnumber: "21-1065-AB-99998",
                details:
                {
                    temperature: 14.8,
                    humidity: 66,
                    airPressure: 1001,
                    moistUpper: 16,
                    moistLower: 14,
                    soilTemperature: 11.4
                }
            },
            {
                name: "Skifte 2",
                isActive: true,
                serialnumber: "21-1065-AB-99999",
                details:
                {
                    temperature: 13,
                    humidity: 53,
                    airPressure: 989,
                    moistUpper: 18,
                    moistLower: 6,
                    soilTemperature: 9.3
                }
            }
        ]
    },
    {
        name: "Veksthus",
        sensors:
        [
            {
                name: "Veksthus 1",
                isActive: true,
                serialnumber: "21-1001-AA-99999",
                details:
                {
                    temperature: 24.4,
                    humidity: 79,
                    battery: 5.57,
                    signal: -78
                }
            },
            {
                name: "Veksthus 2",
                isActive: false,
                serialnumber: "21-1001-AA-99998",
                details:
                {
                    temperature: 20.5,
                    humidity: 51,
                    battery: 5.47,
                    signal: -84
                }
            }
        ]
    },
    {
        name: "Lager",
        sensors:
        [
            {
                name: "Ute",
                isActive: true,
                serialnumber: "21-1001-AA-99997",
                details:
                {
                    temperature: -4.4,
                    humidity: 91.95,
                    battery: 5.20,
                    signal: -74
                }
            },
            {
                name: "Toppkasse",
                isActive: true,
                serialnumber: "21-1001-AA-99996",
                details:
                {
                    temperature: 5.43,
                    humidity: 99.98,
                    battery: 5.20,
                    signal: -76
                }
            },
            {
                name: "Bunnkasse",
                isActive: true,
                serialnumber: "21-1001-AA-99995",
                details:
                {
                    temperature: 3.98,
                    humidity: 99.98,
                    battery: 5.20,
                    signal: -75
                }
            },
            {
                name: "Kanal",
                isActive: true,
                serialnumber: "21-1001-AA-99994",
                details:
                {
                    temperature: 3.37,
                    humidity: 70.86,
                    battery: 5.20,
                    signal: -75
                }
            },
            {
                name: "Rom",
                isActive: true,
                serialnumber: "21-1003-AA-99999",
                details:
                {
                    temperature: 3.86,
                    humidity: 99.95,
                    co2: 741,
                    battery: 5.57,
                    signal: -79
                }
            }
        ]
    },
    {
        name: "Kjøler",
        sensors:
        [
            {
                name: "Kjøler 1",
                isActive: true,
                serialnumber: "21-1001-AA-99993",
                details:
                {
                    temperature: 2.4,
                    humidity: 93,
                    battery: 5.31,
                    signal: -65
                }
            },
            {
                name: "Kjøler 2",
                isActive: true,
                serialnumber: "21-1001-AA-99992",
                details:
                {
                    temperature: 1.7,
                    humidity: 75,
                    battery: 4.74,
                    signal: -80
                }
            }
        ]
    },
    {
        name: "Annet",
        sensors:
        [
            {
                name: "Kyllinghus",
                isActive: true,
                serialnumber: "21-1003-AA-99998",
                details:
                {
                    temperature: 24.88,
                    humidity: 56.72,
                    co2: 2172,
                    signal: -66
                }
            },
            {
                name: "Under fiberduk",
                isActive: true,
                serialnumber: "21-1001-AA-99991",
                details:
                {
                    temperature: 32.2,
                    humidity: 37.52,
                    battery: 5.37,
                    signal: -90
                }
            },
            {
                name: "Frostvakt",
                isActive: true,
                serialnumber: "21-1001-AA-99990",
                details:
                {
                    temperature: 0.27,
                    humidity: 68.87,
                    battery: 5.21,
                    signal: -78
                }
            },
            {
                name: "Utstyrsbod",
                isActive: true,
                serialnumber: "21-1001-AA-99989",
                details:
                {
                    temperature: 18.52,
                    humidity: 42.32,
                    battery: 4.88,
                    signal: -103
                }
            },
            {
                name: "Tunnel",
                isActive: true,
                serialnumber: "21-1001-AA-99988",
                details:
                {
                    temperature: 27.38,
                    humidity: 35.68,
                    battery: 4.98,
                    signal: -83
                }
            }
        ]
    }
]
setTitle('Dashboard');
const renderInfoButton = () =>
{
    html += `
    <div class="row justify-content-end mb-2">
        <div class="col-12 float-end">

        <svg class="info-btn svg-inline--fa fa-info-circle fa-w-16 fa-3x fa-fw float-end" style="margin-right: 10px;" data-toggle="modal" data-target="#myInfowindow" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
            <path fill="#212529" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
        </svg>
        <img class="btn-flag image-responsive float-end m-1" width="50" src="${icons.flagUK.src}" onclick="window.location='demo_uk';">

        <img class="btn-flag image-responsive float-end m-1"width="50" src="${icons.flagNorway.src}">
    </div>
    </div>
    <div class="modal fade" id="myInfowindow" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Irrigation information</h5>
                </div>
                <div class="modal-body">
                    <table>
                        <tbody><tr class="spaceUnder">
                            <td> <img src="../img/irrigation/state_0.png" width="50" height="50" class="float-left"> </td>
                            <td class="tdspace"> Ingen kontakt med sensor</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td> <img src="../img/irrigation/state_1.png" width="50" height="50" class="float-left"> </td>
                            <td class="tdspace"> Sovemodus</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td> <img src="../img/irrigation/state_4.png" width="50" height="50" class="float-left"> </td>
                            <td class="tdspace"> Venter på stabil vanning</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td> <img src="../img/irrigation/state_5.png" width="50" height="50" class="float-left"> </td>
                            <td class="tdspace"> Vanningsmodus</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td><img class="image-responsive" src="${icons.tilt.src}" width="50"></td>
                            <td class="tdspace">${icons.tilt.title}</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td><img class="image-responsive" src="${icons.eta.src}" width="50"></td>
                            <td class="tdspace">Estimert tid for ankomst</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td><img class="image-responsive" src="${icons.speed.src}" width="50"></td>
                            <td class="tdspace">${icons.speed.title}</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td><img class="image-responsive" src="${icons.distance.src}" width="50"></td>
                            <td class="tdspace">Gjenstående distanse</td>
                        </tr>
                        <tr class="spaceUnder">
                            <td><img class="image-responsive" src="${icons.airPressure.src}" width="50"></td>
                            <td class="tdspace">${icons.airPressure.title}</td>
                        </tr>
                    </tbody></table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">${modalBtn}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>`
}

const renderIrrigationSensors = () =>
{
    html += `
        <div class="row">`

    irrigationSensors.forEach(sensor =>
    {
        if (sensor.isActive)
        {
            icon = "irrigation/state_5.png"
            timeStamp = newTime
            details = `
                <div class="col text-center my-0">
                    <img class="image-responsive" src="${icons.eta.src}" width="40" height="40" title="${icons.eta.title}" rel="tooltip" alt="">
                    <div class="row">
                        <div class="col my-0">
                            <span><strong>${futureTime}</strong></span>
                        </div>
                    </div>    
                </div>
                <div class="col text-center my-0">
                    <img class="image-responsive" src="${icons.speed.src}" width="40" height="40" title="${icons.speed.title}" rel="tooltip" alt="">
                    <div class="row">
                        <div class="col my-0">
                            <span><strong>21 m/h</strong></span>
                        </div>
                    </div>    
                </div>`
            collapsableDetails = `
                <table align="center" style="position: static; text-align:center; width:100%;">
                    <tbody>
                        <tr>
                            <th><p><strong>${icons.tilt.title}</strong></p></th>
                            <th><p><strong>${icons.eta.title}</strong></p></th>
                            <th><p><strong>${icons.distance.title}</strong></p></th>
                            <th><p><strong>${icons.flowrate.title}</strong></p></th>
                            <th><p><strong>${icons.vibration.title}</strong></p></th>
                        </tr>
                        <tr>
                            <th><img class="image-responsive" src="${icons.tilt.src}" width="40" height="40" title="${icons.tilt.title}" rel="tooltip" alt="" style="transform: rotate(0deg);"></th>                  
                            <th><img class="image-responsive" src="${icons.eta.src}" width="40" height="40" title="${icons.eta.title}" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                            <th><img class="image-responsive" src="${icons.distance.src}" width="40" height="40" title="${icons.distance.title}" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                            <th><img class="image-responsive" src="${icons.flowrate.src}" width="40" height="40" title="${icons.flowrate.title}" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                            <th><img class="image-responsive" src="${icons.vibration.src}" width="40" height="40" title="${icons.vibration.title}" rel="tooltip" alt="" style="margin-top: -20px;"></th>
                        </tr>
                        <tr>
                            <th><p><strong>0</strong> °</p></th>
                            <th><p><strong>${futureTime}</strong></p></th>
                            <th><p><strong>60</strong> m</p></th>
                            <th><p><strong>28</strong> m<sup>3</sup>/h</p></th>
                            <th><p><strong>44.5</strong> %</p></th>
                        </tr>
                    </tbody>
                </table>`
            buttons = `
                <tr>
                    <th>
                        <label class="switch">
                        <input type="checkbox" checked="" class="btn btn-primary">
                        <span class="slider round"></span>
                        </label>
                    </th>
                    <th>
                        <form>
                            <input type="button" class="btn-7s float-right" data-toggle="modal" data-target="#demoIrrMap" value="${irrigationSensorBtn}">
                        </form>
                    </th>
                </tr>
                <tr>
                    <th>
                        <p><strong>AV / PÅ</strong></p>
                    </th>
                </tr>`
        }
        else
        {
            icon = "irrigation/state_1.png"
            timeStamp = oldTime
            details = `
                <tr> 
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="btn btn-primary">
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>`
            collapsableDetails = ""
            buttons = `
                <tr>
                    <th>
                        <form action="">
                            <input type="button" class="btn-7s float-right" value="${irrigationSensorBtn}">
                        </form>
                    </th>
                </tr>`
        }

        html +=`
            <div class="col-md-6">
                <div class="col-12 bg-white card-rounded mb-2">
                    <div class="p-3">
                        <a class="collapse-toggle" data-toggle="collapse" aria-hidden="true"></a>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <h5>${sensor.name}</h5>
                            </div>
                            <div class="col-12 col-md-4">
                                <span class="float-end">${timeStamp}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col my-0">
                                <a data-toggle="modal" data-target="#demoIrrMap"><img src="../img/${icon}" class="float-left"></a>
                            </div>                            
                            ${details}
                            <div class="col my-0">
                                <svg class="caret svg-inline--fa fa-caret-down fa-w-10 fa-3x fa-fw float-end" data-toggle="collapse" style="color: ${colors.blue};" aria-hidden="true" data-target="#collapse${sensor.serialnumber}" focusable="false" data-prefix="fa" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path>
                                </svg>
                            </div>
                        </div>`
                        if (sensor.isActive) {
                            html += `
                            <div class="row mt-2 mb-0">
                                <div class="col-12 mb-0">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6>${oldIrrTime}</h6>
                                        </div>
            
                                        <div class="col-6 text-end">
                                            <h6>${futureTime}</h6>
                                        </div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-line" style="width: 60%;"></div>
                                    </div>
                                </div>
                            </div>`
                        }

            html += `
                        <div class="bg-white collapse" id="collapse${sensor.serialnumber}">
                            <div class="pt-4 pb-5 mb-3">
                                ${collapsableDetails}
                                <table align="left" style="position: static; text-align:center; width:40%;">
                                    <tbody>
                                        ${buttons}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`

    })
    html += `
        </div>`
}

const renderGroupSensors = () =>
{
    html += `<div class="row px-2">`
    groups.forEach(group =>
    {
        html += `
        <div class="col-12 col-lg-6">
            <div class="card-rounded bg-white mb-2 numberOfgroups">
                <div class="row">
                    <div class="col-12">
                        <h3 class="pt-3 px-3">${group.name}</h3>
                    </div>
                </div>
                <ul class="px-2 py-2" style="list-style-type: none;">`
        
        group.sensors.forEach(sensor =>
        {
            if (sensor.isActive)
            {
                color = colors.green
                timeStamp = newTime
            }
            else
            {
                color = colors.red
                timeStamp = oldTime
            }

            html += `
            <hr class="my-0">
            <li class="bg-white row mx-1 py-1" data-toggle="collapse" data-target="#collapse${sensor.serialnumber}">
                <div class="col-12 col-md-5" style="cursor: pointer;">
                    <div class="row">
                        <span class="circle-1" style="background-color: ${color};"></span>
                        <div class="col mt-0 pt-0 mb-0">
                            <h5 class="mt-0 pt-0 mb-0">${sensor.name}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7 text-end" style="cursor: pointer;">
                    <span class="px-1">
                            <img class="image-responsive" src="${icons.temperature.src}" width="25" height="25" title="${icons.temperature.title}" rel="tooltip" alt="" >
                            <span class="sensor-icon-front px-1">${sensor.details.temperature.toFixed(1)} ${icons.temperature.unit}</span>
                    </span>
                    <span class="px-1">
                            <img class="image-responsive" src="${icons.humidity.src}" width="25" height="25" title="${icons.humidity.title}" rel="tooltip" alt="" >
                            <span class="sensor-icon-front px-1">${sensor.details.humidity.toFixed(0)} ${icons.humidity.unit}</span>
                    </span>
                </div>
                <div class="collapse" id="collapse${sensor.serialnumber}">
                    <div class="row justify-content-center">
                        <div class="col-6 text-right">
                            <span class="sensor-subtitle">${sensor.serialnumber}</span>
                        </div>
                        <div class="col-6 text-end">
                            <span class="sensor-subtitle">${timeStamp}</span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        `

                        
            for (i = 0; i < Object.keys(sensor.details).length; i++)
            {
                const key = Object.keys(sensor.details)[i]
                const value = Object.values(sensor.details)[i]
    
                if (key === "temperature")
                {
                    src = icons.temperature.src
                    title = icons.temperature.title
                    unit = icons.temperature.unit
                }
                else if (key === "humidity")
                {
                    src = icons.humidity.src
                    title = icons.humidity.title
                    unit = icons.humidity.unit
                }
                else if (key === "co2")
                {
                    src = icons.co2.src
                    title = icons.co2.title
                    unit = icons.co2.unit
                }
                else if (key === "battery")
                {
                    src = icons.battery.src
                    title = icons.battery.title
                    unit = icons.battery.unit
                }
                else if (key === "signal")
                {
                    title = icons.signal.title
                    unit = icons.signal.unit
                    if (value < -100) src = icons.signal.src[0]
                    else if (value < -90) src = icons.signal.src[1]
                    else if (value < -80) src = icons.signal.src[2]
                    else src = icons.signal.src[3]
                }
                else if (key === "airPressure")
                {
                    src = icons.airPressure.src
                    title = icons.airPressure.title
                    unit = icons.airPressure.unit
                }
                else if (key === "moistUpper")
                {
                    src = icons.moistUpper.src
                    title = icons.moistUpper.title
                    unit = icons.moistUpper.unit
                }
                else if (key === "moistLower")
                {
                    src = icons.moistLower.src
                    title = icons.moistLower.title
                    unit = icons.moistLower.unit
                }
                else if (key === "soilTemperature")
                {
                    src = icons.soilTemperature.src
                    title = icons.soilTemperature.title
                    unit = icons.soilTemperature.unit
                }
                else console.error("No details found")
    
                html += `
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">
                    <div class="row justify-content-center my-0">
                        <div class="col my-0">
                            <span>${title}</span>
                        </div>
                    </div>
                    <div class="row justify-content-center my-0">
                        <div class="col my-0">
                            <img class="image-responsive" src="${src}" width="30" height="30" title="${title}" rel="tooltip" alt="">
                        </div>
                    </div>
                    <div class="row justify-content-center my-0">
                        <div class="col my-0">
                            <span>${value.toFixed(1)} ${unit}</span>
                        </div>
                    </div>
                </div>`

            }
            /* Sensor closing tags */
            html +=`
                        <div class="col-12 text-center">
                            <button class="btn-7g" href="#">${groupSensorBtn}</button>
                        </div>
                    </div>
                </div>
            </li>`
        })
        /* Group closing tags */
        html += `
                </ul>
            </div>
        </div>`
    })
    html += `</div>`   
}

/* Reused modal to display irrigation map demo image */

const renderIrrigationMap = () =>
{
    html += `
        <div class="modal fade" id="demoIrrMap" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img class="image-responsive" src="/img/demo_irr_map_mobile.webp" width="100%">
                        <div class="modal-footer">
                            <button type="button" class="btn-7g" data-dismiss="modal">${modalBtn}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`
}

window.addEventListener("load", () =>
{
    renderInfoButton()
    renderIrrigationSensors()
    renderGroupSensors()
    renderIrrigationMap() // Popup for irrigationSensorBtn

    document.querySelector(".container-fluid").innerHTML += html
})

/* Collapsable sensor details */

const toggleSensorDetails = (e) =>
{
    const caret = e.target.closest("svg")

    caret.classList.contains("fa-caret-down") ?
    caret.classList.replace("fa-caret-down", "fa-caret-left") :
    caret.classList.replace("fa-caret-left", "fa-caret-down")

    const detailSection = caret.closest("div:not(.col-3)").nextElementSibling

    !detailSection.classList.contains("show") ?
    detailSection.classList.add("show") :
    detailSection.classList.remove("show")
}

/* Listen for caret icon clicks/touch */

document.addEventListener('click', (e) =>
{
    if (e.target.closest("svg").classList.contains("caret")) toggleSensorDetails(e)
})

</script>

<style>
    /* Compensate for broken page structure from extending Laravel layout to this custom page */

    /* Force external navbar to top */

    .navbar
    {
        position: absolute !important;
        top: 0;
        width: 100%
    }

    /* Fix irrigation sensors not filling container when info button is present */

    .container-fluid > div
    {
        width: 100%;
    }

    /* Add space above main container to compensate for navbar being forced to top */

    .py-4
    {
        margin-top: 4.25rem;
    }

    /* First sensor unit icons were not aligned due to different top margins  */

    .row > .col
    {
        margin-top: 1.5rem;
    }

    /* Icon with .dot class was not displaying */

    .dot
    {
        display: block;
        border-radius: 50%;
        width: 25px;
        height: 25px;
    }

    /* Add group icon before h4 text */

    h4::before
    {
        content: "";
        display: inline-block;
        width: 24px;
        height: 24px;
        margin-right: .5rem;
        transform: translate(0, 15%);
        background: url('data:image/svg+xml,%3Csvg class="svg-inline--fa fa-layer-group fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="layer-group" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""%3E%3Cpath fill="%23212529" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"%3E%3C/path%3E%3C/svg%3E');
    }
    
</style>

@endsection

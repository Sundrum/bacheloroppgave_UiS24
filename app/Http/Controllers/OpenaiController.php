<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Models\Api;
class OpenaiController extends Controller
{
    public function groupAnalyze(Request $req){
        $array = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a agronomist assistant, skilled in explaining complex analyzes in a simple and understanding way."
                ],
                [
                    "role" => "user",
                    "content" => "Analyser sensordataen fra datasettet, som tilhører en gruppe med sensorer som står i et potetlager. Kan du gi meg en oversikt over dagens status. Gjerne med eventuelle tiltak som bør gjøres. 
                    Datasett: 
                    CO2 Gammel potetlager, online, 6.7°C , 100%RH,øvregrenseverdi:9°C,nedregrenseverdi:3°C
                    Potetlager gammelt 1, online, 6.7°C , 100%RH,øvregrenseverdi:9°C,nedregrenseverdi:3°C
                    Potetlager gammelt 2, online, 6.2°C , 100%RH,øvregrenseverdi:9°C,nedregrenseverdi:3°C
                    Potetlager gammelt 10 antenne, online, 5.7°C , 100%RH,øvregrenseverdi:9°C,nedregrenseverdi:3°C"
                ]
            ]
        ];
        $response = Api::postOpenAi($array);
        return $response;
    }
}

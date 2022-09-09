<?php

namespace App\Http\Controllers\Development;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api;
use App\Models\Sensorunit;

class MapController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }

    public function getMidMarker(Request $request){
        $sensor = $request->input('sensor');
        $thisStart = $request->input('start');
        $thisEnd = $request->input('end');
        $thisStart = explode(".", $thisStart);

        if($thisEnd == null){
            echo("End null.");
        }
        else{
            $thisEnd = explode(".", $thisEnd);
        }

        $data = Api::getApi('sensorunits/data?serialnumber='.$sensor.'&timestart='.$thisStart[0].'&timestop='.$thisEnd[0].'&sortfield=timestamp');
        // dd($data);
        $marker = array();
        $lat = null;
        $lng = null;
        $test = null;
        $count = 0;
        for($i = 0; $i < count($data['result']); $i++){
            if($data['result'][$i]['probenumber'] == 2) $lat = $data['result'][$i]['value'];
            if($data['result'][$i]['probenumber'] == 3) $lng = $data['result'][$i]['value'];
            if($data['result'][$i]['probenumber'] == 4) $test = $data['result'][$i]['value'];
            if($lat == null || $lng == null){
                
            }
            else if ($lat != null && $lng != null){
                $marker[$count]['lat'] = $lat;
                $marker[$count]['lng'] = $lng;
                $lat = null;
                $lng = null;
                $count++;
            }
            else{
                $lat = null;
                $lng = null;
            }
        }

        try {
            return json_encode($marker);
        }catch (Exception $e){
            return response()->json(array('err'=>'error'));
       }
    }

    public function showCopyRun(){
        // Function to check if a value can be found in an array

        function multi_in_array($value, $array)
        {
            foreach ($array AS $item)
            {
                if (!is_array($item))
                {
                    if ($item == $value)
                    {
                        return true;
                    }
                    continue;
                }

                if (in_array($value, $item))
                {
                    return true;
                }
                else if (multi_in_array($value, $item))
                {
                    return true;
                }
            }
            return false;
        }

        // Add customername and related sensor units to array

        $data = Api::getApi('sensorunits/list?productnumber=21-1020-AA&sortfield=customer_name');
        // $modem->units = Sensorunit::where('serialnumber', 'like', '%'.$modem->serialnumber.'%')->join('products', 'products.id', 'sensorunits.products_id_ref')->select('sensorunits.id','sensorunits.name','sensorunits.customers_id_ref','sensorunits.products_id_ref','sensorunits.serialnumber','sensorunits.severity','sensorunits.install_groups_id_ref','products.productnumber','products.name AS productname')->orderby('name')->get();
        $customerName = array();
        
        $arrayPlace = 0;
        // Sette inn data for $customerName
        for($i = 0; $i < count($data['result']); $i++){
            if(!multi_in_array(trim($data['result'][$i]['customer_name']),$customerName)){
                $customerName[$arrayPlace]['name'] = trim($data['result'][$i]['customer_name']);
                $customerName[$arrayPlace]['sensor_id'][] = trim($data['result'][$i]['serialnumber']);
                $customerName[$arrayPlace]['sensor_name'][trim($data['result'][$i]['serialnumber'])] = trim($data['result'][$i]['sensorunit_location']);
                // $customerName[$arrayPlace]['sensor_name'][trim($data['result'][$i]['serialnumber'])] Her kan trim(...) fjernes for å
                // vise tallverdi i stedet for sensor_ID i arrayet. Hensikten med å bruke trim(...) er å vise hvilke ID-er som hører
                // til hvilke navn, men dette kan kjapt sees uten bruken av trim(...) ved oppsettet vi bruker. Dersom trim(...) brukes
                // her, må man huske å bruke det senere for å finne navn etter behov, men dette skal være en enkel jobb da alt
                // befinner seg i arrayet allerede.
                $arrayPlace++;
            }
            else{
                $customerName[$arrayPlace-1]['sensor_id'][] = trim($data['result'][$i]['serialnumber']);
                $customerName[$arrayPlace-1]['sensor_name'][trim($data['result'][$i]['serialnumber'])] = trim($data['result'][$i]['sensorunit_location']);
                // $customerName[$arrayPlace]['sensor_name'][trim($data['result'][$i]['serialnumber'])] Her kan trim(...) fjernes for å
                // vise tallverdi i stedet for sensor_ID i arrayet. Hensikten med å bruke trim(...) er å vise hvilke ID-er som hører
                // til hvilke navn, men dette kan kjapt sees uten bruken av trim(...) ved oppsettet vi bruker. Dersom trim(...) brukes
                // her, må man huske å bruke det senere for å finne navn etter behov, men dette skal være en enkel jobb da alt
                // befinner seg i arrayet allerede.
            }
        }

        // Add Sensorunit and related run to array

        $data = Api::getApi('sensorunits/list?productnumber=21-1020-AA');
        $sensorID = array();
        for($i = 0; $i < count($data['result']); $i++){
            if(!multi_in_array(trim($data['result'][$i]['serialnumber']),$sensorID)){
                $sensorID[] = trim($data['result'][$i]['serialnumber']);
            }
        }
        sort($sensorID);
        $apiStart = 'irrigation/runlog/list?serialnumber=';
        $apiEnd = '&sortfield=irrigation_run_id';
        $sensorData = array();
        $startLatLng = array();
        $endLatLng = array();
        $count = 0;
        for($i = 0; $i < count($sensorID); $i++){
            $data = Api::getApi($apiStart.$sensorID[$i].$apiEnd);
            for($j = 0; $j < count($data['result']); $j++){
                // For de neste 4 linjene er $j+1 ID-en for hvert run. Dette må husker senere når array hentes opp da plass 0
                // ikke er brukt, og plass 1 er første del av arrayet.

                // Her blir kun sensorer som har minst 1 run inkludert. Grunnen er at count($data['result']) returnerer et tall som
                // må være høyere enn 0 (altså minst 1) for å kunne kjøre for-loopen. Ulempen er at 48 sensorer blir ekskludert, mens
                // fordelen er at vi ignorerer data vi ikke trenger direkte. Merk at dette kan skape problemer i map2.blade.php da vi
                // ikke har noe data å sammenligne ID-en med, men dette kan løses ved å legge inn null-verdier som vises som "tomt".
                $startLatLng[0] = null;
                $startLatLng[1] = null;
                $endLatLng[0] = null;
                $endLatLng[1] = null;
                if ($data['result'][$j]['irrigation_startpoint'] != null){
                    $startLatLng = explode(',',$data['result'][$j]['irrigation_startpoint']);
                }
                if ($data['result'][$j]['irrigation_endpoint'] != null){
                    $endLatLng = explode(',',$data['result'][$j]['irrigation_endpoint']);
                }
                $sensorData[$count][$j+1]['sensor_id'] = $data['result'][$j]['serialnumber'];
                $sensorData[$count][$j+1]['irrigation_starttime'] = $data['result'][$j]['irrigation_starttime'];
                $sensorData[$count][$j+1]['irrigation_endtime'] = $data['result'][$j]['irrigation_endtime'];
                $sensorData[$count][$j+1]['irrigation_startpoint']['lat'] = $startLatLng[0];
                $sensorData[$count][$j+1]['irrigation_startpoint']['lng'] = $startLatLng[1];
                $sensorData[$count][$j+1]['irrigation_endpoint']['lat'] = $endLatLng[0];
                $sensorData[$count][$j+1]['irrigation_endpoint']['lng'] = $endLatLng[1];
            }
            $count++;
            if(count($data["result"]) == null) {
                $count--;
            }
        }
        return view('admin.development.irrigation.copy_run', compact('customerName', 'sensorData'));
    }
}

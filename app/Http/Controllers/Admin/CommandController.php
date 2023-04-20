<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api;
use App\Models\Product;
use Auth, Log;
use DateTime, Redirect;

class CommandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    
    public function viewFirmware() {
        $data = Api::getProxy('queue/list');
        $result = array();
        $i = 0;
        foreach ($data['result'] as $row) {
            $result[$i][0] = $row['queue_id'];
            $result[$i][1] = $row['serialnumber'];
            $result[$i][2] = $row['data'];
            $result[$i][3] = $row['statustext'];
            $result[$i][4] = self::convertToDate($row['dateupdated']);
            $result[$i][5] = self::convertToDate($row['dateadded']);
            $i++;
        }
        $data = json_encode($result);
        return view('admin.firmware.view', compact('data'));
    }

    public static function queueList($serial) {
        $data = Api::getProxy('queue/list?serialnumber='.$serial);
        $result = array();
        $i = 0;
        foreach ($data['result'] as $row) {
            $result[$i][0] = trim($row['queue_id']);
            if ($row['statusid'] == 1) {
                $result[$i][1] = '<div class="ml-2 spinner-border text-secondary" title="'.$row['statustext'].'" role="status"><span class="sr-only">Loading...</span></div>';
            } else if($row['statusid'] == 2){
                $result[$i][1] = '<i class="ml-3 fas fa-lg fa-arrow-right text-secondary" title="'.$row['statustext'].'"></i>';
            } else if($row['statusid'] == 3){
                $result[$i][1] = '<div class="ml-2 spinner-grow text-success" role="status" title="'.$row['statustext'].'"><span class="sr-only">Loading...</span></div>';
            } else if($row['statusid'] == 4){
                $result[$i][1] = '<i class="ml-3 fa fa-lg fa-check text-success" aria-hidden="true" title="'.$row['statustext'].'"></i>';
            } else if($row['statusid'] == 5){
                $result[$i][1] = '<i class="ml-3 fas fa-lg fa-times text-danger" title="'.$row['statustext'].'"></i>';
            } else {
                $result[$i][1] = $row['statusid'];
            }
            $result[$i][2] = trim($row['comment']).' - '.trim($row['data']);
            $result[$i][3] = self::convertToDate($row['dateadded']);
            $i++;
        }
        $data = json_encode($result);
        return $data;
    }

    public static function firmwareList($serial){
        $data = Api::getProxy('firmware/list?serialnumber='.$serial);
        $result = array();
        if (count($data) > 0 && isset($data['result'])) {
            foreach ($data['result'] as $row) {
                if($row['released']) {
                    $result['released'][] = $row;
                } else {
                    $result['notreleased'][] = $row;
                }
            }
        }
        return $result;
    }
    // GET VARIABLES
    public function irrigationFota(Request $req) {
        $response = array();
        if($req->cmd == 'fota') $response[0] = Api::postProxy('firmware/upgrade?serialnumber='.$req->serialnumber.'&firmware_id='.$req->fw);
        else if ($req->cmd == 'settings') $response[0] = Api::postProxy('queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode('Get user settings from unit').'&data='.base64_encode('11'));
        else if($req->cmd == 'setsettings') $response[0] = Api::postProxy('queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode(Auth::user()->user_name. ' added command to Queue.').'&data='.base64_encode($req->commandline));

        if(count($response) > 0) {
            $feedback;
            $feedcounter = 0;
            foreach ($response as $row) {
                if($row['result'] == 'OK') {
                    $feedback = '1';
                } else {
                    $feedcounter++;
                }
            }
        } else {
            $feedback = '0';
        }
        if ($feedcounter > 0) {
            $feedback = '2';
        }
        return $feedback;
    }

    public function deleteQueue(Request $req) {
        $respone = array();
        if(count($req->array) > 0 ) {
            foreach ($req->array as $row){
                $response[] = Api::deleteProxy('queue/delete?queue_id='.$row);
            }
        }
        return $response;
    }

    public function uploadFirmware(Request $req){

        $response = array();
        $firmware_binary = fread(fopen($req->firmware->getPathName(), "r"), filesize($req->firmware->getPathName()));
        $firmware_image = base64_encode($firmware_binary);
        // $url =firmware='.$firmware_image;
        $url = 'https://api.smartsensor.no/v1/firmware/add';

        $obj = array();
        $obj['productnumber'] = $req->productnumber;
        // $obj['firmwarenumber'] = $req->productnumber;
        $obj['version'] = $req->version;
        $obj['firmwarename'] = $req->fw_name;
        $obj['released'] = $req->released;
        $obj['firmware'] = $firmware_image;
        $json_obj = json_encode($obj);
        $message = self::curl_post($url, $json_obj);
        $variable['products'] = Product::all();
        $firmware = Api::getProxy('firmware/list');
        return view('admin.firmware.firmware', compact('firmware', 'variable'))->with('message', $message);
    }

    public function curl_post($url, $post = NULL, array $options = array()) { 
        $defaults = array( 
            CURLOPT_POST => 1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HEADER => 0, 
            CURLOPT_URL => $url, 
            CURLOPT_FRESH_CONNECT => 1, 
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 0,
            CURLOPT_FORBID_REUSE => 1, 
            CURLOPT_TIMEOUT => 4, 
            CURLOPT_POSTFIELDS => $post,	// http_build_query()
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Content-Length: ' . strlen($post)]
        ); 

        $ch = curl_init(); 
        curl_setopt_array($ch, ($options + $defaults)); 
        if( ! $result = curl_exec($ch)) 
        { 
            trigger_error(curl_error($ch)); 
        } 
        curl_close($ch); 
        return $result; 
    }

    public function showFirmware(){
        $variable['products'] = Product::all();
        $firmware = Api::getProxy('firmware/list');
        return view('admin.firmware.firmware', compact('firmware', 'variable'));
    }


    public function deleteFirmware(Request $req){
        if(count($req->array) > 0 ) {
            foreach ($req->array as $row){
                $response[$row]['api'] = Api::deleteProxy('firmware/delete?firmware_id='.$row);
            }
        }
        $feedback = "Deleting of Firmware ID";
        foreach ($response as $key => $value) {
            $feedback .=" ". $key . " - ". ($value['api']['result'] ?? 'Something went wrong!');
        }
        return json_encode($response);
    }

    public static function changeFirmware(Request $req) {
        $response = array();

        if(count($req->array) > 0 ) {
            foreach ($req->array as $row){
                $firmware = DB::connection('7portal')->select('SELECT * FROM firmware WHERE firmware_id = ? LIMIT 1', [$row]);
                foreach ($firmware as $fw) {
                    if ($fw->released) {
                        $response[] = DB::connection('7portal')->update('UPDATE firmware SET released=false WHERE firmware_id = ?', [$row]);
                    } else {
                        $response[] = DB::connection('7portal')->update('UPDATE firmware SET released=true WHERE firmware_id = ?', [$row]);
                    }
                }
            }
        }

        $counter = 1;
        foreach ($response as $row) {
            if ($row != 1) {
                $counter++;
            }
        }
        return $counter;
    }
}

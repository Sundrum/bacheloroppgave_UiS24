<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Api;
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
        $data = Api::getQueue('queue/list');
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
        $data = Api::getQueue('queue/list?serialnumber='.$serial);
        $result = array();
        $i = 0;
        if(isset($data['result'])) {
            foreach ($data['result'] as $row) {
                $result[$i][0] = trim($row['queue_id']);
                if ($row['statusid'] == 1) {
                    $result[$i][1] = '<div class="ml-2 spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>';
                } else if($row['statusid'] == 2){
                    $result[$i][1] = '<i class="ml-3 fas fa-lg fa-arrow-right text-secondary"></i>';
                } else if($row['statusid'] == 3){
                    $result[$i][1] = '<div class="ml-2 spinner-grow text-success" role="status"><span class="sr-only">Loading...</span></div>';
                } else if($row['statusid'] == 4){
                    $result[$i][1] = '<i class="ml-3 fa fa-lg fa-check text-success" aria-hidden="true"></i>';
                } else if($row['statusid'] == 5){
                    $result[$i][1] = '<i class="ml-3 fas fa-lg fa-times text-danger"></i>';
                } else {
                    $result[$i][1] = $row['statusid'];
                }
                $result[$i][2] = trim($row['comment']).' - '.trim($row['data']);
                $result[$i][3] = self::convertToDate($row['dateadded']);
                $i++;
            }
        }
        $data = json_encode($result);
        return $data;
    }

    public static function firmwareList($serial){
        $data = Api::getQueue('firmware/list?serialnumber='.$serial);
        $result = array();
        if(isset($data['result'])) {
            if (count($data) > 0) {
                foreach ($data['result'] as $row) {
                    if($row['released']) {
                        $result['released'][] = $row;
                    } else {
                        $result['notreleased'][] = $row;
                    }
                }
            }
        }

        return $result;
    }
    // GET VARIABLES
    public function irrigationFota(Request $req) {
        $response = array();
        if($req->cmd == 'fota') {
            $queue = Api::getQueue('queue/list?serialnumber='.$req->serialnumber.'&typeid=1');
            if(count($queue) > 0 && isset($queue['result']) && count($queue['result']) > 0) {
                foreach ($queue['result'] as $row) {
                    if($row['statusid'] == 1) {
                        Api::deleteQueue('queue/delete?queue_id='.$row['queue_id']);
                    }
                }
            }
            $response[0] = Api::postQueue('firmware/upgrade?serialnumber='.$req->serialnumber.'&firmware_id='.$req->fw);// 1
            // run after 1 is accepted
            $response[1] = Api::postQueue('queue/add?serialnumber='.$req->serialnumber.'&typeid=2&data=10&comment=GetVersion:Upgrade&encoding=plain'); // 2
            // run last
            $response[2] = Api::postQueue('queue/add?serialnumber='.$req->serialnumber.'&typeid=2&data=9,6000,portal.7sense.no&comment=Command:Upgrade&encoding=plain'); // 3 
            // run after 1 is accepted
            // wait(3600);
            $response[3] = Api::postQueue('queue/add?serialnumber='.$req->serialnumber.'&typeid=2&data=10&comment=GetVersion:Upgrade&encoding=plain'); // 4
        } else if ($req->cmd == 'settings') {
            $comment = 'Get all data from portal';
            $data = '1;4,0;4,1;4,2;4,3;4,4;6;7,0';
            // changed $data1 = '7,1;7,2;7,3;7,4;8,0,0;8,1,0;8,3,0;8,4,0;8,2,2';
            $data1 = '7,1;7,2;7,3;7,4;8,0,0;8,1,0;8,3,0;8,4,0;8,2,0'; // default

            $url1 = 'queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode($comment).'&data='.base64_encode($data);
            $response[0] = Api::postQueue($url1);

            $url2 = 'queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode($comment).'&data='.base64_encode($data1);
            $response[1] = Api::postQueue($url2);
        } else if ($req->cmd == 'settingsdeafult') {
            $comment = 'Deafult settings from portal';
            $data = '5;10;11';
            $url1 = 'queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode($comment).'&data='.base64_encode($data);
            $response[0] = Api::postQueue($url1);
        } else if ($req->cmd == 'changeserial') {
            $comment = 'Change Serialnumber to '.$req->newserial;
            $data = '13,'.$req->newserial.','.$req->imei;
            $url1 = 'queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode($comment).'&data='.base64_encode($data);
            $response[0] = Api::postQueue($url1);
        } else if($req->cmd == 'setsettings') {
            $comment = 'Change settings from portal';
            $data = $req->commandline;
            $url1 = 'queue/add?serialnumber='.$req->serialnumber.'&typeid=2&comment='.rawurlencode($comment).'&data='.base64_encode($data);
            $response[0] = Api::postQueue($url1);
        }

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
                $response[] = Api::deleteQueue('queue/delete?queue_id='.$row);
            }
        }
        return $response;
    }

    public function uploadFirmware(Request $req){

        $response = array();
        $firmware_binary = fread(fopen($req->firmware->getPathName(), "r"), filesize($req->firmware->getPathName()));
        $firmware_image = base64_encode($firmware_binary);
        // $url =firmware='.$firmware_image;
        $url = 'http://localhost:46001/v1/firmware/add';

        $obj = array();
        $obj['productnumber'] = $req->productnumber;
        $obj['firmwarenumber'] = $req->productnumber;
        $obj['version'] = $req->version;
        $obj['firmwarename'] = $req->fw_name;
        $obj['released'] = $req->released;
        $obj['firmware'] = $firmware_image;
        $json_obj = json_encode($obj);
        $message = self::curl_post($url, $json_obj);
        return Redirect::to('admin/firmware?message='.$message);
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
        $firmware = Api::getQueue('firmware/list');
        return view('admin.firmware.firmware', compact('firmware'));
    }


    public function deleteFirmware(Request $req){
        $response = array();
        if(count($req->array) > 0 ) {
            foreach ($req->array as $row){
                $response[] = DB::connection('7portal')->delete('DELETE FROM firmware WHERE firmware_id = ?', [$row]);
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

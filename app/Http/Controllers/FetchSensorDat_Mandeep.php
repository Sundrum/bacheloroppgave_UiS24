<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensordata;
/* 
  ---THis Controller Class is for Mandeep---
 */
class FetchSensorDat_Mandeep extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        /* $this->middleware('checkadmin'); */
       

      
    }


     /* Fetch all the daily sensor data */
     public function sensorData_daily($serial_no) {
       
     $result_daily = Sensordata::getSensordata_daily($serial_no);
      
     echo "<pre>";
      print_r($result_daily);
      echo "</pre>";
      return view('pages/profilemandeep');
     
   
     
    }

     /* Fetch daily probe number */
    public function probeNo_daily($serial_no) {
       $prob_no = 9;
       $result_dailyprobe = Sensordata::getProbenumber_daily($serial_no,$prob_no);
       echo "<pre>";
       print_r($result_dailyprobe);
       echo "</pre>";
       return view('pages/profilemandeep');
    }
     public function sensorData_weekly() {
        
     }
     
     public function sensorData_monthly() {
        
     }

     public function sensorData_yearly() {
        
     }

}

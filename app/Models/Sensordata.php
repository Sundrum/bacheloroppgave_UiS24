<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Sensorunit;


class Sensordata extends Model
{
        
     public static function changeDBConnection($db_name) {
        Config::set('database.connections.7sensor.database', $db_name);
        
    }


    public static function getSensordata_daily($serial_no) {
        
     $unit = Sensorunit::where('serialnumber',$serial_no)->first(); 
     self::changeDBConnection($unit->dbname);
     $result = DB::connection('7sensor')->table('sensordata_daily')->where('serialnumber',$serial_no)->orderBy('timestamp','desc')->get();
     return $result;
    }


    public static function getProbenumber_daily($serial_no,$probeno)  {
        $unit = Sensorunit::where('serialnumber',$serial_no)->first(); 
        self::changeDBConnection($unit->dbname);
        $result_probe = DB::connection('7sensor')
            ->table('sensordata_daily')
            ->where('probenumber',$probeno)
            ->where('serialnumber',$serial_no)
            ->orderBy('timestamp','desc')->get(); 
        
        return $result_probe;
    }
  
}

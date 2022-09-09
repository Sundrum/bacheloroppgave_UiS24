<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GraphController;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\SensorunitVariable;
use App\Models\Treespecies;
use DateTimeZone, DateTime, date, Session;

class GraphController extends Controller {
    /**
    * Fetch the particular company details
    * @return json response
    */
    public static function getUnits() {
      Unit::getUnitsList();
      $sensorunits = Session::get('sensorunits');
      return $sensorunits;
    }

    /**
    * Fetch the particular company details
    * @return json response
    */
    public static function getAllProbes($serial) {
      $probeinformation = Unit::getAllProbes($serial);
      return $probeinformation['result'];
    }
    

    /**
    * Fetch the particular company details
    * @return json response
    */
    public static function getProbeInfo($serialnumber, $probetype) {
      $probeinformation = Unit::getUnitProbeInfo($serialnumber,$probetype);
      return $probeinformation['result'];
    }

  /**
  * Fetch the particular company details
  * @return json response
  */
  public static function getSensorData($serialnumber, $days, $probe, $unittype_id, $time) {

    $data = Unit::getSensorData($serialnumber,$days,$probe);
    // Convert sensordata to graph
    if (is_array($data)) {
      $datatochart = self::formatData($serialnumber, $data, $probe, $unittype_id, $days, $time);
      
      return $datatochart;
    }

    return 'error';
  }

  public static function formatData($serialnumber, $data, $probe, $unittype_id, $days, $time) {
    if ((strpos($serialnumber, '21-1057-') !== false) || (strpos($serialnumber, '21-1058-') !== false) || (strpos($serialnumber, '21-9031-') !== false)) {
      $sensortemp = Unit::getSensorData($serialnumber,$days,0);
    }

    foreach($data['result'] as $item) {
      if ((strpos($serialnumber, '21-1046-') !== false)) {
        if ($unittype_id == 24) {
          $value = $item['value'];
        } else if ($unittype_id == 25) {
          $value = self::convertWoodTemp($item['value']);
        }
      } else if ((strpos($serialnumber, '21-1064-') !== false)) {
        if ($item['value'] == '0') {
          continue;
        } else {
          $value = $item['value'];
        }
      } else if ((strpos($serialnumber, '21-1057-') !== false) || (strpos($serialnumber, '21-1058-') !== false) || (strpos($serialnumber, '21-9031-') !== false)) {
        if ($unittype_id == 24) {
          $sensortemperature = 15;
          if(is_array($sensortemp)) {
            foreach ($sensortemp['result'] as $temp) {
              if ($temp['timestamp'] == $item['timestamp']) {
                $sensortemperature = $temp['value'];
                break;
              }
            }
          }
          $tree_specie = SensorunitVariable::where('sensorunit_variables.serialnumber', $serialnumber)->where('sensorunit_variables.variable', 'tree_species')->first();
          $a = -0.038;
          $b = 1.067;
          if($tree_specie) {
              $tree_temp = Treespecies::find($tree_specie->value);
              $a = $tree_temp->specie_value_a;
              $b = $tree_temp->specie_value_b;
          }
          $value = self::convertWoodMoisture($item['value'], $sensortemperature,$a,$b);

          if (is_nan($value)) {
            continue;
          }

          if (is_infinite($value)) {
            continue;
          }

        } else if ($unittype_id == 25){
          $value = $item['value'];
        } else if ($unittype_id == 33){
          $value = $item['value']/1000000;
        }else {
          $value = $item['value'];
        }
      } else {
        $value = $item['value'];
      }
      
      // $localtime = self::convertTimestampToUserTimezone($item['timestamp']);
      $datetime = new DateTime($item['timestamp']);
      $datetime->setTimezone(new DateTimeZone(self::getUserTimezone()));
      if($time == 2) {
        $localtime = $datetime->format('Y-m-d H:00');
      } else if ($time == 3) {
        $localtime = $datetime->format('Y-m-d 00:00');
      } else {
        $localtime = $datetime->format('Y-m-d H:i:s');
      }
      
      $timestamp = 1000 * strtotime($localtime);
      $result[] = [$timestamp, (float)$value];
    }
    if (isset($result)) {
      return $result;
    } else {
      return [];
      return '-1';
    }
  }
}

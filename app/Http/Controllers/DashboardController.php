<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Sensorprobevariable;
use App\Models\SensorunitVariable;
use App\Models\Treespecies;
use Lang;
use Session, Redirect, DateTime, DateTimeZone;


class DashboardController extends Controller
{
    public static function processIrrigationArray(&$irrigationunits) {
        foreach ($irrigationunits as &$irrUnit) {
            $irrUnit['manipulatedTimestamp'] = self::convertTimestampToUserTimezone($irrUnit['sensorunit_lastconnect']);
            $irrUnit['timestampDifference'] = self::getTimestampDifference($irrUnit['sensorunit_lastconnect']);
            $irrUnit['timestampComment'] = self::getTimestampComment($irrUnit['timestampDifference'], $irrUnit['manipulatedTimestamp']);
        }
    }

    public static function processSensorArray($sensorunits) {
        foreach ($sensorunits['groups'] as &$group) {
            foreach ($group as &$unit) {
                if (is_array($unit)) {
                    $unit['manipulatedTimestamp'] = self::convertTimestampToUserTimezone($unit['sensorunit_lastconnect']);
                    $unit['timestampDifference'] = self::getTimestampDifference($unit['sensorunit_lastconnect']);
                    $unit['timestampComment'] = self::getTimestampComment($unit['timestampDifference'], $unit['manipulatedTimestamp']);
                    foreach ($unit as &$sensor) {
                        if (is_array($sensor) && !empty($sensor)) {
                            self::probeProcess($sensor);
                        }
                    }
                }
            }
        }

        foreach ($sensorunits['sharedgroups'] as &$customer) {
            foreach ($customer as &$group) {
                if (is_array($group)) {
                    foreach ($group as &$unit) {
                        if (is_array($unit)) {
                            $unit['manipulatedTimestamp'] = self::convertTimestampToUserTimezone($unit['sensorunit_lastconnect']);
                            $unit['timestampDifference'] = self::getTimestampDifference($unit['sensorunit_lastconnect']);
                            $unit['timestampComment'] = self::getTimestampComment($unit['timestampDifference'], $unit['manipulatedTimestamp']);
                            foreach ($unit as &$sensor) {
                                if (is_array($sensor) && !empty($sensor)) {
                                    self::probeProcess($sensor);
                                }
                            }
                        }
                    }
                }
            }
        }

        $sensorunits = self::trimAll($sensorunits);

        return $sensorunits;
    }

    // Removes whitespace from all leaf nodes in argument array
    public static function trimAll($array) {
        foreach ($array as $key=>$value){
            if (is_array($value)) {
                $array[$key] = self::trimAll($value);
            } else {
                $array[$key] = trim($value);
            }
        }

        return $array;
    }

    public static function getTimestampComment($timestampDifference, $timestamp) {
        date_default_timezone_set(self::getUserTimezone());
        $secondsToday = time() % 86400;

        if ($timestampDifference < $secondsToday) {
            $comment = Lang::get('dashboard.todayat'). substr($timestamp, 11, 5);
        } else if ($timestampDifference > $secondsToday && $timestampDifference < $secondsToday + 86400) {
            $comment = Lang::get('dashboard.yesterdayat'). substr($timestamp, 11, 5);
        } else {
            $comment = substr($timestamp, 0, 16);
        }

        return $comment;
    }

    public static function startIrrigation(Request $request) {
        $serial = $request->serial;
        $variable = $request->variable;
        $data = Unit::startIrrigation($serial, $variable);

        return $data;
    }

    public static function getOrder () {
        if (Session::get('customernumber')) {
            $customernumber = Session::get('customernumber');
        } else {
            Session::put('customernumber', Auth::user()->customernumber);
            $customernumber = Session::get('customernumber');
        }
        $data = Unit::getGroup($customernumber); // Controler UNIT
        $sharedunits = Session::get('sharedunits');
        $customerunits = Session::get('customerunits');
        $res = array();
        $groups = array();
        $sharedGroups = array();
        
        if (is_array($data)) {
            if (count($data) > 0) {
                foreach($data as $group) {
                    if (isset($group['viewgroup_id'])) {
                        $groups[$group['viewgroup_id']]['viewgroup_name'] = $group['viewgroup_name'];
                        $groups[$group['viewgroup_id']]['viewgroup_description'] = $group['viewgroup_description'];
                        $groups[$group['viewgroup_id']]['viewgroup_id'] = $group['viewgroup_id'];
                    }
                }
            }
        }

        foreach ($customerunits as &$customerunit) {
            $result = Unit::getOrder($customerunit['serialnumber']);
            if (is_array($result)) {
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        $customerunit['viewgroup_id'] = $row['viewgroup_id'];
                        $customerunit['viewgroup_order'] = $row['viewgroup_order'];
                        $groups[$row['viewgroup_id']]['viewgroup_name'] = $row['viewgroup_name'];
                        $groups[$row['viewgroup_id']]['viewgroup_description'] = $row['viewgroup_description'];
                        $groups[$row['viewgroup_id']]['viewgroup_id'] = $row['viewgroup_id'];
                        $groups[$row['viewgroup_id']][$customerunit['viewgroup_order']] = $customerunit;
                    }
                } else {
                    $groups[0][] = $customerunit;
                    $groups[0]['viewgroup_name'] = Lang::get('dashboard.nogroup');
                    $groups[0]['viewgroup_description'] = '';
                    $groups[0]['viewgroup_id'] = 0;
                }
            }
        }
        if(isset($sharedunits['customernumber']) && is_array($sharedunits['customernumber'])) {
            foreach ($sharedunits['customernumber'] as &$customer) {
                foreach ($customer as &$sharedunit) {
                    $result = Unit::getOrder($sharedunit['serialnumber']);
                    if (is_array($result)) {
                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                if (isset($row['viewgroup_id'])) {
                                    $sharedunit['viewgroup_id'] = $row['viewgroup_id'];
                                    $sharedunit['viewgroup_order'] = $row['viewgroup_order'];
                                    $sharedGroups[trim($row['customernumber'])][$row['viewgroup_id']][$sharedunit['viewgroup_order'] ] = $sharedunit;
                                    $sharedGroups[trim($row['customernumber'])][$row['viewgroup_id']]['viewgroup_name'] = $row['viewgroup_name'];
                                    $sharedGroups[trim($row['customernumber'])][$row['viewgroup_id']]['viewgroup_description'] = $row['viewgroup_description'];
                                    $sharedGroups[trim($row['customernumber'])][$row['viewgroup_id']]['viewgroup_id'] = $row['viewgroup_id'];
                                    $sharedGroups[trim($row['customernumber'])][$row['viewgroup_id']]['customernumber'] = $row['customernumber'];
                                    $sharedGroups[trim($row['customernumber'])]['customernumber'] = $sharedunit['customernumber'];
                                    $sharedGroups[trim($row['customernumber'])]['customer_site_title'] = $sharedunit['customer_site_title'];
                                }
                            }
                        } else {
                            $sharedGroups[trim($sharedunit['customernumber'])][0][] = $sharedunit;
                            $sharedGroups[trim($sharedunit['customernumber'])][0]['viewgroup_name'] = Lang::get('dashboard.nogroup');
                            $sharedGroups[trim($sharedunit['customernumber'])][0]['viewgroup_description'] = '';
                            $sharedGroups[trim($sharedunit['customernumber'])][0]['viewgroup_id'] = 0;
                            $sharedGroups[trim($sharedunit['customernumber'])][0]['customernumber'] = $sharedunit['customernumber'];
                            $sharedGroups[trim($sharedunit['customernumber'])]['customernumber'] = $sharedunit['customernumber'];
                            $sharedGroups[trim($sharedunit['customernumber'])]['customer_site_title'] = $sharedunit['customer_site_title'];
                        }
                    }
                }
            }
        }

        $res['groups'] = $groups;
        $res['sharedgroups'] = $sharedGroups;
        //dd($res);
        return $res;
    }

    public static function setOrder (Request $request) {
        $order = $request->order;
        $group = $request->orderlist;
        $index = 0;
        $result = array();
        if ($group == 0) {
            return 'updated';
        } else {
            if (is_array($order)) {
                foreach ($order as $serial) {
                    $exsist = Unit::getOrder($serial);
                    $result[$index]['exsist'] = $exsist;
                    if(count($exsist) > 0) {
                        $result[$index] = Unit::setOrder($serial, $group, $index);
                    } else {
                        $result[$index] = Unit::addOrder($serial, $group, $index);
                    }
                    $index++;
                }
            } 
        }

        return $result;
    }

    public static function addGroup (Request $request) {
        $customernumber = $request->customerNumber;
        $viewgroup_name = $request->groupName;
        
        if (isset($customernumber)) {
            $data = Unit::addGroup($customernumber, $viewgroup_name);
            /*if (isset($request->groupArray)) {
                $units = $request->groupArray;
                $result = array();
                $index = 0;
                foreach ($units as $unit) {
                    $serial = trim($unit);
                    $result[] = Unit::setOrder($serial, $group, $index);
                    $index++;
                }
            }*/
        } else {
            $data = 'Not able to create group';
        }

        return $data;
    }

    public static function setGroup (Request $request) {
        $update = $request->result;
        if (is_array($update)) {
            foreach ($update as $group) {
                $viewgroup_name_id = $group->viewgroup_id;
                $viewgroup_name = $group->name;
                $viewgroup_description = $group->description;
                return $viewgroup_name;
            }
        }
    }

    public static function deleteGroup(Request $request) {
        $viewgroup_id = $request->groupId;
        $customernumber = $request->customerNumber;
        
        $data = Unit::deleteGroup($viewgroup_id, $customernumber);
    }

    public static function updateGroup (Request $request) {
        $customernumber = $request->customernumber;
        $result = $request->result;
        if (is_array($result)) {
            foreach ($result as $group) {
                if ($group['viewgroup_id'] !== 0) {
                    $data[] = Unit::setGroup($group['viewgroup_id'], $group['name'], $group['description']);
                }
            }
        }

        return Redirect::to('dashboard');
    }

    public function setTimezone(Request $request){
        $timezone = $request->timezone;
        Session::put('timezone', $timezone);
        $string = "Your timezone is set: " .$timezone; 
        return $string;
    }

    public static function probeProcess (&$array) {
        foreach ($array as &$probe) {
            if ($probe['sensorprobes_alert_hidden'] === 0) {
                if (trim($probe['unittype_id']) == 24) {

                    $tree_specie = SensorunitVariable::where('sensorunit_variables.serialnumber', $probe['serialnumber'])->where('sensorunit_variables.variable', 'tree_species')->first();
                    $a = -0.038;
                    $b = 1.067;
                    if($tree_specie) {
                        $tree_temp = Treespecies::find($tree_specie->value);
                        $a = $tree_temp->specie_value_a;
                        $b = $tree_temp->specie_value_b;
                    }
                    $value = self::convertWoodMoisture($probe['value'], 20, $a, $b);
                    $probe['header'] = number_format($value, $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                } else if (trim($probe['unittype_id']) == 36){
                    $value = $probe['value']/100;
                    $probe['header'] = number_format($value, $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                } else {
                    $probe['header'] = number_format($probe['value'], $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                    $upper = Sensorprobevariable::select('value')->where('serialnumber', $probe['serialnumber'])->where('variable', 'sensorprobe_upper_threshold')->where('sensorprobe_number', $probe['probenumber'])->first();
                    if(isset($upper->value) && $upper->value) {
                        $probe['upperthreshold'] = round((float)$upper->value, $probe['unittype_decimals']);
                    } else {
                        $probe['upperthreshold'] = 100;
                    }

                    $lower = Sensorprobevariable::select('value')->where('serialnumber', $probe['serialnumber'])->where('variable', 'sensorprobe_lower_threshold')->where('sensorprobe_number', $probe['probenumber'])->first();

                    if(isset($lower->value) && $lower->value) {
                        $probe['lowerthreshold'] = round($lower->value, $probe['unittype_decimals']);
                    } else {
                        $probe['lowerthreshold'] = 0;
                    }

                    $probe['percent'] = self::calculatePercent($probe['value'],  $probe['lowerthreshold'], $probe['upperthreshold']);
                }
            } 
            if ($probe['hidden'] === 0) {
                if (trim($probe['unittype_id']) == 24) {
                    $tree_specie = SensorunitVariable::where('sensorunit_variables.serialnumber', $probe['serialnumber'])->where('sensorunit_variables.variable', 'tree_species')->first();
                    $a = -0.038;
                    $b = 1.067;
                    if($tree_specie) {
                        $tree_temp = Treespecies::find($tree_specie->value);
                        $a = $tree_temp->specie_value_a;
                        $b = $tree_temp->specie_value_b;
                    }
                    $value = self::convertWoodMoisture($probe['value'], 20, $a, $b);
                    $probe['body'] = number_format($value, $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                } else if (trim($probe['unittype_id']) == 33) {
                    $val = ($probe['value']/1000000000);
                    $probe['body'] = number_format($val, $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                } else if (trim($probe['unittype_id']) == 36){
                    $value = $probe['value']/100;
                    $probe['body'] = number_format($value, $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                } else {
                    $probe['body'] = number_format($probe['value'], $probe['unittype_decimals']) . ' ' . trim($probe['unittype_shortlabel']);
                }
            } 

            if (trim($probe['unittype_id']) == 4) {
                $rssi = $probe['value'];
                if ($rssi < -100) {
                    $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/rssi_icons/0.png";
                } else if ($rssi < -90) {
                    $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/rssi_icons/1.png";
                } else if ($rssi < -80) {
                    $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/rssi_icons/2.png";
                } else {
                    $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/rssi_icons/3.png";
                }
            }

            $unittype = substr($probe['serialnumber'],0,7);

            if (trim($probe['unittype_id']) == 2) {
                if (($unittype === "21-1020") || ($unittype === "21-1019") || ($unittype === "21-1060") || ($unittype === "21-1065")) $batt_thresholds = [3.35, 3.4, 3.5, 3.5];
                else if (($unittype === "21-1029")) $batt_thresholds = [3.80, 4.9, 5.45, 5.45];
                else if (($unittype === "21-1030")) $batt_thresholds = [3.35, 3.5, 3.6, 3.7];
                else if (($unittype === "21-1046")) $batt_thresholds = [3.35, 3.5, 3.6, 3.7];
                else if (($unittype === "21-1049")) $batt_thresholds = [3.35, 3.5, 3.6, 3.7];
                else if (($unittype === "21-1036")) $batt_thresholds = [3.35, 3.4, 3.5, 3.5];
                else if ((($unittype === "21-1057")) || (($unittype === "21-1058"))) $batt_thresholds = [3.25, 3.3, 3.4, 3.4];		
                else $batt_thresholds = [3.80, 4.9, 5.45, 5.45];

                $batteryStatus = $probe['value'];
                if ($batteryStatus > $batt_thresholds[3]) $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/battery_icons/battery_100.png";
                else if ($batteryStatus > $batt_thresholds[2]) $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/battery_icons/battery_75.png";
                else if ($batteryStatus > $batt_thresholds[1]) $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/battery_icons/battery_50.png";
                else $probe['unittype_icon'] = "https://storage.portal.7sense.no/images/dashboardicons/battery_icons/battery_25.png";
            }
        }
    }

    public static function calculatePercent($value, $min, $max) {
        if($min == 0) {
            $min = 0.01;
        }
        
        if($value > $max) {
            return 100;
        } else if ($value < $min) {
            return 0;
        }

        $max_percent = (($max/$min)*$max) - $max;
        $result = abs((((($value/$min)*$max)-$max) / $max_percent) * 100);
        
        if($result < 0)  return 0;
        else if ($result > 100) return 100;
        else return $result;
    }

}

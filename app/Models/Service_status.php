<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Service_status extends Model
{
    protected $table = 'service_status';
    protected $primaryKey = 'service_status_id';
    public $timestamps = false;

    public static function getCases() {
        $service_status = DB::table('service_status')
                    ->orderby('service_status.service_status_id', 'ASC')
                    ->get();
        return $service_status;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Service_persons extends Model
{
    protected $table = 'service_persons';
    protected $primaryKey = 'service_person_id';
    public $timestamps = false;

    public static function getCases() {
        $service_persons = DB::table('service_persons')
                    ->orderby('service_persons.service_person_id', 'ASC')
                    ->get();
        return $service_persons;
    }

}
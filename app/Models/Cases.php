<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cases extends Model
{
    protected $table = 'cases';
    protected $primaryKey = 'case_id';
    

    public static function getCases() {
        $caseIndex = DB::table('cases')
                    ->orderby('cases.case_id', 'ASC')
                    ->get();
        return $caseIndex;
    }
}
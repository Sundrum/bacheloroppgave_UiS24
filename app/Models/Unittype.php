<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unittype extends Model
{
    protected $table = 'unittypes';
    protected $primaryKey = 'unittype_id';
    public $timestamps = false;

    public static function getUnittypes() {
        $data = Api::getApi('unittypes/list?sortfield=unittype_id');
        return $data['result'];
    }

    public static function getUnittype($id) {
        $data = Api::getApi('unittypes/list?unittype_id='.$id);
        return $data['result'];
    }
}

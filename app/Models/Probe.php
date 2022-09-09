<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Probe extends Model
{
    public static function getProbes() {
        $data = Api::getApi('products/list?sortfield=product_id');
        return $data['result'];
    }

    public static function getProbe($id) {
        $data = Api::getApi('products/list?product_id='.$id);
        return $data['result'];
    }
}

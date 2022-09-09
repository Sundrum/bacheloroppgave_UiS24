<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Api;
use App\Models\Unit;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    public static function getProducts() {
        $data = Api::getApi('products/list?sortfield=product_id');
        return $data['result'];
    }

    public static function getProduct($id) {
        $data = Api::getApi('products/list?product_id='.$id);
        return $data['result'];
    }
}

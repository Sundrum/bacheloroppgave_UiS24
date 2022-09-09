<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Producttype extends Model
{
    protected $table = 'products_type';
    protected $primaryKey = 'product_type_id';
    public $timestamps = false;
}

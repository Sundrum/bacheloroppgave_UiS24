<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProduct($id) {
        $product = Product::find($id)->first();
        return json_encode($product);
        //return view('admin.product.edit')->with('product', $product[0]);
    }

    public static function getProducts($action) {
        $data = Product::getProducts();
        $count_data = count($data);
        $sorted = array();

        for ($i = 0; $i < $count_data; $i++) {
            if (isset($data[$i]['product_id'])) {
                $sorted[$i][0] = trim($data[$i]['product_id']);
            } else {
                $sorted[$i][0] = '-';
            }

            if (isset($data[$i]['productnumber'])) {
                $sorted[$i][1] = trim($data[$i]['productnumber']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['product_name'])) {
                if(isset($data[$i]['product_image_url'])) {
                    $sorted[$i][2] = '<img src="'.trim($data[$i]['product_image_url']).'" class="avatar" alt="Avatar"> '.trim($data[$i]['product_name']);
                } else {
                    $sorted[$i][2] = trim($data[$i]['product_name']);
                }
            } else {
                $sorted[$i][2] = '-';
            }

            if (isset($data[$i]['product_description'])) {
                $sorted[$i][3] = trim($data[$i]['product_description']);
            } else {
                $sorted[$i][3] = '-';
            }

            if (isset($data[$i]['product_id'])) {
                $id = trim($data[$i]['product_id']);
            } else {
                $id= '-';
            }
            if ($action == 1) {
                $sorted[$i][4] = '<a href="#" class="settings" title data-toggle="tooltip" data-orginal-title="Settings"><i class="mr-2 ml-2 fas fa-lg fa-cog"></i></a>';
                $sorted[$i][4] .= '<a href="#" class="delete" title data-toggle="tooltip" data-orginal-title="Delete"><i class="ml-2 fas fa-lg fa-times-circle" style="color: red;"></i></a>';
            }
        }
        return $sorted;
    }
}

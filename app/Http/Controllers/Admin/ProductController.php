<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sensorprobe;
use App\Models\Producttype;
use Redirect;


class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    
    public function index() {
        $products = Product::all();
        return view('admin.product.view', compact('products'));
    }

    public function new() {
        $product_type = Producttype::all();
        return view('admin.product.details', compact('product_type'));
    }

    public function product($id) {
        $product = Product::find($id);
        $probes = Sensorprobe::where('product_id_ref', $id)->join('unittypes', 'unittypes.unittype_id','unittype_id_ref')->orderby('sensorprobes.sensorprobes_number')->get();
        $product_type = Producttype::all();
        return view('admin.product.details', compact('product', 'probes', 'product_type'));
    }

    public function update(Request $req) {
        if ($req->product_id) {
            $product = Product::find($req->product_id);
            $product->product_name = $req->product_name;
            $product->product_description = $req->product_description;
            $product->product_image_url = $req->product_image_url;
            $product->product_type = $req->product_type;
            $product->document_id_ref = $req->document_id_ref;
            $result = $product->save();
            return Redirect::to('admin/product/'.$product->product_id);
        } else {
            $product = new Product;
            $product->product_name = $req->product_name;
            $product->productnumber = $req->productnumber;
            $product->product_description = $req->product_description;
            $product->product_image_url = $req->product_image_url;
            $product->product_type = $req->product_type;
            $product->document_id_ref = $req->document_id_ref;
            $result = $product->save();
            return Redirect::to('admin/product/'.$product->product_id);
        }
    }

}

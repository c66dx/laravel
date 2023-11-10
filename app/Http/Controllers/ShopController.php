<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){

        $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $products = Product::orderBy('name','ASC')->where('status',1)->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        
        return view('front.shop');
    }
}

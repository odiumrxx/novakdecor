<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;

class ShopController extends Controller
{
   public function index(){
		$products = Product::orderBy('created_at', 'DESC')->paginate(12);
		return view('shop', compact('products'));
	}
}
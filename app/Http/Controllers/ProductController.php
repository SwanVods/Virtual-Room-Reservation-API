<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(Request $req)
    {
        $data = Product::find($req->id);

        $res = [
            'data' => $data
        ];
        return response($res);    
    }
}

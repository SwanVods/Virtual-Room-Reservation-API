<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    /**
     * Index Function
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms
     *
     * @return Response id, name, slug
     */
    public function index()
    {
        $data = Product::select(['id', 'name', 'slug'])->orderBy('created_at', 'DESC')->get();

        $res = [
            'message' => 'List of products order by time added',
            'data' => $data
        ];
        return response($res)->header('Content-Type', 'application/json');
    }

    /**
     * Get detailed room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/room-details?id={id}
     * 
     * @return Response all data attributes
     */
    public function details(Request $req)
    {
        $data = Product::find($req->id);

        $res = [
            'data' => $data
        ];
        return response($res)->header('Content-Type', 'application/json');    
    }

    /**
     * Store created room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/create?{name},{category_id}
     * 
     * @return Response boolean
     */
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => ['required'], 
            'capacity' => ['required'], 
            'size' => ['required'], 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::create([
                'name' => $req->name,
                'slug' => Str::slug($req->name),
                'category_id' => $req->category_id,
                'user_id' => $req->user_id,
                'price' => $req->price,
                'description' => $req->description,
                'capacity' => $req->capacity,
                'size' => $req->size,
                'access_route' => $req->access_route,
                'address' => $req->address,
            ]);            

            $res = [
                'message' => 'Product create successful',
                'data' => $product
            ];

            return response()->json($res, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'data' => 'Failed ' . $e->errorInfo
            ]);
        }


        if ($product) {
            return response('success', 200);
        } else {
            return response('failed');
        }
        
    }

    /**
     * Delete requested id model
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/room-details?id={id}
     * 
     * @return Response
     */
    public function delete(Request $req)
    {
        Product::destroy($req->id);
        
        return response('success');
    }
}

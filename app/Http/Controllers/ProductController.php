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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = auth()->user();
        $data = Product::select(['id', 'name', 'slug'])
            ->with('images')
            // ->with($user)
            ->orderBy('created_at', 'DESC')
            ->get();

        $res = [
            'message' => 'List of products order by time added',
            'data' => $data,
            // 'user' => $user
        ];
        return response()->json($res);
    }

    /**
     * Get detailed room attributes
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/room-details?id={id}
     * 
     * @return \Illuminate\Http\Response All data attributes
     */
    public function show(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => ['integer']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = Product::where('id', $req->room)
                            ->orWhere('slug', $req->room)
                            ->with('images')->get();
            $res = [
                'data' => $data
            ];
            return response()->json($res, 200);    
        } catch (QueryException $th) {
            return response()->json([
                'data' => $th->errorInfo
            ]);
        }
        
    }

    /**
     * Store created room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/create?name={name}&category={category_id}
     * 
     * @return \Illuminate\Http\Response boolean
     */
    public function store(Request $req)
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
                'data' => $e->errorInfo
            ]);
        }
    }
    /**
     * Store created room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/create?name={name}&category={category_id}
     * 
     * @return \Illuminate\Http\Response boolean
     */
    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'room' => ['required', 'integer'],
            'name' => ['required'], 
            'capacity' => ['required'], 
            'size' => ['required'], 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::where('id', $id)->get();
            $product->update([
                'name' => $req->name,
                'slug' => Str::slug($req->name),
                'category_id' => $req->category_id,
                'price' => $req->price,
                'description' => $req->description,
                'capacity' => $req->capacity,
                'size' => $req->size,
                'access_route' => $req->access_route,
                'address' => $req->address,
            ]);

            $res = [
                'message' => 'Product update successful',
                'data' => $product
            ];

            return response()->json($res, Response::HTTP_OK);

        } catch (QueryException $e) {

            return response()->json([
                'data' => $e->errorInfo
            ]);
        }
    }

    /**
     * Delete requested id model
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/delete?id={id}
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        try {
            $data = Product::destroy($req->room);
            $res = [
                'message' => 'success',
                'data' => $data
            ];

            return response()->json($res, Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo
            ]);
        }
    }
}

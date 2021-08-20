<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Index Function
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms
     *
     * @return response list of rooms
     */
    public function index()
    {
        $data = Product::select(['id', 'name', 'slug'])
            ->with('images')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        $res = [
            'message' => 'List of products order by time added',
            'data' => $data
        ];
        return response()->json($res);
    }

    /**
     * Get detailed room attributes
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms/{slug-or-id}
     * 
     * @return response list of room attributes
     */
    public function show(Request $req)
    {
        $data = Product::where('id', $req->room)
                        ->orWhere('slug', $req->room)
                        ->with('images')->first();
                       
        if ($data == null) { // if data empty then send 404
            return response()->json([
                'data' => $data
            ], 404);
        }

        $res = [
            'data' => $data
        ];
        return response()->json($res, 200);
    }

    /**
     * Store created room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms/create
     * 
     * @return \Illuminate\Http\Response boolean
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => ['required'], 
            'category_id' => ['required'], 
            'user_id' => ['required'], 
            'price' => ['required'],
            'capacity' => ['required'], 
            'size' => ['required'], 
            'image' => 'required|image',
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
            if ($req->hasfile('image')) {
                foreach ($req->file('image') as $image) {
                    $product->images()->create([
                        'image' => $image->store('images'),
                    ]);
                }
            }
            $res = [
                'message' => 'Product create successful',
                'data' => [
                    'product' => $product, 
                    'images' => $product->images()->select(['id', 'image'])->get()
                ]
            ];

            return response()->json($res, Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'data' => [
                    'code' => $e->errorInfo[0],
                    'key' => $e->errorInfo[1],
                    'message' => $e->errorInfo[2]
                ]
            ]);
        }
    }

    /**
     * Update room attribute
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms/{room}
     * 
     * @return \Illuminate\Http\Response boolean
     */
    public function update(Request $req)
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
            $product = Product::findOrFail($req->room);
            $product->update([
                'room' => $req->room,
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

        } catch (ModelNotFoundException $e) 
        {
            return response()->json([
                'message' => 'Record not found',
                'data' => $e->getIds(),

            ]);
        }
    }

    /**
     * Delete requested id model
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms/{id}
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        try {
            $product = Product::where('id', $req->room)->first();
            if ($product) {
                throw new \Exception("Data with id " . $req->room . " not found", 1);
                
            }
            $data = $product->delete();
            
            $res = [
                'message' => 'success',
                'data' => $data
            ];

            return response()->json($res, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo
            ]);
        }
    }

    /**
     * Add images to product 
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/rooms/{id}/images
     * 
     * @return \Illuminate\Http\Response boolean
     */
    public function storeImages(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'image' => ['required', 'image', 'file|max:512'],
        ]);

        Storage::disk('local')->put($req->image, 'Contents');

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::create([
                'image' => $req->image,
                'product_id' => $id
            ]);

            $res = [
                'message' => 'Image added',
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
     * Delete requested id model
     * 
     * API Endpoint : 
     * https://virtual.co.id/api/delete/{id}
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroyImages(Request $req)
    {
        try {
            $data = ProductImage::destroy($req->id);
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

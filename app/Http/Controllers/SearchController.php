<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{

    /**
     * Controller for search query
     * 
     * API Endpoint :
     * https://virtual.co.id/api/rooms/search?location={location}&capacity={capacity}&type={type}&date={date}&time={time}&price={price}
     * 
     * @return Response
     */
    public function roomSearch(Request $req)
    {
        $endpointParams['name'] = $req->name;
        $endpointParams['capacity'] = $req->capacity;
        $endpointParams['type'] = $req->type;
        $endpointParams['date'] = $req->date;
        $endpointParams['time'] = $req->time;
        $endpointParams['price_max'] = $req->price_max;
        $endpointParams['price_min'] = $req->price_min;

        $validator = Validator::make($endpointParams, [
            'name' => ['required'],
            'capacity' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = Product::where([
                ['name', 'like', "%{$endpointParams['name']}%"],
                ['type', '=', $endpointParams['type']],
                ['price', 'between', [$endpointParams['price_min'], $endpointParams['price_max']]],
                ['date', '=', $endpointParams['date']],
                ['time', '=', $endpointParams['time']],
            ])->get();

            $res = [
                'data' => $data,
            ];
            return response()->json($res, Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json(['data' => $e->errorInfo]);
        }

    }

    /**
     * Article search query
     * 
     * API Endpoint :
     * https://virtual.co.id/api/rooms/search?location={location}&capacity={capacity}&type={type}&date={date}&time={time}&price={price}
     * 
     * @return Response
     */
    public function articleSearch(Request $req)
    {
        $title = $req->get('data');
        $res = [
            'data' => Article::where('title', 'like', "%{$title}%")->get()
        ];

        return response()->json($res);
    }
}

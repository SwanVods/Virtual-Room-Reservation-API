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
     * https://virtual.co.id/api/rooms/search
     * 
     * @return Response
     */
    public function roomSearch(Request $r)
    {
        $data = Product::where(function($q) use($r){
            if($r->query('name')) {
                $q->orWhere('name', 'like', "%{$r->query('name')}%");
            }
            if($r->query('capacity')) {
                $q->orWhere('capacity', '=', $r->query('capacity'));
            }
            if($r->query('price_min') and $r->query('price_max')) {
                $q->orWhere('price', 'between', [$r->query('price_min'), $r->query('price_max')]);
            }
            if($r->query('type')) {
                $q->orWhere('type', '=', $r->query('type'));
            }
            if($r->query('date')) {
                $q->orWhere('date', '=', $r->query('date'));
            }
            if($r->query('time')) {
                $q->orWhere('time', '=', $r->query('time'));
            }
        })->get();

        $res = [
            'data' => $data,
        ];
        return response()->json($res, Response::HTTP_OK);

    }

    /**
     * Article search query
     * 
     * API Endpoint :
     * https://virtual.co.id/api/articles/search?title={title}
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

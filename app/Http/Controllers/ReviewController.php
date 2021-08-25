<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewPostRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::where('user_id', Auth::user())->get();
        $res = [
            'data' => $reviews
        ];

        return response()->json($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewPostRequest $request)
    {
        $validated = $request->validated();
        $review = Review::create(array_merge($validated, [
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]));

        $res = [
            'success' => true,
            'data' => $review
        ];

        return response()->json($res, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $review = Review::findOrFail($request->id);
            $review->title = $request->title;
            $review->score = $request->score;
            $review->description = $request->description;
            $review->save();
            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $order = Order::find($r->id);
        $res = [
            'data' => $order
        ];

        return response()->json($res);
    }
    
    public function create(Request $r)
    {
        try{
            $created = Order::create([
                'uuid' => Str::uuid()->toString(),
                'user_id' => Auth::user()->id,
                'status' => 'Pending',
                'start_date' => date('Y-m-d H:i:s', $r->start_date),
                'end_date' => date('Y-m-d H:i:s', $r->end_date),
            ]);
        } catch (QueryException $e){
            return response()->json(['success' => false, 'data' => $e->getMessage()]);
        }
        return response()->json(['success' => true, 'data' => $created]);
    }

    public function update(Request $r)
    {
        $order = Order::find($r->id);
        $order->status = $r->status;
        $order->save();

        return response()->json(['success' => true]);
    }

    public function getOrder(Request $r)
    {
        return response()->json(['data' => Order::find($r->query('id'))]);
    }
}

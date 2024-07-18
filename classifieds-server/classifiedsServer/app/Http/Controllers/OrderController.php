<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Orders::query();
        return response()->json($query->get());
    }
    public function cancel($id){
        try{
            $order = Orders::findOrFail($id);
            $order->status = "canceled";
            $order->save();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function destroy($id){
        try{
            Orders::findOrFail($id)->delete();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function store(Request $request)
    {
        try {
            $order = new Orders;
            $body = $request->json()->all();
            // dd($body['listings']);
            $order->listings = json_encode($body['listings']);
            $order->total_price = $body['total'];
            $order->number = $body['phone'];
            $order->address = $body['address'];
            $order->user_id = $body['user'];
            $order->status = 'active';
            $order->save();
            return 'order created';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show($id)
    {
        try {
            return Orders::findOrFail($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

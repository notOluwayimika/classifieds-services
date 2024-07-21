<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Services\MessageService;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class OrderController extends Controller
{
    //
    protected $messageService;

    public function __construct(MessageService $messageService) {
        $this->messageService = $messageService;
    }
    public function index(Request $request)
    {
        $query = Orders::query();
        
        

        return response()->json($query->get());
    }
    public function cancel($id)
    {
        try {
            $order = Orders::findOrFail($id);
            $order->status = "canceled";
            $order->save();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function destroy($id)
    {
        try {
            Orders::findOrFail($id)->delete();
        } catch (Exception $e) {
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
            $listings = [];

            foreach (json_decode($order->listings, true) as $listing) {
                $details = [
                    "id"=>$listing["id"],
                    "quantity"=>$listing["quantity"]
                ];
                array_push($listings,$details);
            }
            $orderdetails =[
                "id"=>$order->id,
                "listings"=>$listings,
                "action"=>"list"
            ];
            $this->messageService->sendMessage('delivery', json_encode($orderdetails));
            return 'order created';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show($id)
    {
        try {
            $order =Orders::findOrFail($id);
            
            return  $order;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

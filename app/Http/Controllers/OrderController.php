<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index');
    }

    public function create()
    {
        return view('order.create');
    }

    public function show(Order $order)
    {
        $online_order = null;
        $items = OrderItem::where('order_id', $order->id)->get();
        //fetch online order
        if ($order->source === 'ONLINE') {
            $online_order = $this->getOnlineOrder($order->agentx_order_id);
        }



        //Fetch items
        return view('order.show', compact('order', 'items', 'online_order'));
    }

    private function getOnlineOrder(int $order_id)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', "https://api.instabucks.co.zw/v1/orders/{$order_id}/pos");
            $statusCode = $response->getStatusCode(); // 200
            $data = $response->getBody()->getContents();

            $dataArray = json_decode($data, true);

            // Return or process the data as needed
            return $dataArray;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle exceptions
            return $e->getMessage();
        }
    }
}

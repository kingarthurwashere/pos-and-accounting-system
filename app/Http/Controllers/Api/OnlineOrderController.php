<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OnlineOrderController extends Controller
{
    public function sync()
    {
        // Get the bearer token from wherever it's stored in your application
        $bearerToken = env('MASTER_KEY'); // Replace this with your actual bearer token

        // Create a Guzzle HTTP client instance
        $client = new Client();

        try {
            // Send the GET request with the bearer token in the Authorization header
            $response = $client->request('GET', env('AGENTX_DOMAIN') . "v1/orders/pos-list", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ]
            ]);

            // Check the response status code
            $statusCode = $response->getStatusCode(); // 200

            // Get the response body content
            $data = $response->getBody()->getContents();

            // Decode the JSON response data
            $dataArray = json_decode($data, true);

            // Process the received data
            foreach ($dataArray as $order) {
                // Check if the order already exists
                $existingOrder = Order::where('order_id', $order['order_id'])->first();

                if (!$existingOrder) {
                    $fetched_order = Order::create([
                        'total' => $order['total'] * 100,
                        'order_id' => $order['order_id'],
                        'agentx_order_id' => $order['id'],
                        'balance' => $order['balance_remaining'],
                        'source' => 'ONLINE',
                        'agent_id' => $order['user_id'],
                        'customer_name' => $order['customer_name'],
                        'customer_phone' => $order['customer_phone'],
                        'customer_email' => $order['customer'] !== null ? $order['customer']['email'] : null,
                        'created_by' => 1,
                    ]);

                    //add items
                    foreach ($order['ordered_items'] as $order_item) {
                        $price = (float)$order_item['details']['usd_price'] * 100;
                        $qty = $order_item['details']['quantity'];
                        OrderItem::create([
                            'order_id' => $fetched_order->id,
                            'product_id' => $order_item['details']['id'],
                            'img_src' => $order_item['details']['image'],
                            'name' => $order_item['details']['name'],
                            'slug' => $order_item['details']['slug'],
                            'price' => $price,
                            'quantity' => $qty,
                            'total' => $price * $qty,
                            'sku' => $order_item['details']['sku'] !== '' ? $order_item['details']['sku'] : null,
                            'is_inventory' => 0
                        ]);
                    }

                    //add payments
                    foreach ($order['payments'] as $pop) {
                        $agent = Agent::where('agentx_id', $pop['user_id'])->first();
                        Payment::create([
                            'payable_identifier' => $fetched_order->id,
                            'covers_full_balance' => $pop['amount'] >= $fetched_order->balance_remaining,
                            'opening_balance' => $fetched_order->balance_remaining + $pop['amount'],
                            'closing_balance' => $fetched_order->balance_remaining,
                            'source' => 'AGENTX-CASH',
                            'tender' => $pop['amount'] / 100,
                            'received_amount' => $pop['amount'] / 100,
                            'received_amount_datetime' => $pop['created_at'],
                            'posted_datetime' => $pop['created_at'],
                            'initiated_by' => $agent === null ? 1 : $agent->user_id,
                            'posted' => 1,
                            'change_amount' => 0,
                            'payable_slug' => 'order',
                            'status' => 'RECEIVED',
                            'location_id' => 1,
                        ]);
                    }
                }
            }

            // Return or process the data as needed
            return $dataArray;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle exceptions
            return $e->getMessage();
        }
    }
}

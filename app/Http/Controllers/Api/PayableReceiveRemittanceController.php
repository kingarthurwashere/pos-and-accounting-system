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

class PayableReceiveRemittanceController extends Controller
{
    public function __invoke(Request $request)
    {
        //$bearerToken = env('MASTER_KEY'); // Replace this with your actual bearer token
        $rules = [
            'payable_slug' => 'required',
            'status' => 'required|in:pending,completed,failed',
            'received_amount' => 'required|numeric',
        ];

        $messages = [
            'status.in' => 'The status field must be one of: pending, completed, failed.',
            'received_amount.numeric' => 'The received_amount field must be a numeric value.',
        ];

        // Perform validation
        $validatedData = $request->validate($rules, $messages);

        return $request->get('received_amount');

        // Create a Guzzle HTTP client instance
        $client = new Client();
    }
}

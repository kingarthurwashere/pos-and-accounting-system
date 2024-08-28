<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderInvoicesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Order $order, Request $request)
    {
        $items = OrderItem::where('order_id', $order->id)->get();

        return view('order.invoices', compact('order', 'items'));
    }
}

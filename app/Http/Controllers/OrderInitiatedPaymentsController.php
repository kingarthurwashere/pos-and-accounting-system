<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderInitiatedPaymentsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('order.initiated-payments');
    }
}

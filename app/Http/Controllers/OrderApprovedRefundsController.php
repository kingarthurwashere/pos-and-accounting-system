<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderApprovedRefundsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('order.approved-refunds');
    }
}

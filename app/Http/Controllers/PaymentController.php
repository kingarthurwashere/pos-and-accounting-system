<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment.index');
    }

    public function create()
    {
        return view('payment.create');
    }
}

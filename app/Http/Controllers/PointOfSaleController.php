<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointOfSaleController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }
}

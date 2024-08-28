<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockProductBatchController extends Controller
{
    public function upload()
    {
        return view('stock-product.batch-upload');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\StockProduct;
use App\Models\StockProductCategory;
use Illuminate\Http\Request;

class StockProductController extends Controller
{
    public function index()
    {
        return view('stock-product.index');
    }

    public function create()
    {
        return view('stock-product.create');
    }

    public function edit(StockProduct $stockProduct)
    {
        $product = $stockProduct;
        $locations = Location::get();
        $categories = StockProductCategory::get();
        return view('stock-product.edit', compact('product', 'locations', 'categories'));
    }
}

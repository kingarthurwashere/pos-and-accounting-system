<?php

namespace App\Http\Controllers;

class RemittanceController extends Controller
{
    public function index()
    {
        return view('remittance.index');
    }

    public function accepted()
    {
        return view('remittance.accepted', []);
    }
    public function due()
    {
        return view('remittance.due', []);
    }
}

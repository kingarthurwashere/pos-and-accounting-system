<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;

class WithdrawalRequestController extends Controller
{
    // public function index() {
    //     return view('withdrawal-request.index', []);
    // }

    public function index()
    {
        return view('withdrawal-request.index');
    }

    public function create()
    {
        return view('withdrawal-request.create', []);
    }
}

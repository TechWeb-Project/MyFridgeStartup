<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainFridgeController extends Controller
{
    public function index() 
    {
        return view('fridge.fridge_dashboard');
    }
    
}

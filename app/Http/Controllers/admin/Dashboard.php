<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    //

    function index(Request $req){
        $data['page'] = 'admin.dashboard.dashboard';  
        $data['total_order'] = 151;       
        return view('admin.includes.layout',$data);
    }
}
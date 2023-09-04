<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class Dashboard extends Controller
{
    //

    function index(Request $req)
    {

        $data['page'] = 'admin.dashboard.dashboard';
        $data['total_order'] = 151;
        $data['total_users'] = User::where('account_type', '0')->count();
        $data['total_professional_users'] = User::where('account_type', '1')->count();
        $data['total_events'] = Event::count();
        return view('admin.includes.layout', $data);
    }
}

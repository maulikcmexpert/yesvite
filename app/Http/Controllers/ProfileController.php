<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use libphonenumber\PhoneNumberUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function index()
    {

        $id = decrypt(session()->get('user')['id']);
        $title = 'Profile';
        $page = 'front.profile';
        $js = ['profile'];
        $user = User::findOrFail($id);
        $user['profile'] = ($user->profile != null) ? asset('public/storage/profile/' . $user->profile) : asset('public/storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('public/storage/bg_profile/' . $user->bg_profileprofile) : asset('public/assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $formattedNumber = phone($user->phone_number, 'US');
        $user['phone_number'] = $formattedNumber;

        $user['join_date'] = $formatted_date;

        return view('layout', compact(
            'title',
            'page',
            'user',
            'js'
        ));
    }

    public function update(Request $request)
    {
    }
}

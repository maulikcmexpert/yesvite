<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {

        $id = decrypt(session()->get('user')['id']);
        $title = 'Profile';
        $page = 'front.profile';
        $user = User::findOrFail($id);
        $user['profile'] = ($user->profile != null) ? asset('public/storage/profile/' . $user->profile) : asset('public/storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->profile != null) ? asset('public/storage/bg_profile/' . $user->bg_profileprofile) : asset('public/storage/profile/no_profile.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');

        $user['join_date'] = $formatted_date;

        return view('layout', compact(
            'title',
            'page',
            'user',
        ));
    }
}

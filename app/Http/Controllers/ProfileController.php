<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    protected $userid;
    public function __construct()
    {
        $userId = Session::get('user')['id'] ?? null;
        echo $userId;
        exit;
        $this->userid = decrypt($userId);
    }
    public function index()
    {


        $title = 'Profile';
        $page = 'front.profile';
        $user = User::findOrFail($this->userid);
        $user['profile'] = ($user->profile != null) ? asset('public/storage/profile/' . $user->profile) : asset('public/storage/profile/no_profile.png');

        return view('layout', compact(
            'title',
            'page',
            'user',
        ));
    }
}

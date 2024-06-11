<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AccountSettingController extends Controller
{



    public function index()
    {

        $id = decrypt(session()->get('user')['id']);
        $user = User::with('user_profile_privacy')->withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                }, 'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment'

            ]
        )->findOrFail($id);
        $title = 'Account Settings';
        $page = 'front.account_setting';
        $js = ['account_setting'];
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;
        $user['photo_via_wifi'] = $user->photo_via_wifi;

        return view('layout', compact(
            'title',
            'page',
            'user',
            'js'
        ));
    }

    public function updateAccountSetting(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($request->setting == 'photo_via_wifi') {
            $user->photo_via_wifi = $request->value;
            if ($user->save()) {

                return response()->json([
                    'status' => 1,
                    'message' => "Upload photos only via Wi-Fi changed",

                ]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

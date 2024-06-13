<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Contact';
        $page = 'front.contact';
        $js = ['contact'];
        $id = decrypt(session()->get('user')['id']);

        $user = User::withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                }, 'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment'

            ]
        )->findOrFail($id);
        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        $yesviteUser = User::where('app_user', '=', '1')->where('id', '!=', $id)->paginate(1);

        return view('layout', compact(
            'title',
            'page',
            'user',
            'js',
            'yesviteUser'
        ));
    }

    public function loadMore(Request $request)
    {
        $id = decrypt(session()->get('user')['id']);
        if ($request->ajax()) {
            $yesviteUser = User::where('app_user', '=', '1')->where('id', '!=', $id)->paginate(1); // Adjust the number as needed
            return view('front.ajax_contacts', compact('yesviteUser'))->render();
        }
        return response()->json(['error' => 'Invalid request'], 400);
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

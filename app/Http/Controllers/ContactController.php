<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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
        $user = User::with(['groups' => function ($query) {
            $query->withCount('groupMembers')->orderBy('id', 'DESC')->limit(2);
        }])->withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                }, 'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment'

            ]
        )->findOrFail($id);
        $groups = Group::where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();

        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        $yesviteUser = User::where('app_user', '=', '1')->where('id', '!=', $id)->paginate(10);

        return view('layout', compact(
            'title',
            'page',
            'user',
            'js',
            'yesviteUser',
            'groups'

        ));
    }

    public function loadMore(Request $request)
    {
        $id = decrypt(session()->get('user')['id']);
        if ($request->ajax()) {
            $yesviteUser = User::where('app_user', '=', '1')->where('id', '!=', $id)->paginate(10); // Adjust the number as needed
            return view('front.ajax_contacts', compact('yesviteUser'))->render();
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }


    public function addContact(Request $request, string $id)
    {

        // dd($request);
        $id = decrypt($id);
   
        $validator = Validator::make($request->all(), [
            'Fname' => 'required|string', // max 2MB
            'Lname' => 'required|string', // max 2MB
            'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/'],
           'email' => 'required|email', // max 2MB

        ], [
            'Fname.required' => 'Please enter First Name',
            'Lname.required' => 'Please enter Last Name',

            'phone_number.numeric' => 'Please enter Phone Number in digit',
            'phone_number.regex' => 'Phone Number format is invalid.',

            'email.required' => 'Please enter email',
            'email.email' => 'Please enter a valid email address', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),

            ]);
        }

        DB::beginTransaction();

        $addcontact =  User::create([
            'firstname' => $request['Fname'],
            'lastname' => $request['Lname'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'country_code' => $request['country_code'],
            'app_user' => '0',
            'parent_user_phone_contact' => $id,
            'user_parent_id' => $id,
            'is_user_phone_contact'=>'1'

        ]);

        DB::commit();
        return response()->json(['status' => 1, 'message' => "Contact Added!", 'user' => $addcontact]);

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

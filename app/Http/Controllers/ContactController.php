<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Rules\PhoneNumberExists;
use Illuminate\Validation\Rule;
use App\Rules\EmailExists;
use Illuminate\Support\Facades\Auth;

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
        $id = Auth::guard('web')->user()->id;
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

        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        $yesviteUser = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')->paginate(10);
        $yesviteGroups = Group::withCount('groupMembers')->paginate(10);
        $yesvitePhones = User::where(['is_user_phone_contact' => '1', 'parent_user_phone_contact' => $id])->paginate(10);


        return view('layout', compact(
            'title',
            'page',
            'user',
            'js',
            'yesviteUser',
            'yesviteGroups',
            'yesvitePhones',
            'groups'

        ));
    }

    public function loadMore(Request $request)
    {
        $id = Auth::guard('web')->user()->id;
        $searchName = $request->search_name;

        if ($request->ajax()) {
            $yesviteUser = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')->paginate(10);

            // if ($searchName) {
            //     $query->where(function ($q) use ($searchName) {
            //         $q->where('firstname', 'LIKE', '%' . $searchName . '%')
            //             ->orWhere('lastname', 'LIKE', '%' . $searchName . '%');
            //     });
            // }

            // $yesviteUser = $query->get();
            return view('front.ajax_contacts', compact('yesviteUser'))->render();
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function loadMoreGroup(Request $request)
    {
        try {
            $searchGroup = $request->input('search_group');

            if ($request->ajax()) {
                $query = Group::withCount('groupMembers');

                if ($searchGroup) {
                    $query->where('name', 'LIKE', '%' . $searchGroup . '%');
                }

                $yesviteGroups = $query->paginate(10);
                return view('front.ajax_groups', compact('yesviteGroups'))->render();
            }
            return response()->json(['error' => 'Invalid request'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
    public function loadMorePhones(Request $request)
    {
        try {
            $id = Auth::guard('web')->user()->id;
            $searchPhone = $request->input('search_phone');

            if ($request->ajax()) {
                $query = User::where(['is_user_phone_contact' => '1', 'parent_user_phone_contact' => $id]);

                if ($searchPhone) {
                    $query->where(function ($q) use ($searchPhone) {
                        $q->where('firstname', 'LIKE', '%' . $searchPhone . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $searchPhone . '%');
                    });
                }

                $yesvitePhones = $query->paginate(10);
                return view('front.ajax_phones', compact('yesvitePhones'))->render();
            }
            return response()->json(['error' => 'Invalid request'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }




    public function addContact(Request $request, string $id)
    {

        // dd($request);
        $id = decrypt($id);
        try {
            $validator = Validator::make($request->all(), [
                'Fname' => 'required|string', // max 2MB
                'Lname' => 'required|string', // max 2MB
                'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/', Rule::unique('users')->ignore(decrypt($request->id))],
                'email' => ['required', 'email', new EmailExists], // max 2MB

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
                'is_user_phone_contact' => '0'

            ]);

            DB::commit();
            return response()->json(['status' => 1, 'message' => "Contact Added!", 'user' => $addcontact]);
        } catch (QueryException $e) {
            DB::Rollback();
            $userData =  getUser($id);
            return response()->json(['status' => 0, 'message' => "db error", 'user' => $userData]);
        } catch (Exception  $e) {
            $userData =  getUser($id);
            return response()->json(['status' => 0, 'message' => "something went wrong", 'user' => $userData]);
        }
    }
    public function editContact(Request $request, string $id)
    {

        $editContact = User::where('id', '=', $id)->get()->first();
        return response()->json(['status' => 1, 'message' => "Contact Added!", 'edit' => $editContact]);
    }

    public function save_editContact(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'edit_Fname' => 'required|string', // max 2MB
                'edit_Lname' => 'required|string', // max 2MB
                'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/'],

            ], [
                'edit_Fname.required' => 'Please enter First Name',
                'edit_Lname.required' => 'Please enter Last Name',

                'phone_number.numeric' => 'Please enter Phone Number in digit',
                'phone_number.regex' => 'Phone Number format is invalid.',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),

                ]);
            }

            DB::beginTransaction();
            $usercontactUpdate = User::where('id', $request->edit_id)->first();

            $usercontactUpdate->firstname = $request->edit_Fname;

            $usercontactUpdate->lastname = $request->edit_Lname;

            $usercontactUpdate->phone_number = $request->phone_number;

            $usercontactUpdate->save();
            DB::commit();

            $usercontactUpdate =  getUser($request->edit_id);
            return response()->json(['status' => 1, 'message' => "Edit Saved!", 'user' => $usercontactUpdate]);
        } catch (QueryException $e) {
            DB::Rollback();
            $userData =  getUser($request->edit_id);
            return response()->json(['status' => 0, 'message' => "db error", 'user' => $userData]);
        } catch (Exception  $e) {
            $userData =  getUser($request->edit_id);
            return response()->json(['status' => 0, 'message' => "something went wrong", 'user' => $userData]);
        }
    }

    public function checkNewContactEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
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

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\contact_sync;
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
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment'

            ]
        )->findOrFail($id);

        $groups = Group::where('user_id', $user->id)->withCount('groupMembers')->orderBy('id', 'DESC')->limit(2)->get();


        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();

        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        // $yesviteUser = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')->paginate(10);
        $yesviteGroups = Group::where('user_id', $user->id)->withCount('groupMembers')->paginate(10);
        // $yesvitePhones = User::where(['is_user_phone_contact' => '1', 'parent_user_phone_contact' => $id])->paginate(10);


        $id = Auth::guard('web')->user()->id;
        $emails = [];
        $getAllContacts = contact_sync::where('contact_id',$id)->where('email','!=','')->get();
        if($getAllContacts->isNotEmpty()){
            $emails = $getAllContacts->pluck('email')->toArray();
        }
        

        $yesvite_users = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
                ->where('id', '!=', $id)
                ->where(['app_user' => '1'])
                ->whereIn('email',$emails)
                ->orderBy('firstname')
                ->limit(6)
                ->get();

            // dd($yesvite_users);
        $yesvite_user = [];
        foreach ($yesvite_users as $user) {
            if ($user->email_verified_at == NULL && $user->app_user == '1') {
                continue;
            }
            $yesviteUserDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : asset('public/storage/profile/' . $user->profile),
                'firstname' => (!empty($user->firstname) || $user->firstname != null) ? $user->firstname : "",
                'lastname' => (!empty($user->lastname) || $user->lastname != null) ? $user->lastname : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'country_code' => (!empty($user->country_code) || $user->country_code != null) ? strval($user->country_code) : "",
                'phone_number' => (!empty($user->phone_number) || $user->phone_number != null) ? $user->phone_number : "",
                'app_user' => (!empty($user->app_user) || $user->app_user != null) ? $user->app_user : "",
            ];
            // $yesviteUserDetail['app_user']  = $user->app_user;
            // $yesviteUserDetail['visible'] =  $user->visible;
            // $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
            // $yesviteUserDetail['prefer_by']  = $user->prefer_by;
            $yesvite_user[] = (object)$yesviteUserDetail;
        }



     
        $getAllContacts = contact_sync::where('contact_id',$id)->orderBy('firstName','asc')->limit(6)
            // ->when($type != 'group', function ($query) use ($request) {
            //     $query->where(function ($q) use ($request) {
            //         $q->limit($request->limit)
            //             ->skip($request->offset);
            //     });
            // })
            // ->when($request->search_user != '', function ($query) use ($search_user) {
            //     $query->where(function ($q) use ($search_user) {
            //         $q->where('firstName', 'LIKE', '%' . $search_user . '%')
            //             ->orWhere('lastName', 'LIKE', '%' . $search_user . '%');
            //     });
            // })
            ->get();

        // dd($yesvite_users);
        $yesvite_phone = [];
        foreach ($getAllContacts as $user) {
            $yesviteUserPhoneDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : $user->profile,
                'firstname' => (!empty($user->firstName) || $user->firstName != null) ? $user->firstName : "",
                'lastname' => (!empty($user->lastName) || $user->lastName != null) ? $user->lastName : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'phone_number' => (!empty($user->phoneWithCode) || $user->phoneWithCode != null) ? $user->phoneWithCode : "",
            ];
            $yesvite_phone[] = (object)$yesviteUserPhoneDetail;
        }


        return view('layout', compact(
            'title',
            'page',
            'user',
            'js',
            'yesvite_user',
            'yesviteGroups',
            'yesvite_phone',
            'groups'

        ));
    }

    public function loadMore(Request $request)
    {
        $id = Auth::guard('web')->user()->id;
        $searchName = $request->search_name;
        $type = $request->type;
        if ($request->ajax()) {
            // $query = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname');
            // if ($searchName) {
            //     $query->where(function ($q) use ($searchName) {
            //         $q->where('firstname', 'LIKE', '%' . $searchName . '%')
            //             ->orWhere('lastname', 'LIKE', '%' . $searchName . '%');
            //     });
            // }
            // $yesviteUser = $query->paginate(10);
            $id = Auth::guard('web')->user()->id;
            $emails = [];
            $getAllContacts = contact_sync::where('contact_id',$id)->where('email','!=','')->get();
            if($getAllContacts->isNotEmpty()){
                $emails = $getAllContacts->pluck('email')->toArray();
            }
            $yesvite_users = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
            ->where('id', '!=', $id)
            ->where(['app_user' => '1'])
            ->whereIn('email',$emails)
            ->orderBy('firstname')
            ->when((!empty($request->offset)&&!empty($request->limit))&&($request->has('limit') && $request->has('offset')), function ($query) use ($request) {
                $query->skip($request->offset)
                ->limit($request->limit);
            })
            ->when(empty($request->search_name), function ($query) {
                $query->limit(6);
            })
            ->when(!empty($request->search_name), function ($query) use ($searchName) {
                $query->where(function ($q) use ($searchName) {
                    $q->where('firstname', 'LIKE', '%' . $searchName . '%')
                      ->orWhere('lastname', 'LIKE', '%' . $searchName . '%');
                });
            })
            ->get();

            $yesvite_user = [];
            foreach ($yesvite_users as $user) {
                if ($user->email_verified_at == NULL && $user->app_user == '1') {
                    continue;
                }
                $yesviteUserDetail = [
                    'id' => $user->id,
                    'profile' => empty($user->profile) ? "" : asset('public/storage/profile/' . $user->profile),
                    'firstname' => (!empty($user->firstname) || $user->firstname != null) ? $user->firstname : "",
                    'lastname' => (!empty($user->lastname) || $user->lastname != null) ? $user->lastname : "",
                    'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                    'country_code' => (!empty($user->country_code) || $user->country_code != null) ? strval($user->country_code) : "",
                    'phone_number' => (!empty($user->phone_number) || $user->phone_number != null) ? $user->phone_number : "",
                    'app_user' => (!empty($user->app_user) || $user->app_user != null) ? $user->app_user : "",
                ];
                $yesvite_user[] = (object)$yesviteUserDetail;
            }
            if(empty($yesvite_user)){
                return response()->json(['status'=>'0']);
            }else{
                if($searchName!=''){
                    return response()->json([
                        'view' => view('front.ajax_contacts', compact('yesvite_user'))->render(),
                        'search' =>'1',
                        'status' => '1',
                    ]);
                }else{
                    return response()->json([
                        'view' => view('front.ajax_contacts', compact('yesvite_user'))->render(),
                        'status' => '1',
                    ]);
                }
             

                // return view('front.ajax_contacts', compact('yesvite_user'))->render();
            }
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function loadMoreGroup(Request $request)
    {
        try {
            $searchGroup = $request->input('search_group');
            $user = Auth::guard('web')->user();

            if ($request->ajax()) {
                $query = Group::where('user_id', $user->id)->withCount('groupMembers');

                if ($searchGroup) {
                    $query->where('name', 'LIKE', '%' . $searchGroup . '%');
                }

                $yesviteGroups = $query->paginate(10);
                // return view('front.ajax_groups', compact('yesviteGroups'))->render();

                if($searchGroup!=''){
                    return response()->json([
                        'view' => view('front.ajax_groups', compact('yesviteGroups'))->render(),
                        'search' =>'1',
                        'status' => '1',
                    ]);
                }else{
                    return response()->json([
                        'view' => view('front.ajax_groups', compact('yesviteGroups'))->render(),
                        'status' => '1',
                    ]);
                }
    
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
            // $searchName = $request->search_name;
            // $type = $request->type;
            if ($request->ajax()) {
            // //     $query = User::where(['is_user_phone_contact' => '1', 'parent_user_phone_contact' => $id]);

            // //     if ($searchPhone) {
            // //         $query->where(function ($q) use ($searchPhone) {
            // //             $q->where('firstname', 'LIKE', '%' . $searchPhone . '%')
            // //                 ->orWhere('lastname', 'LIKE', '%' . $searchPhone . '%');
            // //         });
            // //     }

            // //     $yesvitePhones = $query->paginate(10);
            // $getAllContacts = contact_sync::where('contact_id',$id)

            // ->when($type == 'phone', function ($query) use ($request) {
            //     $query->where(function ($q) use ($request) {
            //         $q->limit($request->limit)
            //             ->skip($request->offset);
            //     });
            // })
            // ->get();
            // ->when($request->search_name != ''||$request->search_name != null, function ($query) use ($searchName) {
            //     $query->where(function ($q) use ($searchName) {
            //         $q->where('firstName', 'LIKE', '%' . $searchName . '%')
            //             ->orWhere('lastName', 'LIKE', '%' . $searchName . '%');
            //     });
            // })

        // dd(count($getAllContacts));

        $query = contact_sync::where('contact_id', $id)->orderBy('firstName','asc');
      
        
        if (!empty($searchPhone)) {
            $query->where(function ($q) use ($searchPhone) {
                $q->where('firstName', 'LIKE', '%' . $searchPhone . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchPhone . '%');
            });
        }
       
        if ((!empty($request->offset)&&!empty($request->limit))&&($request->has('limit') && $request->has('offset'))) {
            $query->skip($request->offset)->take($request->limit);
        }
        if(empty($searchPhone) && empty($request->offset)){
            // dd(1);
            $query->limit(6);
        }
        $getAllContacts = $query->get();
        $yesvite_phone = [];
        foreach ($getAllContacts as $user) {
            $yesviteUserPhoneDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : $user->profile,
                'firstname' => (!empty($user->firstName) || $user->firstName != null) ? $user->firstName : "",
                'lastname' => (!empty($user->lastName) || $user->lastName != null) ? $user->lastName : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'phone_number' => (!empty($user->phoneWithCode) || $user->phoneWithCode != null) ? $user->phoneWithCode : "",
            ];
            $yesvite_phone[] = (object)$yesviteUserPhoneDetail;
        }
        if(empty($yesvite_phone)){
            return response()->json(['status' => '0']);

        }else{
            // return view('front.ajax_phones', compact('yesvite_phone'))->render();

            if($searchPhone!=''){
                return response()->json([
                    'view' => view('front.ajax_phones', compact('yesvite_phone'))->render(),
                    'search' =>'1',
                    'status' => '1',
                ]);
            }else{
                return response()->json([
                    'view' => view('front.ajax_phones', compact('yesvite_phone'))->render(),
                    'status' => '1',
                ]);
            }

        }
            }
            return response()->json(['error' => 'Invalid request'], 400);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }


    public function addContact(Request $request,string $id)
    {
        
        $user = Auth::guard('web')->user();
        // dd($request);
        // $rawData = $request->getContent();
        // $input = json_decode($rawData, true);

        // if ($input === null) {
        //     return response()->json(['status' => 0, 'message' => 'Invalid JSON']);
        // }

        // if ($input['prefer_by'] == 'email') {
        //     $validator = Validator::make($input, [
        //         'firstname' => ['required'],
        //         'lastname' => ['required'],
        //         'email' => ['required', 'email:rfc,dns'],
        //         'prefer_by' => ['required', 'in:email,phone']

        //     ]);
        // } elseif ($input['prefer_by'] == 'phone') {
        //     $validator = Validator::make($input, [
        //         'firstname' => ['required'],
        //         'lastname' => ['required'],
        //         'country_code' => ['required'],
        //         'phone_number' => ['required'],
        //         'email' => ['required', 'email:rfc,dns'],
        //         'prefer_by' => ['required', 'in:email,phone']
        //     ]);
        // }

        $customMessages = [
            'prefer_by.in' => 'The prefer_by field must be email or phone.',
        ];

        // $validator->setCustomMessages($customMessages);
        // if ($validator->fails()) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => $validator->errors()->first(),
        //     ]);
        // }
        try {
            DB::beginTransaction();
            // $emails = array_filter(array_column($input, 'email'));
            $newContacts = [];
            $updatedContacts = [];

            // foreach ($input as $contact) {
            // $contact = $input;
            // dd($contact);
            $email = $request->input('email') ?? '';
            $phone = '+1 '.$request->input('phone_number') ?? '';
            $phonewithCode= $phone;

            // dd($phonewithCode);
            if (
                (isset($request->email) && $request->email != '' && $user->email == $request->email) || 
                (isset($request->phone_number) && $request->phone_number != '' && $user->phone_number == $request->phone_number)
            ) {
        
             return response()->json([
                    'status' => 0,
                    'message' => 'You can not add your details as contact',
                    'data' => $updatedContacts,
                ]);
            }

            if ($email != "") {
                $existingContact = contact_sync::where('email', $email)->first();
                if (isset($existingContact)) {
                    $existingContact->update([
                        'isAppUser' => $existingContact->isAppUser,
                        'phone' => '',
                        'firstName' => $request->input('Fname') ?? $existingContact->firstName,
                        'lastName' => $request->input('Lname') ?? $existingContact->lastName,
                        'photo' => $request->input('photo') ?? $existingContact->photo,
                        'phoneWithCode' => '',
                        'visible' => ($contact['visible'] ?? $existingContact->visible),
                        'preferBy' => $contact['prefer_by'] ?? $existingContact->preferBy,
                    ]);
                    $existingContact->sync_id = $existingContact->id;

                    $updatedContacts[] = $existingContact;
                } else {
                    $newContact = new contact_sync();
                    $newContact->userId = null;
                    $newContact->contact_id = $user->id;
                    $newContact->firstName = $request->input('Fname') ?? '';
                    $newContact->lastName = $request->input('Lname') ?? '';
                    $newContact->phone = '';
                    $newContact->email = $request->input('email') ?? '';
                    $newContact->photo = $request->input('photo') ?? '';
                    $newContact->phoneWithCode = '';
                    $newContact->isAppUser = '0';
                    $newContact->visible = '0';
                    $newContact->preferBy = "email" ?? '';
                    $newContact->created_at = now();
                    $newContact->updated_at = now();
                    $newContact->save();

                    $newContact->sync_id = $newContact->id;
                    $newContacts[] = $newContact;
                }
            }

            if ($phone != "") {
                $existingContact = contact_sync::where('phoneWithCode', $phone)->first();
                if (isset($existingContact)) {
                    $existingContact->update([
                        'isAppUser' => $existingContact->isAppUser,
                        'phone' => '+1 '.$request->input('phone_number') ?? $existingContact->phone,
                        'firstName' => $request->input['Fname'] ?? $existingContact->firstName,
                        'lastName' => $request->input('Lname') ?? $existingContact->lastName,
                        'photo' => $request->input('photo') ?? $existingContact->photo,
                        'phoneWithCode' => $request->input('phone_number') ?? $existingContact->phoneWithCode,
                        'visible' => ($contact['visible'] ?? $existingContact->visible),
                        'preferBy' => $contact['prefer_by'] ?? $existingContact->preferBy,
                    ]);
                    $existingContact->sync_id = $existingContact->id;

                    $updatedContacts[] = $existingContact;
                } else {
                    $newContact = new contact_sync();
                    $newContact->userId = null;
                    $newContact->contact_id = $user->id;
                    $newContact->firstName = $request->input('Fname') ?? '';
                    $newContact->lastName = $request->input('Lname') ?? '';
                    $newContact->phone = '+1 '.$request->input('phone_number') ?? '';
                    $newContact->email = '';
                    $newContact->photo = $request->input('photo') ?? '';
                    $newContact->phoneWithCode = $phonewithCode ?? '';
                    $newContact->isAppUser = '0';
                    $newContact->visible = '0';
                    $newContact->preferBy = "phone" ?? '';
                    $newContact->created_at = now();
                    $newContact->updated_at = now();
                    $newContact->save();

                    $newContact->sync_id = $newContact->id;
                    $newContacts[] = $newContact;
                }
            }
            DB::commit();
            $allSyncedContacts = array_merge($newContacts, $updatedContacts);

            // $emails = array_filter(array_column($input, 'email'));
            // $phoneNumbers = array_filter(array_column($input, 'phone_number'));

            $userDetails = User::select('id', 'email', 'phone_number', 'firstname', 'lastname', 'profile', 'app_user', 'visible', 'prefer_by')
                ->where('email', $request->input('email'))
                ->where('app_user', '1')
                // ->orWhere('phone_number', $request->input('phone_number'))
                ->get();
            // dd($userDetails);
            foreach ($userDetails as $userDetail) {

                contact_sync::where('contact_id', $user->id)
                    ->where(function ($query) use ($userDetail) {
                        $query->where('email', $userDetail->email)
                            ->orWhere('phone', $userDetail->phone_number);
                    })
                    ->update([
                        'userId' => $userDetail->id,
                        'firstName' => $userDetail->firstname,
                        'lastName' => $userDetail->lastname
                    ]);
                $index = array_search(true, array_map(function ($allSyncedContact) use ($userDetail) {

                    // return $updatedContacts['email'] === $userDetail->email || $updatedContacts['phone'] === $userDetail->phone_number;
                    if ($allSyncedContact['email'] == $userDetail->email || $allSyncedContact['phone'] == $userDetail->phone_number) {
                        if ($allSyncedContact['email'] == $userDetail->email) {
                            return $allSyncedContact['email'] === $userDetail->email;
                        }

                        if ($userDetail->phone_number != '' && $allSyncedContact['phone'] == $userDetail->phone_number) {
                            // dd($allSyncedContact);
                            return $allSyncedContact['phone'] === $userDetail->phone_number;
                        }
                    }
                }, $allSyncedContacts));
                if ($index !== false) {

                    // Update the matching contact
                    $allSyncedContacts[$index]['userId'] = $userDetail->id;
                    $allSyncedContacts[$index]['isAppUser'] = (int)$userDetail->app_user;
                    $allSyncedContacts[$index]['firstName'] = $userDetail->firstname;
                    $allSyncedContacts[$index]['lastName'] = $userDetail->lastname;
                    $allSyncedContacts[$index]['visible'] = $userDetail->visible;
                    $allSyncedContacts[$index]['email'] = $userDetail->email;
                    $allSyncedContacts[$index]['phone'] = $userDetail->phone_number;
                    $allSyncedContacts[$index]['preferBy'] = $userDetail->prefer_by;
                    $allSyncedContacts[$index]['photo'] = $userDetail->profile ? asset('storage/profile/' . $userDetail->profile) : '';
                }
            }
            // Fetch all updated contacts from the request payload
            // echo "<pre>";
            // print_r($allSyncedContacts);
            // die;
            $allSyncedContacts = array_map(function ($item) {
                // dd($item);
                $item['isAppUser'] = (int)$item['isAppUser'];
                $item['visible'] = (int)$item['visible'];
                if ($item['phone'] === null) {
                    $item['phone'] = '';
                }
                if ($item['userId'] === null) {
                    $item['userId'] = 0;
                }
                return $item;
            }, $allSyncedContacts);
            // dd($allSyncedContacts);
            return response()->json([
                'status' => 1,
                'message' => empty($updatedContacts) ? 'Contacts inserted successfully.' : 'Contacts updated successfully.',
                'data' => $allSyncedContacts,
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            // dd($e);
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }

//     public function addContact(Request $request, string $id)
//     {

//         // dd($request);
//         $id = decrypt($id);
//         try {
//             $validator = Validator::make($request->all(), [
//                 'Fname' => 'required|string', // max 2MB
//                 'Lname' => 'required|string', // max 2MB
//                 // 'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/', Rule::unique('users')->ignore(decrypt($request->id))],
//                 'phone_number' => ['present', 'nullable', 'regex:/^\d{3}-\d{3}-\d{4}$/', Rule::unique('users', 'phone_number')->ignore(decrypt($request->id)),
// ],

//                 'email' => ['required', 'email', new EmailExists], // max 2MB

//             ], [
//                 'Fname.required' => 'Please enter First Name',
//                 'Lname.required' => 'Please enter Last Name',

//                 // 'phone_number.numeric' => 'Please enter Phone Number in digit',
//                 'phone_number.regex' => 'Phone number must be in the format 123-123-1234.',

//                 'email.required' => 'Please enter email',
//                 'email.email' => 'Please enter a valid email address',
//             ]);

//             if ($validator->fails()) {
//                 return response()->json([
//                     'status' => 0,
//                     'message' => $validator->errors()->first(),

//                 ]);
//             }

//             DB::beginTransaction();

//             $addcontact =  User::create([
//                 'firstname' => $request['Fname'],
//                 'lastname' => $request['Lname'],
//                 'email' => $request['email'],
//                 'phone_number' => $request['phone_number'],
//                 'country_code' => $request['country_code'],
//                 'app_user' => '0',
//                 'parent_user_phone_contact' => $id,
//                 'user_parent_id' => $id,
//                 'is_user_phone_contact' => '0'

//             ]);

//             DB::commit();
//             return response()->json(['status' => 1, 'message' => "Contact Added!", 'user' => $addcontact]);
//         } catch (QueryException $e) {
//             DB::Rollback();
//             $userData =  getUser($id);
//             return response()->json(['status' => 0, 'message' => "db error", 'user' => $userData]);
//         } catch (Exception  $e) {
//             $userData =  getUser($id);
//             return response()->json(['status' => 0, 'message' => "something went wrong", 'user' => $userData]);
//         }
//     }
    public function editContact(Request $request, string $id)
    {
        $editContact = contact_sync::where('id', '=', $id)->get()->first();
        return response()->json(['status' => 1, 'message' => "Contact Added!", 'edit' => $editContact]);
    }
    public function editYesviteContact(Request $request, string $id)
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
                // 'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/'],
                'email' => ['required', 'email', 'unique:users,email,' . $request->edit_id],

            ], [
                'edit_Fname.required' => 'Please enter First Name',
                'edit_Lname.required' => 'Please enter Last Name',

                // 'phone_number.numeric' => 'Please enter Phone Number in digit',
                // 'phone_number.regex' => 'Phone Number format is invalid.',

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

    public function save_editPhoneContact(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'edit_Fname' => 'required|string', // max 2MB
                'edit_Lname' => 'required|string', // max 2MB
                // 'phone_number' => ['present', 'nullable', 'numeric', 'regex:/^\d{10,15}$/'],
                // 'email' => ['required', 'email', 'unique:users,email,' . $request->edit_id],

            ], [
                'edit_Fname.required' => 'Please enter First Name',
                'edit_Lname.required' => 'Please enter Last Name',

                // 'phone_number.numeric' => 'Please enter Phone Number in digit',
                // 'phone_number.regex' => 'Phone Number format is invalid.',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),

                ]);
            }

            DB::beginTransaction();
            $usercontactUpdate = contact_sync::where('id', $request->edit_id)->first();

            $usercontactUpdate->firstName = $request->edit_Fname;

            $usercontactUpdate->lastName = $request->edit_Lname;

            if($request->phone_number!=""){
                $usercontactUpdate->phoneWithCode = '+1 '.$request->phone_number;
                $usercontactUpdate->phone = '+1 '.$request->phone_number;
            }

            if($request->email!=""){
                $usercontactUpdate->email = $request->email;
            }

            $usercontactUpdate->save();
            DB::commit();

            $usercontactUpdate =  getUser($request->edit_id);
            if($usercontactUpdate==null){
                return response()->json(['status' => 1, 'message' => "Edit Saved!", 'user' => ""]);
            }else{
                return response()->json(['status' => 1, 'message' => "Edit Saved!", 'user' => $usercontactUpdate]);
            }
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
        $user = Auth::guard('web')->user();
        $email = $user->email;
        $type_email = $request->input('email');
        if($email==$type_email){
            $exists = User::where('email', $email)->exists();
            if ($exists) {
                return response()->json(false);
            } else {
                return response()->json(true);
            }
        }else{
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

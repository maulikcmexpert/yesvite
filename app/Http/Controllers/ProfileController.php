<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use libphonenumber\PhoneNumberUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

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

    public function update(Request $request, string $id)
    {
        dd($request->lastname);

        try {

            DB::beginTransaction();
            $userUpdate = User::where('id', $user->id)->first();

            $userUpdate->firstname = $input['firstname'];

            $userUpdate->lastname = $input['lastname'];
            $userUpdate->gender = ($input['gender'] != "") ? $input['gender'] : $userUpdate->gender;
            $userUpdate->birth_date = ($input['birth_date'] != "") ? $input['birth_date'] : $userUpdate->birth_date;
            $userUpdate->country_code = ($input['country_code'] != "") ? $input['country_code'] : $userUpdate->country_code;
            $userUpdate->phone_number = ($input['phone_number'] != "") ? $input['phone_number'] : $userUpdate->phone_number;
            $userUpdate->about_me = ($input['about_me'] != "") ? $input['about_me'] : $userUpdate->about_me;
            $userUpdate->zip_code = ($input['zip_code'] != "") ? $input['zip_code'] : $userUpdate->zip_code;
            if ($userUpdate->account_type == '1') {
                $validator = Validator::make($input, [
                    'company_name' => 'required',
                ]);
                $userUpdate->company_name = $input['company_name'];
            }
            $userUpdate->address = ($input['address'] != "") ? $input['address'] : $userUpdate->address;
            $userUpdate->address_2 = (isset($input['address_2'])  && $input['address_2'] != "") ? $input['address_2'] : $userUpdate->address_2;
            $userUpdate->city = ($input['city'] != "") ? $input['city'] : $userUpdate->city;
            $userUpdate->state = ($input['state'] != "") ? $input['state'] : $userUpdate->state;
            $userUpdate->save();
            DB::commit();

            $details = User::where('id', $user->id)->first();
            if (!empty($details)) {
                $totalEvent =  Event::where('user_id', $user->id)->count();
                $totalEventPhotos = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
                $postComments =  EventPostComment::where('user_id', $user->id)->count();
                $profileData = [
                    'id' =>  empty($details->id) ? "" : $details->id,
                    'profile' =>  empty($details->profile) ?  "" : asset('public/storage/profile/' . $details->profile),
                    'bg_profile' =>  empty($details->bg_profile) ? "" : asset('public/storage/bg_profile/' . $details->bg_profile),
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'firstname' => empty($details->firstname) ? "" : $details->firstname,
                    'lastname' => empty($details->lastname) ? "" : $details->lastname,
                    'birth_date' => empty($details->birth_date) ? "" : $details->birth_date,
                    'email' => empty($details->email) ? "" : $details->email,
                    'about_me' => empty($details->about_me) ? "" : $details->about_me,
                    'created_at' => empty($details->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($details->created_at))),
                    'total_events' => $totalEvent,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $postComments,
                    'gender' => empty($details->gender) ? "" : $details->gender,
                    'country_code' => empty($details->country_code) ? "" : strval($details->country_code),
                    'phone_number' => empty($details->phone_number) ? "" : $details->phone_number,
                    'visible' =>  $details->visible,
                    'account_type' =>  $details->account_type,
                    'company_name' => empty($details->company_name) ? "" : $details->company_name,
                    'address' => empty($details->address) ? "" : $details->address,
                    'address_2' => empty($details->address_2) ? "" : $details->address_2,
                    'city' => empty($details->city) ? "" : $details->city,
                    'state' => empty($details->state) ? "" : $details->state,
                    'zip_code' => empty($details->zip_code) ? "" : $details->zip_code
                ];


                return response()->json(['status' => 1, 'data' => $profileData, 'message' => "Changes Saved!"]);
            } else {

                return response()->json(['status' => 0, 'message' => "User profile not found", 'message' => "The requested user profile data does not exist."]);
            }
        } catch (QueryException $e) {
            DB::Rollback();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }
}

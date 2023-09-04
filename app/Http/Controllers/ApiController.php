<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\User;
use App\Models\Event;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class ApiController extends Controller
{

    public function home()
    {
        $user  = Auth::guard('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user  = Auth::guard('api')->user();

        $input = $request->all();
        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }


        if (Storage::disk('public')->exists('profile/' . $user->profile)) {
            Storage::disk('public')->delete('profile/' . $user->profile);
        }

        $image = $request->profile;
        $imageName = time() . '_' . $image->getClientOriginalName();
        Storage::disk('public')->putFileAs('profile', $image, $imageName);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->profile = $imageName;
        $user->gender = ($request->gender != "") ? $request->gender : $user->gender;
        $user->birth_date = ($request->birth_date != "") ? $request->birth_date : $user->birth_date;
        $user->email = ($request->email != "") ? $request->email : $user->email;
        $user->country_code = ($request->country_code != "") ? $request->country_code : $user->country_code;
        $user->phone_number = ($request->phone_number != "") ? $request->phone_number : $user->phone_number;
        $user->about_me = ($request->about_me != "") ? $request->about_me : $user->about_me;
        $user->save();

        if ($user->visible == '1') {
            $validator = Validator::make($input, [
                'company_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->all(),
                    'status' => 401,
                ]);
            }
            $company = Company::where('user_id', $user->id)->first();
            $company->company_name = $request->company_name;
            $company->address = ($request->address != "") ? $request->address : $user->address;
            $company->city = ($request->city != "") ? $request->city : $user->city;
            $company->state = ($request->state != "") ? $request->state : $user->state;
            $company->zipcode = ($request->zipcode != "") ? $request->zipcode : $user->zipcode;
            $company->save();
        }


        $details = User::with('company')->where('id', $user->id)->get();

        $profileData = [];
        if (!empty($details)) {
            $userDetail['id'] = empty($details[0]->id) ? "" : $details[0]->id;
            $userDetail['profile'] =  empty($details[0]->profile) ? "" : asset('storage/profile/' . $details[0]->profile);
            $userDetail['firstname'] = empty($details[0]->firstname) ? "" : $details[0]->firstname;
            $userDetail['lastname'] = empty($details[0]->lastname) ? "" : $details[0]->lastname;
            $userDetail['gender'] = empty($details[0]->gender) ? "" : $details[0]->gender;
            $userDetail['birth_date'] = empty($details[0]->birth_date) ? "" : $details[0]->birth_date;
            $userDetail['email'] = empty($details[0]->email) ? "" : $details[0]->email;
            $userDetail['country_code'] = empty($details[0]->country_code) ? "" : $details[0]->country_code;
            $userDetail['phone_number'] = empty($details[0]->phone_number) ? "" : $details[0]->phone_number;
            $userDetail['about_me'] = empty($details[0]->about_me) ? "" : $details[0]->about_me;
            $userDetail['visible'] =  $details[0]->visible;
            $userDetail['account_type'] =  $details[0]->account_type;
            if ($details[0]->account_type == '1') {
                $userDetail['company_name'] = empty($details[0]->company_name) ? "" : $details[0]->company_name;
                $userDetail['address'] = empty($details[0]->address) ? "" : $details[0]->address;
                $userDetail['city'] = empty($details[0]->city) ? "" : $details[0]->city;
                $userDetail['state'] = empty($details[0]->state) ? "" : $details[0]->state;
                $userDetail['zipcode'] = empty($details[0]->zipcode) ? "" : $details[0]->zipcode;
            }
            $profileData[] = $userDetail;

            return response()->json(['data' => $profileData, 'message' => "Profile updated successfully"], 200);
        } else {
            return response()->json(['error' => "User profile not found", 'message' => "The requested user profile data does not exist."], 404);
        }
    }

    public function myProfile()
    {
        $user  = Auth::guard('api')->user();

        $totalEvent =  Event::where('user_id', $user->id)->count();
        $totalEventPhotos = 0;
        $comments = 0;
        $profileData = [];

        if (!empty($user)) {
            $userDetail['firstname'] = empty($user->firstname) ? "" : $user->firstname;
            $userDetail['firstname'] = empty($user->firstname) ? "" : $user->firstname;
            $userDetail['lastname'] = empty($user->lastname) ? "" : $user->lastname;
            $userDetail['email'] = empty($user->email) ? "" : $user->email;
            $userDetail['about_me'] = empty($user->about_me) ? "" : $user->about_me;
            $userDetail['created_at'] = empty($user->created_at) ? "" :   date('l Y', strtotime($user->created_at));
            $userDetail['total_events'] = $totalEvent;
            $userDetail['total_photos'] = $totalEventPhotos;
            $userDetail['comments'] = $comments;
            $userDetail['gender'] = empty($user->gender) ? "" : $user->gender;
            $userDetail['country_code'] = empty($user->country_code) ? "" : $user->country_code;
            $userDetail['phone_number'] = empty($user->phone_number) ? "" : $user->phone_number;
            $userDetail['visible'] =  $user->visible;
            $userDetail['account_type'] =  $user->account_type;
            if ($user->account_type == '1') {
                $companyDetail = Company::where('user_id', $user->id)->first();
                $userDetail['company_name'] = empty($companyDetail->company_name) ? "" : $companyDetail->company_name;
                $userDetail['address'] = empty($companyDetail->address) ? "" : $companyDetail->address;
                $userDetail['city'] = empty($companyDetail->city) ? "" : $companyDetail->city;
                $userDetail['state'] = empty($companyDetail->state) ? "" : $companyDetail->state;
                $userDetail['zipcode'] = empty($companyDetail->zipcode) ? "" : $companyDetail->zipcode;
            }
            $profileData[] = $userDetail;

            return response()->json(['data' => $profileData, 'message' => "My Profile"], 200);
        } else {
            return response()->json(['error' => "User profile not found", 'message' => "The requested user profile data does not exist."], 404);
        }
    }
}

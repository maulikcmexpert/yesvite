<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    Event,
    EventPost,
    EventPostComment
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use libphonenumber\PhoneNumberUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Exceptions\Handler as Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{

    public function index()
    {

        $id = decrypt(session()->get('user')['id']);
        $title = 'Profile';
        $page = 'front.profile';

        $user = User::findOrFail($id);
        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
        $user['photos']   = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
        $user['comments']   =  EventPostComment::where('user_id', $user->id)->count();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;

        return view('layout', compact(
            'title',
            'page',
            'user',

        ));
    }

    public function publicProfileView()
    {
        $id = decrypt(session()->get('user')['id']);
        $user = User::findOrFail($id);
        $title = 'Public Profile';
        $page = 'front.public_profile';
        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
        $user['photos']   = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
        $user['comments']   =  EventPostComment::where('user_id', $user->id)->count();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        return view('layout', compact(
            'title',
            'page',
            'user',
        ));
    }


    public function edit()
    {
        $id = decrypt(session()->get('user')['id']);
        $user = User::findOrFail($id);
        $title = 'Edit Profile';
        $page = 'front.edit_profile';
        $js = ['profile'];
        $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
        $user['photos']   = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
        $user['comments']   =  EventPostComment::where('user_id', $user->id)->count();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        return view('layout', compact(
            'title',
            'page',
            'user',
            'js'
        ));
    }

    public function changePassword()
    {
        $id = decrypt(session()->get('user')['id']);
        $user = User::findOrFail($id);
        $title = 'Change Password';
        $page = 'front.change_password';
        $js = ['profile'];
        // $user['events'] =   Event::where(['user_id' => $user->id, 'is_draft_save' => '0'])->count();
        // $user['photos']   = EventPost::where(['user_id' => $user->id, 'post_type' => '1'])->count();
        // $user['comments']   =  EventPostComment::where('user_id', $user->id)->count();
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : asset('storage/profile/no_profile.png');
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        return view('layout', compact(
            'title',
            'page',
            'user',
            'js'
        ));
    }

    public function verifyPassword(Request $request)
    {
        echo 1;
        die;
        $password = $request->input('password');

        $id = decrypt(session()->get('user')['id']);
        $user = User::findOrFail($id);
        if (Hash::check($password, $user->password)) {
            return response()->json(['valid' => true]);
        } else {
            return response()->json(['valid' => false]);
        }
    }

    public function update(Request $request, string $id)
    {
        $id = decrypt($id);

        try {

            DB::beginTransaction();
            $userUpdate = User::where('id', $id)->first();

            $userUpdate->firstname = $request->firstname;

            $userUpdate->lastname = $request->lastname;
            $userUpdate->gender = ($request->gender != "" || $request->gender != NULL) ? $request->gender : $userUpdate->gender;
            $userUpdate->birth_date = ($request->birth_date != "" || $request->birth_date != NULL) ? $request->birth_date : $userUpdate->birth_date;
            $userUpdate->country_code = ($request->country_code != "" || $request->country_code != NULL) ? $request->country_code : $userUpdate->country_code;
            $userUpdate->phone_number = ($request->phone_number != ""  || $request->phone_number != NULL)  ? $request->phone_number : "";
            $userUpdate->about_me = ($request->about_me != "" || $request->about_me != NULL) ? $request->about_me : "";
            $userUpdate->zip_code = ($request->zip_code != "" || $request->zip_code != NULL) ? $request->zip_code : $userUpdate->zip_code;
            // if ($userUpdate->account_type == '1') {
            //     $validator = Validator::make($request, [
            //         'company_name' => 'required',
            //     ]);
            //     $userUpdate->company_name = $request->company_name;
            // }
            $userUpdate->address = ($request->address != "") ? $request->address : $userUpdate->address;
            $userUpdate->address_2 = (isset($request->address_2)  && $request->address_2 != "" || $request->address_2 != NULL) ? $request->address_2 : $userUpdate->address_2;
            $userUpdate->city = ($request->city != "" || $request->city != NULL) ? $request->city : $userUpdate->city;
            $userUpdate->state = ($request->state != "" ||  $request->state != NULL) ? $request->state : $userUpdate->state;
            $userUpdate->save();
            DB::commit();

            $userData =  getUser($id);
            return response()->json(['status' => 1, 'message' => "Changes Saved!", 'user' => $userData]);
            return  redirect()->route('profile')->with('success', 'Changes Saved!');
        } catch (QueryException $e) {
            DB::Rollback();
            $userData =  getUser($id);
            return response()->json(['status' => 0, 'message' => "db error", 'user' => $userData]);
            //  return redirect()->route('profile')->with('error', 'db error');
        } catch (Exception  $e) {
            $userData =  getUser($id);
            return response()->json(['status' => 0, 'message' => "something went wrong", 'user' => $userData]);
            // return redirect()->route('profile')->with('error', 'something went wrong');
        }
    }

    public function uploadProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $file = $request->file('file');

        if (!empty($file)) {
            $user = Auth::guard('web')->user();
            if ($user->profile != "" || $user->profile != NULL) {

                if (file_exists(public_path('storage/profile/') . $user->profile)) {
                    $imagePath = public_path('storage/profile/') . $user->profile;
                    unlink($imagePath);
                }
            }





            $imageName = time() . '_' . $file->getClientOriginalName();


            $file->move(public_path('storage/profile'), $imageName);
            $user->profile = $imageName;
            $user->save();
            $imageData =   asset('storage/profile/' . $imageName);
            if (session()->has('user.profile')) {
                session(['user.profile' => $imageData]); // Replace 'new_value' with your desired value
            }
        }
        return $imageData;
    }
    public function uploadBgProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $file = $request->file('file');

        if (!empty($file)) {
            $user = Auth::guard('web')->user();
            if ($user->bg_profile != "" || $user->bg_profile != NULL) {

                if (file_exists(public_path('storage/bg_profile/') . $user->bg_profile)) {
                    $imagePath = public_path('storage/bg_profile/') . $user->bg_profile;
                    unlink($imagePath);
                }
            }

            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/bg_profile'), $imageName);
            $user->bg_profile = $imageName;
            $user->save();
            $imageData =   asset('storage/bg_profile/' . $imageName);
            if (session()->has('user.bg_profile')) {
                session(['user.bg_profile' => $imageData]); // Replace 'new_value' with your desired value
            }
        }
        return $imageData;
    }
}

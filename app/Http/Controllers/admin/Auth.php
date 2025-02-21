<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
// use Cookie;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\DB;
use App\Mail\forgotpasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Auth extends Controller
{
    //

    public function checkLogin(Request $req)
    {

        $req->validate(
            [
                'email' => 'required|email|exists:admins,email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Please enter email!',
                'email.exists' => 'This email is not registered!',
                'password.required' => 'Please enter password!'
            ]
        );
        $adminData = Admin::where("email", $req->input('email'))->first();

        if (Hash::check($req->input('password'), $adminData->password)) {

            if ($adminData) {


                $remember = $req->has('remember');
                if ($remember) {
                    Cookie::queue('admin_email', $adminData->email, 120);
                    Cookie::queue('admin_password', $req->password, 120);
                } else {
                    Cookie::queue(Cookie::forget('admin_email'));
                    Cookie::queue(Cookie::forget('admin_password'));

                    Cookie::forget('admin_email');
                    Cookie::forget('admin_password');
                }

                $saveOtp =   Admin::where("id", $adminData->id)->first();

                // if (config('app.debug', true)) {
                //         $saveOtp->otp = '111111';
                //         $saveOtp->save();
                //         $sendMesage = [
                //             "status" => true,
                //             "message" => "success"
                //         ];
                // }else{
                        $token = str_pad(random_int(0, 9999), 6, '0', STR_PAD_LEFT);
                        session()->forget('otp');
                        session(['otp' => $token]);
                        $saveOtp->otp = $token;
                        $saveOtp->save();
                        if (!empty($adminData->phone_number)){
                        $phoneNumber = '+' . $adminData->country_code . $adminData->phone_number;
                            $message = "Your verification code for Admin is: " . $token;
                            $sendMesage =  sendSMS($phoneNumber, $message);
                            $sendMesage = [
                                "status" => true,
                                "message" => "success"
                            ];
                        }else{
                            $sendMesage = [
                                "status" => false,
                                "message" => "Please add mobile number",
                            ];
                        }
                       
            // }

                if ($sendMesage['status']  == true) {
                    return Redirect::to(URL::to('admin/factor_authenticate', encrypt($adminData->id)))->with('msg', 'Verification code is sent successfully');
                } else if ($sendMesage['status'] == false) {
                    return  Redirect::to('admin')->with('msg_error', $sendMesage['message']);
                }
            }
        }

        return  Redirect::to('admin')->with('msg_error', 'Invalid credentials!');
    }

    public function twoFactorAuthenticate($id)
    {
        $data['page'] = 'admin/auth/factor_authentication';

        $data['js'] = ['login'];
        $data['id'] = $id;

        return view('admin/auth/main', $data);
    }

    public function checkFactorAuthentication(Request $request)
    {

        $id = decrypt($request->adminId);

        $checkOtp = Admin::where(['id' => $id, 'otp' => $request->verification_otp])->first();

        // $role=$checkOtp->role;
        if ($checkOtp != null) {
            // dd($checkOtp);
            $sessionArray = ['id' => $checkOtp->id, 'name' => $checkOtp->name,'role'=>$checkOtp->role];
            Session::put(['admin' => $sessionArray]);
            if (Session::has('admin') && $checkOtp->role=="admin") {
                return Redirect::to(URL::to('/admin/dashboard'))->with('msg', 'Loggedin successfully!');;
            }else if(Session::has('admin') && ($checkOtp->role=="designer"||$checkOtp->role=="Designer")){
                return Redirect::to(URL::to('/admin/create_template'))->with('msg', 'Loggedin successfully!');;
            }else{
                return Redirect::to(URL::to('/admin/create_template'))->with('msg', 'Loggedin successfully!');;
            }

            // if (Session::has('admin') && ($checkOtp->role=="designer"||$checkOtp->role=="Designer")) {

            // }

            return Redirect::to(URL::to('/admin'))->with('msg_error', 'Invalid credentials!');;
        }
        return redirect()->to(URL::to('admin/factor_authenticate', $request->adminId))
        ->with('msg_error', 'Invalid verification code');
        // return Redirect::to(URL::to('admin/factor_authenticate', $request->adminId))->with('msg_error', 'Invalid verification code');
    }

    public function checkEmail(Request $req)
    {
        $adminData = Admin::where("email", $req->input('email'))->get();

        if (count($adminData) > 0) {
            echo "false";
            die;
        }
        echo "true";
        die;
    }

    public function registerAdmin(Request $req)
    {
        $req->validate(
            [
                'email' => 'required|email|unique:admins,email',
                'name' => 'required',
                'password' => 'required|same:confirm_password',
                'confirm_password' => 'required',
            ],
            [
                'name.required' => 'Please enter name!',
                'email.required' => 'Please enter email!',
                'email.unique' => 'This email is already exist!',
                'password.same' => 'Please enter password and confirm password same!',
                'password.required' => 'Please enter password!',
                'confirm_password.required' => 'Please enter confirm password!'
            ]
        );

        $admin = new Admin;
        $admin->name = $req->input('name');
        $admin->email = $req->input('email');
        $admin->password = Hash::make($req->input('password'));
        if ($admin->save()) {
            return  Redirect::to('admin')->with('msg', 'Admin Registered successfully!');
        }
        return  Redirect::to('admin')->with('msg_error', 'Error to registretion!');
    }
    public function checkAdminEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = Admin::where('email', $email)->exists();

        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
    }
    public function forgotpassword(Request $req)
    {
        $req->validate(
            [
                'email' => 'required|email|exists:admins,email',
            ],
            [
                'email.required' => 'Please enter email!',
                'email.exists' => 'This email is not registered!',
            ]
        );
        $token = Str::random(60);

        $adminData = Admin::where("email", $req->input('email'))->first();
        $adminData->remember_token = $token;
        $adminData->save();
        Mail::to($req->input('email'))->send(new forgotpasswordMail($token));
        return  Redirect::to('admin')->with('msg', 'Email send successfully!');
    }

    public function checkToken($token)
    {
        $adminData = Admin::where("remember_token", $token)->first();
        if ($adminData == null) {

            $userData = DB::table('password_resets')
                ->where([
                    'token' => $token
                ])
                ->first();
            if ($userData == null) {

                return  Redirect::to('admin')->with('msg_error', 'Invalid token!');
            }
        }
        $data['js'] = ['login'];
        $data['page'] = 'admin.auth.updatePassword';
        return view('admin.auth.main', $data);
    }
    public function updatePassword(Request $req, $token)
    {
        $req->validate(
            [

                'password' => 'required|same:confirm_password',
                'confirm_password' => 'required',
            ],
            [
                'password.same' => 'Please enter password and confirm password same!',
                'password.required' => 'Please enter password!',
                'confirm_password.required' => 'Please enter confirm password!'
            ]
        );
        $adminData = Admin::where("remember_token", $token)->first();
        if ($adminData == null) {

            $userData = DB::table('password_resets')
                ->where([
                    'token' => $token
                ])
                ->first();


            if ($userData == null) {

                return  Redirect::to('admin')->with('msg_error', 'Invalid token!');
            } else {

                $user = User::where('email', $userData->email)
                    ->update(['password' => Hash::make($req->input('password'))]);

                DB::table('password_resets')->where(['email' => $userData->email])->delete();
                return  Redirect::to('admin')->with('msg', 'Password Updated successfully!');
            }
        }
        $adminData->password = Hash::make($req->input('password'));
        $adminData->remember_token = null;
        $adminData->save();

        return  Redirect::to('admin')->with('msg', 'Password Updated successfully!');
    }


    public function AdminPasswordChange()
    {
        $title = 'Change Password';

        $page = 'admin.password.update_admin_password';
        $js = 'admin.password.changepasswordjs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }
  

    // public function changePassword(Request $request)
    // {

    //     $id=Session::get('admin');
    //     dd($id);
    //     // $validator = Validator::make($request->all(), [
    //     //     'current_password' => 'required|min:8',
    //     //     'new_password' => 'required|min:8',
    //     //     'conform_password' => 'required|min:8|same:new_password',
    //     // ]);

    //     // if ($validator->fails()) {
    //     //     return redirect()->route('profile.change_password')
    //     //         ->withErrors($validator)
    //     //         ->withInput();
    //     // }
    //     $id = decrypt(session()->get('user')['id']);
    //     $userUpdate = Admin::where('id', $id)->first();
    //     $userUpdate->password = Hash::make($request->new_password);
    //     $userUpdate->save();

    //     DB::commit();
    //     toastr()->success('Password Changed');
    //     return  redirect()->route('profile.edit');
    // }


}

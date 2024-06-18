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
use Cookie;
use Illuminate\Support\Facades\DB;
use App\Mail\forgotpasswordMail;
use App\Models\User;

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
            $sessionArray = ['id' => $adminData->id, 'name' => $adminData->name];
            Session::put(['admin' => $sessionArray]);
        }
        if (Session::has('admin')) {

            if ($req->input('remember') != null) {
                Cookie::queue('email', $req->input('email'), 120);
                Cookie::queue('password', $req->input('password'), 120);
            } else {

                Cookie::forget('email');
                Cookie::forget('password');
            }
            return Redirect::to(URL::to('/admin/dashboard'))->with('success', 'Loggedin successfully!');;
        } else {
            return  Redirect::to('admin')->with('error', 'Invalid credentials!');
        }
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
            return  Redirect::to('admin/login')->with('success', 'Admin Registered successfully!');
        }
        return  Redirect::to('admin/login')->with('error', 'Error to registretion!');
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
        return  Redirect::to('admin/login')->with('success', 'Email send successfully!');
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

                return  Redirect::to('admin/login')->with('error', 'Invalid token!');
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

                return  Redirect::to('admin/login')->with('error', 'Invalid token!');
            } else {

                $user = User::where('email', $userData->email)
                    ->update(['password' => Hash::make($req->input('password'))]);

                DB::table('password_resets')->where(['email' => $userData->email])->delete();
                return  Redirect::to('admin/login')->with('success', 'Password Updated successfully!');
            }
        }
        $adminData->password = Hash::make($req->input('password'));
        $adminData->remember_token = null;
        $adminData->save();

        return  Redirect::to('admin/login')->with('success', 'Password Updated successfully!');
    }
}

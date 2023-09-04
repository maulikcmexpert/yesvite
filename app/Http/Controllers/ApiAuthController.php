<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\forgotpasswordMail;
use App\Models\Company;
use App\Models\Device;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Redirect;
use PDO;

class ApiAuthController extends Controller
{
    public function signup(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }
        try {
            DB::beginTransaction();

            $randomString = Str::random(30);

            $image = $request->profile;
            $imageName = time() . '_' . $image->getClientOriginalName();
            Storage::disk('public')->putFileAs('profile', $image, $imageName);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'profile' => $imageName,
                'email' => $request->email,
                'account_type' => $request->account_type,
                'remember_token' =>  $randomString,
                'password' => Hash::make($request->password),
            ]);


            if (!empty($request->company_name)) {
                $companyDetail = new Company;
                $companyDetail->user_id = $user->id;
                $companyDetail->company_name = $request->company_name;
                $companyDetail->save();
            }
            DB::commit();
            Mail::send('emails.emailVerificationEmail', ['token' => $randomString], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });

            return response()->json(['message' => "Please verify your email"], 201);
        } catch (QueryException $e) {
            DB::rollBack();
        }
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            if ($user->email_verified_at != NULL) {

                // device  add//

                $this->userDevice($user->id, $request);

                // device  add//
                $token = Token::where('user_id', $user->id)->first();
                $token->revoke();
                $token = Auth::user()->createToken('API Token')->accessToken;

                $detail = [
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                ];

                return response()->json(['data' => $detail, 'token' => $token], 200);
            } else {
                $randomString = Str::random(30);
                $user->remember_token = $randomString;
                $user->save();
                Mail::send('emails.emailVerificationEmail', ['token' => $randomString], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Email Verification Mail');
                });
                return response()->json(['message' => 'Please check your email'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function userDevice($id, $requestData)
    {


        if (Device::where('user_id', $id)->exists()) {
            Device::where('user_id', $id)->delete();
        }

        $device = new Device;
        $device->user_id = $id;
        $device->device_id = $requestData['device_id'];
        $device->device_token = $requestData['device_token'];
        $device->model = $requestData['model'];
        $device->save();
    }

    public function passwordLink(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }
        $token = Str::random(60);


        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now()
        ]);


        Mail::to($request->input('email'))->send(new forgotpasswordMail($token));
        return response()->json(['message' => 'Email send successful'], 200);
    }


    public function resetPassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',
            'token' => 'required|numeric|digits:4',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 401,
            ]);
        }

        $token = Password_reset::where('email', $request->email)
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$token) {
            // Token not found or expired
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token record
        $token->delete();

        return response()->json(['message' => 'Password reset successful'], 200);
    }


    public function verifyAccount($token)
    {


        $verifyUser = User::where('remember_token', $token)->first();

        if (!is_null($verifyUser)) {


            if ($verifyUser->email_verified_at == NULL) {
                $verifyUser->email_verified_at = strtotime(date('Y-m-d  h:i:s'));
                $verifyUser->status = '1';
                $verifyUser->remember_token = NULL;
                $verifyUser->save();
                echo "<h1 style='color:green,text-align:center'>Your e-mail is verified. You can now login.</h1>";
                exit;
            } else {
                echo "<h1 style='color:green,text-align:center'>Your e-mail is already verified. You can now login.</h1>";
                exit;
            }
        } else {
            echo "<span>Invalid token!</span>";
            exit;
        }
    }
}

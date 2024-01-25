<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\forgotpasswordMail;
use App\Models\Device;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Redirect;
use PDO;

use App\Rules\AtLeastOnePresentRule;

// Rules //

use App\Rules\verify_otp;

class ApiAuthController extends Controller
{
    public function signup(Request $request)
    {

        $rawData = $request->getContent();


        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'account_type' => 'required|in:0,1',
            'company_name' => 'present'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }
        try {
            DB::beginTransaction();

            $randomString = Str::random(30);


            $usersignup =  User::create([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'account_type' => $input['account_type'],
                'company_name' => ($input['account_type'] == '1') ? $input['company_name'] : "",
                'password' => Hash::make($input['password']),
                'remember_token' =>  $randomString,

            ]);

            DB::commit();

            $userDetails = User::where('id', $usersignup->id)->first();

            $userData = [
                'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                'email' => $userDetails->email,
                'token' => $randomString
            ];
            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                $message->to($input['email']);
                $message->subject('Email Verification Mail');
            });

            return response()->json(['status' => 1, 'message' => "Your Registration is sucessfully , Please verify your email"]);
        } catch (QueryException $e) {

            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }

    public function login(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'device_id' => 'required',
            'device_token' => 'required',
            'model' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {

            $user = Auth::user();

            if ($user->email_verified_at != NULL) {

                // device  add//
                if ($user->status == '9') {
                    return response()->json(['status' => 0, 'message' => 'Account deleted']);
                }

                $this->userDevice($user->id, $input);

                // device  add//
                $token = Token::where('user_id', $user->id)->first();

                if ($token) {
                    $token->delete();
                }
                $token = Auth::user()->createToken('API Token')->accessToken;

                $detail = [
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                ];

                return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
            } else {

                $users = User::where('id', $user->id)->first();
                $randomString = Str::random(30);
                $users->remember_token = $randomString;
                $users->save();

                $userDetails = User::where('id', $user->id)->first();

                $userData = [
                    'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                    'email' => $userDetails->email,
                    'token' => $randomString
                ];
                Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                    $message->to($input['email']);
                    $message->subject('Email Verification Mail');
                });

                return response()->json(['status' => 0, 'message' => 'Please check and verify your email address.']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Email or password invalid']);
        }
    }


    public function socialLogin(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }




        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'device_id' => 'required',
            'device_token' => 'required',

            'model' => 'required',
            'facebook_token_id' => 'required_without_all:gmail_token_id,apple_token_id,instagram_token_id',
            'gmail_token_id' => 'required_without_all:facebook_token_id,apple_token_id,instagram_token_id',
            'apple_token_id' => 'required_without_all:facebook_token_id,gmail_token_id,instagram_token_id',
            'instagram_token_id' => 'required_without_all:facebook_token_id,gmail_token_id,apple_token_id',
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }
        $isExistUser = User::where("email", $input['email'])->first();

        if ($isExistUser != null) {


            if (isset($input['social_type']) && $input['social_type'] === 'facebook') {

                $isExistUser->facebook_token_id = $input['facebook_token_id'];
                $isExistUser->save();
            } else if (isset($input['social_type']) && $input['social_type'] === 'gmail') {
                $isExistUser->gmail_token_id = $input['gmail_token_id'];
                $isExistUser->save();
            } else if (isset($input['social_type']) && $input['social_type'] === 'apple') {

                $isExistUser->apple_token_id = $input['apple_token_id'];
                $isExistUser->save();
            } else if (isset($input['social_type']) && $input['social_type'] === 'instagram') {
                $isExistUser->instagram_token_id = $input['instagram_token_id'];
                $isExistUser->save();
            }
            $userId = $isExistUser->id;

            $this->userDevice($userId, $input);

            // device  add//
            $token = Token::where('user_id', $userId)->first();

            if ($token) {
                $token->delete();
            }
            $userInfo = User::where("id", $userId)->first();
            $token = $userInfo->createToken('API Token')->accessToken;
            $detail = [
                'firstname' => $userInfo->firstname,
                'lastname' => $userInfo->lastname,
                'email' => $userInfo->email,
                'account_type' => $userInfo->account_type,
            ];

            return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
        } else {
            DB::beginTransaction();
            $usersignup = new User;
            $usersignup->firstname = $input['firstname'];
            $usersignup->lastname = $input['lastname'];
            $usersignup->email = $input['email'];

            if (isset($input['social_type']) && $input['social_type'] === 'facebook') {
                $usersignup->facebook_token_id = $input['facebook_token_id'];
            } else if (isset($input['social_type']) && $input['social_type'] === 'gmail') {
                $usersignup->gmail_token_id = $input['gmail_token_id'];
            } else if (isset($input['social_type']) && $input['social_type'] === 'apple') {

                $usersignup->apple_token_id = $input['apple_token_id'];
            } else if (isset($input['social_type']) && $input['social_type'] === 'instagram') {
                $usersignup->instagram_token_id = $input['instagram_token_id'];
            }
            $usersignup->save();

            $userId = $usersignup->id;

            $this->userDevice($userId, $input);

            // device  add//
            $token = Token::where('user_id', $userId)->first();

            if ($token) {
                $token->delete();
            }
            $userInfo = User::where("id", $userId)->first();
            $token = $userInfo->createToken('API Token')->accessToken;
            $detail = [
                'firstname' => $userInfo->firstname,
                'lastname' => $userInfo->lastname,
                'email' => $userInfo->email,
                'account_type' => $userInfo->account_type,
            ];
            DB::commit();
            return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
        }

        // if (isset($input['account_type'])) {

        //     DB::beginTransaction();
        //     $usersignup = new User;
        //     $usersignup->firstname = $input['firstname'];
        //     $usersignup->lastname = $input['lastname'];
        //     $usersignup->email = $input['email'];
        //     $usersignup->account_type = $input['account_type'];

        //     if (isset($input['social_type']) && $input['social_type'] === 'facebook') {
        //         $usersignup->facebook_token_id = $input['facebook_token_id'];
        //     } else if (isset($input['social_type']) && $input['social_type'] === 'gmail') {
        //         $usersignup->gmail_token_id = $input['gmail_token_id'];
        //     } else if (isset($input['social_type']) && $input['social_type'] === 'apple') {

        //         $usersignup->apple_token_id = $input['apple_token_id'];
        //     } else if (isset($input['social_type']) && $input['social_type'] === 'instagram') {
        //         $usersignup->instagram_token_id = $input['instagram_token_id'];
        //     }
        //     $usersignup->save();

        //     $userId = $usersignup->id;


        //     $this->userDevice($userId, $input);

        //     // device  add//
        //     $token = Token::where('user_id', $userId)->first();

        //     if ($token) {
        //         $token->delete();
        //     }
        //     $userInfo = User::where("id", $userId)->first();
        //     $token = $userInfo->createToken('API Token')->accessToken;
        //     $detail = [
        //         'firstname' => $userInfo->firstname,
        //         'lastname' => $userInfo->lastname,
        //         'email' => $userInfo->email,
        //         'account_type' => $userInfo->account_type,
        //     ];
        //     DB::commit();
        //     return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
        // }
        // return response()->json(['status' => 0, 'message' => "registering process"]);
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

        $rawData = $request->getContent();


        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],

            );
        }
        $token = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        //if exist then delete //
        Password_reset::where('email', $input['email'])->delete();
        //if exist then delete //

        DB::table('password_resets')->insert([
            'email' => $input['email'],
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now()
        ]);

        $userDetails = User::where('email', $input['email'])->first();


        $digit1 = substr($token, 0, 1); // Extract first digit
        $digit2 = substr($token, 1, 1); // Extract second digit
        $digit3 = substr($token, 2, 1); // Extract third digit
        $digit4 = substr($token, 3, 1); // Extract fourth digit


        $userData = [
            'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
            'email' => $userDetails->email,
            'digit1' => $digit1,
            'digit2' => $digit2,
            'digit3' => $digit3,
            'digit4' => $digit4
        ];
        Mail::to($input['email'])->send(new forgotpasswordMail(array($userData)));
        return response()->json(['status' => 1, 'message' => 'Email send successful,Please check your email']);
    }

    public function verifyOtp(Request $request)
    {
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $validator = Validator::make($input, [
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:4', new verify_otp],
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }

        //    Password_reset::where('email', $input['email'])->delete();

        return response()->json(['status' => 1,  'message' => "OTP is verifed"]);
    }


    public function resetPassword(Request $request)
    {

        $rawData = $request->getContent();

        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $validator = Validator::make($input, [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ],
            );
        }


        $user = User::where('email', $input['email'])->first();
        $user->password = Hash::make($input['password']);
        if ($user->save()) {
            Password_reset::where('email', $input['email'])->delete();
            return response()->json(['status' => 1, 'message' => 'Password reset successful']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Password not updated please try again']);
        }

        // Delete the token record
    }

    public function verifyAccount($token)
    {


        $verifyUser = User::where('remember_token', $token)->first();

        if (!is_null($verifyUser)) {
            $faild = "";

            if ($verifyUser->email_verified_at == NULL) {
                $verifyUser->email_verified_at = strtotime(date('Y-m-d  h:i:s'));
                $verifyUser->status = '1';
                $verifyUser->remember_token = NULL;
                $verifyUser->save();
                $message = "Your Email was verified. You can continue using the application.";
                return view('emailVarification', compact('message', 'faild'));
            } else {
                $message = "Your Email was already verified. You can continue using the application.";
                return view('emailVarification', compact('message', 'faild'));
            }
        } else {
            $message = "This is Your Invalid Token.";
            $faild = "faild";
            return view('emailVarification', compact('message', 'faild'));
        }
    }
}

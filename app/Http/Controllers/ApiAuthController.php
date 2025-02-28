<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Password_reset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\forgotpasswordMail;
use App\Models\Coin_transactions;
use App\Models\Device;
use App\Models\LoginHistory;
use App\Models\contact_sync;
use App\Models\EventInvitedUser;
use App\Models\EventPost;


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
use Illuminate\Support\Facades\Session;

class ApiAuthController extends Controller
{
    public function signup(Request $request)
    {
        $rawData = $request->getContent();
        $input = json_decode($rawData, true);
        if ($input == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        $existUser = User::where('email', $input['email'])
            ->where('app_user', '0')
            ->first();

        if ($existUser != null) {
            $validator1 = Validator::make($input, [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'password' => 'required|min:8',
                'account_type' => 'required|in:0,1',
                'company_name' => 'present'
            ]);

            if ($validator1->fails()) {
                $errors = $validator1->errors();
                $errorKeys = $errors->keys();
                $firstErrorKey = $errorKeys[0];
                $status = 0;
                if ($firstErrorKey == 'email') {
                    $status = 2;
                }
                return response()->json(
                    [
                        'status' => $status,
                        'message' => $validator1->errors()->first()
                    ],
                );
            }

            try {
                DB::beginTransaction();

                $randomString = Str::random(30);

                $usersignup =  User::where('id', $existUser->id)->update([
                    'firstname' => $input['firstname'],
                    'lastname' => $input['lastname'],
                    'email' => strtolower($input['email']),
                    'account_type' => $input['account_type'],
                    'company_name' => ($input['account_type'] == '1') ? $input['company_name'] : "",
                    'password' => Hash::make($input['password']),
                    'password_updated_date' => date('Y-m-d'),
                    'remember_token' =>  $randomString,
                    'register_type' => 'API Normal register',
                    'app_user' => '1',
                    'coins' => config('app.default_coin', 30)
                ]);

                DB::commit();

                $userDetails = User::where('id', $existUser->id)->first();

                $userData = [
                    // 'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                    'username' => $userDetails->firstname,
                    'email' => $userDetails->email,
                    'token' => $randomString
                ];

                $coin_transaction = new Coin_transactions();
                $coin_transaction->user_id = $existUser->id;
                $coin_transaction->status = '0';
                $coin_transaction->type = 'credit';
                $coin_transaction->coins = config('app.default_coin', 30);
                $coin_transaction->current_balance = config('app.default_coin', 30);
                $coin_transaction->description = 'Signup Bonus';
                $coin_transaction->endDate = Carbon::now()->addYear()->toDateString();
                $coin_transaction->save();

                Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                    $message->to($input['email']);
                    $message->subject('Verify your Yesvite email address');
                });

                return response()->json(['status' => 1, 'message' => "Account successfully created, please verify your email before you can log in"]);
            } catch (QueryException $e) {

                DB::rollBack();
                Log::error('Error to register user ' . $e);
                print_r($e);
                return response()->json(['status' => 0, 'message' => "Something went wrong"]);
            }
        }


        $validator = Validator::make($input, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'account_type' => 'required|in:0,1',
            'company_name' => 'present'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorKeys = $errors->keys();
            $firstErrorKey = $errorKeys[0];
            $status = 0;
            if ($firstErrorKey == 'email') {
                $status = 2;
            }
            // dd($status);
            return response()->json(
                [
                    'status' => $status,
                    'message' => $validator->errors()->first()
                ],
            );
        }
        // dd($validator);
        try {
            DB::beginTransaction();

            $randomString = Str::random(30);

            $checkUser = User::where("email", $input['email'])->first();
            $usersignup =  $checkUser;
            if ($checkUser != null) {
                $checkUser->firstname = $input['firstname'];
                $checkUser->lastname = $input['lastname'];
                $checkUser->email = strtolower($input['email']);
                $checkUser->account_type = $input['account_type'];
                $checkUser->company_name = ($input['account_type'] == '1') ? $input['company_name'] : "";
                $checkUser->password = Hash::make($input['password']);
                $checkUser->password_updated_date = date('Y-m-d');
                $checkUser->remember_token =  $randomString;
                $checkUser->register_type =  'API Normal register';
                $checkUser->coins = config('app.default_coin', 30);
                $checkUser->save();
            } else {
                $checkUser = new User();
                $checkUser->firstname = $input['firstname'];
                $checkUser->lastname = $input['lastname'];
                $checkUser->email = strtolower($input['email']);
                $checkUser->account_type = $input['account_type'];
                $checkUser->company_name = ($input['account_type'] == '1') ? $input['company_name'] : "";
                $checkUser->password = Hash::make($input['password']);
                $checkUser->password_updated_date = date('Y-m-d');
                $checkUser->remember_token =  $randomString;
                $checkUser->register_type =  'API Normal register';
                $checkUser->coins = config('app.default_coin', 30);
                $checkUser->save();

                // $usersignup =  User::create([
                //     'firstname' => $input['firstname'],
                //     'lastname' => $input['lastname'],
                //     'email' => $input['email'],
                //     'account_type' => $input['account_type'],
                //     'company_name' => ($input['account_type'] == '1') ? $input['company_name'] : "",
                //     'password' => Hash::make($input['password']),
                //     'password_updated_date' => date('Y-m-d'),
                //     'remember_token' =>  $randomString,
                //     'register_type' => 'API Normal register',
                // ]);
            }
            // event(new \App\Events\UserRegistered($usersignup));

            DB::commit();

            $userDetails = User::where('id', $checkUser->id)->first();

            $userData = [
                'username' => $userDetails->firstname,
                'email' => $userDetails->email,
                'token' => $randomString
            ];

            $coin_transaction = new Coin_transactions();
            $coin_transaction->user_id = $checkUser->id;
            $coin_transaction->status = '0';
            $coin_transaction->type = 'credit';
            $coin_transaction->coins = config('app.default_coin', 30);
            $coin_transaction->current_balance = config('app.default_coin', 30);
            $coin_transaction->description = 'Signup Bonus';
            $coin_transaction->endDate = Carbon::now()->addYear()->toDateString();
            $coin_transaction->save();

            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                $message->to(strtolower($input['email']));
                $message->subject('Verify your Yesvite email address');
            });

            return response()->json(['status' => 1, 'message' => "Account successfully created, please verify your email before you can log in"]);
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Error to register user ' . $e);
            print_r($e);
            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }


    function getUserAccountType($userId)
    {
        return User::select('account_type')->where('id', $userId)->first();
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
            'password' => 'required|min:6',
            'device_id' => 'required',
            // 'device_token' => 'required',
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
        $getUser = User::where('email', $input['email'])->first();
        if ($getUser) {
            if ($getUser->password == NULL) {
                return response()->json(['status' => 0, 'message' => 'Invalid login method']);
            }
        }
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {

            $user = Auth::user();

            //for making app_user 1 
            $users = User::where('id', $user->id)->first();
            $users->app_user = "1";
            $users->save();
            $userIpAddress = request()->ip();

            if ($user->email_verified_at != NULL) {

                $loginHistory = LoginHistory::where('user_id', $user->id)->first();


                if ($loginHistory) {
                    $new_count = $loginHistory->login_count + 1;
                    $loginHistory->ip_address = $userIpAddress;
                    $loginHistory->login_at = now();
                    $loginHistory->login_count = $new_count;
                    $loginHistory->save();
                } else {
                    $loginHistory = new LoginHistory();
                    $loginHistory->user_id = $user->id;
                    $loginHistory->ip_address = $userIpAddress;
                    $loginHistory->login_at = now();
                    $loginHistory->login_count = 1;
                    $loginHistory->save();
                }

                $alreadyLog = null;
                if (isset($input['add_account']) && $input['add_account'] == '1') {

                    $checkType = $this->getUserAccountType($input['user_id']);
                    if ($checkType->account_type == $user->account_type) {
                        $msg = "";
                        if ($checkType->account_type == '0') {
                            $msg = "personal";
                        } else if ($checkType->account_type == '1') {
                            $msg = "proffiesional";
                        }
                        return response()->json(['status' => 0, 'message' => 'You have already login ' . $msg]);
                    }

                    $alreadyLog = User::select('id', 'firstname as first_name', 'lastname as last_name', 'email', 'profile')->where('id', $input['user_id'])->first();

                    $alreadyLog['profile'] = ($alreadyLog->profile != null) ? asset('public/storage/profile/' . $alreadyLog->profile) : "";
                }
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
                    'user_id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                    'is_first_login' => $user->is_first_login,
                    'is_already_log' => $alreadyLog
                ];


                event(new \App\Events\UserRegistered($user));
                logoutFromWeb($user->id);
                return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
            } else {

                $users = User::where('id', $user->id)->first();
                $randomString = Str::random(30);
                $users->remember_token = $randomString;
                $users->save();

                $userDetails = User::where('id', $user->id)->first();

                $userData = [
                    'username' => $userDetails->firstname,
                    'email' => $userDetails->email,
                    'token' => $randomString,
                    'is_first_login' => $userDetails->is_first_login
                ];
                Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($input) {
                    $message->to($input['email']);
                    $message->subject('Verify your Yesvite email address');
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
            // 'firstname' => 'required',
            // 'email' => 'required|email',
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
        if (isset($input['social_type']) && $input['social_type'] === 'apple') {
            $isExistUser = User::where("apple_token_id", $input['apple_token_id'])->first();
        } else {
            $isExistUser = User::where("email", $input['email'])->first();
        }

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
                'user_id' => $userId,
                'firstname' => $userInfo->firstname,
                'lastname' => $userInfo->lastname,
                'email' => $userInfo->email,
                'account_type' => $userInfo->account_type,
                'is_first_login' => $userInfo->is_first_login
            ];
            // logoutFromWeb($userId);
            return response()->json(['status' => 1, 'data' => $detail, 'token' => $token]);
        } else {

            DB::beginTransaction();
            $usersignup = new User;
            if (isset($input['model']) && $input['model'] === 'Ios') {
                $firstname = $input['firstname'];
                $lastname = $input['lastname'];
            } else {
                $name = $input['firstname'];
                $nameParts = explode(' ', $name);

                $firstname = $nameParts[0];
                $lastname = $nameParts[1];
            }


            // $usersignup->firstname = $input['firstname'];
            // $usersignup->lastname = $input['lastname'];

            $usersignup->firstname = $firstname;
            $usersignup->lastname = $lastname;
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
            $usersignup->email_verified_at = strtotime(date('Y-m-d  h:i:s'));
            $usersignup->register_type = 'API Social signup';
            $usersignup->coins = config('app.default_coin', 30);
            $usersignup->save();

            $userId = $usersignup->id;

            $this->userDevice($userId, $input);

            // device  add//
            $token = Token::where('user_id', $userId)->first();

            if ($token) {
                $token->delete();
            }
            $userInfo = User::where("id", $userId)->first();

            $coin_transaction = new Coin_transactions();
            $coin_transaction->user_id = $userId;
            $coin_transaction->status = '0';
            $coin_transaction->type = 'credit';
            $coin_transaction->coins = config('app.default_coin', 30);
            $coin_transaction->current_balance = config('app.default_coin', 30);
            $coin_transaction->description = 'Signup Bonus';
            $coin_transaction->endDate = Carbon::now()->addYear()->toDateString();
            $coin_transaction->save();

            $token = $userInfo->createToken('API Token')->accessToken;
            $detail = [
                'user_id' => $userId,
                'firstname' => $userInfo->firstname,
                'lastname' => $userInfo->lastname,
                'email' => $userInfo->email,
                'account_type' => $userInfo->account_type,
                'is_first_login' => $userInfo->is_first_login
            ];
            DB::commit();
            // logoutFromWeb($userId);
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
        $users = User::where('email', $input['email'])->first();
        if ($users->password == null && $users->facebook_token_id == null && $users->instagram_token_id == null && $users->gmail_token_id == null && $users->apple_token_id == null) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => "User Doesn't Exist !",
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
            'password' => 'required|min:6',
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
        $user->password_updated_date = date('Y-m-d');
        if ($user->save()) {
            Password_reset::where('email', $input['email'])->delete();
            return response()->json(['status' => 1, 'message' => 'Password reset successfully']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Password not updated please try again']);
        }

        // Delete the token record
    }

    public function verifyAccount($token)
    {


        $verifyUser = User::where('remember_token', $token)->first();
        $faild = "";
        if (!is_null($verifyUser)) {

            $tokenCreationTime = strtotime($verifyUser->updated_at);
            $currentTime = time(); // Current timestamp

            if (($currentTime - $tokenCreationTime) > 10800 && $verifyUser->resend_verification_mail == "0") {
                $message = "The token has expired. Please request a new verification link.";
                $faild = "faild";
                $user_id = $verifyUser->id;
                return view('emailVarification', compact('message', 'faild', 'user_id'));
            }

            if ($verifyUser->email_verified_at == NULL) {
                $faild = "verified";
                $verifyUser->email_verified_at = strtotime(date('Y-m-d  h:i:s'));
                $verifyUser->status = '1';
                $verifyUser->remember_token = NULL;
                $verifyUser->save();
                $checkContactSync=contact_sync::where('email',$verifyUser->email)->first();
                if($checkContactSync){
                    $checkContactSync->userId=$verifyUser->id;
                    $checkContactSync->updated_at = now();
                    $checkContactSync->save();
    
                    EventInvitedUser::where('sync_id', $checkContactSync->id)
                    ->update(['user_id' => $verifyUser->id]);
    
                    EventPost::where('sync_id',$checkContactSync->id)
                    ->update(['user_id'=>$verifyUser->id]);
                
                }
                $message = "Email Verification Successful\nYou may now log into your Yesvite account here";
                return view('emailVarification', compact('message', 'faild'));
            } else {
                $message = "Verification Already Succesful Your email has been verified. you may now log into your account.";
                return view('emailVarification', compact('message', 'faild'));
            }
        } else {
            $message = "You have already verified your account.";
            $faild = "";
            return view('emailVarification', data: compact('message'));
        }
    }
}

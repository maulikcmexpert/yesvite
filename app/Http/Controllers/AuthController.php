<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Cookie;
use App\Models\User;
use App\Rules\EmailExists;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $page = 'auth/login';
        $title = "Login";
        $js = ['login'];

        return view('layout', compact('page', 'title', 'js'));
    }

    public function register()
    {
        $page = 'auth/register';
        $title = "Register";
        $js = ['register'];

        return view('layout', compact('page', 'title', 'js'));
    }

    public function userRegister(Request $request)
    {


        if ($request->account_type == '1') {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => ['required', 'email', new EmailExists], // Use the custom validation rule
                'zip_code' => 'required|string|max:10',
                'businesspassword' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                'businesscpassword' => 'required|same:businesspassword',
            ], [
                'firstname.required' => 'Please enter your first name',
                'lastname.required' => 'Please enter your last name',
                'email.required' => 'Please enter your email',
                'email.email' => 'Please enter a valid email address',
                'zip_code.required' => 'Please enter your zip code',
                'businesspassword.required' => 'Please enter your password',
                'businesspassword.regex' => 'Your password must be at least 8 characters long and contain both letters and numbers',
                'businesscpassword.required' => 'Please confirm your password',
                'businesscpassword.same' => 'Passwords do not match',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => ['required', 'email', new EmailExists], // Use the custom validation rule
                'zip_code' => 'required|string|max:10',
                'password' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                'cpassword' => 'required|same:password',

            ], [
                'firstname.required' => 'Please enter your first name',
                'lastname.required' => 'Please enter your last name',
                'email.required' => 'Please enter your email',
                'email.email' => 'Please enter a valid email address',
                'zip_code.required' => 'Please enter your zip code',
                'password.required' => 'Please enter your password',
                'password.regex' => 'Your password must be at least 8 characters long and contain both letters and numbers',
                'cpassword.required' => 'Please confirm your password',
                'cpassword.same' => 'Passwords do not match',

            ]);
        }


        if ($validator->fails()) {
            toastr()->success($validator->errors()->first());
            return false;
        }

        try {
            $randomString = Str::random(30);

            DB::beginTransaction();
            $storeUser = new User();

            $storeUser->account_type =  $request->account_type;
            if ($request->account_type == '1') {

                $storeUser->company_name = $request->company_name;
            }

            $storeUser->firstname =  $request->firstname;
            $storeUser->lastname =  $request->lastname;
            $storeUser->email =  $request->email;
            $storeUser->zip_code =  $request->zip_code;
            $storeUser->password = ($request->account_type != '1') ?  Hash::make($request->password) : Hash::make($request->businesspassword);


            $storeUser->password_updated_date =  date('Y-m-d');
            $storeUser->remember_token =   $randomString;
            $storeUser->save();
            DB::commit();
            $userDetails = User::where('id', $storeUser->id)->first();

            $userData = [
                'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                'email' => $userDetails->email,
                'token' => $randomString
            ];
            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });
            flash('Account successfully created, please verify your email before you can log in');
            toastr()->success('Account successfully created, please verify your email before you can log in');
            return  Redirect::to('login');
        } catch (QueryException $e) {
            DB::Rollback();
            toastr()->error('Register not successfull');
            return  Redirect::to('register');
        } catch (Exception  $e) {
            toastr()->error('something went wrong');
            return  Redirect::to('register');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter a valid Email',
            'password.required' => 'Please enter a Password',
            'password.min' => 'Password must be at least 8 characters',
        ]);


        $remember = $request->has('remember'); // Check if "Remember Me" checkbox is checked

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();
            // if ($user->email_verified_at != NULL) {


            $sessionArray = [
                'id' => encrypt($user->id),
                'username' => $user->firstname . ' ' . $user->lastname,
                'email' => $user->email,

                'profile' => ($user->profile != NULL || $user->profile != "") ? asset('public/storage/profile/' . $user->profile) : asset('public/storage/profile/no_profile.png')
            ];
            Session::put(['user' => $sessionArray]);

            if (Session::has('user')) {

                if ($remember) {
                    Cookie::queue('email', $user->email, 120);
                    Cookie::queue('password', $request->password, 120);
                } else {

                    Cookie::forget('email');
                    Cookie::forget('password');
                }
                event(new \App\Events\UserRegistered($user));

                toastr()->success('Logged in successfully!');
                return redirect()->route('home');
            } else {
                toastr()->error('Invalid credentials!');
                return  Redirect::to('login');
            }
            // } 
            // else {
            //     $randomString = Str::random(30);
            //     $user->remember_token = $randomString;
            //     $user->save();

            //     $userData = [
            //         'username' => $user->firstname . ' ' . $user->lastname,
            //         'email' => $user->email,
            //         'token' => $randomString,
            //         'is_first_login' => $user->is_first_login
            //     ];
            //     Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($user) {
            //         $message->to($user->email);
            //         $message->subject('Email Verification Mail');
            //     });
            //     toastr()->success('Please check and verify your email address.');
            //     return  Redirect::to('login');
            // }
        }
        toastr()->error('Email or Passqword invalid');
        return  Redirect::to('login');
    }


    function getUserAccountType($userId)
    {
        return User::select('account_type')->where('id', $userId)->first();
    }
    public function checkAddAccount(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter a valid Email',
            'password.required' => 'Please enter a Password',
            'password.min' => 'Password must be at least 8 characters',
        ]);


        $remember = $request->has('remember'); // Check if "Remember Me" checkbox is checked

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();
            // if ($user->email_verified_at != NULL) {


            $checkType = $this->getUserAccountType(decrypt(Session::get('user')['id']));

            if ($checkType->account_type == $user->account_type) {
                $loginUser = User::where('id', decrypt(Session::get('user')['id']))->first();


                if ($loginUser != null) {

                    Auth::login($loginUser);
                }
                $msg = "";
                if ($checkType->account_type == '0') {
                    $msg = "personal";
                } else if ($checkType->account_type == '1') {
                    $msg = "proffiesional";
                }
                toastr()->error('You have already login ' . $msg);
                return  Redirect::to('profile');
            }

            $alreadyLog = User::select('id', 'firstname', 'lastname', 'email', 'profile')->where('id', decrypt(Session::get('user')['id']))->first();
            if ($alreadyLog != null) {

                $alreadyLog['profile'] = ($alreadyLog->profile != null) ? asset('storage/profile/' . $alreadyLog->profile) : asset('public/storage/profile/no_profile.png');

                $sessionAlreadyArray = [
                    'id' => encrypt($alreadyLog->id),
                    'secondary_username' => $alreadyLog->firstname . ' ' . $alreadyLog->lastname,
                    'secondary_email' => $alreadyLog->email,
                    'secondary_profile' => $alreadyLog->profile,

                ];
                Session::put(['secondary_user' => $sessionAlreadyArray]);
                Session::forget('user');
            }

            $sessionArray = [
                'id' => encrypt($user->id),
                'username' => $user->firstname . ' ' . $user->lastname,
                'email' => $user->email,
                'profile' => ($user->profile != NULL || $user->profile != "") ? asset('storage/profile/' . $user->profile) : asset('public/storage/profile/no_profile.png')
            ];
            Session::put(['user' => $sessionArray]);
            if (Session::has('user')) {

                if ($remember) {
                    Cookie::queue('email', $user->email, 120);
                    Cookie::queue('password', $request->password, 120);
                } else {

                    Cookie::forget('email');
                    Cookie::forget('password');
                }
                event(new \App\Events\UserRegistered($user));
                toastr()->success('Logged in successfully!');
                return redirect()->route('home');
            } else {
                toastr()->error('Invalid credentials!');
                return  Redirect::to('login');
            }
            // } 
            // else {
            //     $randomString = Str::random(30);
            //     $user->remember_token = $randomString;
            //     $user->save();

            //     $userData = [
            //         'username' => $user->firstname . ' ' . $user->lastname,
            //         'email' => $user->email,
            //         'token' => $randomString,
            //         'is_first_login' => $user->is_first_login
            //     ];
            //     Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($user) {
            //         $message->to($user->email);
            //         $message->subject('Email Verification Mail');
            //     });
            //     toastr()->success('Please check and verify your email address.');
            //     return  Redirect::to('login');
            // }
        }
        toastr()->error('Email or Passqword invalid');
        return  Redirect::to('home');
    }


    public function addAccount()
    {

        $page = 'auth/add_account';
        $title = "Login";
        // $js = ['login'];

        return view('layout', compact('page', 'title'));
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

    public function checkEmailExistence(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
    }
}

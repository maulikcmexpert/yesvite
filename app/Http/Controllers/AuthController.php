<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
// use Cookie;
use App\Models\User;
use App\Models\LoginHistory;

use App\Rules\EmailExists;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Flasher\Prime\FlasherInterface;
use Google_Client;
use App\Mail\forgotpasswordMail;
use App\Models\Coin_transactions;
use Laravel\Passport\Token;
// use GuzzleHttp\Client;
use Google\Client;
use Google\Service\AndroidPublisher;
use Laravel\Socialite\Facades\Socialite;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $firebase;
    protected $usersReference;

    public function __construct()
    {
        $this->firebase = Firebase::database();
        $this->usersReference = $this->firebase->getReference('users');
        // $this->database = $database;
        // $this->chatRoom = $this->database->getReference();
    }
    public function index() {}

    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-play-service-account.json'));
        $client->addScope('https://www.googleapis.com/auth/androidpublisher');
        $client->setRedirectUri(route('google/callback'));

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {

        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-play-service-account.json'));
        $client->setRedirectUri('https://yesvite.cmexpertiseinfotech.in/google/callback');

        $client->authenticate($request->input('code'));
        $accessToken = $client->getAccessToken();
        dd($accessToken);

        // Save the refresh token
        $refreshToken = $accessToken['refresh_token'];
        dd($refreshToken);
        // Save this refresh token securely, e.g., in the database
        // User::update(['google_refresh_token' => $refreshToken]);

        return 'Refresh token saved!';
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
                'businesspassword' => 'required|string|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                'businesscpassword' => 'required|same:businesspassword',
            ], [
                'firstname.required' => 'Please enter your first name',
                'lastname.required' => 'Please enter your last name',
                'email.required' => 'Please enter your email',
                'email.email' => 'Please enter a valid email address',
                'zip_code.required' => 'Please enter your zip code',
                'businesspassword.required' => 'Please enter your password',
                'businesspassword.regex' => 'Your password must be at least 6 characters long and contain both letters and numbers',
                'businesscpassword.required' => 'Please confirm your password',
                'businesscpassword.same' => 'Passwords do not match',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => ['required', 'email', new EmailExists], // Use the custom validation rule
                'zip_code' => 'required|string|max:10',
                // 'password' => 'required|string|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                'password' => 'required|string|min:6',
                'cpassword' => 'required|same:password',

            ], [
                'firstname.required' => 'Please enter your first name',
                'lastname.required' => 'Please enter your last name',
                'email.required' => 'Please enter your email',
                'email.email' => 'Please enter a valid email address',
                'zip_code.required' => 'Please enter your zip code',
                'password.required' => 'Please enter your password',
                // 'password.regex' => 'Your password must be at least 6 characters long and contain both letters and numbers',
                'cpassword.required' => 'Please confirm your password',
                'cpassword.same' => 'Passwords do not match',

            ]);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            toastr('reCAPTCHA verification failed. Please try again.', 'error');
            return redirect()->back()->withErrors(['captcha' => 'reCAPTCHA verification failed. Please try again.']);
        }

        if ($validator->fails()) {
            toastr($validator->errors()->first(), 'error');
            return redirect()->back()->withErrors(['captcha' => $validator->errors()->first()]);
            // Redirect::to('register')->with('error', $validator->errors()->first());
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
            $storeUser->register_type =   'web normal register';
            $storeUser->coins =  config('app.default_coin', 30);
            $storeUser->save();
            DB::commit();
            $userDetails = User::where('id', $storeUser->id)->first();

            $coin_transaction = new Coin_transactions();
            $coin_transaction->user_id = $storeUser->id;
            $coin_transaction->status = '0';
            $coin_transaction->type = 'credit';
            $coin_transaction->coins = config('app.default_coin', 30);
            $coin_transaction->current_balance = config('app.default_coin', 30);
            $coin_transaction->description = 'Signup Bonus';
            $coin_transaction->endDate = Carbon::now()->addYear()->toDateString();
            $coin_transaction->save();

            $userData = [
                // 'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
                'username' => $userDetails->firstname,
                'email' => $userDetails->email,
                'token' => $randomString
            ];
            $this->addInFirebase($storeUser->id);
            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Verify your Yesvite email address');
            });


            return  Redirect::to('login')->with('msg', 'Account successfully created, please verify your email before you can log in');
        } catch (QueryException $e) {
            DB::Rollback();

            return  Redirect::to('register')->with('msg_error', 'Register not successfull');
        } catch (Exception  $e) {

            return  Redirect::to('register')->with('msg_error', 'something went wrong');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter a valid Email',
            'password.required' => 'Please enter a Password',
            'password.min' => 'Password must be at least 6 characters',
        ]);


        $remember = $request->has('remember'); // Check if "Remember Me" checkbox is checked
        $userData = User::where('email', $request->email)->first();
        if ($userData != NULL) {
            if ($userData->account_status != 'Unblock') {
                return redirect()->back()->withErrors([
                    'email' => 'Ban User: Temporarily or permanently suspend user.',
                ])->withInput();
            }
            if (Auth::attempt($credentials, $remember)) {
                $userIpAddress = request()->ip();

                $user = Auth::guard('web')->user();
                if ($user->email_verified_at != NULL) {

                    Session::regenerate();
                    $user->current_session_id = Session::getId();
                    $user->save();

                    $sessionArray = [
                        'id' => encrypt($user->id),
                        'first_name' => $user->firstname,
                        'last_name' => $user->lastname,
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
                            Cookie::queue(Cookie::forget('email'));
                            Cookie::queue(Cookie::forget('password'));

                            Cookie::forget('email');
                            Cookie::forget('password');
                        }

                        // $this->logoutFromApplication($user->id);
                        event(new \App\Events\UserRegistered($user));

                        add_user_firebase($user->id, 'Online');

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
                        if ($user->isTemporary_password == "1") {
                            return redirect()->route('profile.change_password')->with('msg', 'Please changer your temparory password.');
                        } else {
                            return redirect()->route('home');
                        }
                    } else {
                        return redirect()->back()->withErrors([
                            'email' => 'Invalid credentials!',
                        ])->withInput();
                        // return  Redirect::to('login')->with('error', 'Invalid credentials!');
                    }
                } else {
                    $randomString = Str::random(30);
                    $user->remember_token = $randomString;
                    $user->save();

                    $userData = [
                        'username' => $user->firstname,
                        'email' => $user->email,
                        'token' => $randomString,
                        'is_first_login' => $user->is_first_login
                    ];


                    Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Verify your Yesvite email address');
                    });

                    return  Redirect::to('login')->with('msg', 'Please check and verify your email address.');
                }
            }
        }
        return redirect()->back()->withErrors([
            'email' => 'Email or Password invalid!',
        ])->withInput();
        // return  Redirect::to('login')->with('error', 'Email or Password invalid!');
    }


    public function addInFirebase($userId)
    {
        $userData = User::findOrFail($userId);
        // dd($userData);
        $userName =  $userData->firstname . ' ' . $userData->lastname;
        $updateData = [
            'userChatId' => '',
            'userCountryCode' => (string)$userData->country_code,
            'userGender' => 'male',
            'userEmail' => $userData->email,
            'userId' => (string)$userId,
            'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
            'userName' => $userName,
            'userPhone' => (string)$userData->phone_number,
            'userProfile' => request()->server('HTTP_HOST') . '/public/storage/profile/' . $userData->profile,
            'userStatus' => 'Online',
            'userTypingStatus' => 'Not typing...'
        ];

        // Create a new user node with the userId
        $userRef = $this->usersReference->getChild((string)$userId);
        $userSnapshot = $userRef->getValue();

        if ($userSnapshot) {
            // User exists, update the existing data
            $userRef->update($updateData);
        } else {
            // User does not exist, create a new user node
            $userRef->set($updateData);
        }
    }


    function getUserAccountType($userId)
    {
        return User::select('account_type')->where('id', $userId)->first();
    }




    public function currentUserLogin($currentLogUser)
    {
        Auth::guard('web')->login($currentLogUser);

        $prevUserLogin =  Auth::guard('web')->user();
        $prevUserLogin->current_session_id = Session::getId();
        $prevUserLogin->save();
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


        $currentLogUser = User::where('id', Auth::id())->firstOrFail();
        Auth::logout();



        $remember = $request->has('remember'); // Check if "Remember Me" checkbox is checked

        if (Auth::attempt($credentials, $remember)) {

            $secondUser = Auth::guard('web')->user();

            if ($secondUser->email_verified_at != NULL) {

                Session::regenerate();
                $secondUser->current_session_id = Session::getId();
                $secondUser->save();


                if ($currentLogUser->account_type == $secondUser->account_type) {



                    if ($currentLogUser != null) {
                        Auth::guard('web')->logout();
                        $this->currentUserLogin($currentLogUser);
                    }
                    $msg = "";
                    if ($currentLogUser->account_type == '0') {
                        $msg = "personal";
                    } else if ($currentLogUser->account_type == '1') {
                        $msg = "proffiesional";
                    }


                    return  Redirect::to('home')->with('msg_error', 'You have already login ' . $msg);
                }


                if ($currentLogUser != null) {

                    $currentLogUser['profile'] = ($currentLogUser->profile != null) ? asset('storage/profile/' . $currentLogUser->profile) : "";

                    $sessionAlreadyArray = [
                        'id' => encrypt($currentLogUser->id),
                        'first_name' => $currentLogUser->firstname,
                        'last_name' => $currentLogUser->lastname,
                        'secondary_username' => $currentLogUser->firstname . ' ' . $currentLogUser->lastname,
                        'secondary_email' => $currentLogUser->email,
                        'secondary_profile' => $currentLogUser->profile,

                    ];
                    Session::put(['secondary_user' => $sessionAlreadyArray]);
                    Session::forget('user');
                }

                $sessionArray = [
                    'id' => encrypt($secondUser->id),
                    'first_name' => $secondUser->firstname,
                    'last_name' => $secondUser->lastname,
                    'username' => $secondUser->firstname . ' ' . $secondUser->lastname,
                    'email' => $secondUser->email,
                    'profile' => ($secondUser->profile != NULL || $secondUser->profile != "") ? asset('storage/profile/' . $secondUser->profile) : ""
                ];
                Session::put(['user' => $sessionArray]);
                if (Session::has('user')) {

                    if ($remember) {
                        Cookie::queue('email', $secondUser->email, 120);
                        Cookie::queue('password', $request->password, 120);
                    } else {

                        Cookie::forget('email');
                        Cookie::forget('password');
                    }
                    event(new \App\Events\UserRegistered($secondUser));
                    $this->logoutFromApplication($secondUser->id);
                    return redirect()->route('home')->with('msg', 'Logged in successfully!');
                } else {

                    return  Redirect::to('login')->with('msg_error', 'Invalid credentials!');
                }
            } else {
                $this->currentUserLogin($currentLogUser);
                $randomString = Str::random(30);
                $secondUser->remember_token = $randomString;
                $secondUser->save();

                $userData = [
                    'username' => $secondUser->firstname . ' ' . $secondUser->lastname,
                    'first_name' => $secondUser->firstname,
                    'last_name' => $secondUser->lastname,
                    'email' => $secondUser->email,
                    'token' => $randomString,
                    'is_first_login' => $secondUser->is_first_login
                ];
                Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($secondUser) {
                    $message->to($secondUser->email);
                    $message->subject('Verify your Yesvite email address');
                });

                return  Redirect::to('add_account')->with('msg', 'Please check and verify your email address.');
            }
        }

        $this->currentUserLogin($currentLogUser);

        return  Redirect::to('profile')->with('msg_error', 'Email or Password invalid');
    }


    public function switchAccount($id)
    {
        $currentLogUser = User::where('id', Auth::id())->firstOrFail();


        $currentLogUser['profile'] = ($currentLogUser->profile != null) ? asset('storage/profile/' . $currentLogUser->profile) : "";

        $id = decrypt($id);
        $checkUser = User::where('id', $id)->first();
        if ($checkUser != null) {
            Auth::logout();

            $sessionAlreadyArray = [
                'id' => encrypt($currentLogUser->id),
                'first_name' => $currentLogUser->firstname,
                'last_name' => $currentLogUser->lastname,
                'secondary_username' => $currentLogUser->firstname . ' ' . $currentLogUser->lastname,
                'secondary_email' => $currentLogUser->email,
                'secondary_profile' => $currentLogUser->profile,

            ];
            Session::put(['secondary_user' => $sessionAlreadyArray]);
            Session::forget('user');

            Auth::loginUsingId($id);
            $switchAccount =  Auth::guard('web')->user();
            $switchAccount->current_session_id = Session::getId();
            $switchAccount->save();

            $sessionArray = [
                'id' => encrypt($switchAccount->id),
                'first_name' => $switchAccount->firstname,
                'last_name' => $switchAccount->lastname,
                'username' => $switchAccount->firstname . ' ' . $switchAccount->lastname,
                'email' => $switchAccount->email,
                'profile' => ($switchAccount->profile != NULL || $switchAccount->profile != "") ? asset('storage/profile/' . $switchAccount->profile) : ""
            ];
            Session::put(['user' => $sessionArray]);

            $this->logoutFromApplication($switchAccount->id);
            return redirect()->route('profile')->with('msg', 'Logged in successfully!');
        }
        return redirect()->route('profile')->with('msg_error', 'Logged faild!');
    }

    public function addAccount()
    {

        $page = 'auth/add_account';
        $title = "Login";
        $js = ['login'];
        return view('layout', compact('page', 'title', 'js'));
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


    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        return response()->json($exists);
    }



    public function storeAdvertisementStatus(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('closed') && $request->closed) {
                session(['advertisement_closed' => true]);
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'User not logged in']);
        }
    }

    public function logoutFromApplication($id)
    {



        $check = Device::where('user_id', $id)->first();


        if ($check != null) {
            $check->delete();
            Token::where('user_id', $id)->delete();
        }
    }

    public function forgetpassword()
    {
        $page = 'front/forgetpassword';
        $title = "Forget Password";
        $js = ['forget_password'];
        return view('layout', compact('page', 'title', 'js'));
    }

    public function otpverification(Request $request)
    {
        // dd($request);
        $token = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $userDetails = User::where('email', $request->email)->first();

        if ($userDetails == null) {
            return redirect()->back()->with('msg', 'You have not entered existing email');
        }

        $user_id = $userDetails->id;

        $digit1 = substr($token, 0, 1);
        $digit2 = substr($token, 1, 1);
        $digit3 = substr($token, 2, 1);
        $digit4 = substr($token, 3, 1);

        $useremail = $request->email;

        $userData = [
            'username' => $userDetails->firstname,
            // 'username' => $userDetails->firstname . ' ' . $userDetails->lastname,
            'email' => $userDetails->email,
            'digit1' => $digit1,
            'digit2' => $digit2,
            'digit3' => $digit3,
            'digit4' => $digit4
        ];

        $otp = $digit1 . $digit2 . $digit3 . $digit4;

        Mail::to($request->email)->send(new forgotpasswordMail(array($userData)));

        // dd($otp);
        $page = 'front/otpverification';
        $title = "Verify Otp";
        $js = ['forget_password'];

        if ($request->ajax()) {
            return response()->json(['success' => '1', 'otp' => $otp]);
        }
        return view('layout', compact('page', 'title', 'js', 'otp', 'user_id', 'useremail'));
    }

    public function checkOtp(Request $request)
    {
        $user_id = $request->user_id;
        $num1 = $request->number1;
        $num2 = $request->number2;
        $num3 = $request->number3;
        $num4 = $request->number4;

        $otp = $num1 . $num2 . $num3 . $num4;

        if ($request->generated_otp == $otp) {
            $page = 'front/forget_changepassword';
            $title = "Change Password";
            $js = ['forget_password'];
            return view('layout', compact('page', 'title', 'js', 'user_id'));
        }
    }

    public function forgetChangepassword(Request $request)
    {

        // $request->validate([
        //     'new_password' => 'required|min:8',
        //     'conform_password' => 'required|min:8|same:new_password',
        //     'user_id'=>'required'
        // ]);

        $userUpdate = User::where('id', $request->user_id)->first();
        $userUpdate->password = Hash::make($request->new_password);
        $userUpdate->password_updated_date = date('Y-m-d');
        $userUpdate->save();

        DB::commit();
        // toastr()->success('Password Changed');
        return  Redirect::to('login')->with('msg', 'Password has been changed.');

        // return  redirect()->route('auth.login');
    }

    public function getAccessToken()
    {
        $accessToken = getAccessToken();
        return response()->json(['access_token' => $accessToken]);
    }
}

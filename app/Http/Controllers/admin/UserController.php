<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
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
    public function index(UserDataTable $DataTable)
    {

        // if ($request->ajax()) {

        //     $data = User::where(['account_type' => '0', 'app_user' => '1'])->orderBy('id', 'desc');

        //     return Datatables::of($data)



        //         ->addIndexColumn()

        //         ->addColumn('number', function ($row) {

        //             static $count = 1;

        //             return $count++;
        //         })

        //         ->addColumn('profile', function ($row) {

        //             if (trim($row->profile) != "" || trim($row->profile) != NULL) {

        //                 if (Storage::disk('public')->exists('profile/' . $row->profile)) {
        //                     $imageUrl = asset('storage/profile/' . $row->profile);
        //                 } else {
        //                     $imageUrl = asset('storage/no_profile.png');
        //                 }
        //             } else {

        //                 $imageUrl = asset('storage/no_profile.png');
        //             }



        //             return '<div class="symbol-label">
        //             <img src="' . $imageUrl . '" alt="No Image" class="w-50">
        //         </div>';
        //         })

        //         ->addColumn('username', function ($row) {


        //             return $row->firstname . ' ' . $row->lastname;
        //         })

        //         ->addColumn('app_user', function ($row) {

        //             if ($row->app_user == '1') {

        //                 return '<i class="fa-solid fa-mobile"></i>';
        //             } else {
        //                 return '<span class="text-danger">Not App User</span>';
        //             }
        //         })

        //         // ->addColumn('action', function ($row) {

        //         //     $cryptId = encrypt($row->id);

        //         //     $edit_url = route('users.edit', $cryptId);

        //         //     $delete_url = route('users.destroy', $cryptId);

        //         //     $actionBtn = '<div class="action-icon">
        //         //         <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
        //         //         <form action="' . $delete_url . '" method="POST">' .
        //         //         csrf_field() . // Changed from @csrf to csrf_field()
        //         //         method_field("DELETE") . // Changed from @method to method_field()
        //         //         '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
        //         //         </div>';

        //         //     return $actionBtn;
        //         // })

        //         ->rawColumns(['number', 'profile', 'username', 'app_user'])



        //         ->make(true);
        // }
        $title = 'Users';
        $page = 'admin.user.list';
        $js = 'admin.user.userjs';
        return $DataTable->render('admin.includes.layout', compact('title', 'page', 'js'));
        // return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add User';

        $page = 'admin.user.add';

        $js = 'admin.user.userjs';





        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            // 'phone_number' => 'required|string|unique:users',
        ]);

        DB::beginTransaction();
        try {
            $addUser = new User();

            // Set the addUser attributes
            $password = $request['firstname'] . '123';  // Generate temporary password
            $randomString = Str::random(30);  // Generate random remember token

            $addUser->firstname = $request['firstname'];
            $addUser->lastname = $request['lastname'];
            $addUser->email = $request['email'];
            $requireNewPassword = $request->has('require_new_password') ? true : false;
            if ($requireNewPassword) {
                $addUser->isTemporary_password = '1';
            }
            $addUser->app_user  = '1';
            $addUser->remember_token = $randomString;
            $addUser->register_type = 'Admin create user';
            $addUser->password = Hash::make($password);
            // $addUser->isTemporary_password = '1';
            $addUser->email_verified_at = Carbon::now()->toDateTimeString();
            $addUser->password_updated_date = Carbon::now()->format('Y-m-d');

            // Add phone number if provided
            if (!empty($request['phone_number'])) {
                $addUser->phone_number = $request['phone_number'];
            }

            // Save the addUser to the database
            $addUser->save();
            $userData = [
                'username' => $request['firstname'] . ' ' . $request['lastname'],
                'email' => $request['email'],
                'token' => $randomString,
                'password' => $password
            ];

            // dd($userData);
            $this->addInFirebase($addUser->id);

            Mail::send('emails.emailVerificationEmail', ['userData' => $userData], function ($message) use ($request) {
                $message->to($request['email']);
                $message->subject('Verify your Yesvite email address');
            });

            DB::commit();
            $this->addInFirebase($addUser->id);
            return redirect()->route('users.index')->with('msg', 'User Add successfully !');
        } catch (\Exception $e) {
            dd($e);
            // Rollback transaction on error
            DB::rollBack();
            return redirect()->route('users.create')->with('msg_error', 'User creation failed!');
        }
    }


    public function checkNewContactEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
    }

    public function CheckExistingUserEmail(Request $request)
{
    // dd($request);
    $email = $request->input('email');
    $id = $request->input('id');
    $existsEmail = User::where('email', $email)
                        ->where('id', '!=', $id)
                        ->exists();
    if ($existsEmail) {
        return response()->json(false);
    } 
    return response()->json(true);
}


    public function checkAdminEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = Admin::where('email', $email)->first();
        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
    }

    public function checkNewContactNumber(Request $request)
    {
        $phone_number = $request->input('phone_number');
        $id = $request->input('id');

        if ($id != '') {
            $exists = User::where('phone_number', $phone_number)->where('id', '!=', $id)->exists();
        } else {
            $exists = User::where('phone_number', $phone_number)->exists();
        }

        if ($exists) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
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
        $user_id = decrypt($id);

        // Get the template data by ID
        $getTemData = User::findOrFail($user_id);

        $title = 'Edit user';
        $page = 'admin.user.edit';
        $js = 'admin.user.userjs';
        $subcatId = $id;




        // Pass the data to the view
        return view('admin.includes.layout', compact(
            'title',
            'page',
            'js',
            'getTemData',

        ));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Decrypt the user ID if needed
        $user = user::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            // 'ci' => 'required|string|max:255',
            // 'lastname' => 'required|string|max:255',
        ]);




        // Update user data
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        // $user->address = $request->input('address');
        // $user->address_2 = $request->input('address_2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');



        // Save the updated user details
        $user->save();

        // Redirect back to the user list or show a success message
        return redirect()->route('users.index')
            ->with('msg', 'User updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_temp_password(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $requireNewPassword = $request->has('require_new_password') ? true : false;
            $user_id = decrypt($id);
            $update_password = User::where('id', $user_id)->first();

            $update_password->password = Hash::make($request->password); // Use bcrypt for password hashing

            if ($requireNewPassword) {
                $update_password->isTemporary_password = 1; // Save as temporary
            }
            $update_password->save();
            $userData = [
                'username' => $update_password->firstname,
                'password' => $request->password
            ];
            try {
                Mail::send('emails.temporary_password_email', ['userData' => $userData], function ($message) use ($update_password) {
                    $message->to($update_password->email);
                    $message->subject('Temporary Password Mail');
                });
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('msg_error', 'Failed to send email: ' . $e->getMessage());
            }
            DB::commit();
            return redirect()->route('users.index')->with('msg', 'User password updated and email sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function updateStatus(Request $request)
    {
        $user = User::find($request->id); // Assuming 'id' is sent from the frontend

        if ($user) {
            // Toggle the status between 'Ban' and 'Unban'
            $user->account_status = $user->account_status == 'Block' ? 'Unblock' : 'Block';
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'User status updated successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'User not found.']);
    }
}

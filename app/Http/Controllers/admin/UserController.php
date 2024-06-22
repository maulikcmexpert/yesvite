<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {



        if ($request->ajax()) {

            $data = User::where(['account_type' => '0', 'app_user' => '1'])->orderBy('id', 'desc');

            return Datatables::of($data)



                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })

                ->addColumn('profile', function ($row) {

                    if (trim($row->profile) != "" || trim($row->profile) != NULL) {

                        if (Storage::disk('public')->exists('profile/' . $row->profile)) {
                            $imageUrl = asset('storage/profile/' . $row->profile);
                        } else {
                            $imageUrl = asset('storage/no_profile.png');
                        }
                    } else {

                        $imageUrl = asset('storage/no_profile.png');
                    }



                    return '<div class="symbol-label">
                    <img src="' . $imageUrl . '" alt="No Image" class="w-50">
                </div>';
                })

                ->addColumn('username', function ($row) {


                    return $row->firstname . ' ' . $row->lastname;
                })

                ->addColumn('app_user', function ($row) {

                    if ($row->app_user == '1') {

                        return '<i class="fa-solid fa-mobile"></i>';
                    } else {
                        return '<span class="text-danger">Not App User</span>';
                    }
                })

                // ->addColumn('action', function ($row) {

                //     $cryptId = encrypt($row->id);

                //     $edit_url = route('users.edit', $cryptId);

                //     $delete_url = route('users.destroy', $cryptId);

                //     $actionBtn = '<div class="action-icon">
                //         <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                //         <form action="' . $delete_url . '" method="POST">' .
                //         csrf_field() . // Changed from @csrf to csrf_field()
                //         method_field("DELETE") . // Changed from @method to method_field()
                //         '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                //         </div>';

                //     return $actionBtn;
                // })

                ->rawColumns(['number', 'profile', 'username', 'app_user'])



                ->make(true);
        }



        $title = 'Users';

        $page = 'admin.user.list';

        $js = 'admin.user.userjs';





        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Users';

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
            $password = $request['firstname'] . '123';

            $data = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'password' => Hash::make($password),
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'password_updated_date' => Carbon::now()->format('Y-m-d'),
            ];

            if (!empty($request['phone_number'])) {
                $data['phone_number'] = $request['phone_number'];
            }
            $addUser = User::create($data);


            DB::commit();

            return redirect()->route('users.index')->with('success', 'User Add successfully !');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return redirect()->route('users.create')->with('error', 'User creation failed!');
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
}

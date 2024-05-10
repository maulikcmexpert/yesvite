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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {



        if ($request->ajax()) {

            $data = User::where('account_type', '0')->orderBy('id', 'desc');

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

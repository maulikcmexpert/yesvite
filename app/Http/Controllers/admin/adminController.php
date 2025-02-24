<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\QueryException;


class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function AdminPasswordChange()
    {
        $title = 'Change Password';

        $page = 'admin.password.update_admin_password';
        $js = 'admin.password.changepasswordjs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }
  

    public function changePassword(Request $request)
    {
        try {
            $admin=Session::get('admin');
            $request->validate([
                'current_password' => 'required|min:6',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password',
            ]);
                
            $id = $admin['id'];
            DB::beginTransaction();
            $userUpdate = Admin::where('id', $id)->first();
            $userUpdate->password = Hash::make($request->new_password);
            $userUpdate->save();
            DB::commit();
            return Redirect::to(URL::to(path: '/admin/dashboard'))->with('msg', 'Password Updated Successfully!');;
            } 
        catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error' . $e->getMessage());
            return Redirect::to(URL::to(path: '/admin/dashboard'))->with('msg', 'Password Not Updated!!');;
            }
    }


    public function verifyPassword(Request $request)
    {
        $admin=Session::get('admin');
        $id = $admin['id'];
        $password = $request->input('current_password');
        $user = Admin::findOrFail($id);
        if (Hash::check($password, $user->password)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
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

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


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

        $admin=Session::get('admin');
        dd($admin->id);
        // $validator = Validator::make($request->all(), [
        //     'current_password' => 'required|min:8',
        //     'new_password' => 'required|min:8',
        //     'conform_password' => 'required|min:8|same:new_password',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->route('profile.change_password')
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        $id = decrypt(session()->get('user')['id']);
        $userUpdate = Admin::where('id', $id)->first();
        $userUpdate->password = Hash::make($request->new_password);
        $userUpdate->save();

        DB::commit();
        toastr()->success('Password Changed');
        return  redirect()->route('profile.edit');
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

<?php

namespace App\Http\Controllers\admin;

use App\DataTables\AccountVerificationDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    EventPost,
    EventPostImage,
    UserReportToPost,
    User
};
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Mail;
use App\Mail\forgotpasswordMail;

class AccountVerification extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AccountVerificationDataTable $DataTable)
    {
        $title = 'User Account Verification';
        $page = 'admin.account_verification.list';
        // $js = 'admin.post_reports.post_reportsjs';
        return $DataTable->render('admin.includes.layout', compact('title', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function verify(string $id)
    {
        try{
        $user_id=decrypt($id);

        $verify=User::where('id',$user_id)->first();
        if($verify){
            $verify->email_verified_at= time();
            $verify->save();
        }

        return redirect()->route('account_verification.index')->with("success", "User Verified Successfully !");
    } catch (QueryException $e) {
        DB::rollBack();
       
    }
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
        $title = 'Edit User Information';
        $page = 'admin.account_verification.edit';
        // $js = 'admin.post_reports.post_reportsjs';
        return view('admin.includes.layout', compact('title', 'page'));    }

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

    public function SetPassword(string $id){
        $title = 'Set User Temporary Password';
        $page = 'admin.user.set_password';
        // $js = 'admin.post_reports.post_reportsjs';
        return view('admin.includes.layout', compact('title', 'page'));
        // return redirect()->route('design.index')->with("success", "Email Resend Successfully !");

    }
}

<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UserResendEmailVerifyDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    EventPost,
    EventPostImage,
    UserReportToPost
};
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Services\DataTable;


class UserResendEmailVerify extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserResendEmailVerifyDataTable $DataTable)
    {
        $title = 'User Resend Verification Mail';
        $page = 'admin.resend_email_verification.list';
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

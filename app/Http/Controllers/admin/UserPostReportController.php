<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    UserReportToPost
};
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPostReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = UserReportToPost::with(['events', 'users', 'event_posts'])->orderBy('id', 'desc');

            return Datatables::of($data)



                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })

                ->addColumn('username', function ($row) {

                    return $row->users->firstname . ' ' . $row->users->lastname;
                })

                ->addColumn('event_name', function ($row) {

                    return $row->events->event_name;
                })


                ->addColumn('post_type', function ($row) {

                    if ($row->event_posts->post_type == '0') {

                        return "<span class='text-info'>Normal</span>";
                    }
                    if ($row->event_posts->post_type == '1') {

                        return "<span class='text-info'>Photos and videos</span>";
                    }
                    if ($row->event_posts->post_type == '2') {

                        return "<span class='text-info'>Polls</span>";
                    }
                    if ($row->event_posts->post_type == '3') {

                        return "<span class='text-info'>Recording</span>";
                    }
                })



                ->rawColumns(['number', 'username', 'event_name', 'post_type'])



                ->make(true);
        }



        $title = 'Post Reports';

        $page = 'admin.post_reports.list';

        $js = 'admin.post_reports.post_reportsjs';





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

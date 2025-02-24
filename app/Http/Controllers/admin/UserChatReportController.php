<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UserChatReportDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    EventPost,
    EventPostImage,
    UserReportToPost,
    UserReportChat
};
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Services\DataTable;

class UserChatReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserChatReportDataTable $DataTable)
    {

        // if ($request->ajax()) {
        //     $data = UserReportToPost::with(['events', 'users', 'event_posts'])->orderBy('id', 'desc');


        //     return Datatables::of($data)



        //         ->addIndexColumn()

        //         ->addColumn('number', function ($row) {

        //             static $count = 1;

        //             return $count++;
        //         })

        //         ->addColumn('username', function ($row) {

        //             return $row->users->firstname . ' ' . $row->users->lastname;
        //         })

        //         ->addColumn('event_name', function ($row) {

        //             return $row->events->event_name;
        //         })


        //         ->addColumn('post_type', function ($row) {

        //             if ($row->event_posts->post_type == '0') {

        //                 return "<span class='text-info'>Normal</span>";
        //             }
        //             if ($row->event_posts->post_type == '1') {

        //                 return "<span class='text-info'>Photos and videos</span>";
        //             }
        //             if ($row->event_posts->post_type == '2') {

        //                 return "<span class='text-info'>Polls</span>";
        //             }
        //             if ($row->event_posts->post_type == '3') {

        //                 return "<span class='text-info'>Recording</span>";
        //             }
        //         })

        //         ->addColumn('action', function ($row) {

        //             $cryptId = encrypt($row->id);

        //             // $edit_url = route('users.edit', $cryptId);

        //             // $delete_url = route('users.destroy', $cryptId);
        //             $view_url = route('user_post_report.show', $cryptId);

        //             $actionBtn = '<div class="action-icon">
        //                 <a class="" href="' . $view_url . '" title="View"><i class="fa fa-eye"></i></a>';

        //             return $actionBtn;
        //         })

        //         ->rawColumns(['number', 'username', 'event_name', 'post_type', 'action'])



        //         ->make(true);
        // }
        $title = 'Users Chat Reports';
        $page = 'admin.post_chat_reports.list';
        $js = 'admin.post_reports.post_reportsjs';
        return $DataTable->render('admin.includes.layout', compact('title', 'page', 'js'));
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
    public function show(string $id) {}

    public function setpostTime($dateTime)
    {

        $commentDateTime = $dateTime; // Replace this with your actual timestamp

        // Convert the timestamp to a Carbon instance
        $commentTime = Carbon::parse($commentDateTime);

        // Calculate the time difference
        $timeAgo = $commentTime->diffForHumans(); // This will give the time ago format


        // Display the time ago
        return $timeAgo;
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
        try {
            DB::beginTransaction();
            $chat_report_id = decrypt($id);
            UserReportChat::find($chat_report_id)->delete();
            DB::commit();
            return redirect()->route('/admin/user_chat_report')
                ->with('msg', 'Chat Report deleted successfully');
        } catch (QueryException $e) {
            DB::rollBack();
        }
    }

    public function deleteChatReport(string $id) {}
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Event;
use App\Models\EventImage;
use App\Models\EventPost;
use App\Models\EventInvitedUser;
use Carbon\Carbon;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        dd($request->eventType);
        $eventType = "";


        if ($request->ajax()) {
            $eventDate = $request->input('filter');
            $status = $request->input('status');
            $data = Event::with(['user' => function ($query) use ($eventType) {

                if ($eventType == 'normal_event') {

                    $query->where(['app_user' => '1', 'account_type' => '0']);
                } else if ($eventType == 'professional_event') {
                    $query->where(['app_user' => '1', 'account_type' => '1']);
                }
            }])->orderBy('id', 'desc');

            if ($eventDate) {
                $data->where('start_date', $eventDate);
            }
            if ($status == 'upcoming_events') {
                $data->where('start_date', '>', date('Y-m-d'));
            }

            if ($status == 'past_events') {
                $data->where('start_date', '<', date('Y-m-d'));
            }
            if ($status == 'draft_events') {
                $data->where('is_draft_save', '1');
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('number', function ($row) {
                    static $count = 1;
                    return $count++;
                })
                ->addColumn('event_by', function ($row) {


                    return $row->user->firstname . ' ' . $row->user->lastname;
                })
                ->addColumn('email', function ($row) {


                    return $row->user->email;
                })
                ->addColumn('start_date', function ($row) {


                    return date('F j, Y', strtotime($row->start_date));
                })

                ->addColumn('end_date', function ($row) {

                    return date('F j, Y', strtotime($row->end_date));
                })
                ->addColumn('venue', function ($row) {

                    return $row->event_location_name;
                })
                ->addColumn('event_status', function ($row) {

                    if ($row->is_draft_save == '1') {

                        return '<img src="' . asset('public/storage/event_icon/draft.png') . '" class="img-fluid" alt="" width="25px" title="Draft Event">';
                    } else if ($row->is_draft_save == '0') {
                        if ($row->start_date > date('Y-m-d')) {
                            return '<img src="' . asset('public/storage/event_icon/upcoming.png') . '" class="img-fluid" alt="" width="25px" title="Upcoming Event">';
                        }
                        if ($row->start_date == date('Y-m-d')) {
                            return "<span class='text-success'>Published Event</span>";
                        }
                        return "<span class='text-info'>Past Event</span>";
                    }
                })

                ->addColumn('action', function ($row) {

                    $cryptId = encrypt($row->id);

                    // $edit_url = route('users.edit', $cryptId);

                    // $delete_url = route('users.destroy', $cryptId);
                    $view_url = route('events.show', $cryptId);

                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $view_url . '" title="View"><i class="fa fa-eye"></i></a>';

                    return $actionBtn;
                })

                ->rawColumns([
                    'number',
                    'event_by',
                    'email',
                    'start_date',
                    'end_date',
                    'venue',
                    'event_status',
                    'action'
                ])



                ->make(true);
        }



        $title = 'Event Lists';

        $page = 'admin.event_management.list';

        $js = 'admin.event_management.eventjs';


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
    public function show($id)
    {

        $eventId =  decrypt($id);


        $title = 'Event Detail';
        $page = 'admin.event_management.view';
        $js = 'admin.event_management.eventviewjs';
        $eventDetail =  Event::where('id', $eventId)->first();
        $eventImage = EventImage::where('event_id', $eventId)->get();
        $eventDate = $eventDetail->start_date;
        $current_date = Carbon::now();
        $datetime1 = Carbon::parse($eventDate);

        $datetime2 = Carbon::parse($current_date);
        $event_id = $id;
        $till_days = 0;
        if ($eventDate > $current_date) {

            $till_days = $datetime1->diff($datetime2)->days;
        }
        $eventDetail['till_days'] = $till_days;



        return view('admin.includes.layout', compact('title', 'page', 'js', 'eventImage', 'event_id', 'eventDetail'));
    }

    public function invitedUsers(Request $request)
    {
        $eventId = $request->input('eventId');
        $event_id = decrypt($eventId);

        $data =  EventInvitedUser::with('user')->where('event_id', $event_id)->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('number', function ($row) {
                static $count = 1;
                return $count++;
            })
            ->addColumn('username', function ($row) {

                $isCoHost = "";
                if ($row->is_co_host == '1') {
                    $isCoHost =  "<span class='text-success'>Co-host</span>";
                }
                return $row->user->firstname . ' ' . $row->lastname . ' ' . $isCoHost;
            })
            ->addColumn('email', function ($row) {
                return $row->user->email;
            })


            ->addColumn('rsvp_status', function ($row) {

                if ($row->rsvp_status == NULL) {
                    return "No Reply";
                } else if ($row->rsvp_status == "0") {
                    return "Not coming";
                } else if ($row->rsvp_status == "1") {
                    return "coming";
                }
            })


            ->addColumn('total_posts', function ($row) {

                $totalPosts = EventPost::where('user_id', $row->user_id)->count();
                return $totalPosts;
            })

            ->rawColumns([
                'number',
                'username',
                'email',
                'rsvp_status',
                'total_posts'
            ])

            ->make(true);
    }


    public function eventPosts($id)
    {

        $eventId = decrypt($id);
        $title = 'Event Posts';
        $page = 'admin.event_management.eventpost';
        $js = 'admin.event_management.eventpostjs';
        $getPosts = EventPost::with(
            [
                'user',
                'post_image',
                'event_post_poll' => function ($query) {
                    $query->with('event_poll_option');
                }
            ]
        )->withCount(['event_post_comment' => function ($query) {
            $query->where('parent_comment_id', NULL);
        }, 'event_post_reaction'])->where('event_id', $eventId)->get();

        foreach ($getPosts as $key => $val) {
            $getPosts[$key]['posttime'] = $this->setpostTime($val->created_at);
        }
        $event_id = $id;
        return view('admin.includes.layout', compact('title', 'page', 'js', 'event_id', 'getPosts'));
    }

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
        //
    }
}

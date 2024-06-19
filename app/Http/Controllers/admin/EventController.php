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


        if ($request->ajax()) {
            $eventDate = $request->input('filter');
            $status = $request->input('status');
            $event_type = $request->input('event_type');

            $data = Event::with(['user'])->whereHas('user', function ($query) use ($event_type) {


                if ($event_type == 'normal_user_event') {
                    $query->where('account_type', '0');
                }
                if ($event_type == 'professional_event') {
                    $query->where('account_type', '1');
                }
            })->orderBy('id', 'desc');

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
                        return '<svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <title>Draft Event</title>

                        <path d="M4.35153 0.602308H16.9572C18.071 0.602308 19.0821 1.05723 19.8142 1.79107C20.5463 2.52491 21.0002 3.53841 21.0002 4.65483V16.3097C21.0002 16.4701 20.934 16.6148 20.8276 16.7184L12.7692 24.7959C12.6584 24.9077 12.5125 24.9629 12.3667 24.9629H4.35153C3.23775 24.9629 2.22664 24.508 1.49454 23.7742C0.762438 23.0403 0.308594 22.0268 0.308594 20.9104V4.65408C0.308594 3.53766 0.762438 2.52416 1.49454 1.79032C2.22664 1.05648 3.23775 0.601562 4.35153 0.601562V0.602308ZM19.0561 16.881H15.8665C15.06 16.881 14.3272 17.2114 13.7967 17.7431C13.2655 18.2756 12.9366 19.0094 12.9366 19.8179V23.015L19.0561 16.881ZM11.7975 23.8226V19.8186C11.7975 18.6947 12.2544 17.6753 12.9917 16.9362C13.729 16.1971 14.746 15.7392 15.8673 15.7392H19.8618V4.65483C19.8618 3.85387 19.536 3.126 19.0092 2.59874C18.4832 2.07148 17.757 1.74408 16.958 1.74408H4.35227C3.55321 1.74408 2.82706 2.07073 2.30104 2.59874C1.77503 3.126 1.44841 3.85387 1.44841 4.65483V20.9112C1.44841 21.7121 1.77429 22.44 2.30104 22.9672C2.82706 23.4945 3.55321 23.8219 4.35227 23.8219H11.7975V23.8226ZM5.57319 12.4824C5.25847 12.4824 5.00328 12.2266 5.00328 11.9112C5.00328 11.5957 5.25847 11.3407 5.57319 11.3407H15.7363C16.051 11.3407 16.3062 11.5965 16.3062 11.9112C16.3062 12.2266 16.051 12.4824 15.7363 12.4824H5.57319ZM5.57319 8.08389C5.25847 8.08389 5.00328 7.82809 5.00328 7.51337C5.00328 7.19791 5.25847 6.94211 5.57319 6.94211H15.7363C16.051 6.94211 16.3062 7.19791 16.3062 7.51337C16.3062 7.82883 16.051 8.08389 15.7363 8.08389H5.57319Z" fill="#FB7E37"/>
                        </svg>
                        ';
                        // return '<img src="' . asset('storage/event_icon/draft.png') . '" class="img-fluid" alt="" width="25px" title="Draft Event">';
                    } else if ($row->is_draft_save == '0') {
                        if ($row->start_date > date('Y-m-d')) {
                            return '<img src="' . asset('storage/event_icon/upcoming.png') . '" class="img-fluid" alt="" width="25px" title="Upcoming Event">';
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


            ->addColumn('total_posts', function ($row) use ($event_id) {

                $totalPosts = EventPost::where(['user_id' => $row->user_id, 'event_id' => $event_id])->count();
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

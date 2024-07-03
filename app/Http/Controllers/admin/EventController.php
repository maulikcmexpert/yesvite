<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

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
            } else {
                $data->where('is_draft_save', '0');
            }
            return Datatables::of($data)
                // ->addColumn('number', function ($row) {
                //     static $count = 1;
                //     return $count++;
                // })
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
                            // return '<img src="' . asset('storage/event_icon/upcoming.png') . '" class="img-fluid" alt="" width="25px" title="Upcoming Event">';
                            return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <title>Upcoming Event</title>                            
                                    <g clip-path="url(#clip0_3031_14624)">
                                    <path d="M22.0841 1.71811H18.674V0.5625C18.674 0.413316 18.6147 0.270242 18.5092 0.164752C18.4037 0.0592633 18.2607 3.35276e-08 18.1115 3.35276e-08C17.9623 3.35276e-08 17.8192 0.0592633 17.7137 0.164752C17.6082 0.270242 17.549 0.413316 17.549 0.5625V1.71811H12.5625V0.5625C12.5625 0.413316 12.5032 0.270242 12.3977 0.164752C12.2923 0.0592632 12.1492 0 12 0C11.8508 0 11.7077 0.0592632 11.6023 0.164752C11.4968 0.270242 11.4375 0.413316 11.4375 0.5625V1.71811H6.45103V0.5625C6.45103 0.413316 6.39177 0.270242 6.28628 0.164752C6.18079 0.0592632 6.03772 0 5.88853 0C5.73935 0 5.59627 0.0592632 5.49078 0.164752C5.38529 0.270242 5.32603 0.413316 5.32603 0.5625V1.71811H1.91592C1.40797 1.71869 0.920983 1.92074 0.561805 2.27991C0.202626 2.63909 0.000583094 3.12608 0 3.63403L0 22.084C0.00057069 22.592 0.202608 23.079 0.561788 23.4382C0.920968 23.7974 1.40796 23.9994 1.91592 24H22.0841C22.592 23.9994 23.079 23.7974 23.4382 23.4382C23.7974 23.079 23.9994 22.592 24 22.0841V3.63403C23.9994 3.12608 23.7974 2.63909 23.4382 2.27991C23.079 1.92074 22.592 1.71869 22.0841 1.71811ZM1.91592 2.84311H5.32603V3.99872C5.32603 4.1479 5.38529 4.29098 5.49078 4.39647C5.59627 4.50196 5.73935 4.56122 5.88853 4.56122C6.03772 4.56122 6.18079 4.50196 6.28628 4.39647C6.39177 4.29098 6.45103 4.1479 6.45103 3.99872V2.84311H11.4375V3.99872C11.4375 4.1479 11.4968 4.29098 11.6023 4.39647C11.7077 4.50196 11.8508 4.56122 12 4.56122C12.1492 4.56122 12.2923 4.50196 12.3977 4.39647C12.5032 4.29098 12.5625 4.1479 12.5625 3.99872V2.84311H17.549V3.99872C17.549 4.1479 17.6082 4.29098 17.7137 4.39647C17.8192 4.50196 17.9623 4.56122 18.1115 4.56122C18.2607 4.56122 18.4037 4.50196 18.5092 4.39647C18.6147 4.29098 18.674 4.1479 18.674 3.99872V2.84311H22.0841C22.2938 2.84336 22.4948 2.92677 22.6431 3.07504C22.7913 3.22331 22.8748 3.42434 22.875 3.63403V7.44263H1.125V3.63403C1.12525 3.42434 1.20866 3.22331 1.35693 3.07504C1.5052 2.92677 1.70623 2.84336 1.91592 2.84311ZM22.0841 22.875H1.91592C1.70623 22.8748 1.50519 22.7914 1.35692 22.6431C1.20864 22.4948 1.12524 22.2938 1.125 22.0841V8.56763H22.875V22.0841C22.8748 22.2938 22.7914 22.4948 22.6431 22.6431C22.4948 22.7914 22.2938 22.8748 22.0841 22.875ZM17.4083 15.3234C17.4606 15.3757 17.502 15.4377 17.5303 15.5059C17.5586 15.5742 17.5731 15.6473 17.5731 15.7212C17.5731 15.7951 17.5586 15.8682 17.5303 15.9365C17.502 16.0047 17.4606 16.0667 17.4083 16.119L13.2815 20.2459C13.2292 20.2981 13.1672 20.3395 13.099 20.3678C13.0307 20.3961 12.9576 20.4106 12.8837 20.4106C12.8098 20.4106 12.7367 20.3961 12.6684 20.3678C12.6002 20.3395 12.5382 20.2981 12.486 20.2459C12.4337 20.1936 12.3923 20.1316 12.364 20.0634C12.3357 19.9951 12.3212 19.922 12.3212 19.8481C12.3212 19.7742 12.3357 19.7011 12.364 19.6329C12.3923 19.5646 12.4337 19.5026 12.486 19.4504L15.6444 16.2919L6.53859 16.2787C6.38941 16.2786 6.24638 16.2193 6.14096 16.1137C6.03555 16.0082 5.97639 15.865 5.97649 15.7159C5.9766 15.5667 6.03596 15.4236 6.14153 15.3182C6.24709 15.2128 6.39021 15.1536 6.53939 15.1537H6.54023L15.6609 15.1671L12.486 11.9922C12.3805 11.8867 12.3212 11.7436 12.3212 11.5944C12.3212 11.4452 12.3805 11.3021 12.486 11.1967C12.5914 11.0912 12.7345 11.0319 12.8837 11.0319C13.0329 11.0319 13.176 11.0912 13.2815 11.1967L17.4083 15.3234Z" fill="#1842D8"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_3031_14624">
                                    <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                    </svg>
                                    ';
                        }
                        if ($row->start_date == date('Y-m-d')) {
                            return "<span class='text-success'>Published Event</span>";
                        }
                        // return "<span class='text-info'>Past Event</span>";
                        return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <title>Past Event</title>                            

                    <g clip-path="url(#clip0_3031_14627)">
                    <path d="M20.4852 3.51476C18.2187 1.24831 15.2053 8.45306e-05 12 8.45306e-05C9.84352 8.45306e-05 7.74958 0.572567 5.90796 1.65987L4.45409 0.205959C4.35576 0.107621 4.23048 0.0406497 4.09408 0.0135149C3.95769 -0.0136198 3.81632 0.000300507 3.68783 0.0535154C3.55935 0.10673 3.44954 0.19685 3.37227 0.312477C3.29501 0.428105 3.25377 0.564047 3.25377 0.703113V4.06746C3.25377 4.45577 3.56858 4.77058 3.95689 4.77058H7.32124C7.4603 4.77058 7.59624 4.72934 7.71187 4.65208C7.8275 4.57481 7.91762 4.465 7.97083 4.33652C8.02405 4.20803 8.03797 4.06666 8.01083 3.93027C7.9837 3.79387 7.91673 3.66859 7.81839 3.57026L6.93935 2.69121C8.48608 1.8485 10.2187 1.40628 12 1.40628C17.8414 1.40628 22.5937 6.15859 22.5937 12C22.5937 17.8414 17.8414 22.5937 12 22.5937C6.15855 22.5937 1.40624 17.8414 1.40624 12C1.40624 11.6117 1.09143 11.2969 0.703122 11.2969C0.314811 11.2969 0 11.6117 0 12C0 15.2053 1.24818 18.2187 3.51467 20.4852C5.78121 22.7517 8.79465 23.9999 12 23.9999C15.2053 23.9999 18.2187 22.7517 20.4852 20.4852C22.7517 18.2187 23.9999 15.2053 23.9999 12C23.9999 8.79469 22.7517 5.78125 20.4852 3.51476ZM4.66001 3.36429V2.40064L5.30515 3.04573L5.31003 3.0506L5.62376 3.36434L4.66001 3.36429Z" fill="#353535"/>
                    <path d="M16.5187 11.3H12.7031V7.48438C12.7031 7.09606 12.3884 6.78125 12 6.78125C11.6117 6.78125 11.2969 7.09606 11.2969 7.48438V12.0031C11.2969 12.3914 11.6117 12.7062 12 12.7062H16.5187C16.9071 12.7062 17.2219 12.3914 17.2219 12.0031C17.2219 11.6148 16.9071 11.3 16.5187 11.3Z" fill="#353535"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_3031_14627">
                    <rect width="24" height="24" fill="white"/>
                    </clipPath>
                    </defs>
                    </svg>
                    ';
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
                    // 'number',
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

<?php

namespace App\Http\Controllers;

use App\Models\{
    Event,
    EventPost,
    EventPostComment,
    EventInvitedUser,
    Notification,
    EventImage,
    EventGiftRegistry,
    UserReportToPost,
    EventPostImage,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    UserPotluckItem,
    PostControl,
    EventPostCommentReaction,
    EventPostReaction,
    EventUserStory,
    UserSeenStory,
    EventPostPoll,
    EventPostPollOption,
    contact_sync,
    User,
    UserEventStory,
    UserEventPollData
};
use Spatie\Image\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class EventWallController extends BaseController
{
    protected $perPage;

    public function __construct()
    {
        parent::__construct();
        $this->perPage = 5;
    }
    public function index_new(String $id)
    {
        $title = 'event wall';
        $user = Auth::guard('web')->user();
        $js = ['event_wall', 'post_like_comment', 'guest_rsvp', 'guest'];

        $event = decrypt($id);
        $encrypt_event_id = $id;
        $page = 'front.event_wall.event_wall';
        $selectedFilters = session('filterSession');
        dd($selectedFilters);
        if (!$event) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        // Optimize User Query
        $users = User::withCount([
            'event' => fn($query) => $query->where('is_draft_save', '0'),
            'event_post' => fn($query) => $query->where('post_type', '1'),
            'event_post_comment',
        ])->findOrFail($user->id);

        $users['events'] = Event::where('user_id', $users->id)->where('is_draft_save', '0')->count();
        $users['profile'] = $users->profile ? asset("storage/profile/{$users->profile}") : "";
        $users['bg_profile'] = $users->bg_profile ? asset("storage/bg_profile/{$users->bg_profile}") : asset('assets/front/image/Frame 1000005835.png');

        $currentDateTime = now();

        // Optimize Event Stories Query
        $eventStoriesLists = EventUserStory::with([
            'user:id,firstname,lastname,profile',
            'user_event_story' => fn($query) => $query->where('created_at', '>', now()->subHours(24))
        ])
            ->where('created_at', '>', now()->subHours(24))
            ->where('event_id', $event)
            ->where('user_id', '!=', $user->id)
            ->get();

        $storiesList = $eventStoriesLists->map(function ($value) use ($user) {
            return [
                'id' => $value->id,
                'user_id' => $value->user->id,
                'username' => "{$value->user->firstname} {$value->user->lastname}",
                'profile' => $value->user->profile ? asset("storage/profile/{$value->user->profile}") : "",
                'story' => $value->user_event_story->map(function ($storyVal) use ($user) {
                    return [
                        'id' => $storyVal->id,
                        'storyurl' => $storyVal->story ? asset("storage/event_user_stories/{$storyVal->story}") : "",
                        'type' => $storyVal->type,
                        'post_time' => $this->setpostTime($storyVal->created_at),
                        'is_seen' => UserSeenStory::where('user_id', $user->id)->where('user_event_story_id', $storyVal->id)->exists() ? "1" : "0",
                        'video_duration' => $storyVal->type == 'video' ? ($storyVal->duration ?? "") : "",
                        'created_at' => $storyVal->created_at
                    ];
                })
            ];
        });

        // Optimize Polls Query
        $polls = EventPostPoll::with('event_poll_option')
            ->withCount('user_poll_data')
            ->where('event_id', $event)
            ->orderByDesc('id')
            ->get();

        $wallData['owner_stories'] = [];

        // Optimize Logged-in User Stories Query
        $eventLoginUserStoriesList = EventUserStory::with([
            'user:id,firstname,lastname,profile',
            'user_event_story' => fn($query) => $query->where('created_at', '>', now()->subHours(24))
        ])
            ->where('event_id', $event)
            ->where('user_id', $user->id)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if ($eventLoginUserStoriesList) {
            $wallData['owner_stories'][] = [
                'id' => $eventLoginUserStoriesList->id,
                'user_id' => $eventLoginUserStoriesList->user->id,
                'username' => "{$eventLoginUserStoriesList->user->firstname} {$eventLoginUserStoriesList->user->lastname}",
                'profile' => $eventLoginUserStoriesList->user->profile ? asset("storage/profile/{$eventLoginUserStoriesList->user->profile}") : "",
                'story' => $eventLoginUserStoriesList->user_event_story->map(function ($storyVal) use ($user) {
                    return [
                        'id' => $storyVal->id,
                        'storyurl' => $storyVal->story ? asset("storage/event_user_stories/{$storyVal->story}") : "",
                        'type' => $storyVal->type,
                        'post_time' => $this->setpostTime($storyVal->updated_at),
                        'is_seen' => UserSeenStory::where('user_id', $user->id)->where('user_event_story_id', $storyVal->id)->exists() ? "1" : "0",
                        'video_duration' => $storyVal->type == 'video' ? ($storyVal->duration ?? "") : "",
                        'created_at' => $storyVal->updated_at
                    ];
                })
            ];
        }
        $postList = [];
        // $selectedFilters = "";
        $eventCreatorId = Event::where('id', $event)->pluck('user_id')->first();
        $eventCreator = Event::where('id', $event)->first();
        $title = $eventCreator->event_name . ' wall';
        $eventPostList = EventPost::with(['user', 'post_image', 'event_post_poll.eventPollOptions'])
            ->withCount([
                'event_post_comment' => fn($query) => $query->whereNull('parent_comment_id'),
                'event_post_reaction'
            ])
            ->where('event_id', $event)
            ->where('is_in_photo_moudle', '0')
            ->whereDoesntHave('post_control', fn($query) => $query->where('user_id', $user->id)->where('post_control', 'hide_post'));

        $checkEventOwner = Event::where('id', $event)->where('user_id', $user->id)->exists();

        if (!$checkEventOwner) {
            $eventPostList->where(function ($query) use ($user, $event) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $event) {
                        $subQuery->whereHas('user', fn($userQuery) => $userQuery->where('app_user', '1'))
                            ->where('event_id', $event)
                            ->where('user_id', $user->id)
                            ->where(function ($privacyQuery) {
                                $privacyQuery->where(fn($q) => $q->where('rsvp_d', '1')->where('rsvp_status', '1')->where('post_privacy', '2'))
                                    ->orWhere(fn($q) => $q->where('rsvp_d', '1')->where('rsvp_status', '0')->where('post_privacy', '3'))
                                    ->orWhere(fn($q) => $q->where('rsvp_d', '0')->where('post_privacy', '4'))
                                    ->orWhere(fn($q) => $q->where('post_privacy', '1'));
                            });
                    });
            });
        }

        // Execute Query
        $eventPostList = $eventPostList->orderByDesc('id')->get();

        // Apply Selected Filters
        if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            $eventPostList = $eventPostList->filter(function ($post) use ($selectedFilters, $eventCreatorId) {
                foreach ($selectedFilters as $filter) {
                    switch ($filter) {
                        case 'host_update':
                            if ($post->user_id == $eventCreatorId) return true;
                            break;
                        case 'video_uploads':
                            if ($post->post_type == '1' && $post->post_image->contains('type', 'video')) return true;
                            break;
                        case 'photo_uploads':
                            if ($post->post_type == '1' && $post->post_image->contains('type', 'image')) return true;
                            break;
                        case 'polls':
                            if ($post->post_type == '2') return true;
                            break;
                        case 'comments':
                            if ($post->post_type == '0') return true;
                            break;
                    }
                }
                return false;
            });
        }

        foreach ($eventPostList as $value) {
            // Check RSVP status and event ownership only once
            $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
            $isEventOwner = $value->user->id === $user->id;

            // Check if post is controlled (e.g., hidden or muted)
            $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
            if ($postControl && $postControl->post_control == 'hide_post') {
                continue; // Skip hidden posts
            }

            // Get event invitation data (kids and adults count)
            $count_kids_adult = EventInvitedUser::where(['event_id' => $event, 'user_id' => $value->user->id])->first();

            // Process RSVP and user data
            if ($value->post_type == '4' && !empty($value->post_message)) {
                $EventPostMessageData = json_decode($value->post_message, true);
                $rsvpstatus = $EventPostMessageData['status'] ?? $checkUserRsvp;
                $kids = $EventPostMessageData['kids'] ?? 0;
                $adults = $EventPostMessageData['adults'] ?? 0;
            } else {
                $rsvpstatus = $checkUserRsvp;
                $kids = $count_kids_adult->kids ?? 0;
                $adults = $count_kids_adult->adults ?? 0;
            }

            // Build the post details array
            $postsNormalDetail = [
                'id' => $value->id,
                'user_id' => $value->user->id,
                'is_co_host' => EventInvitedUser::where(['event_id' => $eventCreator->id, 'user_id' => $value->user->id, 'is_co_host' => '1'])->exists() ? "1" : "0",
                'is_host' => $value->user->id == $eventCreator->user_id ? 1 : 0,
                'username' => $value->user->firstname . ' ' . $value->user->lastname,
                'profile' => asset('storage/profile/' . ($value->user->profile ?? '')),
                'post_message' => $value->post_type == '4' ? '' : $value->post_message,
                'rsvp_status' => (string)$rsvpstatus,
                'kids' => (int)$kids,
                'adults' => (int)$adults,
                'location' => trim($value->user->city . ($value->user->state ? ', ' . $value->user->state : '')),
                'post_type' => $value->post_type,
                'post_privacy' => $value->post_privacy,
                'created_at' => $value->created_at,
                'posttime' => setpostTime($value->created_at),
                'commenting_on_off' => $value->commenting_on_off,
                'post_image' => [],
                'total_poll_vote' => 0,
                'poll_duration' => '',
                'is_expired' => false,
                'poll_id' => 0,
                'poll_question' => '',
                'poll_option' => []
            ];

            // Check if the post type is poll
            if ($value->post_type == '2') {
                $poll = $value->event_post_poll->eventPollOptions;
                $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count ?? 0;
                $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                $postsNormalDetail['poll_duration'] = $pollDura;
                $postsNormalDetail['is_expired'] = empty($pollDura);
                $postsNormalDetail['poll_id'] = $polls->id;
                $postsNormalDetail['poll_question'] = $polls->poll_question;

                foreach ($polls->eventPollOptions as $optionValue) {
                    $optionData = [
                        'id' => $optionValue->id,
                        'option' => $optionValue->option,
                        'total_vote' => getOptionAllTotalVote($polls->id) ? round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%" : "0%",
                        'is_poll_selected' => checkUserGivePoll($user->id, $polls->id, $optionValue->id)
                    ];
                    $postsNormalDetail['poll_option'][] = $optionData;
                }
            }

            // Process post images
            if ($value->post_type == '1' && !empty($value->post_image)) {
                foreach ($value->post_image as $imgVal) {
                    $postMedia = [
                        'id' => $imgVal->id,
                        'media_url' => asset('storage/post_image/' . $imgVal->post_image),
                        'type' => $imgVal->type,
                        'thumbnail' => $imgVal->thumbnail ? asset('storage/thumbnails/' . $imgVal->thumbnail) : '',
                        'video_duration' => ($imgVal->type == 'video' && $imgVal->duration) ? $imgVal->duration : null
                    ];
                    $postsNormalDetail['post_image'][] = $postMedia;
                }
            }

            // Other post details
            $postsNormalDetail['post_recording'] = $value->post_recording ? asset('storage/event_post_recording/' . $value->post_recording) : "";
            $postsNormalDetail['reactionList'] = getOnlyReaction($value->id);
            $postsNormalDetail['total_comment'] = $value->event_post_comment_count;
            $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;
            $postsNormalDetail['is_reaction'] = $checkUserIsReaction ? '1' : '0';
            $postsNormalDetail['self_reaction'] = $checkUserIsReaction ? $checkUserIsReaction->reaction : "";
            $postsNormalDetail['is_owner_post'] = $value->user->id == $user->id ? 1 : 0;
            $postsNormalDetail['is_mute'] = $postControl && $postControl->post_control == 'mute' ? 1 : 0;

            $postCommentList = [];
            $postComment = getComments($value->id);

            // Pre-fetch necessary related data
            $likes = []; // Store likes data for comments and replies
            $mainParentIds = []; // Store main parent comment ids for replies

            foreach ($postComment as $commentVal) {
                $commentInfo = [
                    'id' => $commentVal->id,
                    'event_post_id' => $commentVal->event_post_id,
                    'comment' => $commentVal->comment_text,
                    'user_id' => $commentVal->user_id,
                    'username' => trim($commentVal->user->firstname . ' ' . $commentVal->user->lastname) ?: null,
                    'profile' => $commentVal->user->profile ? asset('storage/profile/' . $commentVal->user->profile) : "",
                    'location' => null, // Default value
                    'comment_total_likes' => $commentVal->post_comment_reaction_count,
                    'is_like' => checkUserIsLike($commentVal->id, $user->id),
                    'total_replies' => $commentVal->replies_count,
                    'created_at' => $commentVal->created_at,
                    'posttime' => setpostTime($commentVal->created_at),
                    'comment_replies' => [],
                ];

                // Concatenate city and state for location only if they exist
                $city = trim($commentVal->user->city ?? '');
                $state = trim($commentVal->user->state ?? '');
                if ($city || $state) {
                    $commentInfo['location'] = $city . ($state ? ', ' . $state : '');
                }

                // Load replies for the current comment
                foreach ($commentVal->replies as $reply) {
                    $mainParentId = $reply->main_parent_comment_id;
                    if (!isset($mainParentIds[$mainParentId])) {
                        $mainParentIds[$mainParentId] = EventPostComment::with('user')->withCount('post_comment_reaction', 'replies')->where('main_parent_comment_id', $mainParentId)->get();
                    }
                    $replyData = buildReplyData($reply, $user);
                    $commentInfo['comment_replies'][] = $replyData;
                }

                // Store comment information
                $postCommentList[] = $commentInfo;
            }

            $postsNormalDetail['post_comment'] = $postCommentList;
            $postList[] = $postsNormalDetail;
        }

        $eventDetail = Event::with([
            'user',
            'event_image' => function ($query) {
                $query->orderBy('type', 'ASC'); // Order event images by type
            },
            'event_schedule',
            'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party', 'event_wall', 'guest_list_visible_to_guests');
            },
            'event_invited_user' => function ($query) use ($user) {
                $query->where('is_co_host', '1')->with('user');
            },
            'event_invited_user.user' // To avoid multiple queries for the user's profile
        ])->where('id', $event)->first();

        $eventDetails = [
            'id' => $eventDetail->id,
            'event_name' => $eventDetail->event_name,
            'hosted_by' => $eventDetail->hosted_by,
            'event_date' => $eventDetail->start_date,
            'event_time' => $eventDetail->rsvp_start_time,
            'end_date' => $eventDetail->end_date,
            'end_time' => $eventDetail->rsvp_end_time,
            'event_location_name' => $eventDetail->event_location_name,
            'address_1' => $eventDetail->address_1,
            'address_2' => $eventDetail->address_2,
            'state' => $eventDetail->state,
            'zip_code' => $eventDetail->zip_code,
            'city' => $eventDetail->city,
            'latitude' => $eventDetail->latitude ?? '',
            'longitude' => $eventDetail->longitude ?? '',
            'guest_list_visible_to_guests' => $eventDetail->event_settings->guest_list_visible_to_guests ?? '',
            'message_to_guests' => $eventDetail->message_to_guests,
            'is_host' => ($eventDetail->user_id == $user->id) ? 1 : 0,
            'is_co_host' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'user_id' => $user->id, 'is_co_host' => '1'])->exists() ? 1 : 0,
            'podluck' => $eventDetail->event_settings->podluck ?? "",
            'event_wall' => $eventDetail->event_settings->event_wall ?? "",
            'total_limit' => $eventDetail->event_settings->allow_limit ?? 0,
            'allow_limit' => $eventDetail->event_settings->allow_limit ?? 0,
            'adult_only_party' => $eventDetail->event_settings->adult_only_party ?? 0,
            'event_created_timestamp' => Carbon::parse($eventDetail->start_date)->timestamp,
            'event_detail' => [],
        ];

        // Event Image URLs
        $eventDetails['event_images'] = $eventDetail->event_image->pluck('image')->map(fn($image) => asset('storage/event_images/' . $image))->toArray();

        // RSVP Status
        $rsvpStatus = EventInvitedUser::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->first();
        $eventDetails['rsvp_status'] = $rsvpStatus ? ($rsvpStatus->rsvp_status == '1' ? '1' : '0') : '';

        // Days till Event Calculation
        $eventDate = Carbon::parse($eventDetail->start_date);
        $currentDate = Carbon::now();
        $tillDays = $eventDate->diffInDays($currentDate);

        if ($eventDate >= $currentDate) {
            $tillDays = $tillDays == 0 ? "Today" : ($tillDays == 1 ? "Tomorrow" : $tillDays);
        } else {
            $eventDetails['is_past'] = $eventDetail->end_date < $currentDate ? true : false;
            $tillDays = "Ongoing";
        }
        $eventDetails['days_till_event'] = $tillDays;

        // Event Schedule
        $eventDetails['event_schedule'] = $eventDetail->event_schedule->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'activity_title' => $schedule->type == '1' ? "Start Event" : ($schedule->type == '3' ? "End Event" : $schedule->activity_title),
                'start_time' => $schedule->start_time ?? '',
                'end_time' => $schedule->end_time ?? '',
                'type' => $schedule->type
            ];
        })->toArray();

        // Co-hosts Info
        $eventDetails['co_hosts'] = $eventDetail->event_invited_user->map(function ($host) {
            $user = $host->user;
            return [
                'id' => $user->id,
                'profile' => asset('storage/profile/' . ($user->profile ?? '')),
                'name' => trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')),
                'email' => $user->email ?? '',
                'phone_number' => $user->phone_number ?? '',
            ];
        })->toArray();

        // Gift Registry
        $eventDetails['gift_registry'] = [];
        if (!empty($eventDetail->gift_registry_id)) {
            $giftRegistryData = EventGiftRegistry::whereIn('id', explode(',', $eventDetail->gift_registry_id))->get();
            $eventDetails['gift_registry'] = $giftRegistryData->map(function ($registry) {
                return [
                    'id' => $registry->id,
                    'registry_recipient_name' => $registry->registry_recipient_name,
                    'registry_link' => $registry->registry_link
                ];
            })->toArray();
        }

        // Event About Host
        $totalInvitedUser = EventInvitedUser::where(['event_id' => $eventDetail->id])->count();
        $eventAboutHost = [
            'attending' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('adults') + EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('kids'),
            'adults' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('adults'),
            'kids' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('kids'),
            'not_attending' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '0'])->count(),
            'pending' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_d' => '0'])->count(),
            'comment' => EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count(),
            'photo_uploaded' => EventPostImage::where(['event_id' => $eventDetail->id])->count(),
            'total_invite' => $totalInvitedUser,
            'rsvp_rate' => EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->count(),
            'rsvp_rate_percent' => $totalInvitedUser > 0 ? EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->count() / $totalInvitedUser * 100 . "%" : "0%",
            'today_upstick' => $totalInvitedUser > 0 ? EventInvitedUser::where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->whereDate('created_at', Carbon::today())->count() / $totalInvitedUser * 100 . "%" : "0%"
        ];

        $eventInfo = [
            'guest_view' => $eventDetails,
            'host_view' => $eventAboutHost
        ];
        Session::forget('filterSession');
        return view('layout', compact(
            'title',
            'page',
            'users',
            'event',
            'eventInfo',
            'eventDetails',
            'storiesList',
            'wallData',
            'postList',
            'encrypt_event_id',
            'current_page',
            'rsvpSent',
            'login_user_id',
            'js'
        ));
    }

    function buildReplyData($reply, $user)
    {
        $replyInfo = [
            'id' => $reply->id,
            'event_post_id' => $reply->event_post_id,
            'main_comment_id' => $reply->main_parent_comment_id,
            'comment' => $reply->comment_text,
            'user_id' => $reply->user_id,
            'username' => trim($reply->user->firstname . ' ' . $reply->user->lastname) ?: null,
            'profile' => $reply->user->profile ? asset('storage/profile/' . $reply->user->profile) : "",
            'location' => null, // Default value
            'comment_total_likes' => $reply->post_comment_reaction_count,
            'is_like' => checkUserIsLike($reply->id, $user->id),
            'total_replies' => $reply->replies_count,
            'created_at' => $reply->created_at,
            'posttime' => setpostTime($reply->created_at),
        ];

        // Concatenate city and state for location only if they exist
        $city = trim($reply->user->city ?? '');
        $state = trim($reply->user->state ?? '');
        if ($city || $state) {
            $replyInfo['location'] = $city . ($state ? ', ' . $state : '');
        }

        // Load child replies if necessary (up to a reasonable level of recursion)
        $replyInfo['comment_replies'] = [];
        $childReplies = getChildReplies($reply->id, $reply->event_post_id);
        foreach ($childReplies as $childReply) {
            $childReplyData = buildReplyData($childReply, $user);
            $replyInfo['comment_replies'][] = $childReplyData;
        }

        return $replyInfo;
    }

    function getChildReplies($parentId, $postId)
    {
        return EventPostComment::with('user')->withCount('post_comment_reaction', 'replies')
            ->where('parent_comment_id', $parentId)
            ->where('event_post_id', $postId)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function index(String $id)
    {
        $title = 'event wall';
        $user  = Auth::guard('web')->user();
        $js = ['event_wall', 'post_like_comment', 'guest_rsvp', 'guest'];

        $event = decrypt($id);
        $encrypt_event_id = $id;
        $page = 'front.event_wall.event_wall';
        $selectedFilters = [];
        // $selectedFilters = (session('filterSession')=="")?[]:$selectedFilters ;
        $selectedFilters = empty(session('filterSession')) ? [] : session('filterSession');
        $is_filter_applied = "0";
        if (!empty(session('filterSession'))) {
            $is_filter_applied = "1";
        }
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }

        $users = User::withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment',
            ]
        )->findOrFail($user->id);
        $users['events'] =   Event::where(['user_id' => $users->id, 'is_draft_save' => '0'])->count();
        $users['profile'] = ($users->profile != null) ? asset('storage/profile/' . $users->profile) : "";
        $users['bg_profile'] = ($users->bg_profile != null) ? asset('storage/bg_profile/' . $users->bg_profile) : asset('assets/front/image/Frame 1000005835.png');

        $currentDateTime = Carbon::now();


        $eventStoriesLists = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', now()->subHours(24));
        }])
            ->where('created_at', '>', now()->subHours(24))
            ->where('event_id', $event)
            ->where('user_id', '!=', $user->id)->get();
        // }dd
        // dd($eventStoriesLists);
        $storiesList = [];
        if (!empty($eventStoriesLists)) {
            foreach ($eventStoriesLists as $value) {
                $storiesDetaill['id'] =  $value->id;
                $storiesDetaill['user_id'] =  $value->user->id;
                $storiesDetaill['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                $storiesDetaill['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $storyAlldata = [];
                foreach ($value->user_event_story as $storyVal) {
                    $storiesData['id'] = $storyVal->id;
                    $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story);
                    $storiesData['type'] = $storyVal->type;
                    $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                    $checkISeen = UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $storyVal->id])->count();
                    $storiesData['is_seen'] = ($checkISeen != 0) ? "1" : "0";
                    if ($storyVal->type == 'video') {
                        $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                    }
                    $storiesData['created_at'] =  $storyVal->created_at;
                    $storyAlldata[] = $storiesData;
                }
                $storiesDetaill['story'] = $storyAlldata;
                $storiesList[] = $storiesDetaill;
            }
        }


        $polls = EventPostPoll::with('event_poll_option')
            ->withCount('user_poll_data')
            ->where('event_id', $event)
            // ->whereDoesntHave('post_control', function ($query) use ($user) {
            //     $query->where('user_id', $user->id)
            //         ->where('post_control', 'hide_post');
            // })
            ->orderBy('id', 'desc')  // Sorting by created_at in descending order
            ->get();



        $wallData['owner_stories'] = [];

        $eventLoginUserStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            $query->where('created_at', '>', now()->subHours(24));
        }])
            ->where(['event_id' => $event, 'user_id' => $user->id])
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if ($eventLoginUserStoriesList != null) {
            $storiesDetaill['id'] =  $eventLoginUserStoriesList->id;
            $storiesDetaill['user_id'] =  $eventLoginUserStoriesList->user->id;
            $storiesDetaill['username'] =  $eventLoginUserStoriesList->user->firstname . ' ' . $eventLoginUserStoriesList->user->lastname;
            $storiesDetaill['profile'] =  empty($eventLoginUserStoriesList->user->profile) ? "" : asset('storage/profile/' . $eventLoginUserStoriesList->user->profile);
            $storiesDetaill['story'] = [];
            foreach ($eventLoginUserStoriesList->user_event_story as $storyVal) {
                $storiesData['id'] = $storyVal->id;
                $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story);
                $storiesData['type'] = $storyVal->type;
                $storiesData['post_time'] =  $this->setpostTime($storyVal->updated_at);
                $checkISeen = UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $storyVal->id])->count();
                $storiesData['is_seen'] = ($checkISeen != 0) ? "1" : "0";
                if ($storyVal->type == 'video') {
                    $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                }
                $storiesData['created_at'] = $storyVal->updated_at;
                $storiesDetaill['story'][] = $storiesData;
            }
            $wallData['owner_stories'][] = $storiesDetaill;
        }
        ///postlist
        $postList = [];
        $eventCreator = Event::where('id', $event)->first();
        $title = $eventCreator->event_name . ' wall';
        // DB::enableQueryLog();
        $eventPostList = EventPost::query();
        $eventPostList->with(['user', 'post_image'])
            ->withCount([
                'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                },
                'event_post_reaction'
            ])
            ->where([
                'event_id' => $event,
                'is_in_photo_moudle' => '0'
            ])
            ->whereDoesntHave('post_control', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('post_control', 'hide_post');
            });
        $checkEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
        // dd($checkEventOwner);
        if ($checkEventOwner == null) {
            // dd(1);
            $eventPostList = $eventPostList->where(function ($query) use ($user, $event) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $event) {
                        $subQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('app_user', '1'); // Only app users
                        })
                            ->where('event_id', $event)
                            ->where('user_id', $user->id)
                            ->where(function ($privacyQuery) {
                                // Various privacy conditions
                                $privacyQuery->where(function ($q) {
                                    $q->where('rsvp_d', '1')
                                        ->where('rsvp_status', '1')
                                        ->where('post_privacy', '2');
                                })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '1')
                                            ->where('rsvp_status', '0')
                                            ->where('post_privacy', '3');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '0')
                                            ->where('post_privacy', '4');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('post_privacy', '1');
                                    });
                            });
                    });
            });
        }

        // Execute the query and get the results

        // dd(DB::getQueryLog());
        if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            $eventPostList->where(function ($query) use ($selectedFilters, $eventCreator) {
                foreach ($selectedFilters as $filter) {

                    switch ($filter) {
                        case 'host_update':
                            $query->orWhere('user_id', $eventCreator->user_id);
                            break;
                        case 'video_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'video');
                                    });
                            });
                            break;
                        case 'photo_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'image');
                                    });
                            });
                            break;
                        case 'polls':
                            $query->orWhere('post_type', '2');
                            break;
                        case 'comments':
                            $query->orWhere('post_type', '0');
                            break;
                            // Add more cases for other filters if needed
                    }
                }
            });
        }
        Session::forget('filterSession');
        $eventPostList = $eventPostList->orderBy('id', 'DESC')->get();
        if ($eventPostList != "") {
            //dd($eventPostList);
            foreach ($eventPostList as  $value) {
                if (!$value->user) {
                    continue;
                }
                $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
                // dd($postControl);
                $count_kids_adult = EventInvitedUser::where(['event_id' => $event, 'user_id' => $value->user->id])
                    ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                    ->first();
                if ($postControl != null) {
                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $checkUserIsReaction = EventPostReaction::where(['event_id' => $event, 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                if (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') {
                    $EventPostMessageData = json_decode($value->post_message, true);
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $kids = '0';
                    $adults = '0';
                    if (isset($EventPostMessageData['status'])) {
                        $rsvpstatus = (int)$EventPostMessageData['status'];
                    }
                    if (isset($EventPostMessageData['kids'])) {
                        $kids = (int)$EventPostMessageData["kids"];
                    }
                    if (isset($EventPostMessageData['adults'])) {
                        $adults = (int)$EventPostMessageData["adults"];
                    }
                } else {
                    $kids = isset($count_kids_adult['kids']) ? $count_kids_adult['kids'] : 0;
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $adults = isset($count_kids_adult['adults']) ? $count_kids_adult['adults'] : 0;
                }
                $postsNormalDetail['id'] =  $value->id;
                $postsNormalDetail['user_id'] =  $value->user->id;
                // $postsNormalDetail['is_host'] =  ($value->user->id == $user->id) ? 1 : 0;
                $isCoHost =  EventInvitedUser::where(['event_id' => $eventCreator->id, 'user_id' => $value->user->id, 'is_co_host' => '1'])->first();
                $postsNormalDetail['is_co_host'] = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";
                $postsNormalDetail['is_host'] =  ($value->user->id == $eventCreator->user_id) ? 1 : 0;
                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus ?? "";
                $postsNormalDetail['kids'] = (int)$kids;
                $postsNormalDetail['adults'] = (int)$adults;
                $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) . ($value->user->state != "" ? ', ' . $value->user->state : '') : "";
                $postsNormalDetail['post_type'] = $value->post_type;
                $postsNormalDetail['post_privacy'] = $value->post_privacy;
                $postsNormalDetail['created_at'] = $value->created_at;
                $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                $postsNormalDetail['commenting_on_off'] = $value->commenting_on_off;
                $postsNormalDetail['post_image'] = [];
                $totalEvent =  Event::where('user_id', $value->user->id)->count();
                $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                $postsNormalDetail['user_profile'] = [
                    'id' => $value->user->id,
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
                    'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                    'username' => $value->user->firstname . ' ' . $value->user->lastname,
                    'location' => ($value->user->city != NULL) ? $value->user->city : "",
                    'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                    'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                    'total_events' => $totalEvent,
                    'visible' => $value->user->visible,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'message_privacy' => $value->user->message_privacy
                ];

                if ($value->post_type == '1' && !empty($value->post_image)) {
                    foreach ($value->post_image as $imgVal) {
                        $postMedia = [
                            'id' => $imgVal->id,
                            'media_url' => asset('storage/post_image/' . $imgVal->post_image),
                            'type' => $imgVal->type,
                            'thumbnail' => (isset($imgVal->thumbnail) && $imgVal->thumbnail != null) ?  asset('storage/thumbnails/' . $imgVal->thumbnail) : '',
                        ];
                        if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                            $postMedia['video_duration'] = $imgVal->duration;
                        } else {
                            unset($postMedia['video_duration']);
                        }
                        $postsNormalDetail['post_image'][] = $postMedia;
                    }
                }
                $postsNormalDetail['total_poll_vote'] = 0;
                $postsNormalDetail['poll_duration'] = "";
                $postsNormalDetail['is_expired'] = false;
                $postsNormalDetail['poll_id'] = 0;
                $postsNormalDetail['poll_question'] = "";
                $postsNormalDetail['poll_option'] = [];
                if ($value->post_type == '2') {
                    // Poll
                    $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event, 'event_post_id' => $value->id])->first();
                    $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                    $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                    $postsNormalDetail['poll_duration'] = $pollDura;
                    $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                    $postsNormalDetail['poll_id'] = $polls->id;
                    $postsNormalDetail['poll_question'] = $polls->poll_question;
                    $postsNormalDetail['total_poll_duration'] = $polls->poll_duration;

                    foreach ($polls->event_poll_option as $optionValue) {
                        $optionData['id'] = $optionValue->id;
                        $optionData['option'] = $optionValue->option;
                        $optionData['total_vote'] =  "0%";
                        if (getOptionAllTotalVote($polls->id) != 0) {
                            $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                        }
                        $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);
                        $postsNormalDetail['poll_option'][] = $optionData;
                    }
                }
                $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);
                $reactionList = getOnlyReaction($value->id);
                $postsNormalDetail['reactionList'] = $reactionList;
                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;
                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;
                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';
                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";
                $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                $postsNormalDetail['is_mute'] =  0;
                if ($postControl != null) {
                    if ($postControl->post_control == 'mute') {
                        $postsNormalDetail['is_mute'] =  1;
                    }
                }
                $postCommentList = [];

                $postComment = getComments($value->id);
                foreach ($postComment as $commentVal) {
                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_id'] = $commentVal->event_post_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    // $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;
                    $firstName = $commentVal->user->firstname ?? '';
                    $lastName = $commentVal->user->lastname ?? '';

                    // Concatenate only if at least one value exists
                    $commentInfo['username'] = trim($firstName . ' ' . $lastName) ?: null;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('storage/profile/' . $commentVal->user->profile) : "";
                    // $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) .($value->user->state != "" ? ', ' . $value->user->state : ''): "";
                    // $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
                    // $commentInfo['location'] = $commentVal->user->city != "" ? trim($commentVal->user->city) . ($commentVal->user->state != "" ? ', ' . $commentVal->user->state : '') : "";
                    $commentInfo['location'] = null; // Default value

                    if (!empty($commentVal->user)) {
                        $city = trim($commentVal->user->city ?? '');
                        $state = trim($commentVal->user->state ?? '');

                        $commentInfo['location'] = ($city || $state) ? ($city . ($state ? ', ' . $state : '')) : null;
                    }


                    $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                    $commentInfo['total_replies'] = $commentVal->replies_count;

                    $commentInfo['created_at'] = $commentVal->created_at;
                    $commentInfo['posttime'] = setpostTime($commentVal->created_at);

                    $commentInfo['comment_replies'] = [];

                    foreach ($commentVal->replies as $reply) {
                        $mainParentId = (new EventPostComment())->getMainParentId($reply->parent_comment_id);

                        $replyCommentInfo['id'] = $reply->id;

                        $replyCommentInfo['event_post_id'] = $reply->event_post_id;
                        $replyCommentInfo['main_comment_id'] = $reply->main_parent_comment_id;

                        $replyCommentInfo['comment'] = $reply->comment_text;

                        $replyCommentInfo['user_id'] = $reply->user_id;
                        $firstName = $reply->user->firstname ?? '';
                        $lastName = $reply->user->lastname ?? '';

                        // Concatenate only if at least one value exists
                        $replyCommentInfo['username'] = trim($firstName . ' ' . $lastName) ?: null;
                        // $replyCommentInfo['username'] = $reply->user->firstname . ' ' . $reply->user->lastname;

                        $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('storage/profile/' . $reply->user->profile) : "";

                        // $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
                        // $replyCommentInfo['location'] =  $reply->user->city != "" ? trim($reply->user->city) . ($reply->user->state != "" ? ', ' . $reply->user->state : '') : "";
                        $replyCommentInfo['location']  = null; // Default value

                        if (!empty($reply->user)) {
                            $city = trim($reply->user->city ?? '');
                            $state = trim($reply->user->state ?? '');

                            $replyCommentInfo['location'] = ($city || $state) ? ($city . ($state ? ', ' . $state : '')) : null;
                        }
                        // $replyCommentInfo['comment_total_likes'] = $reply->post_comment_reaction_count;

                        $replyCommentInfo['is_like'] = checkUserIsLike($reply->id, $user->id);

                        $replyCommentInfo['total_replies'] = $reply->replies_count;

                        $replyCommentInfo['created_at'] = $reply->created_at;
                        $replyCommentInfo['posttime'] = setpostTime($reply->created_at);
                        $commentInfo['comment_replies'][] = $replyCommentInfo;


                        $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $reply->event_post_id, 'parent_comment_id' => $reply->id])->orderBy('id', 'DESC')->get();

                        foreach ($replyComment as $childReplyVal) {

                            if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {

                                $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();


                                $commentChildReply['id'] = $childReplyVal->id;

                                $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;
                                $commentChildReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                                $commentChildReply['comment'] = $childReplyVal->comment_text;
                                $commentChildReply['user_id'] = $childReplyVal->user_id;

                                $commentChildReply['username'] = (!empty($childReplyVal->user)) ? ($childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname) : "";

                                $commentChildReply['profile'] = (!empty($childReplyVal->user) && $childReplyVal->user->profile != '') ? asset('storage/profile/' . $childReplyVal->user->profile) : "";
                                $commentChildReply['location'] = (!empty($childReplyVal->user)) ? $childReplyVal->user->city : "";

                                $commentChildReply['comment_total_likes'] = ($childReplyVal->post_comment_reaction_count != "") ? $childReplyVal->post_comment_reaction_count : "0";

                                $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);

                                $commentChildReply['total_replies'] = $totalReply;
                                $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                                $commentChildReply['created_at'] = $childReplyVal->created_at;

                                $commentInfo['comment_replies'][] = $commentChildReply;

                                $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $childReplyVal->event_post_id, 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                                foreach ($replyChildComment as $childInReplyVal) {

                                    if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {

                                        $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();


                                        $commentChildInReply['id'] = $childInReplyVal->id;

                                        $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;
                                        $commentChildInReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                        $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                        $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                        $commentChildInReply['username'] =   (!empty($childInReplyVal->user)) ? ($childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname) : "";

                                        $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('storage/profile/' . $childInReplyVal->user->profile) : "";
                                        $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";

                                        // $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;
                                        $commentChildInReply['comment_total_likes'] = ($childInReplyVal->post_comment_reaction_count != "") ? $childInReplyVal->post_comment_reaction_count : "0";

                                        $commentChildInReply['is_like'] = checkUserIsLike($childInReplyVal->id, $user->id);

                                        $commentChildInReply['total_replies'] = $totalReply;
                                        $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                        $commentChildInReply['created_at'] = $childInReplyVal->created_at;

                                        $commentInfo['comment_replies'][] = $commentChildInReply;
                                    }
                                }
                            }
                        }
                    }


                    $postCommentList[] = $commentInfo;
                }

                $postsNormalDetail['post_comment'] = $postCommentList;
                $postList[] = $postsNormalDetail;
            }
            // dd($postList);
        }
        //}

        $eventDetail = Event::with([
            'user',
            'event_image' => function ($query) {
                $query->orderBy('type', 'ASC');  // Ordering event images by 'type' in ascending order
            },
            'event_schedule',
            'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party', 'event_wall', 'guest_list_visible_to_guests');
            },
            'event_invited_user' => function ($query) {
                $query->where('is_co_host', '1')->with('user');
            }
        ])->where('id', $event)->first();

        $guestView = [];
        $eventDetails['id'] = $eventDetail->id;
        $eventDetails['event_images'] = [];
        if (count($eventDetail->event_image) != 0) {
            foreach ($eventDetail->event_image as $values) {
                $eventDetails['event_images'][] = asset('storage/event_images/' . $values->image);
            }
        }
        $event_comments = EventPostComment::where(['event_id' => $eventDetail->id])->count();

        $eventDetails['user_profile'] = empty($eventDetail->user->profile) ? "" : asset('storage/profile/' . $eventDetail->user->profile);
        $eventDetails['event_name'] = $eventDetail->event_name;
        $eventDetails['hosted_by'] = $eventDetail->hosted_by;
        $eventDetails['total_event_comments'] = $event_comments;
        $eventDetails['is_host'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
        $isCoHost =  EventInvitedUser::where(['event_id' => $eventDetail->id, 'user_id' => $user->id, 'is_co_host' => '1'])->first();
        $eventDetails['is_co_host'] = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";
        $eventDetails['podluck'] = $eventDetail->event_settings->podluck ?? "";
        $eventDetails['event_wall'] = $eventDetail->event_settings->event_wall ?? "";
        $eventDetails[' guest_list_visible_to_guests'] = $eventDetail->event_settings->guest_list_visible_to_guests ?? "";
        $rsvp_status = "";
        $checkUserrsvp = EventInvitedUser::whereHas('user', function ($query) {
            // $query->where('app_user', '1');
        })->where(['user_id' => $user->id, 'event_id' => $event])->first();
        // dd($checkUserrsvp);
        // if ($value->rsvp_by_date >= date('Y-m-d')) {

        if ($checkUserrsvp != null) {

            if ($checkUserrsvp->rsvp_status == '1') {
                $rsvp_status = '1'; // rsvp you'r going
            } else if ($checkUserrsvp->rsvp_status == '0') {
                $rsvp_status = '0'; // rsvp you'r not going
            }
        }
        $eventDetails['rsvp_status'] = $rsvp_status;
        $eventDetails['allow_limit'] = $eventDetail->event_settings->allow_limit ?? 0;
        $eventDetails['adult_only_party'] = $eventDetail->event_settings->adult_only_party ?? 0;
        $eventDetails['event_date'] = $eventDetail->start_date;
        $eventDetails['event_time'] = $eventDetail->rsvp_start_time;
        $eventDetails['end_date'] = $eventDetail->end_date;
        $eventDetails['end_time'] = $eventDetail->rsvp_end_time;
        // if ($eventDetail->event_schedule->isNotEmpty()) {

        //     $eventDetails['event_time'] = $eventDetail->event_schedule->first()->start_time . ' to ' . $eventDetail->event_schedule->last()->end_time;
        // }
        $eventDetails['rsvp_by'] = (!empty($eventDetail->rsvp_by_date) || $eventDetail->rsvp_by_date != NULL) ? $eventDetail->rsvp_by_date : date('Y-m-d', strtotime($eventDetail->created_at));
        $current_date = date('Y-m-d');
        $eventDate = $eventDetail->start_date;
        $datetime1 = Carbon::parse($eventDate);
        $datetime2 =  Carbon::parse($current_date);
        $till_days = strval($datetime1->diff($datetime2)->days);

        if ($eventDate >= $current_date) {
            if ($till_days == 0) {
                $till_days = "Today";
            }
            if ($till_days == 1) {
                $till_days = "Tomorrow";
            }
        } else {
            $eventEndDate = $eventDetail->end_date;
            $till_days = "On going";
            if ($eventEndDate < $current_date) {
                $till_days = "Past";
            }
        }
        $eventDetail['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;
        $eventDetails['days_till_event'] = $till_days;
        $eventDetails['event_created_timestamp'] = Carbon::parse($eventDate)->timestamp;
        $eventDetails['message_to_guests'] = $eventDetail->message_to_guests;

        $coHosts = [];
        foreach ($eventDetail->event_invited_user as $hostValues) {
            $coHostDetail['id'] = $hostValues->user_id;
            $coHostDetail['profile'] = (empty($hostValues->user->profile) || $hostValues->user->profile == NULL) ? "" : asset('storage/profile/' . $hostValues->user->profile);
            $fullName = trim(($hostValues->user->firstname ?? '') . ' ' . ($hostValues->user->lastname ?? ''));
            $coHostDetail['name'] = !empty($fullName) ? $fullName : null;

            $coHostDetail['email'] = (empty($hostValues->user->email) || $hostValues->user->email == NULL) ? "" : $hostValues->user->email;
            $coHostDetail['phone_number'] = (empty($hostValues->user->phone_number) || $hostValues->user->phone_number == NULL) ? "" : $hostValues->user->phone_number;
            $coHosts[] = $coHostDetail;
        }
        $eventDetails['co_hosts'] = $coHosts;
        $eventDetails['event_location_name'] = $eventDetail->event_location_name;
        $eventDetails['address_1'] = $eventDetail->address_1;
        $eventDetails['address_2'] = $eventDetail->address_2;
        $eventDetails['state'] = $eventDetail->state;
        $eventDetails['zip_code'] = $eventDetail->zip_code;
        $eventDetails['city'] = $eventDetail->city;
        $eventDetails['latitude'] = (!empty($eventDetail->latitude) || $eventDetail->latitude != null) ? $eventDetail->latitude : "";
        $eventDetails['logitude'] = (!empty($eventDetail->logitude) || $eventDetail->logitude != null) ? $eventDetail->logitude : "";

        $eventsScheduleList = [];
        foreach ($eventDetail->event_schedule as $key => $value) {
            $event_name =  $value->activity_title;
            if ($value->type == '1') {
                $event_name = "Start Event";
            } elseif ($value->type == '3') {
                $event_name = "End Event";
            }
            $scheduleDetail['id'] = $value->id;
            $scheduleDetail['activity_title'] = $event_name;
            $scheduleDetail['start_time'] = ($value->start_time != null) ? $value->start_time : "";
            $scheduleDetail['end_time'] = ($value->end_time != null) ? $value->end_time : "";
            $scheduleDetail['type'] = $value->type;
            $eventsScheduleList[] = $scheduleDetail;
        }
        $eventDetails['event_schedule'] = $eventsScheduleList;

        $eventDetails['gift_registry'] = [];
        if (!empty($eventDetail->gift_registry_id)) {
            $giftregistry = explode(',', $eventDetail->gift_registry_id);
            $giftregistryData = EventGiftRegistry::whereIn('id', $giftregistry)->get();
            foreach ($giftregistryData as $value) {
                $giftRegistryDetail['id'] = $value->id;
                $giftRegistryDetail['registry_recipient_name'] = $value->registry_recipient_name;
                $giftRegistryDetail['registry_link'] = $value->registry_link;
                $eventDetails['gift_registry'][] = $giftRegistryDetail;
            }
        }
        $eventDetails['event_detail'] = "";
        if ($eventDetail->event_settings) {
            $eventData = [];
            if ($eventDetail->event_settings->allow_for_1_more == '1') {
                $eventData[] = "Can Bring Guests ( limit " . $eventDetail->event_settings->allow_limit . ")";
            }
            if ($eventDetail->event_settings->adult_only_party == '1') {
                $eventData[] = "Adults Only";
            }
            if ($eventDetail->rsvp_by_date_set == '1') {
                $eventData[] = date('F d, Y', strtotime($eventDetail->rsvp_by_date));
            }
            if ($eventDetail->event_settings->podluck == '1') {
                $eventData[] = "Event Potluck";
            }
            if ($eventDetail->event_settings->gift_registry == '1') {
                $eventData[] = "Gift Registry";
            }
            if ($eventDetail->event_settings->events_schedule == '1') {
                $eventData[] = "Event has Schedule";
            }
            if ($eventDetail->start_date != $eventDetail->end_date) {
                $eventData[] = "Multiple Day Event";
            }
            if (!empty($eventData) || empty($eventData)) {
                $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                // $guestData = EventInvitedUser::with('user') // Eager load the related 'user' model
                //     ->where(['event_id'=>$eventDetail->id,'is_co_host'=>"0"])
                //     // ->where('user_id', '!=', $user->id)
                //     ->get();
                $guestData = getInvitedUsersListNew($eventDetail->id);


                $eventData[] = "Number of guests : " . $numberOfGuest;
                $eventData['guests'] = $guestData;
            }
            $eventDetails['event_detail'] = $eventData;
        }
        $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit ?? 0;
        $eventInfo['guest_view'] = $eventDetails;
        $totalEnvitedUser = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $eventDetail->id])->count();

        $eventattending = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->count();

        $eventNotComing = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['rsvp_d' => '1', 'rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();



        $todayrsvprate = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])

            ->whereDate('created_at', '=', date('Y-m-d'))

            ->count();



        $pendingUser = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $eventDetail->id, 'rsvp_d' => '0'])->count();



        $adults = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('adults');

        $kids = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1'])->sum('kids');


        $eventAboutHost['attending'] = $adults + $kids;



        $eventAboutHost['adults'] = (int)$adults;

        $eventAboutHost['kids'] = (int)$kids;



        $eventAboutHost['not_attending'] = $eventNotComing;

        $eventAboutHost['pending'] = $pendingUser;

        $eventAboutHost['comment'] = EventPostComment::where(['event_id' => $eventDetail->id, 'user_id' => $user->id])->count();
        $total_photos = EventPostImage::where(['event_id' => $eventDetail->id])->count();

        $eventAboutHost['photo_uploaded'] = $total_photos;

        $eventAboutHost['total_invite'] =  count(getEventInvitedUser($event));

        $eventAboutHost['invite_view_rate'] = EventInvitedUser::whereHas('user', function ($query) {

            $query->where('app_user', '1');
        })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count();

        $invite_view_percent = 0;
        if ($totalEnvitedUser != 0) {

            $invite_view_percent = EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count() / $totalEnvitedUser * 100;
        }

        $eventAboutHost['invite_view_percent'] = round($invite_view_percent, 2) . "%";

        $today_invite_view_percent = 0;
        if ($totalEnvitedUser != 0) {
            $today_invite_view_percent =   EventInvitedUser::whereHas('user', function ($query) {

                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100;
        }

        $eventAboutHost['today_invite_view_percent'] = round($today_invite_view_percent, 2)  . "%";

        $eventAboutHost['rsvp_rate'] = $eventattending;

        $eventAboutHost['rsvp_rate_percent'] = ($totalEnvitedUser != 0) ? $eventattending / $totalEnvitedUser * 100 . "%" : 0 . "%";

        $eventAboutHost['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";

        $eventInfo['host_view'] = $eventAboutHost;
        $rsvpSent = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where(['user_id' => $user->id, 'event_id' => $event])->first();
        $current_page = "wall";
        $login_user_id  = $user->id;
        Session::forget('filterSession');

        // return $wallData;
        return view('layout', compact(
            'title',
            'page',
            'users',
            'event',
            'eventInfo',
            'eventDetails',
            'storiesList',
            'wallData',
            'postList',
            'encrypt_event_id',
            'current_page',
            'eventDetails',
            'rsvpSent',
            'login_user_id',
            'js',
            'selectedFilters',
            'is_filter_applied'

        ));
    }

    public function createPost(Request $request)
    {
        // dd($request->all());
        $user  = Auth::guard('web')->user();

        $creatEventPost = new EventPost;
        $creatEventPost->event_id = $request->event_id;
        $creatEventPost->user_id = $user->id;
        $creatEventPost->post_message = $request->postContent;
        $creatEventPost->post_type = $request->post_type;
        // if ($request->hasFile('post_recording')) {
        //     $record = $request->post_recording;
        //     $recordingName = time() . '_' . $record->getClientOriginalName();
        //     $record->move(public_path('storage/event_post_recording'), $recordingName);
        //     $creatEventPost->post_recording = $recordingName;
        // }
        $creatEventPost->post_privacy = $request->post_privacys;
        // $creatEventPost->post_message = $request->input('content');
        $creatEventPost->commenting_on_off = $request->commenting_on_off;
        $creatEventPost->is_in_photo_moudle = "0";
        $creatEventPost->save();

        if ($creatEventPost->id  && $request->file('files')) {
            $postimages = $request->file('files');

            $video = 0;
            $image = 0;

            // dd($postimages);
            foreach ($postimages as $key => $postImage) {

                $imageName = time() . $key . '_' . $postImage->getClientOriginalName();


                $postImage->move(public_path('storage/post_image'), $imageName);

                $checkIsimageOrVideo = checkIsimageOrVideo($postImage);
                $duration = "";
                $thumbName = "";

                if ($checkIsimageOrVideo == 'video') {
                    $duration = getVideoDuration($postImage);
                    $thumbName = generate_thumbnail($imageName);
                    // if (isset($request->thumbnail) && $request->thumbnail != Null) {
                    //     $thumbimage = $request->thumbnail[$key];
                    //     $thumbName = time() . $key . '_' . $thumbimage->getClientOriginalName();
                    //     // $checkIsimageOrVideo = checkIsimageOrVideo($thumbimage);
                    //     $thumbimage->move(public_path('storage/thumbnails'), $thumbName);
                    // }
                    // if (file_exists(public_path('storage/post_image/') . $imageName)) {
                    //     $imagePath = public_path('storage/post_image/') . $imageName;
                    //     unlink($imagePath);
                    // }
                    // $postImage->move(public_path('storage/post_image'), $imageName);
                }
                // else {

                //     $temporaryThumbnailPath = public_path('storage/post_image/') . 'tmp_' . $imageName;
                //     Image::load($postImgValue->getRealPath())
                //         ->width(500)
                //         ->optimize()
                //         ->save($temporaryThumbnailPath);
                //     $destinationPath = public_path('storage/post_image/');
                //     if (!file_exists($destinationPath)) {
                //         mkdir($destinationPath, 0755, true);
                //     }
                //     rename($temporaryThumbnailPath,$destinationPath);
                // }
                if ($checkIsimageOrVideo == 'video') {
                    $video++;
                } else {
                    $image++;
                }
                $eventPostImage = new EventPostImage();
                $eventPostImage->event_id = $request->event_id;
                $eventPostImage->event_post_id = $creatEventPost->id;
                $eventPostImage->post_image = $imageName;
                $eventPostImage->duration = $duration;
                $eventPostImage->type = $checkIsimageOrVideo;
                $eventPostImage->thumbnail = $thumbName;
                $eventPostImage->save();
            }



            if ($request->post_type == '2') {
                $eventPostPoll = new EventPostPoll;
                $eventPostPoll->event_id = $request->event_id;
                $eventPostPoll->event_post_id = $creatEventPost->id;
                $eventPostPoll->poll_question = $request->poll_question;
                $eventPostPoll->poll_duration = $request->poll_duration;
                if ($eventPostPoll->save()) {
                    $option = json_decode($request->option);
                    foreach ($option as $value) {
                        $pollOption = new EventPostPollOption;
                        $pollOption->event_post_poll_id = $eventPostPoll->id;
                        $pollOption->option = $value;
                        $pollOption->save();
                    }
                }
            }
            $notificationParam = [
                'sender_id' => $user->id,
                'event_id' => $request->event_id,
                'post_id' => $creatEventPost->id,
                'is_in_photo_moudle' => $request->is_in_photo_moudle,
                'post_type' => $request->post_type,
                'post_privacy' => $request->post_privacy,
                'video' => $video,
                'image' => $image
            ];
        }



        // if ($request->is_in_photo_moudle == '1') {
        //     sendNotification('photos', $notificationParam);
        // } else {
        //     sendNotification('upload_post', $notificationParam);
        // }
        return redirect()->back()->with('msg', 'Event Post created successfully!');
    }

    public function eventViewUser($user_id, $event_id)
    {
        $checkViewbyuser = EventInvitedUser::whereHas('user', function ($query) {
            $query->where('app_user', '1');
        })->where(['user_id' => $user_id, 'event_id' => $event_id])->first();
        if ($checkViewbyuser != null) {
            if ($checkViewbyuser->read == '0') {
                $checkViewbyuser->read = '1';
                $checkViewbyuser->event_view_date = date('Y-m-d');
                $checkViewbyuser->save();
                DB::commit();
                return response()->json(['status' => 1, 'message' => "viewed invite"]);
            }
        }
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
    public function createStory(Request $request)
    {
        // dd($request);
        $user = Auth::guard('web')->user()->id;

        $event_id = $request->eventId;
        $storyNames = $request->file('story'); // Get the file names from the request



        try {

            DB::beginTransaction();
            $checkAlreadyStories = EventUserStory::where(['event_id' => $event_id, 'user_id' => $user])->first();

            $createStory = $checkAlreadyStories;

            if ($checkAlreadyStories == null) {


                // $createStory =  EventUserStory::create([
                //     'event_id' => $event_id,
                //     'user_id' => $user,
                // ]);
                $createStory = new EventUserStory();
                $createStory->event_id = $event_id;
                $createStory->user_id = $user;

                // Save the data
                $createStory->save();
            }
            if ($createStory) {
                if (!empty($request->file('story'))) {

                    $createStory->created_at = Carbon::now();
                    $createStory->save();

                    $storyData = $request->file('story');

                    foreach ($storyData as $postStoryValue) {
                        $postStory = $postStoryValue;
                        $imageName = time() . '_' . $postStory->getClientOriginalName();
                        $checkIsimageOrVideo = checkIsimageOrVideo($postStory);
                        $duration = '0';
                        if ($checkIsimageOrVideo == 'video') {
                            $duration = getVideoDuration($postStory);

                            // if (file_exists(public_path('storage/event_user_stories/') . $imageName)) {

                            //     $imagePath = public_path('storage/event_user_stories/') . $imageName;
                            //     unlink($imagePath);
                            // }


                            $postStory->move(public_path('storage/event_user_stories'), $imageName);
                        } else {
                            $postStory->move(public_path('storage/event_user_stories'), $imageName);
                        }

                        $storyId = $createStory->id;
                        $storylestest =   UserEventStory::create([
                            'event_story_id' => $storyId,
                            'story' => $imageName,
                            'duration' => $duration,
                            'type' => $checkIsimageOrVideo
                        ]);
                    }
                    DB::commit();
                    $currentDateTime = Carbon::now();

                    $getStoryData =   EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                        $query->where('created_at', '>', $currentDateTime->subHours(24));
                    }])->where(['event_id' => $event_id, 'user_id' => $user])->where('created_at', '>', $currentDateTime->subHours(24))->first();

                    $storiesDeta['owner_stories'] = [];
                    if ($getStoryData != null) {

                        $storiesDetail['id'] =  $getStoryData->id;
                        $storiesDetail['user_id'] =  $getStoryData->user->id;

                        $storiesDetail['username'] =  $getStoryData->user->firstname . ' ' . $getStoryData->user->lastname;

                        $storiesDetail['profile'] =  empty($getStoryData->user->profile) ? "" : asset('storage/profile/' . $getStoryData->user->profile);

                        $storiesDetail['story'] = [];
                        foreach ($getStoryData->user_event_story as $storyVal) {
                            $storiesData['id'] = $storyVal->id;
                            $storiesData['storyurl'] = empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story);
                            $storiesData['type'] = $storyVal->type;

                            if ($storyVal->type == 'video') {
                                $storiesData['video_duration'] = (!empty($storyVal->duration)) ? $storyVal->duration : "";
                            }
                            $storiesData['post_time'] =  $this->setpostTime($storyVal->created_at);
                            $storiesData['created_at'] =  $storyVal->created_at;
                            $storiesDetail['story'][] = $storiesData;
                        }
                        $storiesDeta['owner_stories'][] = $storiesDetail;
                    }

                    return response()->json(['status' => 1, 'message' => "Event story uploaded successfully", 'data' => $storiesDeta]);
                }
            } else {
                return response()->json(['status' => 0, 'message' => "Event story not uploaded"]);
            }
        } catch (QueryException $e) {
            // dd($e);
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        }
    }


    public function fetchUserStories(Request $request, $eventId)
    {
        $user = Auth::guard('web')->user();
        $event_id = $eventId;
        $storyType = $request->query('storyType');

        try {
            $currentDateTime = Carbon::now();

            // Initialize response structure
            $storiesDeta = [
                'owner_stories' => [],
            ];

            $otherdata = [
                'other_stories' => [],
            ];
            // Fetch the authenticated user's stories
            $getStoryData = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])
                ->where('event_id', $event_id)
                ->where('user_id', $user->id)
                ->where('created_at', '>', now()->subHours(24))
                ->first();

            // Process owner's stories
            if ($getStoryData != null) {
                $storiesDetail = [
                    'id' => $getStoryData->id,
                    'user_id' => $getStoryData->user->id,
                    'username' => $getStoryData->user->firstname . ' ' . $getStoryData->user->lastname,
                    'profile' => empty($getStoryData->user->profile) ? "" : asset('storage/profile/' . $getStoryData->user->profile),
                    'story' => [],
                ];

                foreach ($getStoryData->user_event_story as $storyVal) {
                    $storiesData = [
                        'id' => $storyVal->id,
                        'storyurl' => empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story),
                        'type' => $storyVal->type,
                        'post_time' => $this->setpostTime($storyVal->created_at),
                        'created_at' => $storyVal->created_at,
                    ];

                    if ($storyVal->type == 'video') {
                        $storiesData['video_duration'] = !empty($storyVal->duration) ? $storyVal->duration : "";
                    }

                    $storiesDetail['story'][] = $storiesData;
                }

                $storiesDeta['owner_stories'][] = $storiesDetail;
            }

            // Fetch other users' stories
            $otherStories = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])
                ->where('event_id', $event_id)
                ->where('user_id', '!=', $user->id)
                ->where('created_at', '>', now()->subHours(24))
                ->get();

            foreach ($otherStories as $value) {
                $storiesDetaill = [
                    'id' => $value->id,
                    'user_id' => $value->user->id,
                    'username' => $value->user->firstname . ' ' . $value->user->lastname,
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'story' => [],
                ];

                foreach ($value->user_event_story as $storyVal) {
                    $storiesData = [
                        'id' => $storyVal->id,
                        'storyurl' => empty($storyVal->story) ? "" : asset('storage/event_user_stories/' . $storyVal->story),
                        'type' => $storyVal->type,
                        'post_time' => $this->setpostTime($storyVal->created_at),
                        'is_seen' => UserSeenStory::where(['user_id' => $user->id, 'user_event_story_id' => $storyVal->id])->exists() ? "1" : "0",
                        'created_at' => $storyVal->created_at,
                    ];

                    if ($storyVal->type == 'video') {
                        $storiesData['video_duration'] = !empty($storyVal->duration) ? $storyVal->duration : "";
                    }

                    $storiesDetaill['story'][] = $storiesData;
                }

                $otherdata['other_stories'][] = $storiesDetaill;
            }
            if ($storyType == "owner") {
                return response()->json(['status' => 1, 'message' => "Stories fetched successfully", 'data' => $storiesDeta]);
            } else {
                return response()->json(['status' => 1, 'message' => "Stories fetched successfully", 'data' => $otherdata]);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => "Error fetching stories"]);
        }
    }

    public function createPoll(Request $request)
    {

        // Validate the request
        $request->validate([
            'question' => 'required|string|max:255',
            'duration' => 'required|string',
            'options' => 'required|array|min:2', // Ensure at least two options are provided
            'options.*' => 'required|string|max:100', // Validate each option
        ]);

        $user = Auth::guard('web')->user()->id;
        $creatEventPost = new EventPost;
        $creatEventPost->event_id = $request->event_id;
        $creatEventPost->user_id = $user;
        $creatEventPost->post_message = $request->input('content');

        if ($request->hasFile('post_recording')) {
            $record = $request->post_recording;
            $recordingName = time() . '_' . $record->getClientOriginalName();
            $record->move(public_path('storage/event_post_recording'), $recordingName);
            $creatEventPost->post_recording = $recordingName;
        }
        $creatEventPost->post_privacy = $request->post_privacys;
        $creatEventPost->post_type = "2";
        $creatEventPost->commenting_on_off = $request->commenting_on_off;
        $creatEventPost->is_in_photo_moudle = "0";
        $creatEventPost->save();
        // Create the poll
        $eventPostPoll = new EventPostPoll;
        $eventPostPoll->event_id = $request->event_id; // Example event ID
        $eventPostPoll->event_post_id =  $creatEventPost->id; // Example post ID
        $eventPostPoll->poll_question = $request->question;
        $eventPostPoll->poll_duration = $request->duration;

        if ($eventPostPoll->save()) {
            // Save poll options
            foreach ($request->options as $value) {
                $pollOption = new EventPostPollOption();
                $pollOption->event_post_poll_id = $eventPostPoll->id;
                $pollOption->option = $value;
                $pollOption->save();
            }
        }

        return redirect()->back()->with('msg', 'Poll created successfully!');
    }

    public function get_reaction_post_list(Request $request)
    {
        $user = Auth::guard('web')->user();
        $post_id = $request->post_id;
        $getreaction = EventPostReaction::where('event_post_id', $post_id)
            ->with(['user'])
            ->get();

        $reactionWiseUsers = [];
        foreach ($getreaction as $reaction) {
            $reactionWiseUsers[$reaction->reaction][] = [
                'user_id' => $reaction->user->id ?? null,
                'firstname' => $reaction->user->firstname ?? null,
                'lastname' => $reaction->user->lastname ?? null,
                'profile' => !empty($reaction->user->profile) ? asset('storage/profile/' . $reaction->user->profile) : "",
                'location' => $reaction->user->city != "" ? trim($reaction->user->city) . ($reaction->user->state != "" ? ', ' . $reaction->user->state : '') : ""
            ];
        }
        $totalReactions = $getreaction->count();
        $reactionCounts = $getreaction->groupBy('reaction')->map(function ($group) {
            return $group->count();
        });
        $reaction_detail = [
            'total_count' => $totalReactions,
            'reaction_count' => $reactionCounts,
        ];
        return response()->json(['status' => 1, 'reaction_detail' => $reaction_detail, 'reaction_list' => $reactionWiseUsers]);

        // dd($reaction_detail,$reactionWiseUsers);
    }





    public function VoteOfPoll(Request $request)
    {
        $pollId = $request->input('poll_id');
        $optionId = $request->input('option_id');
        $userId = Auth::guard('web')->user()->id;

        // Fetch the poll to validate its expiration status
        $poll = EventPostPoll::find($pollId);

        if (!$poll) {
            return response()->json(['success' => false, 'message' => 'Poll not found.'], 404);
        }

        // Check if the poll is expired
        $pollDuration = getLeftPollTime($poll->updated_at, $poll->poll_duration);
        if ($pollDuration === "") { // Assuming "" means expired in `getLeftPollTime`
            return response()->json([
                'success' => false,
                'message' => 'This poll has expired. Votes cannot be updated.',
            ]);
        }

        // Check if the user has already voted
        $existingVote = UserEventPollData::where('event_post_poll_id', $pollId)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            // If the user has already voted, update the existing vote
            $existingVote->event_poll_option_id = $optionId;
            $existingVote->save();

            // Recalculate the poll data
            $updatedPoll = $this->getPollData($request)->getData();

            return response()->json([
                'success' => true,
                'message' => 'Vote updated successfully.',
                'poll_data' => $updatedPoll,
            ]);
        } else {
            // If the user hasn't voted yet, record the new vote
            UserEventPollData::create([
                'event_post_poll_id' => $pollId,
                'event_poll_option_id' => $optionId,
                'user_id' => $userId,
            ]);

            // Recalculate the poll data
            $updatedPoll = $this->getPollData($request)->getData();

            return response()->json([
                'success' => true,
                'message' => 'Vote submitted successfully.',
                'poll_data' => $updatedPoll,
            ]);
        }
    }


    public function GetPollData(Request $request)
    {
        //   dd($request);
        // $validator = Validator::make($request->all(), [
        //     'event_id' => 'required|integer',
        //     'event_post_id' => 'required|integer',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 422);
        // }
        //  dd($request);
        $eventId = (int)$request->input('eventId');
        $eventPostId = (int)$request->input('eventPostId');


        // dd($eventId,$eventPostId);
        // Fetch multiple polls, with their options and user poll data count
        $polls = EventPostPoll::with('event_poll_option')
            ->withCount('user_poll_data')
            ->where(['event_id' => $eventId, 'event_post_id' => $eventPostId])
            ->get();

        // dd($polls);
        // Check if polls exist
        if ($polls->isEmpty()) {
            return response()->json(['message' => 'Polls not found'], 404);
        }

        $pollsData = [];

        $userId = Auth::guard('web')->user()->id;

        foreach ($polls as $poll) {
            // Get the poll duration and check if it is expired for each poll
            $post_time = setpostTime($poll->created_at);
            $pollDuration = getLeftPollTime($poll->updated_at, $poll->poll_duration);
            $isExpired = ($pollDuration == "");

            $pollData = [
                'poll_id' => $poll->id,
                'poll_question' => $poll->poll_question,
                'total_poll_duration' => $poll->poll_duration,
                'poll_duration_left' => $pollDuration,
                'is_expired' => $isExpired,
                'post_time' => $post_time,
                'total_poll_vote' => $poll->user_poll_data_count,
                'poll_options' => [],
            ];

            // Loop through each poll's options and calculate vote percentages
            foreach ($poll->event_poll_option as $option) {
                $totalVotes = getOptionAllTotalVote($poll->id);
                $optionTotalVotes = getOptionTotalVote($option->id);

                $pollData['poll_options'][] = [
                    'id' => $option->id,
                    'option' => $option->option,
                    'total_vote_percentage' => $totalVotes > 0
                        ? round(($optionTotalVotes / $totalVotes) * 100) . '%'
                        : '0%',
                    'is_poll_selected' => checkUserGivePoll($userId, $poll->id, $option->id), // This should return true/false based on whether the user has voted for this option
                ];
            }

            // Add the poll data to the polls data array
            $pollsData[] = $pollData;
        }

        return response()->json($pollsData);
    }
    // public function createEventPost(Request $request)
    // {

    //     $user = Auth::guard('web')->user()->id;

    //     // Create new event post
    //     $createEventPost = new EventPost();
    //     $createEventPost->event_id = $request->event_id;
    //     $createEventPost->user_id = $user;
    //     $createEventPost->post_message = $request->input('content'); // Placeholder, update as necessary
    //     $createEventPost->post_privacy = $request->input('post_privacys'); // Example: public post
    //     $createEventPost->post_type = "1"; // Define post type
    //     $createEventPost->commenting_on_off = $request->input('commenting_on_off'); // Comments allowed
    //     $createEventPost->is_in_photo_moudle = "1"; // Whether the post contains photos
    //     $createEventPost->save();

    //     // Check if files were uploaded
    //     if ($createEventPost->id && $request->hasFile('files')) {
    //         $postFiles = $request->file('files'); // Get the uploaded files
    //         $imageUrls = [];
    //         $videoCount = 0;
    //         $imageCount = 0;

    //         foreach ($postFiles as $key => $postFile) {
    //             $fileName = time() . $key . '_' . $postFile->getClientOriginalName();
    //             $checkIsImageOrVideo = checkIsImageOrVideo($postFile); // Assuming this is a helper function
    //             $duration = "";
    //             $thumbName = "";

    //             // Process video
    //             if ($checkIsImageOrVideo == 'video') {
    //                 $duration = getVideoDuration($postFile); // Assuming this is a helper function
    //                 $thumbName = genrate_thumbnail($fileName, $createEventPost->id);
    //                 $postFile->move(public_path('storage/post_image'), $fileName);
    //             }
    //             //else {
    //             //     // Process image
    //             //     $temporaryThumbnailPath = public_path('storage/post_image/') . 'tmp_' . $fileName;
    //             //     Image::load($postFile->getRealPath())
    //             //         ->width(500)
    //             //         ->optimize()
    //             //         ->save($temporaryThumbnailPath);
    //             //     rename($temporaryThumbnailPath, public_path('storage/post_image/') . $fileName);
    //             // }

    //             // Count images and videos
    //             if ($checkIsImageOrVideo == 'video') {
    //                 $videoCount++;
    //             } else {
    //                 $imageCount++;
    //             }

    //             // Save post image
    //             $eventPostImage = new EventPostImage();
    //             $eventPostImage->event_id = $request->event_id;
    //             $eventPostImage->event_post_id = $createEventPost->id;
    //             $eventPostImage->post_image = $fileName;
    //             $eventPostImage->duration = $duration;
    //             $eventPostImage->type = $checkIsImageOrVideo;
    //             $eventPostImage->thumbnail = $thumbName;
    //             $eventPostImage->save();
    //         }

    //         return redirect()->back()->with('success', 'Event post uploded successfully!');
    //     }

    //     return redirect()->back()->with('success', 'Event Post created successfully!');
    // }



    public function userPostLikeDislike(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Check if user has already reacted to this post
        $checkReaction = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id'],
            'user_id' => $user->id
        ])->first();

        // Convert the emoji reaction to Unicode
        $reaction_unicode = sprintf('\u{%X}', mb_ord($request['reaction'], 'UTF-8'));
        $unicode = strtoupper(bin2hex(mb_convert_encoding($request['reaction'], 'UTF-32', 'UTF-8')));

        if (!$checkReaction) {
            // User has not reacted yet, insert the reaction
            $event_post_reaction = new EventPostReaction;
            $event_post_reaction->event_id = $request['event_id'];
            $event_post_reaction->event_post_id = $request['event_post_id'];
            $event_post_reaction->user_id = $user->id;
            $event_post_reaction->reaction = $reaction_unicode;
            $event_post_reaction->unicode = $unicode;
            $event_post_reaction->save();

            $message = "Post liked by you";
            $isReaction = 1;
        } else {


            // User has already reacted
            if ($checkReaction->unicode != $unicode) {

                // Reaction is different from current, update it
                $checkReaction->reaction = $reaction_unicode;
                $checkReaction->unicode = $unicode;
                $checkReaction->save();
                $message = "Post liked by you";
                $isReaction = 1;
            } else {

                // Same reaction, dislike the post
                $checkReaction->delete();
                $removeNotification = Notification::where([
                    'event_id' => $request['event_id'],
                    'sender_id' => $user->id,
                    'post_id' => $request['event_post_id'],
                    'notification_type' => 'like_post'
                ])->first();

                if ($removeNotification) {
                    $removeNotification->delete();
                }
                $message = "Post Disliked by you";
                $isReaction = 0;
            }
        }


        // Get total count of reactions
        $counts = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id']
        ])->count();

        // Get the top 3 most common reactions
        $total_counts = EventPostReaction::where([
            'event_id' => $request['event_id'],
            'event_post_id' => $request['event_post_id']
        ])
            ->select('reaction', 'unicode', DB::raw('COUNT(*) as count'))
            ->groupBy('reaction', 'unicode')
            ->orderByDesc('count')
            ->take(3)
            ->pluck('reaction')
            ->toArray();

        // Get post reactions with user details
        $postReactions = getReaction($request['event_post_id']);
        $postReaction = [];

        foreach ($postReactions as $reactionVal) {
            $reactionInfo = [
                'id' => $reactionVal->id,
                'event_post_id' => $reactionVal->event_post_id,
                'reaction' => $reactionVal->reaction,
                'user_id' => $reactionVal->user_id,
                'username' => $reactionVal->user->firstname . ' ' . $reactionVal->user->lastname,
                'location' => $reactionVal->user->city ?? "",
                'profile' => !empty($reactionVal->user->profile) ? asset('storage/profile/' . $reactionVal->user->profile) : ""
            ];

            $postReaction[] = $reactionInfo;
        }

        return response()->json([
            'status' => 1,
            'is_reaction' => $isReaction,
            'message' => $message,
            'count' => $counts,
            'post_reaction' => $postReaction,
            'reactionList' => $total_counts
        ]);
    }


    public function userPostCommentReplyReaction(Request $request)

    {

        $user  = Auth::guard('web')->user();

        $checkcommentReaction = EventPostCommentReaction::with(['event_post_comment'])->where(['event_post_comment_id' => $request['event_post_comment_id'], 'user_id' => $user->id])->count();

        if ($checkcommentReaction == 0) {
            $post_comment_reaction = new EventPostCommentReaction;

            $post_comment_reaction->event_post_comment_id = $request['event_post_comment_id'];

            $post_comment_reaction->user_id = $user->id;

            $post_comment_reaction->reaction = $request['reaction'];

            $post_comment_reaction->save();



            $checkcommentReactionData = EventPostCommentReaction::with('event_post_comment')->where(['event_post_comment_id' => $request['event_post_comment_id'], 'user_id' => $user->id])->first();

            // $notificationParam = [

            //     'sender_id' => $user->id,

            //     'event_id' => $checkcommentReactionData->event_post_comment->event_id,

            //     'post_id' => $checkcommentReactionData->event_post_comment->event_post_id,
            //     'comment_id' => $request['event_post_comment_id']

            // ];

            // sendNotification('reply_comment_reaction', $notificationParam);
            $totalCount = EventPostCommentReaction::where(['event_post_comment_id' => $request['event_post_comment_id']])->count();

            return response()->json(['status' => 1, 'message' => "Post comment like by you", "self_reaction" => $request['reaction'], "count" => $totalCount]);
        } else {

            $checkcommentReaction = EventPostCommentReaction::where(['event_post_comment_id' => $request['event_post_comment_id'], 'user_id' => $user->id]);

            $checkcommentReaction->delete();


            $totalCount = EventPostCommentReaction::where(['event_post_comment_id' => $request['event_post_comment_id']])->count();
            return response()->json(['status' => 1, 'message' => "Post comment disliked by you", "self_reaction" => "", "count" => $totalCount]);
        }
    }
    public function userPostComment(Request $request)

    {
        // dd($request);
        $user  = Auth::guard('web')->user();
        $rawData = $request->getContent();




        EventPostComment::where(['event_id' => $request['event_id'], 'event_post_id' => $request['event_post_id'], 'user_id' => $user->id])->count();

        $event_post_comment = new EventPostComment;

        $event_post_comment->event_id = $request['event_id'];

        $event_post_comment->event_post_id = $request['event_post_id'];

        $event_post_comment->user_id = $user->id;

        $event_post_comment->comment_text = $request['comment'];

        if (isset($request['media']) && !empty($request['media'])) {
            $image = $request['media'];

            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/comment_media'), $imageName);
            $event_post_comment->media = $imageName;
            $event_post_comment->type = $request['type'];
        }

        $event_post_comment->save();

        // $notificationParam = [
        //     'sender_id' => $user->id,
        //     'event_id' => $request['event_id'],
        //     'post_id' => $request['event_post_id'],
        //     'comment_id' => $event_post_comment->id
        // ];

        // sendNotification('comment_post', $notificationParam);



        $postComment = getComments($request['event_post_id']);

        $letestComment =  EventPostComment::with('user')->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $request['event_post_id'], 'parent_comment_id' => NULL])->orderBy('id', 'DESC')->limit(1)->first();



        $postCommentList = [
            'id' => $letestComment->id,

            'event_post_id' => $letestComment->event_post_id,

            'comment' => $letestComment->comment_text,
            'media' => (!empty($letestComment->media) && $letestComment->media != NULL) ? asset('storage/comment_media/' . $letestComment->media) : "",

            'user_id' => $letestComment->user_id,

            'username' => $letestComment->user->firstname . ' ' . $letestComment->user->lastname,

            'profile' => (!empty($letestComment->user->profile)) ? asset('storage/profile/' . $letestComment->user->profile) : "",

            'comment_total_likes' => $letestComment->post_comment_reaction_count,

            'location' => $letestComment->user->city != "" ? trim($letestComment->user->city) . ($letestComment->user->state != "" ? ', ' . $letestComment->user->state : '') : "",
            // 'is_like' => checkUserPhotoIsLike($letestComment->id, $user->id),
            'is_like' => 0,

            'created_at' => $letestComment->created_at,

            'total_replies' => $letestComment->replies_count,

            'posttime' => setpostTime($letestComment->created_at),
            'comment_replies' => []
        ];

        return response()->json(['success' => true, 'total_comments' => count($postComment), 'data' => $postCommentList, 'message' => "Post commented by you"]);
    }
    public function userPostCommentReply(Request $request)

    {
        $user  = Auth::guard('web')->user();



        $parentCommentId =  $request['parent_comment_id'];
        $mainParentId = (new EventPostComment())->getMainParentId($parentCommentId) ?? "";


        $event_post_comment = new EventPostComment;
        $event_post_comment->event_id = $request['event_id'];
        $event_post_comment->event_post_id = $request['event_post_id'];
        $event_post_comment->user_id = $user->id;
        $event_post_comment->parent_comment_id = $request['parent_comment_id'];
        $event_post_comment->main_parent_comment_id = $mainParentId;
        $event_post_comment->comment_text = $request['comment'];
        if (isset($request['media']) && !empty($request['media'])) {
            $image = $request['media'];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/comment_media'), $imageName);
            $event_post_comment->media = $imageName;
            $event_post_comment->type = $request['type'];
        }
        $event_post_comment->save();


        // $notificationParam = [
        //     'sender_id' => $user->id,
        //     'event_id' => $request['event_id'],
        //     'post_id' => $request['event_post_id'],
        //     'comment_id' => $event_post_comment->id
        // ];
        // sendNotification('reply_on_comment_post', $notificationParam);

        $replyList =   EventPostComment::with(['user', 'replies' => function ($query) {
            $query->withcount('post_comment_reaction', 'replies')->orderBy('id', 'DESC');
        }])->withcount('post_comment_reaction', 'replies')->where(['id' => $mainParentId, 'event_post_id' => $request['event_post_id']])->orderBy('id', 'DESC')->first();

        $commentInfo['id'] = $replyList->id;
        $commentInfo['event_post_id'] = $replyList->event_post_id;
        $commentInfo['comment'] = $replyList->comment;
        $commentInfo['user_id'] = $replyList->user_id;
        $commentInfo['username'] = $replyList->user->firstname . ' ' . $replyList->user->lastname;
        $commentInfo['location'] = $replyList->user->city != "" ? trim($replyList->user->city) . ($replyList->user->state != "" ? ', ' . $replyList->user->state : '') : "";
        $commentInfo['profile'] = (!empty($replyList->user->profile)) ? asset('storage/profile/' . $replyList->user->profile) : "";
        $commentInfo['comment_total_likes'] = $replyList->post_comment_reaction_count;
        $commentInfo['is_like'] = checkUserIsLike($replyList->id, $user->id);
        $commentInfo['created_at'] = $replyList->created_at;
        $commentInfo['total_replies'] = $replyList->replies_count;
        $commentInfo['posttime'] = setpostTime($replyList->created_at);
        $commentInfo['comment_replies'] = [];

        if (!empty($replyList->replies)) {
            foreach ($replyList->replies as $replyVal) {
                $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $replyVal->id)->count();
                $commentReply['id'] = $replyVal->id;
                $commentReply['event_post_id'] = $replyVal->event_post_id;
                $commentReply['comment'] = $replyVal->comment_text;
                $commentReply['user_id'] = $replyVal->user_id;
                $commentReply['username'] = $replyVal->user->firstname . ' ' . $replyVal->user->lastname;
                $commentReply['profile'] = (!empty($replyVal->user->profile)) ? asset('storage/profile/' . $replyVal->user->profile) : "";
                // $commentReply['location'] = (!empty($replyVal->user->city)) ? $replyVal->user->city : "";
                $commentReply['location'] = $replyVal->user->city != "" ? trim($replyVal->user->city) . ($replyVal->user->state != "" ? ', ' . $replyVal->user->state : '') : "";
                $commentReply['comment_total_likes'] = $replyVal->post_comment_reaction_count;
                $commentReply['main_comment_id'] = $replyVal->main_parent_comment_id;
                $commentReply['is_like'] = checkUserIsLike($replyVal->id, $user->id);
                $commentReply['total_replies'] = $totalReply;
                $commentReply['posttime'] = setpostTime($replyVal->created_at);
                $commentReply['created_at'] = $replyVal->created_at;
                $commentInfo['comment_replies'][] = $commentReply;
                $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $request['event_post_id'], 'parent_comment_id' => $replyVal->id])->orderBy('id', 'DESC')->get();

                foreach ($replyComment as $childReplyVal) {
                    if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {
                        $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();
                        $commentChildReply['id'] = $childReplyVal->id;
                        $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;
                        $commentChildReply['comment'] = $childReplyVal->comment_text;
                        $commentChildReply['user_id'] = $childReplyVal->user_id;
                        $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;
                        $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('storage/profile/' . $childReplyVal->user->profile) : "";
                        $commentChildReply['location'] = $childReplyVal->user->city != "" ? trim($childReplyVal->user->city) . ($childReplyVal->user->state != "" ? ', ' . $childReplyVal->user->state : '') : "";
                        $commentChildReply['comment_total_likes'] = $childReplyVal->post_comment_reaction_count;
                        $commentReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                        $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);
                        $commentChildReply['total_replies'] = $totalReply;
                        $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                        $commentChildReply['created_at'] = $childReplyVal->created_at;
                        $commentInfo['comment_replies'][] = $commentChildReply;
                        $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $request['event_post_id'], 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                        foreach ($replyChildComment as $childInReplyVal) {
                            if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {
                                $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();
                                $commentChildInReply['id'] = $childInReplyVal->id;
                                $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;
                                $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                $commentChildInReply['user_id'] = $childInReplyVal->user_id;
                                $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;
                                $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('storage/profile/' . $childInReplyVal->user->profile) : "";
                                $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";
                                $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;
                                $commentReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                $commentChildInReply['is_like'] = checkUserIsLike($childInReplyVal->id, $user->id);
                                $commentChildInReply['total_replies'] = $totalReply;
                                $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                $commentChildInReply['created_at'] = $childInReplyVal->created_at;
                                $commentInfo['comment_replies'][] = $commentChildInReply;
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'total_comments' => 0, 'data' => $commentInfo, 'message' => "Post comment replied by you"]);
    }

    public function postControl(Request $request)
    {
        // dd($request);

        $user  = Auth::guard('web')->user();



        $checkIsPostControl = PostControl::where(['event_id' => $request['event_id'], 'user_id' => $user->id, 'event_post_id' => $request['event_post_id']])->first();
        if ($checkIsPostControl == null) {
            $setPostControl = new PostControl;

            $setPostControl->event_id = $request['event_id'];
            $setPostControl->user_id = $user->id;
            $setPostControl->event_post_id = $request['event_post_id'];
            $setPostControl->post_control = $request['post_control'];
            $setPostControl->save();
        } else {
            $checkIsPostControl->post_control = $request['post_control'];
            $checkIsPostControl->save();
        }

        $message = "";
        if ($request['post_control'] == 'hide_post') {
            $message = "Post is hide from your wall";
        } else if ($request['post_control'] == 'unhide_post') {
            $message = "Post is unhide";
        } else if ($request['post_control'] == 'mute') {
            $message = "Mute every post from this user will post";
        } else if ($request['post_control'] == 'unmute') {
            $message = "Unmuted every post from this user will post";
        } else if ($request['post_control'] == 'report') {
            $reportCreate = new UserReportToPost;
            $reportCreate->event_id = $request['event_id'];
            $reportCreate->user_id =  $user->id;
            $reportCreate->report_type = $request['report_type'];
            $reportCreate->report_description = $request['report_description'];
            $reportCreate->event_post_id = $request['event_post_id'];
            $reportCreate->save();

            $savedReportId =  $reportCreate->id;
            $createdAt = $reportCreate->created_at;
            $message = "Reported to admin for this post";

            $support_email = env('SUPPORT_MAIL');

            $getName = UserReportToPost::with(['users', 'events'])->where('id', $savedReportId)->first();
            $data = [
                'reporter_username' => $getName->users->firstname . ' ' . $getName->users->lastname,
                'event_name' => $getName->events->event_name,
                'report_type' => $getName->report_type,
                'report_description' => ($getName->report_description != "") ? $getName->report_description : "",
                'report_time' => Carbon::parse($createdAt)->format('Y-m-d h:i A'),
                'report_from' => "post"
            ];

            Mail::send('emails.reportEmail', ['userdata' => $data], function ($messages) use ($support_email) {
                $messages->to($support_email)
                    ->subject('Post Report Mail');
            });
        }
        return response()->json(['status' => 1, 'type' => $request['post_control'], 'message' => $message]);
    }
    public function get_phoneContact(Request $request)
    {
        // Authenticated user ID
        $id = Auth::guard('web')->user()->id;

        // Request variables
        $type = $request->type; // Optional filtering by type
        $search_user = $request->search_name ?? ''; // Search query, default empty

        // Query phone contacts
        $phone_contact = contact_sync::where('contact_id', $id)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type); // Apply type filter if provided
            })
            ->when(!empty($request->limit), function ($query) use ($request) {
                $query->limit($request->limit)
                    ->offset($request->offset); // Pagination with limit and offset
            })
            ->when(!empty($search_user), function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstName', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastName', 'LIKE', '%' . $search_user . '%'); // Search by first or last name
                });
            })
            ->orderBy('firstName', 'asc') // Order by first name
            ->get();
        // dd($phone_contact);
        $phoneContact = view('front.event_wall.guest_phoneContact', [
            'contacts' => $phone_contact
        ])->render();
        // Return response in JSON format
        return response()->json([
            'status' => 'success',
            'message' => 'Phone contacts retrieved successfully',
            'contacts' => $phoneContact
        ]);
    }

    public function get_yesviteContact(Request $request)
    {
        Session::forget('contactwall_ids');
        Session::forget('userwall_ids');
        Session::forget('add_guest_user_id');
        // Authenticated user ID
        $userId = Auth::guard('web')->user()->id;

        // Request parameters
        $type = $request->input('type');
        $searchUser = $request->input('search_name', ''); // Default to empty string
        $emails = $request->input('emails', []); // Default to empty array
        $limit = $request->input('limit');
        $offset = $request->input('offset');
        $eventId = $request->input('event_id'); // Ma
        // Query yesvite users
        $id = Auth::guard('web')->user()->id;
        $emails = [];
        $getAllContacts = contact_sync::where('contact_id', $id)->where('email', '!=', '')->orderBy('firstname')
            ->get();

        // dd($getAllContacts);
        if ($getAllContacts->isNotEmpty()) {
            $emails = $getAllContacts->pluck('email')->toArray();
        }
        $yesvite_users = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
            ->where('id', '!=', $id)
            ->where(['app_user' => '1'])
            ->whereIn('email', $emails)
            ->orderBy('firstname')
            ->when(!empty($limit), function ($query) use ($limit, $offset) {
                $query->limit($limit)
                    ->offset($offset); // Pagination
            })
            ->when(!empty($searchUser), function ($query) use ($searchUser) {
                $query->where(function ($q) use ($searchUser) {
                    $q->where('firstname', 'LIKE', "%{$searchUser}%")
                        ->orWhere('lastname', 'LIKE', "%{$searchUser}%"); // Search by name
                });
            })
            ->orderBy('firstname', 'asc') // Order results by first name
            ->get();
        $yesviteUsers = [];
        foreach ($yesvite_users as $user) {
            if ($user->email_verified_at == NULL && $user->app_user == '1') {
                continue;
            }
            $yesviteUserDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : asset('public/storage/profile/' . $user->profile),
                'firstname' => (!empty($user->firstname) || $user->firstname != null) ? $user->firstname : "",
                'lastname' => (!empty($user->lastname) || $user->lastname != null) ? $user->lastname : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'country_code' => (!empty($user->country_code) || $user->country_code != null) ? strval($user->country_code) : "",
                'phone_number' => (!empty($user->phone_number) || $user->phone_number != null) ? $user->phone_number : "",
                'app_user' => (!empty($user->app_user) || $user->app_user != null) ? $user->app_user : "",
            ];
            $yesviteUsers[] = (object)$yesviteUserDetail;
        }

        // $yesviteUsers = User::select(
        //     'id',
        //     'firstname',
        //     'profile',
        //     'lastname',
        //     'email',
        //     'country_code',
        //     'phone_number',
        //     'app_user',
        //     'prefer_by',
        //     'email_verified_at',
        //     'parent_user_phone_contact',
        //     'visible',
        //     'message_privacy'
        // )
        //     ->where('id', '!=', $userId) // Exclude authenticated user
        //     ->where('app_user', '1') // Filter by app users
        //     ->when(!empty($emails), function ($query) use ($emails) {
        //         $query->whereIn('email', $emails); // Filter by provided emails
        //     })
        //     ->when(!empty($limit), function ($query) use ($limit, $offset) {
        //         $query->limit($limit)
        //             ->offset($offset); // Pagination
        //     })
        //     ->when(!empty($searchUser), function ($query) use ($searchUser) {
        //         $query->where(function ($q) use ($searchUser) {
        //             $q->where('firstname', 'LIKE', "%{$searchUser}%")
        //                 ->orWhere('lastname', 'LIKE', "%{$searchUser}%"); // Search by name
        //         });
        //     })
        //     ->orderBy('firstname', 'asc') // Order results by first name
        //     ->get();

        // Query phone contacts
        $phoneContacts = contact_sync::where('contact_id', $userId)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type); // Apply type filter
            })
            ->when(!empty($limit), function ($query) use ($limit, $offset) {
                $query->limit($limit)
                    ->offset($offset); // Pagination
            })
            ->when(!empty($searchUser), function ($query) use ($searchUser) {
                $query->where(function ($q) use ($searchUser) {
                    $q->where('firstName', 'LIKE', "%{$searchUser}%")
                        ->orWhere('lastName', 'LIKE', "%{$searchUser}%"); // Search by name
                });
            })
            ->orderBy('firstName', 'asc')
            ->whereNull('userId') // Order results by first name
            ->get();
        $invitedUsers = [];
        if (!empty($eventId)) {
            $userIds = session()->get('userwall_ids', []);

            $invitedYesviteUsers = EventInvitedUser::with('user')
                ->where('event_id', $eventId)
                ->where('is_co_host', '0')
                ->whereNotNull('user_id')
                // ->whereNull('sync_id')
                ->get();
            if ($invitedYesviteUsers) {
                foreach ($invitedYesviteUsers as $user) {
                    $userVal = User::select(
                        'id',
                        'firstname',
                        'lastname',
                        'profile',
                        'email',
                        'country_code',
                        'phone_number',
                        'app_user',
                        'prefer_by',
                        'email_verified_at',
                        'parent_user_phone_contact',
                        'visible',
                        'message_privacy'
                    )->where(['id' => $user['user_id'], 'app_user' => '1'])->first();

                    if ($userVal) {
                        $userEntry = [
                            'id' => $userVal->id,
                            'firstname' => $userVal->firstname,
                            'lastname' => $userVal->lastname,
                            // 'prefer_by' => $userVal->prefer_by,
                            'prefer_by' => $user->prefer_by,
                            'invited_by' => $userVal->prefer_by == 'email' ? $userVal->email : $userVal->phone_number,
                            'profile' => $userVal->profile ?? '',
                        ];
                        $userIds[] = $userEntry;
                    }
                }
                session()->put('userwall_ids', $userIds);
                Session::save();
            }

            $userIdsSession = session()->get('contactwall_ids', []);
            $invitedContactUsers = EventInvitedUser::with('user')
                ->where('event_id', $eventId)
                ->where('is_co_host', '0')
                ->whereNotNull('sync_id')
                ->get();
            if ($invitedContactUsers) {
                foreach ($invitedContactUsers as $user) {
                    $userVal = contact_sync::select(
                        'id',
                        'firstname',
                        'lastname',
                        'photo',
                        'preferBy',
                        'phone',
                        'email'

                    )->where('id', $user['sync_id']) // Order results by first name
                        ->first();
                    if ($userVal) {
                        $userEntry = [
                            'sync_id' => $userVal->id,
                            'firstname' => $userVal->firstname,
                            'lastname' => $userVal->lastname,
                            'prefer_by' => $userVal->preferBy,
                            'invited_by' => $userVal->prefer_by == 'email' ? $userVal->email : $userVal->phone,
                            'profile' => (!empty($userVal->photo) && $userVal->photo != NULL && preg_match('/\.(jpg|jpeg|png)$/i', basename($userVal->photo))) ?? '',

                        ];
                        $userIdsSession[] = $userEntry;
                    }
                }
                session()->put('contactwall_ids', $userIdsSession);
                Session::save();
            }
        }
        $selected_yesvite_user = Session::get('userwall_ids');
        $selected_phone_user = Session::get('contactwall_ids');
        // Render the yesvite contacts view
        $yesviteContactHtml = view('front.event_wall.guest_yesviteContact', [
            'yesviteUsers' => $yesviteUsers,
            'phone_contact' => $phoneContacts,
            'invitedUsers' => $invitedUsers,
            'selected_yesvite_user' => $selected_yesvite_user,
            'selected_phone_user' => $selected_phone_user,
        ])->render();

        // Return response in JSON format
        return response()->json([
            'status' => 'success',
            'message' => 'Contacts retrieved successfully',
            'yesvite_contacts' => $yesviteContactHtml,
            'invited_users' => $invitedUsers,
            'selected_yesvite_user' => $selected_yesvite_user,
            'selected_phone_user' => $selected_phone_user,
        ]);
    }


    public function sendInvitation(Request $request)
    {
        $user  = Auth::guard('web')->user();
        $is_failed = "0";
        if (isset($request->is_failed) && $request->is_failed != "") {
            $is_failed = "1";
        }

        // try {
        if (!empty($request['guest_list'])) {

            $id = 0;
            $ids = [];
            $newInvite = [];
            $newInviteGuest = [];
            foreach ($request['guest_list'] as $value) {

                if ($value['app_user'] == "0") {

                    $checkUserInvitation = EventInvitedUser::with(['contact_sync'])->where(['event_id' => $request['event_id'], 'user_id' => ''])->where('sync_id', '!=', '')->get()->pluck('sync_id')->toArray();
                    $id = $value['id'];
                    if (!in_array($value['id'], $checkUserInvitation)) {
                        $checkUserExist = contact_sync::where('id', $value['id'])->first();
                        $newUserId = NULL;

                        if ($checkUserExist) {
                            if ($checkUserExist->email != '') {
                                $newUserId = checkUserEmailExist($checkUserExist);
                            }
                        }
                        EventInvitedUser::create([
                            'event_id' => $request['event_id'],
                            'prefer_by' => $value['prefer_by'],
                            'sync_id' => $value['id'],
                            'user_id' => $newUserId
                        ]);
                    } else {
                        $updateUser =  EventInvitedUser::with('contact_sync')->where(['event_id' => $request['event_id'], 'sync_id' => $id])->first();
                        $updateUser->prefer_by = $value['prefer_by'];
                        $updateUser->save();
                    }
                    $newInviteGuest[] = ['id' => $id];
                } else {

                    $checkUserInvitation = EventInvitedUser::with(['user'])->where(['event_id' => $request['event_id'], 'is_co_host' => '0'])->get()->pluck('user_id')->toArray();
                    $id = $value['id'];
                    if (!in_array($value['id'], $checkUserInvitation)) {
                        EventInvitedUser::create([
                            'event_id' => $request['event_id'],
                            'prefer_by' => $value['prefer_by'],
                            'user_id' => $value['id']
                        ]);
                    } else {
                        $updateUser =  EventInvitedUser::with('user')->where(['event_id' => $request['event_id'], 'user_id' => $id])->first();
                        $updateUser->prefer_by = $value['prefer_by'];
                        $updateUser->save();
                    }
                    $newInvite[] = ['id' => $id];
                }

                $ids[] = $id;
            }

            if (isset($newInvite) && !empty($newInvite)) {
                $filteredIds = array_map(
                    fn($guest) => $guest['id'],
                    $newInvite // This is the array you're operating on
                );
                // dd($filteredIds);
                $notificationParam = [
                    'sender_id' => $user->id,
                    'event_id' => $request['event_id'],
                    'newUser' => $filteredIds
                ];
                // dispatch(new SendNotificationJob(array('invite', $notificationParam)));
                sendNotification('invite', $notificationParam);
            }
            if (isset($newInviteGuest) && !empty($newInviteGuest)) {
                $filteredIds = array_map(
                    fn($guest) => $guest['id'],
                    $newInviteGuest // This is the array you're operating on
                );
                $notificationParam = [
                    'sender_id' => $user->id,
                    'event_id' => $request['event_id'],
                    'newUser' => $filteredIds
                ];
                sendNotificationGuest('invite', $notificationParam);
            }
            if ($is_failed == "0") {
                debit_coins($user->id, $request['event_id'], count($ids));
            }
        }





        return response()->json(['status' => 1, 'message' => "invites sent sucessfully"]);
        // }
        // catch (QueryException $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "db error"]);
        // }
        //  catch (\Exception $e) {

        //     DB::rollBack();

        //     return response()->json(['status' => 0, 'message' => "something went wrong"]);
        // }
    }

    public function  faildInvites(Request $request)

    {
        $user  = Auth::guard('web')->user();

        $rawData = $request->getContent();






        try {
            $sendFaildInvites = EventInvitedUser::where(['event_id' => $request['event_id'], 'invitation_sent' => '9'])->get();

            $faildInviteList = [];
            foreach ($sendFaildInvites as $value) {
                if ($value->user_id != '') {
                    $userDetail['id'] = $value->user->id;
                    $userDetail['first_name'] = (!empty($value->user->firstname) || $value->user->firstname != NULL) ? $value->user->firstname : "";
                    $userDetail['last_name'] = (!empty($value->user->lastname) || $value->user->lastname != NULL) ? $value->user->lastname : "";
                    $userDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                    $userDetail['email'] = (!empty($value->user->email)) ? $value->user->email : "";
                    $userDetail['country_code'] = (string)$value->user->country_code;
                    $userDetail['phone_number'] = (!empty($value->user->phone_number)) ? $value->user->phone_number : "";
                    $userDetail['app_user'] = $value->user->app_user;
                    $userDetail['prefer_by'] = $value->prefer_by;
                } else if ($value->sync_id != '') {
                    $userDetail['id'] = $value->contact_sync->id;
                    $userDetail['first_name'] = (!empty($value->contact_sync->firstName) || $value->contact_sync->firstName != NULL) ? $value->contact_sync->firstName : "";
                    $userDetail['last_name'] = (!empty($value->contact_sync->lastName) || $value->contact_sync->lastName != NULL) ? $value->contact_sync->lastName : "";
                    $userDetail['profile'] = (!empty($value->contact_sync->photo) || $value->contact_sync->photo != NULL) ? $value->contact_sync->photo : "";
                    $userDetail['email'] = (!empty($value->contact_sync->email)) ? $value->contact_sync->email : "";
                    $userDetail['country_code'] = '';
                    $userDetail['phone_number'] = (!empty($value->contact_sync->phoneWithCode)) ? $value->contact_sync->phoneWithCode : "";
                    $userDetail['app_user'] = $value->contact_sync->isAppUser;
                    $userDetail['prefer_by'] = $value->prefer_by;
                }
                $faildInviteList[] = $userDetail;
            }

            return response()->json(['status' => 1, 'data' => $faildInviteList, 'message' => "Faild invites"]);
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "error"]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    // public function filters(Request $request)
    // {

    //     $selectedFilters = $request->input('filters');
    //     $eventCreator = Event::where('id', $input['event_id'])->first();
    //     $eventPostList = EventPost::query();
    //     $eventPostList->with(['user', 'post_image'])
    //         ->withCount([
    //             'event_post_comment' => function ($query) {
    //                 $query->where('parent_comment_id', NULL);
    //             },
    //             'event_post_reaction'
    //         ])
    //         ->where([
    //             'event_id' => $input['event_id'],
    //             'is_in_photo_moudle' => '0'
    //         ])
    //         ->whereDoesntHave('post_control', function ($query) use ($user) {
    //             $query->where('user_id', $user->id)
    //                 ->where('post_control', 'hide_post');
    //         });
    //     $checkEventOwner = Event::where(['id' => $input['event_id'], 'user_id' => $user->id])->first();

    //     if ($checkEventOwner == null) {
    //         $eventPostList->where(function ($query) use ($user, $input) {
    //             $query->where('user_id', $user->id)
    //                 ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $input) {
    //                     $subQuery->whereHas('user', function ($userQuery) {
    //                         $userQuery->where('app_user', '1');
    //                     })
    //                         ->where('event_id', $input['event_id'])
    //                         ->where('user_id', $user->id)
    //                         ->where(function ($privacyQuery) {
    //                             $privacyQuery->where(function ($q) {
    //                                 $q->where('rsvp_d', '1')
    //                                     ->where('rsvp_status', '1')
    //                                     ->where('post_privacy', '2');
    //                             })
    //                                 ->orWhere(function ($q) {
    //                                     $q->where('rsvp_d', '1')
    //                                         ->where('rsvp_status', '0')
    //                                         ->where('post_privacy', '3');
    //                                 })
    //                                 ->orWhere(function ($q) {
    //                                     $q->where('rsvp_d', '0')
    //                                         ->where('post_privacy', '4');
    //                                 })
    //                                 ->orWhere(function ($q) {
    //                                     // This block is for post_privacy == 1
    //                                     $q->where('post_privacy', '1');
    //                                 });
    //                         });
    //                 });
    //         });
    //     }
    //     $eventPostList->orderBy('id', 'DESC');
    //     if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
    //         $eventPostList->where(function ($query) use ($selectedFilters, $eventCreator) {
    //             foreach ($selectedFilters as $filter) {
    //                 switch ($filter) {
    //                     case 'host_update':
    //                         $query->orWhere('user_id', $eventCreator->user_id);
    //                         break;
    //                     case 'video_uploads':
    //                         $query->orWhere(function ($qury) {
    //                             $qury->where('post_type', '1')
    //                                 ->whereHas('post_image', function ($q) {
    //                                     $q->where('type', 'video');
    //                                 });
    //                         });
    //                         break;
    //                     case 'photo_uploads':
    //                         $query->orWhere(function ($qury) {
    //                             $qury->where('post_type', '1')
    //                                 ->whereHas('post_image', function ($q) {
    //                                     $q->where('type', 'image');
    //                                 });
    //                         });
    //                         break;
    //                     case 'polls':
    //                         $query->orWhere('post_type', '2');
    //                         break;
    //                     case 'comments':
    //                         $query->orWhere('post_type', '0');
    //                         break;
    //                 }
    //             }
    //         });
    //     }
    // }

    public function wallFilters(Request $request)
    {
        $user  = Auth::guard('web')->user();

        $event =  $request->event_id;
        $postList = [];
        $selectedFilters = $request->input('filters');

        if ($request->is_delete == "1") {
            Session::forget('filterSession');
            return;
        }
        Session::put('filterSession', $selectedFilters);
        Session::save();
        return;
        $eventCreator = Event::where('id', $event)->first();
        $eventPostList = EventPost::query();
        $eventPostList->with(['user', 'post_image'])
            ->withCount([
                'event_post_comment' => function ($query) {
                    $query->where('parent_comment_id', NULL);
                },
                'event_post_reaction'
            ])
            ->where([
                'event_id' => $event,
                'is_in_photo_moudle' => '0'
            ])
            ->whereDoesntHave('post_control', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('post_control', 'hide_post');
            });
        $checkEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
        // dd($checkEventOwner);
        if ($checkEventOwner == null) {
            // dd(1);
            $eventPostList = $eventPostList->where(function ($query) use ($user, $event) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $event) {
                        $subQuery->whereHas('user', function ($userQuery) {
                            $userQuery->where('app_user', '1'); // Only app users
                        })
                            ->where('event_id', $event)
                            ->where('user_id', $user->id)
                            ->where(function ($privacyQuery) {
                                // Various privacy conditions
                                $privacyQuery->where(function ($q) {
                                    $q->where('rsvp_d', '1')
                                        ->where('rsvp_status', '1')
                                        ->where('post_privacy', '2');
                                })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '1')
                                            ->where('rsvp_status', '0')
                                            ->where('post_privacy', '3');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('rsvp_d', '0')
                                            ->where('post_privacy', '4');
                                    })
                                    ->orWhere(function ($q) {
                                        $q->where('post_privacy', '1');
                                    });
                            });
                    });
            });
        }

        // Execute the query and get the results
        // dd($selectedFilters);
        if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            $eventPostList->where(function ($query) use ($selectedFilters, $eventCreator) {
                foreach ($selectedFilters as $filter) {
                    switch ($filter) {
                        case 'host_update':
                            $query->orWhere('user_id', $eventCreator->user_id);
                            break;
                        case 'video_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'video');
                                    });
                            });
                            break;
                        case 'photo_uploads':
                            $query->orWhere(function ($qury) {
                                $qury->where('post_type', '1')
                                    ->whereHas('post_image', function ($q) {
                                        $q->where('type', 'image');
                                    });
                            });
                            break;
                        case 'polls':
                            $query->orWhere('post_type', '2');
                            break;
                        case 'comments':
                            $query->orWhere('post_type', '0');
                            break;
                        case 'rsvp':
                            $query->orWhere('post_type', '4');
                            break;
                            // Add more cases for other filters if needed
                    }
                }
            });
        }

        $eventPostList = $eventPostList->orderBy('id', 'DESC')->get();

        // $totalPostWalls = $eventPostList->count();
        // $results = $eventPostList->paginate(10);
        // $total_page_of_eventPosts = ceil($totalPostWalls / $this->perPage);

        // dd($checkEventOwner);
        //if (!empty($checkEventOwner)) {
        //dd(1);
        // if (count($results) != 0) {
        if ($eventPostList != "") {
            foreach ($eventPostList as  $value) {
                if (!$value->user) {
                    continue;
                }
                $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
                // dd($postControl);
                $count_kids_adult = EventInvitedUser::where(['event_id' => $event, 'user_id' => $value->user->id])
                    ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                    ->first();
                if ($postControl != null) {
                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $checkUserIsReaction = EventPostReaction::where(['event_id' => $event, 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                if (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') {
                    $EventPostMessageData = json_decode($value->post_message, true);
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $kids = '0';
                    $adults = '0';
                    if (isset($EventPostMessageData['status'])) {
                        $rsvpstatus = (int)$EventPostMessageData['status'];
                    }
                    if (isset($EventPostMessageData['kids'])) {
                        $kids = (int)$EventPostMessageData["kids"];
                    }
                    if (isset($EventPostMessageData['adults'])) {
                        $adults = (int)$EventPostMessageData["adults"];
                    }
                } else {
                    $kids = isset($count_kids_adult['kids']) ? $count_kids_adult['kids'] : 0;
                    $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                    $adults = isset($count_kids_adult['adults']) ? $count_kids_adult['adults'] : 0;
                }
                $postsNormalDetail['id'] =  $value->id;
                $postsNormalDetail['user_id'] =  $value->user->id;
                // $postsNormalDetail['is_host'] =  ($value->user->id == $user->id) ? 1 : 0;
                $isCoHost =  EventInvitedUser::where(['event_id' => $eventCreator->id, 'user_id' => $value->user->id, 'is_co_host' => '1'])->first();
                $postsNormalDetail['is_co_host'] = (isset($isCoHost) && $isCoHost->is_co_host != "") ? $isCoHost->is_co_host : "0";
                $postsNormalDetail['is_host'] =  ($value->user->id == $eventCreator->user_id) ? 1 : 0;
                $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus ?? "";
                $postsNormalDetail['kids'] = (int)$kids;
                $postsNormalDetail['adults'] = (int)$adults;
                $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) . ($value->user->state != "" ? ', ' . $value->user->state : '') : "";
                $postsNormalDetail['post_type'] = $value->post_type;
                $postsNormalDetail['post_privacy'] = $value->post_privacy;
                $postsNormalDetail['created_at'] = $value->created_at;
                $postsNormalDetail['posttime'] = setpostTime($value->created_at);
                $postsNormalDetail['commenting_on_off'] = $value->commenting_on_off;
                $postsNormalDetail['post_image'] = [];
                $totalEvent =  Event::where('user_id', $value->user->id)->count();
                $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                $postsNormalDetail['user_profile'] = [
                    'id' => $value->user->id,
                    'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                    'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
                    'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                    'username' => $value->user->firstname . ' ' . $value->user->lastname,
                    'location' => ($value->user->city != NULL) ? $value->user->city : "",
                    'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                    'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                    'total_events' => $totalEvent,
                    'visible' => $value->user->visible,
                    'total_photos' => $totalEventPhotos,
                    'comments' => $comments,
                    'message_privacy' => $value->user->message_privacy
                ];

                if ($value->post_type == '1' && !empty($value->post_image)) {
                    foreach ($value->post_image as $imgVal) {
                        $postMedia = [
                            'id' => $imgVal->id,
                            'media_url' => asset('storage/post_image/' . $imgVal->post_image),
                            'type' => $imgVal->type,
                            'thumbnail' => (isset($imgVal->thumbnail) && $imgVal->thumbnail != null) ?  asset('storage/thumbnails/' . $imgVal->thumbnail) : '',
                        ];
                        if ($imgVal->type == 'video' && isset($imgVal->duration) && $imgVal->duration !== "") {
                            $postMedia['video_duration'] = $imgVal->duration;
                        } else {
                            unset($postMedia['video_duration']);
                        }
                        $postsNormalDetail['post_image'][] = $postMedia;
                    }
                }
                $postsNormalDetail['total_poll_vote'] = 0;
                $postsNormalDetail['poll_duration'] = "";
                $postsNormalDetail['is_expired'] = false;
                $postsNormalDetail['poll_id'] = 0;
                $postsNormalDetail['poll_question'] = "";
                $postsNormalDetail['poll_option'] = [];
                if ($value->post_type == '2') {
                    // Poll
                    $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event, 'event_post_id' => $value->id])->first();
                    $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                    $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                    $postsNormalDetail['poll_duration'] = $pollDura;
                    $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                    $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                    $postsNormalDetail['poll_id'] = $polls->id;
                    $postsNormalDetail['poll_question'] = $polls->poll_question;
                    $postsNormalDetail['total_poll_duration'] = $polls->poll_duration;

                    foreach ($polls->event_poll_option as $optionValue) {
                        $optionData['id'] = $optionValue->id;
                        $optionData['option'] = $optionValue->option;
                        $optionData['total_vote'] =  "0%";
                        if (getOptionAllTotalVote($polls->id) != 0) {
                            $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
                        }
                        $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $polls->id, $optionValue->id);
                        $postsNormalDetail['poll_option'][] = $optionData;
                    }
                }
                $postsNormalDetail['post_recording'] = empty($value->post_recording) ? "" : asset('storage/event_post_recording/' . $value->post_recording);
                $reactionList = getOnlyReaction($value->id);
                $postsNormalDetail['reactionList'] = $reactionList;
                $postsNormalDetail['total_comment'] = $value->event_post_comment_count;
                $postsNormalDetail['total_likes'] = $value->event_post_reaction_count;
                $postsNormalDetail['is_reaction'] = ($checkUserIsReaction != NULL) ? '1' : '0';
                $postsNormalDetail['self_reaction'] = ($checkUserIsReaction != NULL) ? $checkUserIsReaction->reaction : "";
                $postsNormalDetail['is_owner_post'] = ($value->user->id == $user->id) ? 1 : 0;
                $postsNormalDetail['is_mute'] =  0;
                if ($postControl != null) {
                    if ($postControl->post_control == 'mute') {
                        $postsNormalDetail['is_mute'] =  1;
                    }
                }
                $postCommentList = [];

                $postComment = getComments($value->id);
                foreach ($postComment as $commentVal) {
                    $commentInfo['id'] = $commentVal->id;

                    $commentInfo['event_post_id'] = $commentVal->event_post_id;

                    $commentInfo['comment'] = $commentVal->comment_text;

                    $commentInfo['user_id'] = $commentVal->user_id;

                    // $commentInfo['username'] = $commentVal->user->firstname . ' ' . $commentVal->user->lastname;
                    $firstName = $commentVal->user->firstname ?? '';
                    $lastName = $commentVal->user->lastname ?? '';

                    // Concatenate only if at least one value exists
                    $commentInfo['username'] = trim($firstName . ' ' . $lastName) ?: null;

                    $commentInfo['profile'] = (!empty($commentVal->user->profile)) ? asset('storage/profile/' . $commentVal->user->profile) : "";
                    // $postsNormalDetail['location'] = $value->user->city != "" ? trim($value->user->city) .($value->user->state != "" ? ', ' . $value->user->state : ''): "";
                    // $commentInfo['location'] = ($commentVal->user->city != NULL) ? $commentVal->user->city : "";
                    // $commentInfo['location'] = $commentVal->user->city != "" ? trim($commentVal->user->city) . ($commentVal->user->state != "" ? ', ' . $commentVal->user->state : '') : "";
                    $commentInfo['location'] = null; // Default value

                    if (!empty($commentVal->user)) {
                        $city = trim($commentVal->user->city ?? '');
                        $state = trim($commentVal->user->state ?? '');

                        $commentInfo['location'] = ($city || $state) ? ($city . ($state ? ', ' . $state : '')) : null;
                    }


                    $commentInfo['comment_total_likes'] = $commentVal->post_comment_reaction_count;

                    $commentInfo['is_like'] = checkUserIsLike($commentVal->id, $user->id);

                    $commentInfo['total_replies'] = $commentVal->replies_count;

                    $commentInfo['created_at'] = $commentVal->created_at;
                    $commentInfo['posttime'] = setpostTime($commentVal->created_at);

                    $commentInfo['comment_replies'] = [];

                    foreach ($commentVal->replies as $reply) {
                        $mainParentId = (new EventPostComment())->getMainParentId($reply->parent_comment_id);

                        $replyCommentInfo['id'] = $reply->id;

                        $replyCommentInfo['event_post_id'] = $reply->event_post_id;
                        $replyCommentInfo['main_comment_id'] = $reply->main_parent_comment_id;

                        $replyCommentInfo['comment'] = $reply->comment_text;

                        $replyCommentInfo['user_id'] = $reply->user_id;
                        $firstName = $reply->user->firstname ?? '';
                        $lastName = $reply->user->lastname ?? '';

                        // Concatenate only if at least one value exists
                        $replyCommentInfo['username'] = trim($firstName . ' ' . $lastName) ?: null;
                        // $replyCommentInfo['username'] = $reply->user->firstname . ' ' . $reply->user->lastname;

                        $replyCommentInfo['profile'] = (!empty($reply->user->profile)) ? asset('storage/profile/' . $reply->user->profile) : "";

                        // $replyCommentInfo['location'] = ($reply->user->city != NULL) ? $reply->user->city : "";
                        // $replyCommentInfo['location'] =  $reply->user->city != "" ? trim($reply->user->city) . ($reply->user->state != "" ? ', ' . $reply->user->state : '') : "";
                        $replyCommentInfo['location']  = null; // Default value

                        if (!empty($reply->user)) {
                            $city = trim($reply->user->city ?? '');
                            $state = trim($reply->user->state ?? '');

                            $replyCommentInfo['location'] = ($city || $state) ? ($city . ($state ? ', ' . $state : '')) : null;
                        }
                        // $replyCommentInfo['comment_total_likes'] = $reply->post_comment_reaction_count;

                        $replyCommentInfo['is_like'] = checkUserIsLike($reply->id, $user->id);

                        $replyCommentInfo['total_replies'] = $reply->replies_count;

                        $replyCommentInfo['created_at'] = $reply->created_at;
                        $replyCommentInfo['posttime'] = setpostTime($reply->created_at);
                        $commentInfo['comment_replies'][] = $replyCommentInfo;


                        $replyComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $reply->event_post_id, 'parent_comment_id' => $reply->id])->orderBy('id', 'DESC')->get();

                        foreach ($replyComment as $childReplyVal) {

                            if ($childReplyVal->parent_comment_id != $childReplyVal->main_parent_comment_id) {

                                $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childReplyVal->id)->count();


                                $commentChildReply['id'] = $childReplyVal->id;

                                $commentChildReply['event_post_id'] = $childReplyVal->event_post_id;
                                $commentChildReply['main_comment_id'] = $childReplyVal->main_parent_comment_id;
                                $commentChildReply['comment'] = $childReplyVal->comment_text;
                                $commentChildReply['user_id'] = $childReplyVal->user_id;

                                $commentChildReply['username'] = $childReplyVal->user->firstname . ' ' . $childReplyVal->user->lastname;

                                $commentChildReply['profile'] = (!empty($childReplyVal->user->profile)) ? asset('storage/profile/' . $childReplyVal->user->profile) : "";
                                $commentChildReply['location'] = (!empty($childReplyVal->user->city)) ? $childReplyVal->user->city : "";

                                $commentChildReply['comment_total_likes'] = ($childReplyVal->post_comment_reaction_count != "") ? $childReplyVal->post_comment_reaction_count : "0";

                                $commentChildReply['is_like'] = checkUserIsLike($childReplyVal->id, $user->id);

                                $commentChildReply['total_replies'] = $totalReply;
                                $commentChildReply['posttime'] = setpostTime($childReplyVal->created_at);
                                $commentChildReply['created_at'] = $childReplyVal->created_at;

                                $commentInfo['comment_replies'][] = $commentChildReply;

                                $replyChildComment =  EventPostComment::with(['user'])->withcount('post_comment_reaction', 'replies')->where(['main_parent_comment_id' => $mainParentId, 'event_post_id' => $childReplyVal->event_post_id, 'parent_comment_id' => $childReplyVal->id])->orderBy('id', 'DESC')->get();

                                foreach ($replyChildComment as $childInReplyVal) {

                                    if ($childInReplyVal->parent_comment_id != $childInReplyVal->main_parent_comment_id) {

                                        $totalReply = EventPostComment::withcount('post_comment_reaction')->where("parent_comment_id", $childInReplyVal->id)->count();


                                        $commentChildInReply['id'] = $childInReplyVal->id;

                                        $commentChildInReply['event_post_id'] = $childInReplyVal->event_post_id;
                                        $commentChildInReply['main_comment_id'] = $childInReplyVal->main_parent_comment_id;
                                        $commentChildInReply['comment'] = $childInReplyVal->comment_text;
                                        $commentChildInReply['user_id'] = $childInReplyVal->user_id;

                                        $commentChildInReply['username'] = $childInReplyVal->user->firstname . ' ' . $childInReplyVal->user->lastname;

                                        $commentChildInReply['profile'] = (!empty($childInReplyVal->user->profile)) ? asset('storage/profile/' . $childInReplyVal->user->profile) : "";
                                        $commentChildInReply['location'] = (!empty($childInReplyVal->user->city)) ? $childInReplyVal->user->city : "";

                                        // $commentChildInReply['comment_total_likes'] = $childInReplyVal->post_comment_reaction_count;
                                        $commentChildInReply['comment_total_likes'] = ($childInReplyVal->post_comment_reaction_count != "") ? $childInReplyVal->post_comment_reaction_count : "0";

                                        $commentChildInReply['is_like'] = checkUserIsLike($childInReplyVal->id, $user->id);

                                        $commentChildInReply['total_replies'] = $totalReply;
                                        $commentChildInReply['posttime'] = setpostTime($childInReplyVal->created_at);
                                        $commentChildInReply['created_at'] = $childInReplyVal->created_at;

                                        $commentInfo['comment_replies'][] = $commentChildInReply;
                                    }
                                }
                            }
                        }
                    }


                    $postCommentList[] = $commentInfo;
                }

                $postsNormalDetail['post_comment'] = $postCommentList;
                $postList[] = $postsNormalDetail;
            }

            $login_user_id  = $user->id;

            // dd($postList);
            return response()->json(['view' => view('front.event_wall.filter_wall_post', compact('postList', 'login_user_id', 'event'))->render()]);
        }
    }

    public function fetch_all_invited_user(Request $request)
    {

        $user  = Auth::guard('web')->user();

        $eventId = $request->event_id;
        $fetch_event_data = Event::where('id', $eventId)->first();
        $is_host = 0;
        if ($fetch_event_data->user_id == $user->id) {
            $is_host = 1;
        }
        $all_invited_user = getInvitedUsersListNew($eventId);


        return response()->json(['view' => view('front.event_wall.right_all_guest_list', compact('all_invited_user', 'eventId', 'is_host'))->render(), 'status' => 1]);

        // return response()->json(['view' => 1, 'data' => $faildInviteList, 'message' => "Faild invites"]);


    }
}

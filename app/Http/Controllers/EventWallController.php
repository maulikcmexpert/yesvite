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
    EventPostImage,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    UserPotluckItem,
    PostControl,
    EventPostReaction,
    EventUserStory,
    UserSeenStory,
    EventPostPoll,

    
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;class EventWallController extends Controller
{
    protected $perPage;

    public function __construct(){
        $this->perPage = 5;
    }
    public function index(String $id){
       
        $user  = Auth::guard('web')->user();
        $event_id=$id;
        if ($event_id == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            $page = (isset($input['page'])) ? $input['page'] : "1";
            $this->eventViewUser($user->id, $event_id);
            $currentDateTime = Carbon::now();
            $wallData = [];
            $wallData['owner_stories'] = [];

            $eventLoginUserStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])
                ->where(['event_id' => $event_id, 'user_id' => $user->id])
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

            $totalStories =  EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                $query->where('created_at', '>', now()->subHours(24));
            }])
                ->where('event_id', $event_id)
                ->where('created_at', '>', now()->subHours(24))
                ->where('user_id', '!=', $user->id)->count();

            // if (isset($input['type']) && ($input['type'] == '1' || $input['type'] == '0')) {

            //     $total_page_of_stories = ceil($totalStories / $this->perPage);
            //     $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
            //         $query->where('created_at', '>', now()->subHours(24));
            //     }])
            //         ->where('created_at', '>', now()->subHours(24))
            //         ->where('event_id', $event_id)
            //         ->where('user_id', '!=', $user->id)
            //         ->paginate($this->perPage, ['*'], 'page', $page);
            // } else {
                $total_page_of_stories = ceil($totalStories / $this->perPage);
                $eventStoriesList = EventUserStory::with(['user', 'user_event_story' => function ($query) use ($currentDateTime) {
                    $query->where('created_at', '>', now()->subHours(24));
                }])
                    ->where('created_at', '>', now()->subHours(24))
                    ->where('event_id', $event_id)
                    ->where('user_id', '!=', $user->id)->paginate($this->perPage, ['*'], 'page', "1");
            // }
            $storiesList = [];
            if (count($eventStoriesList) != 0) {
                foreach ($eventStoriesList as $value) {
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
            //  Posts List //
            // $selectedFilters = $request->input('filters');
            $eventCreator = Event::where('id', $event_id)->first();
            $eventPostList = EventPost::query();
            $eventPostList->with(['user', 'post_image'])
                ->withCount([
                    'event_post_comment' => function ($query) {
                        $query->where('parent_comment_id', NULL);
                    },
                    'event_post_reaction'
                ])
                ->where([
                    'event_id' => $event_id,
                    'is_in_photo_moudle' => '0'
                ])
                ->whereDoesntHave('post_control', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('post_control', 'hide_post');
                });
            $checkEventOwner = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
            if ($checkEventOwner == null) {
                $eventPostList->where(function ($query) use ($user, $event_id) {
                    $query->where('user_id', $user->id)
                        ->orWhereHas('event.event_invited_user', function ($subQuery) use ($user, $event_id) {
                            $subQuery->whereHas('user', function ($userQuery) {
                                $userQuery->where('app_user', '1');
                            })
                                ->where('event_id', $event_id)
                                ->where('user_id', $user->id)
                                ->where(function ($privacyQuery) {
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
            $eventPostList->orderBy('id', 'DESC');
            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            //     $eventPostList->where(function ($query) use ($selectedFilters, $eventCreator) {
            //         foreach ($selectedFilters as $filter) {
            //             switch ($filter) {
            //                 case 'host_update':
            //                     $query->orWhere('user_id', $eventCreator->user_id);
            //                     break;
            //                 case 'video_uploads':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where('post_type', '1')
            //                             ->whereHas('post_image', function ($q) {
            //                                 $q->where('type', 'video');
            //                             });
            //                     });
            //                     break;
            //                 case 'photo_uploads':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where('post_type', '1')
            //                             ->whereHas('post_image', function ($q) {
            //                                 $q->where('type', 'image');
            //                             });
            //                     });
            //                     break;
            //                 case 'polls':
            //                     $query->orWhere('post_type', '2');
            //                     break;
            //                 case 'comments':
            //                     $query->orWhere('post_type', '0');
            //                     break;
            //                     // Add more cases for other filters if needed
            //             }
            //         }
            //     });
            // }

            $totalPostWalls = $eventPostList->count();
            $results = $eventPostList->paginate($this->perPage, ['*'], 'page', $page);
            $total_page_of_eventPosts = ceil($totalPostWalls / $this->perPage);
            $postList = [];
            // dd($eventPostList);
            if (!empty($checkEventOwner)) {
                if (count($results) != 0) {
                    foreach ($results as  $value) {
                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                        $ischeckEventOwner = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
                        $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event_id, 'event_post_id' => $value->id])->first();
                        $count_kids_adult = EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $value->user->id])
                            ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                            ->first();
                        if ($postControl != null) {
                            if ($postControl->post_control == 'hide_post') {
                                continue;
                            }
                        }
                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $event_id, 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

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
                        $postsNormalDetail['is_host'] =  ($value->user->id == $user->id) ? 1 : 0;
                        $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                        $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                        $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                        $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus;
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
                            $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event_id, 'event_post_id' => $value->id])->first();
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
                        $postList[] = $postsNormalDetail;
                    }
                }
            } else {
                if (count($results) != 0) {
                    foreach ($results as $value) {

                        $checkUserRsvp = checkUserAttendOrNot($value->event_id, $value->user->id);
                        $count_kids_adult = EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $value->user->id])
                            ->select('kids', 'adults', 'event_id', 'rsvp_status', 'user_id')
                            ->first();
                        $ischeckEventOwner = Event::where(['id' => $event_id, 'user_id' => $value->user->id])->first();
                        $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event_id, 'event_post_id' => $value->id])->first();
                        $checkUserIsReaction = EventPostReaction::where(['event_id' => $event_id, 'event_post_id' => $value->id, 'user_id' => $user->id])->first();

                        // if ($value->post_privacy == '1') {
                        // $EventPostMessageData = [];
                        if (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') {
                            $EventPostMessageData = json_decode($value->post_message, true);
                            $rsvpstatus = (isset($value->post_type) && $value->post_type == '4' && $value->post_message != '') ? $value->post_message : $checkUserRsvp;
                            $kids = '0';
                            $adults = '0';
                            if (isset($EventPostMessageData['status'])) {
                                $rsvpstatus = (string)$EventPostMessageData['status'];
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
                        $postsNormalDetail['username'] =  $value->user->firstname . ' ' . $value->user->lastname;
                        $postsNormalDetail['profile'] =  empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile);
                        $postsNormalDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                        $postsNormalDetail['post_message'] = (empty($value->post_message) || $value->post_type == '4') ? "" :  $value->post_message;
                        $postsNormalDetail['rsvp_status'] = (string)$rsvpstatus;
                        $postsNormalDetail['kids'] = (int)$kids;
                        $postsNormalDetail['adults'] = (int)$adults;
                        $postsNormalDetail['location'] = ($value->user->city != NULL) ? $value->user->city : "";
                        $postsNormalDetail['commenting_on_off'] = $value->commenting_on_off;
                        $postsNormalDetail['post_type'] = $value->post_type;
                        $postsNormalDetail['post_privacy'] = $value->post_privacy;
                        $postsNormalDetail['created_at'] = $value->created_at;
                        $postsNormalDetail['posttime'] = setpostTime($value->created_at);
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
                        if ($value->post_type == '2') { // Poll
                            $polls = EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event_id, 'event_post_id' => $value->id])->first();
                            $postsNormalDetail['total_poll_vote'] = $polls->user_poll_data_count;
                            $pollDura = getLeftPollTime($polls->updated_at, $polls->poll_duration);
                            $postsNormalDetail['poll_duration'] = $pollDura;
                            // $postsNormalDetail['poll_duration'] =  empty($polls->poll_duration) ? "" :  $polls->poll_duration;
                            $leftDay = (int) preg_replace('/[^0-9]/', '', $polls->poll_duration);
                            $postsNormalDetail['is_expired'] =  ($pollDura == "") ? true : false;
                            $postsNormalDetail['poll_id'] = $polls->id;
                            $postsNormalDetail['poll_question'] = $polls->poll_question;
                            $postsNormalDetail['total_poll_duration'] = $polls->poll_duration;
                            foreach ($polls->event_poll_option as $optionValue) {
                                $optionData['id'] = $optionValue->id;
                                $optionData['option'] = $optionValue->option;
                                $optionData['total_vote'] = "0%";
                                if (getOptionAllTotalVote($polls->id) != 0) {
                                    $optionData['total_vote'] =   round(getOptionTotalVote($optionValue->id) / getOptionAllTotalVote($polls->id) * 100) . "%";
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
                        $postList[] = $postsNormalDetail;
                    
                    }
                }
            }
            $userrsvp_status = EventInvitedUser::where(['user_id' => $user->id, 'event_id' => $event_id])->pluck('rsvp_status')->first();
            $rsvp_status = (isset($userrsvp_status) && $userrsvp_status != "") ? $userrsvp_status : "";
            $wallData['stories'] = $storiesList;
            $wallData['posts'] = $postList;
            $filename = 'event_wall_response.txt';
            $commentnumber = json_encode(['status' => 1, 'rsvp_status' => $rsvp_status, 'total_page_of_stories' => $total_page_of_stories, 'total_page_of_eventPosts' => $total_page_of_eventPosts, 'data' => $wallData, 'message' => "Event wall data"]);
            Storage::append($filename, $commentnumber);
            
            return compact('rsvp_status','total_page_of_stories','total_page_of_eventPosts','wallData');
            // return response()->json(['status' => 1, 'rsvp_status' => $rsvp_status, 'total_page_of_stories' => $total_page_of_stories, 'total_page_of_eventPosts' => $total_page_of_eventPosts, 'data' => $wallData, 'message' => "Event wall data", 'subscription_plan_name' => $eventCreator->subscription_plan_name]);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
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
}

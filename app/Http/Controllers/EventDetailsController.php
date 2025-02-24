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
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EventDetailsController extends BaseController
{

    protected $perPage;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = 5;
    }

    public function index(String $id)
    {

        $user  = Auth::guard('web')->user();
        $event_id = $id;
        if ($event_id == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            //event_wall_data
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

            // return compact('rsvp_status','total_page_of_stories','total_page_of_eventPosts','wallData');

            //event_wall_data//



            //event_about_data
            $eventDetail = Event::with([
                'user',
                'event_image' => function ($query) {
                    $query->orderBy('type', 'ASC');
                },
                'event_schedule',
                'event_settings',
                'event_invited_user' => function ($query) {
                    $query->where('is_co_host', '1')->with('user');
                }
            ])->where('id', $event_id)->first();
            $guestView = [];
            $eventDetails['id'] = $eventDetail->id;
            $eventDetails['event_images'] = [];
            if (count($eventDetail->event_image) != 0) {
                foreach ($eventDetail->event_image as $values) {
                    $eventDetails['event_images'][] = asset('storage/event_images/' . $values->image);
                }
            }
            $eventDetails['user_profile'] = empty($eventDetail->user->profile) ? "" : asset('storage/profile/' . $eventDetail->user->profile);
            $eventDetails['event_name'] = $eventDetail->event_name;
            $eventDetails['hosted_by'] = $eventDetail->hosted_by;
            $eventDetails['is_host'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
            $eventDetails['event_date'] = $eventDetail->start_date;
            $eventDetails['event_time'] = $eventDetail->rsvp_start_time;
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
                $coHostDetail['name'] = $hostValues->user->firstname . ' ' . $hostValues->user->lastname;
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
                if (empty($eventData)) {
                    $eventData[] = date('F d, Y', strtotime($eventDetail->start_date));
                    $numberOfGuest = EventInvitedUser::where('event_id', $eventDetail->id)->count();
                    $eventData[] = "Number of guests : " . $numberOfGuest;
                }
                $eventDetails['event_detail'] = $eventData;
            }
            $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
            $eventInfo['guest_view'] = $eventDetails;

            //  Host view //
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
            $eventAboutHost['total_invite'] =  count(getEventInvitedUser($event_id));
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

            //event_about_data//

            //event_guest_data
            $eventDetail = Event::with(['user', 'event_settings', 'event_image' => function ($query) {
                $query->orderBy('type', 'ASC'); // Order event images by type
            }, 'event_schedule' => function ($query) {}])->where('id', $event_id)->first();
            $eventattending = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])->count();

            $eventNotComing = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['rsvp_d' => '1', 'rsvp_status' => '0', 'event_id' => $eventDetail->id])->count();

            $pendingUser = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_d' => '0'])->count();

            $adults = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('adults');

            $kids = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'rsvp_status' => '1', 'rsvp_d' => '1'])->sum('kids');

            $eventGuest['is_event_owner'] = ($eventDetail->user_id == $user->id) ? 1 : 0;
            $eventGuest['event_wall'] = $eventDetail->event_settings->event_wall;
            $eventGuest['guest_list_visible_to_guests'] = $eventDetail->event_settings->guest_list_visible_to_guests;
            $eventGuest['attending'] = $adults + $kids;
            $eventGuest['total_invitation'] =  count(getEventInvitedUser($event_id));
            $eventGuest['adults'] = (int)$adults;
            $eventGuest['kids'] =  (int)$kids;
            $eventGuest['not_attending'] = $eventNotComing;
            $eventGuest['pending'] = $pendingUser;
            $eventGuest['allow_limit'] = $eventDetail->event_settings->allow_limit;
            $eventGuest['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
            $eventGuest['subscription_plan_name'] = ($eventDetail->subscription_plan_name != NULL) ? $eventDetail->subscription_plan_name : "";
            $eventGuest['subscription_invite_count'] = ($eventDetail->subscription_invite_count != NULL) ? $eventDetail->subscription_invite_count : 0;
            $eventGuest['is_past'] = ($eventDetail->end_date < date('Y-m-d')) ? true : false;

            $userRsvpStatusList = EventInvitedUser::query();
            $userRsvpStatusList->whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'invitation_sent' => '1'])->get();
            // $selectedFilters = $request->input('filters');
            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {

            //     $userRsvpStatusList->where(function ($query) use ($selectedFilters) {
            //         foreach ($selectedFilters as $filter) {

            //             switch ($filter) {
            //                 case 'attending':
            //                     $query->orWhere('rsvp_status', '1');
            //                     break;
            //                 case 'not_attending':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where(['rsvp_status' => '0']);
            //                     });
            //                     break;
            //                 case 'no_reply':
            //                     $query->orWhere(function ($qury) {
            //                         $qury->where(['rsvp_status' => NULL]);
            //                     });
            //                     break;
            //             }
            //         }
            //     });
            // }
            $result = $userRsvpStatusList->get();
            $eventGuest['rsvp_status_list'] = [];
            if (count($result) != 0) {
                foreach ($result as $value) {
                    $rsvpUserStatus = [];
                    $rsvpUserStatus['id'] = $value->id;
                    $rsvpUserStatus['user_id'] = $value->user->id;
                    $rsvpUserStatus['first_name'] = $value->user->firstname;
                    $rsvpUserStatus['last_name'] = $value->user->lastname;
                    $rsvpUserStatus['username'] = $value->user->firstname . ' ' . $value->user->lastname;
                    $rsvpUserStatus['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                    $rsvpUserStatus['email'] = ($value->user->email != '') ? $value->user->email : "";
                    $rsvpUserStatus['phone_number'] = ($value->user->phone_number != '') ? $value->user->phone_number : "";
                    $rsvpUserStatus['prefer_by'] =  $value->prefer_by;
                    $rsvpUserStatus['kids'] = $value->kids;
                    $rsvpUserStatus['adults'] = $value->adults;
                    $rsvpUserStatus['rsvp_status'] =  ($value->rsvp_status != null) ? (int)$value->rsvp_status : NULL;
                    if ($value->rsvp_d == '0' && ($value->read == '1' || $value->read == '0') || $value->rsvp_status == null) {
                        $rsvpUserStatus['rsvp_status'] = 2; // no reply 
                    }
                    $rsvpUserStatus['read'] = $value->read;
                    $rsvpUserStatus['rsvp_d'] = $value->rsvp_d;
                    $rsvpUserStatus['invitation_sent'] = $value->invitation_sent;
                    $totalEvent =  Event::where('user_id', $value->user->id)->count();
                    $totalEventPhotos =  EventPost::where(['user_id' => $value->user->id, 'post_type' => '1'])->count();
                    $comments =  EventPostComment::where('user_id', $value->user->id)->count();
                    $rsvpUserStatus['user_profile'] = [
                        'id' => $value->user->id,
                        'profile' => empty($value->user->profile) ? "" : asset('storage/profile/' . $value->user->profile),
                        'bg_profile' => empty($value->user->bg_profile) ? "" : asset('storage/bg_profile/' . $value->user->bg_profile),
                        'app_user' =>  $value->user->app_user,
                        'gender' => ($value->user->gender != NULL) ? $value->user->gender : "",
                        'first_name' => $value->user->firstname,
                        'last_name' => $value->user->lastname,
                        'username' => $value->user->firstname . ' ' . $value->user->lastname,
                        'location' => ($value->user->city != NULL) ? $value->user->city : "",
                        'about_me' => ($value->user->about_me != NULL) ? $value->user->about_me : "",
                        'created_at' => empty($value->user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($value->user->created_at))),
                        'total_events' => $totalEvent,
                        'visible' => $value->user->visible,
                        'total_photos' => $totalEventPhotos,
                        'comments' => $comments,
                        'message_privacy' =>  $value->user->message_privacy
                    ];
                    $eventGuest['rsvp_status_list'][] = $rsvpUserStatus;
                }
            }
            $getInvitedusers = getInvitedUsers($event_id);
            $eventGuest['invited_user_id'] = $getInvitedusers['invited_user_id'];
            $eventGuest['invited_guests'] = $getInvitedusers['invited_guests'];
            //  event about view //
            $getEventData = Event::with('event_schedule')->where('id', $event_id)->first();
            $eventGuest['remaining_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? ($getEventData->subscription_invite_count - (count($eventGuest['invited_user_id']) + count($eventGuest['invited_guests']))) : 0;

            $totalEnvitedUser = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id])->count();

            $todayrsvprate = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['rsvp_status' => '1', 'event_id' => $eventDetail->id])
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->count();

            $eventGuest['total_invite'] =  count(getEventInvitedUser($event_id));
            $eventGuest['invite_view_rate'] = EventInvitedUser::whereHas('user', function ($query) {
                $query->where('app_user', '1');
            })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count();

            $invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {
                $invite_view_percent = EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'read' => '1'])->count() / $totalEnvitedUser * 100;
            }

            $eventGuest['invite_view_percent'] = round($invite_view_percent, 2) . "%";
            $today_invite_view_percent = 0;
            if ($totalEnvitedUser != 0) {
                $today_invite_view_percent =   EventInvitedUser::whereHas('user', function ($query) {
                    $query->where('app_user', '1');
                })->where(['event_id' => $eventDetail->id, 'read' => '1', 'event_view_date' => date('Y-m-d')])->count() / $totalEnvitedUser * 100;
            }

            $eventGuest['today_invite_view_percent'] = round($today_invite_view_percent, 2)  . "%";
            $eventGuest['rsvp_rate'] = $eventattending;
            $eventGuest['rsvp_rate_percent'] = ($totalEnvitedUser != 0) ? $eventattending / $totalEnvitedUser * 100 . "%" : 0 . "%";
            $eventGuest['today_upstick'] = ($totalEnvitedUser != 0) ? $todayrsvprate / $totalEnvitedUser * 100 . "%" : 0 . "%";
            // return compact('eventGuest'); 
            //event_guest_data//

            //event_photo_data
            // $selectedFilters = $request->input('filters');
            $getPhotoList = EventPost::query();
            $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])->withCount(['event_post_reaction', 'post_image', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where(['event_id' => $event_id, 'post_type' => '1']);
            $eventCreator = Event::where('id', $event_id)->first();
            // if (!empty($selectedFilters) && !in_array('all', $selectedFilters)) {
            //     $getPhotoList->where(function ($query) use ($selectedFilters, $eventCreator) {
            //         foreach ($selectedFilters as $filter) {
            //             switch ($filter) {
            //                 case 'time_posted':
            //                     $query->orderBy('id', 'desc');
            //                     break;
            //                 case 'guest':
            //                     $query->orWhere('user_id', '!=', $eventCreator->user_id);

            //                     break;
            //                 case 'photos':
            //                     $query->orWhereHas('post_image', function ($subQuery) {
            //                         $subQuery->where('type', 'image');
            //                     });
            //                     break;
            //                 case 'videos':
            //                     $query->orWhereHas('post_image', function ($subQuery) {
            //                         $subQuery->where('type', 'video');
            //                     });
            //                     break;
            //                     // Add more cases for other filters if needed
            //             }
            //         }
            //     });
            // }
            $getPhotoList->orderBy('id', 'desc');
            $results = $getPhotoList->get();
            $postPhotoList = [];
            foreach ($results as $value) {
                $ischeckEventOwner = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event_id, 'event_post_id' => $value->id])->first();
                if ($postControl != null) {

                    if ($postControl->post_control == 'hide_post') {
                        continue;
                    }
                }
                $postPhotoDetail['user_id'] = $value->user->id;
                $postPhotoDetail['is_own_post'] = ($value->user->id == $user->id) ? "1" : "0";
                $postPhotoDetail['is_host'] =  ($ischeckEventOwner != null) ? 1 : 0;
                $postPhotoDetail['firstname'] = $value->user->firstname;
                $postPhotoDetail['lastname'] = $value->user->lastname;
                $postPhotoDetail['profile'] = (!empty($value->user->profile) || $value->user->profile != NULL) ? asset('storage/profile/' . $value->user->profile) : "";
                $selfReaction = EventPostReaction::where(['user_id' => $user->id, 'event_post_id' => $value->id])->first();
                $postPhotoDetail['is_reaction'] = ($selfReaction != NULL) ? '1' : '0';
                $postPhotoDetail['self_reaction'] = ($selfReaction != NULL) ? $selfReaction->reaction : "";
                $postPhotoDetail['event_id'] = $value->event_id;
                $postPhotoDetail['id'] = $value->id;
                $postPhotoDetail['post_message'] = (!empty($value->post_message) || $value->post_message != NULL) ? $value->post_message : "";
                $postPhotoDetail['post_time'] = $this->setpostTime($value->updated_at);
                $postPhotoDetail['is_in_photo_moudle'] = $value->is_in_photo_moudle;
                $photoVideoData = "";
                if (!empty($value->post_image)) {
                    $photData = $value->post_image;
                    foreach ($photData as $val) {
                        $photoVideoDetail['id'] = $val->id;
                        $photoVideoDetail['event_post_id'] = $val->event_post_id;
                        $photoVideoDetail['post_media'] = (!empty($val->post_image) || $val->post_media != NULL) ? asset('storage/post_image/' . $val->post_image) : "";
                        $photoVideoDetail['thumbnail'] = (!empty($val->thumbnail) || $val->thumbnail != NULL) ? asset('storage/thumbnails/' . $val->thumbnail) : "";
                        $photoVideoDetail['type'] = $val->type;
                        $photoVideoData = $photoVideoDetail;
                    }
                }

                $postPhotoDetail['mediaData'] = $photoVideoData;
                $postPhotoDetail['total_media'] = ($value->post_image_count - 1 != 0 && $value->post_image_count - 1 != -1)  ? "+" . $value->post_image_count - 1 : "";
                $getPhotoReaction = getReaction($value->id);
                $reactionList = [];
                foreach ($getPhotoReaction as $values) {
                    $reactionList[] = $values->reaction;
                }
                $postPhotoDetail['reactionList'] = $reactionList;
                $postPhotoDetail['total_likes'] = $value->event_post_reaction_count;
                $postPhotoDetail['total_comments'] = $value->event_post_comment_count;
                $postPhotoList[] = $postPhotoDetail;
            }
            if (empty($postPhotoList)) {
                $postPhotoList = [];
            }
            //  if (!empty($postPhotoList)) {
            //     //  return compact('postPhotoList');
            //      // return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            //  } else {
            //      $postPhotoList="";
            //     //  return compact('postPhotoList');
            //      // return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            //  }

            //event_photo_data//

            //event_potluck_data
            $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                $query->with(['users', 'user_potluck_items' => function ($subquery) {
                    $subquery->with('users')->sum('quantity');
                }]);
            }])->withCount('event_potluck_category_item')->where('event_id', $event_id)->get();
            $totalItems = EventPotluckCategoryItem::where('event_id', $event_id)->sum('quantity');
            $spoken_for = UserPotluckItem::where('event_id', $event_id)->sum('quantity');
            $checkEventOwner = Event::FindOrFail($event_id);
            $potluckDetail['total_potluck_categories'] = count($eventpotluckData);
            $potluckDetail['is_event_owner'] = ($checkEventOwner->user_id == $user->id) ? 1 : 0;
            $potluckDetail['is_past'] = ($checkEventOwner['end_date'] < date('Y-m-d')) ? true : false;
            $potluckDetail['potluck_items'] = $totalItems;
            $potluckDetail['spoken_for'] = $spoken_for;
            $potluckDetail['left'] = $totalItems - $spoken_for;
            $potluckDetail['item'] = $totalItems;
            $potluckDetail['available'] = $totalItems;
            if (!empty($eventpotluckData)) {
                $potluckCategoryData = [];
                $potluckItemsSummury = [];
                //   dd($eventpotluckData);
                foreach ($eventpotluckData as $value) {
                    $itempotluckCategory['id'] = $value->id;
                    $itempotluckCategory['category'] = $value->category;
                    $itempotluckCategory['total_items'] =  $value->event_potluck_category_item_count;

                    $i = 0;
                    $totalSpoken = 0;
                    foreach ($value->event_potluck_category_item as  $checkItem) {
                        $mainQty = $checkItem->quantity;
                        $spokenFor = UserPotluckItem::where('event_potluck_item_id', $checkItem->id)->sum('quantity');
                        if ($mainQty <= $spokenFor) {
                            $totalSpoken += 1;
                        }
                    }
                    $itempotluckCategory['spoken_items'] = $totalSpoken;
                    $potluckItemsSummury[] = $itempotluckCategory;
                }
                $potluckDetail['item_summary'] = $potluckItemsSummury;
                foreach ($eventpotluckData as $value) {
                    $potluckCategory['id'] = $value->id;
                    $potluckCategory['category'] = $value->category;
                    $potluckCategory['created_by'] = $value->users->firstname . ' ' . $value->users->lastname;
                    $potluckCategory['quantity'] = $value->quantity;
                    $potluckCategory['items'] = [];
                    if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {
                        foreach ($value->event_potluck_category_item as $itemValue) {
                            $potluckItem['id'] =  $itemValue->id;
                            $potluckItem['description'] =  $itemValue->description;
                            $potluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                            $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                            $potluckItem['quantity'] =  $itemValue->quantity;
                            $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                            $potluckItem['spoken_quantity'] =  $spoken_for;
                            $potluckItem['item_carry_users'] = [];
                            foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                $userPotluckItem['id'] = $itemcarryUser->id;
                                $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                $userPotluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                                $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('storage/profile/' . $itemcarryUser->users->profile);
                                $userPotluckItem['first_name'] = $itemcarryUser->users->firstname;
                                $userPotluckItem['quantity'] = (!empty($itemcarryUser->quantity) || $itemcarryUser->quantity != NULL) ? $itemcarryUser->quantity : "0";
                                $userPotluckItem['last_name'] = $itemcarryUser->users->lastname;
                                $potluckItem['item_carry_users'][] = $userPotluckItem;
                            }
                            $potluckCategory['items'][] = $potluckItem;
                        }
                    }
                    $potluckCategoryData[] = $potluckCategory;
                }

                $potluckDetail['podluck_category_list'] = $potluckCategoryData;

                // return compact('potluckDetail');
                // return response()->json(['status' => 1, 'data' => $potluckDetail, 'message' => " Potluck data"]);
            } else {
                $potluckDetail = "";
                // return response()->json(['status' => 0, 'message' => "No data in potluck"]);
            }
            //event_potluck_data//

            return compact('wallData', 'postPhotoList', 'eventInfo', 'eventGuest', 'potluckDetail');
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

    function setpostTime($dateTime)
    {
        $commentDateTime = $dateTime;
        $commentTime = Carbon::parse($commentDateTime);
        $timeAgo = $commentTime->diffForHumans();
        return $timeAgo;
    }
}

<?php

use App\Models\EventAddContact;
use App\Models\EventImage;
use App\Models\EventInvitedUser;
use App\Models\EventPostComment;
use App\Models\EventPostCommentReaction;
use App\Models\EventPostImage;
use App\Models\EventPostReaction;
use App\Models\EventPostPhotoReaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserEventPollData;
use App\Models\EventType;
use App\Models\User;
use App\Models\Event;
use App\Models\EventPostPoll;
use App\Models\EventDesignCategory;
use App\Models\EventPostPhotoComment;
use App\Models\EventPhotoCommentReaction;

function getEventType()
{
    //     $result = EventDesignCategory::all();
    //     $eventType = [];
    //     foreach ($result as $value) {
    //         $eventData['id'] = $value->id;
    //         $eventData['event_type'] = $value->category_name;
    //         $eventType[] = $eventData;
    //     }
    //     return  $eventType;
    return  EventType::all();
}


function getParentCommentUserData($parent_comment_id)
{

    return EventPostComment::with('user')->where('id', $parent_comment_id)->first();
}
function getPhotoParentCommentUserData($parent_comment_id)
{
    return EventPostPhotoComment::with('user')->where('id', $parent_comment_id)->first();
}

function getYesviteContactList($id)
{
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact')->where('id', '!=', $id)->orderBy('firstname')
        ->get();
    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        // if ($user->parent_user_phone_contact != $id && $user->app_user == '0') {
        //     echo  $user->id;
        //     continue;
        // }
        if ($user->email_verified_at == NULL && $user->app_user == '1') {
            continue;
        }
        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('public/storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = $user->firstname;
        $yesviteUserDetail['last_name'] = $user->lastname;
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }
    return  $yesviteUser;
}

function getEventInvitedUser($event_id)
{

    return  EventInvitedUser::whereHas('user', function ($query) {
        $query->where('app_user', '1');
    })->where('event_id', $event_id)->get();
}


function checkUserGivePoll($user_id, $post_poll_id, $option_id)
{
    return UserEventPollData::where(['user_id' => $user_id, 'event_post_poll_id' => $post_poll_id, 'event_poll_option_id' => $option_id])->exists();
}
function getTotalEventInvitedUser($event_id)
{

    $total =   EventInvitedUser::whereHas('user', function ($query) {
        $query->where('app_user', '1');
    })->where('event_id', $event_id)->count();
    return $total + 1;
}

function getEventImages($event_id)
{

    return  EventImage::where('event_id', $event_id)->get();
}

function getPostImages($event_post_id)
{
    return  EventPostImage::where('event_post_id', $event_post_id)->get();
}

function getReaction($event_post_id)
{


    return  EventPostReaction::with('user')->where('event_post_id', $event_post_id)->get();
}

function getOnlyReaction($event_post_id)
{

    $onlyReaction =   EventPostReaction::where([

        'event_post_id' => $event_post_id
    ])
        ->select('reaction', DB::raw('COUNT(*) as count'))
        ->groupBy('reaction', 'unicode')
        ->orderByDesc('count')
        ->take(3)
        ->pluck('reaction')
        ->toArray();

    $reactionList = [];

    foreach ($onlyReaction as $val) {
        // $react['emoji'] = $val;
        $react = $val;
        $reactionList[] = $react;
    }
    return $reactionList;
}



function getPhotoReaction($event_post_photo_id)
{
    return  EventPostPhotoReaction::with('user')->where('event_post_photo_id', $event_post_photo_id)->get();
}

function getPostPhotoComments($event_post_photo_id)
{
    return  EventPostPhotoComment::with(['user', 'replies' => function ($query) {
        $query->withcount('post_photo_comment_reaction', 'replies');
    }])->withcount('post_photo_comment_reaction', 'replies')->where(['event_post_photo_id' => $event_post_photo_id, 'parent_comment_id' => NULl])->get();
}


function getPhotoCommentReaction($event_photo_comment_id)
{
    return  EventPhotoCommentReaction::where(['event_photo_comment_id' => $event_photo_comment_id])->count();
}

function getComments($event_post_id)
{
    return  EventPostComment::with(['user', 'replies' => function ($query) {
        $query->withcount('post_comment_reaction', 'replies');
    }])->withcount('post_comment_reaction', 'replies')->where(['event_post_id' => $event_post_id, 'parent_comment_id' => NULl])->orderBy('id', 'DESC')->get();
}


function checkUserIsLike($event_post_comment_id, $user_id)
{
    $checkUserRection = EventPostCommentReaction::where(['event_post_comment_id' => $event_post_comment_id, 'user_id' => $user_id])->count();
    if ($checkUserRection != 0) {
        return 1;
    } else {
        return 0;
    }
}

function checkUserPhotoIsLike($event_photo_comment_id, $user_id)
{
    $checkUserRection = EventPhotoCommentReaction::where(['event_photo_comment_id' => $event_photo_comment_id, 'user_id' => $user_id])->count();
    if ($checkUserRection != 0) {
        return 1;
    } else {
        return 0;
    }
}

function getOptionTotalVote($event_poll_option_id)
{
    return UserEventPollData::where('event_poll_option_id', $event_poll_option_id)->count();
}



function checkUserAttendOrNOt($event_id, $user_id)
{
    $checkEventOwner = Event::where(['user_id' => $user_id, "id" => $event_id])->exists();
    if ($checkEventOwner) {
        return 3; // owner
    }
    $userAcceptStatus =  EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $user_id])->first();
    if ($userAcceptStatus->rsvp_status == '1'  &&  $userAcceptStatus->rsvp_d == '1') {
        return 1; // comming
    }

    if ($userAcceptStatus->rsvp_status == '0'  &&  $userAcceptStatus->rsvp_d == '1') {
        return 2; // not coming
    }
    if ($userAcceptStatus->rsvp_status == '0'  && $userAcceptStatus->read == '1' &&  $userAcceptStatus->rsvp_d == '0') {

        return 0; // No  Reply
    }
    return 0;
}

function getPollData($event_id, $post_id)
{

    $user  = Auth::guard('api')->user();
    $eventPollData =   EventPostPoll::with('event_poll_option')->withCount('user_poll_data')->where(['event_id' => $event_id, 'event_post_id' => $post_id])->first();


    $postsPollDetail['total_poll_vote'] = $eventPollData->user_poll_data_count;



    $postsPollDetail['poll_id'] = $eventPollData->id;

    $postsPollDetail['poll_question'] = $eventPollData->poll_question;

    $postsPollDetail['poll_option'] = [];

    foreach ($eventPollData->event_poll_option as $optionValue) {

        $optionData['id'] = $optionValue->id;

        $optionData['option'] = $optionValue->option;

        $optionData['total_vote'] =  round(getOptionTotalVote($optionValue->id) * 100 / getTotalEventInvitedUser($event_id)) . "%";
        $optionData['is_poll_selected'] = checkUserGivePoll($user->id, $eventPollData->id, $optionValue->id);


        $postsPollDetail['poll_option'][] = $optionData;
    }
    return $postsPollDetail;
}

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
use App\Models\Group;
use App\Models\EventDesignCategory;
use App\Models\EventPostPhotoComment;
use App\Models\EventPhotoCommentReaction;
use App\Models\UserPrivacyPolicy;
use Carbon\Carbon;
use Kreait\Laravel\Firebase\Facades\Firebase;

function getTotalUnreadMessageCount()
{
    $userId = auth()->id();
    if ($userId == '' && $userId <= 0) {
        return 0;
    }
    $firebase = Firebase::database();
    $overviewRef = $firebase->getReference('overview/' . $userId);
    $snapshot = $overviewRef->getValue();
    $totalUnreadCount = 0;

    if ($snapshot) {
        foreach ($snapshot as $conversationId => $conversation) {
            if (isset($conversation['unReadCount'])) {
                $totalUnreadCount += $conversation['unReadCount'];
            }
        }
    }

    return $totalUnreadCount;
}

function getUser($id)
{
    return User::where('id', $id)->first();
}
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

function getGuestRsvpPendingCount($eventId)
{
    return  EventInvitedUser::whereHas('user', function ($query) {

        $query->where('app_user', '1');
    })->where(['event_id' => $eventId, 'rsvp_d' => '0'])->count();
}

function upcomingEventsCount($userId)
{
    $usercreatedList = Event::with(['user', 'event_settings', 'event_schedule'])->where('start_date', '>', date('Y-m-d'))

        ->where('user_id', $userId)
        ->where('is_draft_save', '0')
        ->orderBy('start_date', 'ASC')

        ->get();

    $invitedEvents = EventInvitedUser::whereHas('user', function ($query) {

        $query->where('app_user', '1');
    })->where('user_id', $userId)->get()->pluck('event_id');



    $invitedEventsList = Event::with(['event_image', 'user', 'event_settings', 'event_schedule'])

        ->whereIn('id', $invitedEvents)->where('start_date', '>', date('Y-m-d'))
        ->where('is_draft_save', '0')
        ->orderBy('start_date', 'ASC')
        ->get();

    $allEvents = $usercreatedList->merge($invitedEventsList)->sortBy('start_date');

    return count($allEvents);
}

function pendingRsvpCount($userId)
{

    $total_need_rsvp_event_count = EventInvitedUser::whereHas('event', function ($query) {
        $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
    })->where(['user_id' => $userId, 'rsvp_status' => NULL])->count();

    $PendingRsvpEventId = "";
    if ($total_need_rsvp_event_count == 1) {
        $res = EventInvitedUser::select('event_id')->whereHas('event', function ($query) {
            $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
        })->where(['user_id' => $userId, 'rsvp_status' => NULL])->first();
        $PendingRsvpEventId = $res->event_id;
    }
    return compact('total_need_rsvp_event_count', 'PendingRsvpEventId');
}

function hostingCount($userId)
{

    $totalHosting = Event::where(['is_draft_save' => '0', 'user_id' => $userId])->where('start_date', '>=', date('Y-m-d'))->count();
    return $totalHosting;
}

function invitedToCount($userId)
{

    $totalInvited = EventInvitedUser::whereHas('event', function ($query) {
        $query->where('is_draft_save', '0')->where('start_date', '>=', date('Y-m-d'));
    })->where('user_id', $userId)->count();
    return $totalInvited;
}


function getParentCommentUserData($parent_comment_id)
{

    return EventPostComment::with('user')->where('id', $parent_comment_id)->first();
}
function getPhotoParentCommentUserData($parent_comment_id)
{
    return EventPostPhotoComment::with('user')->where('id', $parent_comment_id)->first();
}

function getGroupList($id)
{
    $groupList = Group::withCount('groupMembers')
        ->orderByDesc('group_members_count')
        ->where('user_id', $id)
        ->take(2)
        ->get();


    $groupListArr = [];

    foreach ($groupList as $value) {
        $group['id'] = $value->id;
        $group['name'] = $value->name;
        $group['member_count'] = $value->group_members_count;
        $groupListArr[] = $group;
    }

    return $groupListArr;
}
function getYesviteContactList($id)
{
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')->where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')
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
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }
    return  $yesviteUser;
}

function getYesviteContactListPage($id, $perPage, $page, $search_name)
{
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
        ->where('id', '!=', $id)
        ->where('is_user_phone_contact', '0')
        ->where(function ($query) {
            $query->whereNull('email_verified_at')
                ->where('app_user', '!=', '1')
                ->orWhereNotNull('email_verified_at');
        })
        ->where(DB::raw("CONCAT(firstname, ' ', lastname)"), 'like', "%{$search_name}%")
        ->orderBy('firstname')
        ->paginate($perPage, ['*'], 'page', $page);

    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }

    return  $yesviteUser;
}

function getEventInvitedUser($event_id, $rsvp_d = null)
{

    return  EventInvitedUser::whereHas('user', function ($query) use ($rsvp_d) {
        $query->when($rsvp_d == null, function ($q) {
            $q->where('app_user', '1');
        })->when($rsvp_d != null, function ($q) {
            $q->where('rsvp_d', '1')
                ->where('app_user', '1');
        });
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
        $query->withcount('post_photo_comment_reaction', 'replies')->orderBy('id', 'DESC');
    }])->withcount('post_photo_comment_reaction', 'replies')->where(['event_post_photo_id' => $event_post_photo_id, 'parent_comment_id' => NULl])->get();
}


function getPhotoCommentReaction($event_photo_comment_id)
{
    return  EventPhotoCommentReaction::where(['event_photo_comment_id' => $event_photo_comment_id])->count();
}

function getComments($event_post_id)
{
    return  EventPostComment::with(['user', 'replies' => function ($query) {
        $query->withcount('post_comment_reaction', 'replies')->orderBy('id', 'DESC');
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

function getOptionAllTotalVote($id)
{
    return UserEventPollData::where('event_post_poll_id', $id)->count();
}



function checkUserAttendOrNOt($event_id, $user_id)
{
    $checkEventOwner = Event::where(['user_id' => $user_id, "id" => $event_id])->exists();
    if ($checkEventOwner) {
        return 3; // owner
    }
    $userAcceptStatus =  EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $user_id])->first();
    if ($userAcceptStatus != null) {

        if ($userAcceptStatus->rsvp_status == '1'  &&  $userAcceptStatus->rsvp_d == '1') {
            return 1; // comming
        }

        if ($userAcceptStatus->rsvp_status == '0'  &&  $userAcceptStatus->rsvp_d == '1') {
            return 2; // not coming
        }
        if ($userAcceptStatus->rsvp_status == '0'  && $userAcceptStatus->read == '1' &&  $userAcceptStatus->rsvp_d == '0') {

            return 0; // No  Reply
        }
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

function getInvitedUsers($eventId)
{
    $eventDetail['invited_guests'] = [];
    $eventDetail['invited_user_id'] = [];

    $invitedUsers = EventInvitedUser::query();
    $invitedUsers->with(['event', 'user'])->where(['event_id' => $eventId]);
    $result = $invitedUsers->get();

    if (!empty($result)) {
        foreach ($result as $guestVal) {

            if ($guestVal->user->parent_user_phone_contact ==  $guestVal->event->user_id && $guestVal->user->is_user_phone_contact == '1') {
                $invitedGuestDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                $invitedGuestDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                $invitedGuestDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                $invitedGuestDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                $invitedGuestDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                $invitedGuestDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $eventDetail['invited_guests'][] = $invitedGuestDetail;
            } elseif ($guestVal->user->is_user_phone_contact == '0') {
                $invitedUserIdDetail['user_id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                $invitedUserIdDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                $eventDetail['invited_user_id'][] = $invitedUserIdDetail;
            }
        }
    }
    return $eventDetail;
}

function getDeferentBetweenTime($time1, $time2)
{



    // Convert time strings to Carbon objects
    $carbonTime1 = Carbon::createFromFormat('h:i A', $time1);
    $carbonTime2 = Carbon::createFromFormat('h:i A', $time2);

    // Find the difference in minutes
    $diffInMinutes = $carbonTime1->diffInMinutes($carbonTime2);

    // Convert minutes to hours and minutes
    $hours = floor($diffInMinutes / 60);
    $minutes = $diffInMinutes % 60;

    // Format response
    $response = '';
    if ($hours > 0) {
        $response .= "$hours h";
    }
    if ($minutes > 0) {
        $response .= " $minutes m";
    }
    return $response;
}


function getLeftPollTime($createdDate, $pollDuration)
{


    // Assuming you have the create date as a string
    $createDate = $createdDate;
    $pollDuration = $pollDuration;

    // Extract the number of hours from the poll duration
    preg_match('/(\d+)\s*Hour/i', $pollDuration, $matches);
    $hours = isset($matches[1]) ? (int)$matches[1] : 0;
    preg_match('/(\d+)\s*Day/i', $pollDuration, $matches);
    $days = isset($matches[1]) ? (int)$matches[1] : 0;

    preg_match('/(\d+)\s*Week/i', $pollDuration, $matches);
    $weeks = isset($matches[1]) ? (int)$matches[1] : 0;

    preg_match('/(\d+)\s*Month/i', $pollDuration, $matches);
    $months = isset($matches[1]) ? (int)$matches[1] : 0;
    // Convert the create date to a Carbon instance
    $createDateTime = Carbon::parse($createDate);

    // Add hours to the create date
    if ($hours != 0) {

        $endTime = $createDateTime->addHours($hours);
    }
    if ($days != 0) {

        $endTime = $createDateTime->addDays($days);
    }
    if ($weeks != 0) {

        $endTime = $createDateTime->addWeeks($weeks);
    }

    if ($months != 0) {

        $endTime = $createDateTime->addMonths($months);
    }


    // Get the current time
    $currentTime = Carbon::now();

    // Calculate the remaining time
    $remainingTime = "0";
    if ($endTime > $currentTime) {

        $remainingTime = $endTime->diff($currentTime);
    }

    // Format the remaining time
    $formattedRemainingTime = '';
    if ($remainingTime != "0") {

        if ($remainingTime->d > 0) {
            $formattedRemainingTime .= $remainingTime->d . ' d';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->h > 0) {
            $formattedRemainingTime .= $remainingTime->h . 'h ';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->i > 0) {
            $formattedRemainingTime .= $remainingTime->i . 'm ';
            return trim($formattedRemainingTime);
        }
        if ($remainingTime->s > 0) {
            $formattedRemainingTime .= $remainingTime->s . 's ';
            return trim($formattedRemainingTime);
        }
    }

    return $formattedRemainingTime;
    // Output the formatted remaining time



}

function getYesviteSelectedUserPage($id, $perPage, $page, $eventId)
{
    $yesviteEvents = Event::where('id', '=', $eventId)->first();
    dd($yesviteEvents);
    $yesviteRegisteredUser = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
        ->where('id', '!=', $id)
        ->where('is_user_phone_contact', '0')
        ->with(['event' => function ($query) use ($eventId) {
            $query->where('id', $eventId);
        }])
        ->where(function ($query) {
            $query->whereNull('email_verified_at')
                ->where('app_user', '!=', '1')
                ->orWhereNotNull('email_verified_at');
        })
        ->orderBy('firstname')
        ->paginate($perPage, ['*'], 'page', $page);
    dd($yesviteRegisteredUser);
    $yesviteUser = [];
    foreach ($yesviteRegisteredUser as $user) {

        $yesviteUserDetail['id'] = $user->id;
        $yesviteUserDetail['profile'] = empty($user->profile) ? "" : asset('storage/profile/' . $user->profile);
        $yesviteUserDetail['first_name'] = (!empty($user->firstname) || $user->firstname != Null) ? $user->firstname : "";;
        $yesviteUserDetail['last_name'] = (!empty($user->lastname) || $user->lastname != Null) ? $user->lastname : "";
        $yesviteUserDetail['email'] = (!empty($user->email) || $user->email != Null) ? $user->email : "";
        $yesviteUserDetail['country_code'] = (!empty($user->country_code) || $user->country_code != Null) ? strval($user->country_code) : "";
        $yesviteUserDetail['phone_number'] = (!empty($user->phone_number) || $user->phone_number != Null) ? $user->phone_number : "";
        $yesviteUserDetail['app_user']  = $user->app_user;
        $yesviteUserDetail['visible'] =  $user->visible;
        $yesviteUserDetail['message_privacy'] =  $user->message_privacy;
        $yesviteUserDetail['prefer_by']  = $user->prefer_by;
        $yesviteUser[] = $yesviteUserDetail;
    }
    return  $yesviteUser;
}

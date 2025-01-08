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
    EventPostReaction
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventPhotoController extends Controller
{
    public function index(String $id)
    {
        $title = 'event photos';
        $page = 'front.event_wall.event_photos';
        $user  = Auth::guard('web')->user();
        $js = ['event_photo'];
        // $rawData = $request->getContent();
        // $input = json_decode($rawData, true);
        $event = decrypt($id);
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            // $selectedFilters = $request->input('filters');
            $getPhotoList = EventPost::query();
            $getPhotoList->with(['user', 'event_post_reaction', 'post_image'])->withCount(['event_post_reaction', 'post_image', 'event_post_comment' => function ($query) {
                $query->where('parent_comment_id', NULL);
            }])->where(['event_id' => $event, 'post_type' => '1']);
            $eventCreator = Event::where('id', $event)->first();
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
                $ischeckEventOwner = Event::where(['id' => $event, 'user_id' => $user->id])->first();
                $postControl = PostControl::where(['user_id' => $user->id, 'event_id' => $event, 'event_post_id' => $value->id])->first();
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
            // if (!empty($postPhotoList)) {
            //     return compact('postPhotoList');
            //     // return response()->json(['status' => 1, 'data' => $postPhotoList, 'message' => "Photo List"]);
            // } else {
            //     $postPhotoList="";
            //     return compact('postPhotoList');
            //     // return response()->json(['status' => 0, 'data' => $postPhotoList, 'message' => "Photo not found"]);
            // }
            $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings' => function ($query) {
                $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
            },  'event_invited_user' => function ($query) {
                $query->where('is_co_host', '1')->with('user');
            }])->where('id', $event)->first();
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
            $eventDetails['podluck'] = $eventDetail->event_settings->podluck;
            $eventDetails['allow_limit'] = $eventDetail->event_settings->allow_limit;
            $eventDetails['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
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
            $current_page = "photos";

            return view('layout', compact('page', 'js','title', 'event', 'eventDetails', 'postPhotoList', 'current_page')); // return compact('eventInfo');
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
    }



    function setpostTime($dateTime)
    {
        $commentDateTime = $dateTime;
        $commentTime = Carbon::parse($commentDateTime);
        $timeAgo = $commentTime->diffForHumans();
        return $timeAgo;
    }

    public function createEventPost(Request $request)
    {
        // dd($request);
        $user = Auth::guard('web')->user()->id;

        // Create new event post
        $createEventPost = new EventPost();
        $createEventPost->event_id = $request->event_id;
        $createEventPost->user_id = $user;
        $createEventPost->post_message = $request->input('content'); // Placeholder, update as necessary
        $createEventPost->post_privacy = "1"; // Example: public post
        $createEventPost->post_type = "1"; // Define post type
        $createEventPost->commenting_on_off = "1"; // Comments allowed
        $createEventPost->is_in_photo_moudle = "1"; // Whether the post contains photos
        $createEventPost->save();

        // Check if files were uploaded
        if ($createEventPost->id && $request->hasFile('files')) {
            $postFiles = $request->file('files'); // Get the uploaded files
            $imageUrls = [];
            $videoCount = 0;
            $imageCount = 0;

            foreach ($postFiles as $key => $postFile) {
                $fileName = time() . $key . '_' . $postFile->getClientOriginalName();

                // Save file to storage/app/public/post_image/
                $postFile->move(public_path('storage/post_image'), $fileName);


                $checkIsImageOrVideo = checkIsImageOrVideo($postFile); // Assuming this is a helper function
                $duration = "";
                $thumbName = "";

                // Process video
                if ($checkIsImageOrVideo == 'video') {
                    $duration = getVideoDuration($postFile); // Assuming this is a helper function
                    $thumbName = genrate_thumbnail($fileName, $createEventPost->id);
                    $postFile->move(public_path('storage/post_image/'), $fileName);
                }

                //     // Process image



                // Count images and videos
                if ($checkIsImageOrVideo == 'video') {
                    $videoCount++;
                } else {
                    $imageCount++;
                }

                // Save post image
                $eventPostImage = new EventPostImage();
                $eventPostImage->event_id = $request->event_id;
                $eventPostImage->event_post_id = $createEventPost->id;
                $eventPostImage->post_image = $fileName;
                $eventPostImage->duration = $duration;
                $eventPostImage->type = $checkIsImageOrVideo;
                $eventPostImage->thumbnail = $thumbName;
                $eventPostImage->save();
            }

            return redirect()->back()->with('success', 'Event post uploded successfully!');
        }

        return redirect()->back()->with('success', 'Event Post created successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\{
    contact_sync,
    User,
    Event,
    EventInvitedUser,
    EventSetting,
    EventGreeting,
    EventCoHost,
    EventGuestCoHost,
    EventGiftRegistry,
    EventImage,
    EventUserRsvp,
    EventSchedule,
    EventPotluckCategory,
    EventPotluckCategoryItem,
    Notification,
    EventPostComment,
    EventPost,
    EventPostCommentReaction,
    EventPostImage,
    EventPostPoll,
    EventPostPollOption,
    EventAddContact,
    EventPostReaction,
    EventUserStory,
    UserEventPollData,
    EventPostPhoto,
    EventPostPhotoReaction,
    EventPostPhotoComment,
    EventPhotoCommentReaction,
    EventPostPhotoData,
    EventDesign,
    EventDesignCategory,
    EventDesignSubCategory,
    EventDesignColor,
    EventDesignStyle,
    UserEventStory,
    PostControl,
    UserPotluckItem,
    Device,
    EventType,
    UserReportToPost,
    Group,
    GroupMember,
    TextData,
    UserNotificationType,
    UserProfilePrivacy,
    UserSeenStory,
    UserSubscription,
    VersionSetting
};
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\SendInvitationMailJob as sendInvitation;
use DateTime;
use Faker\Core\Number;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use function PHPUnit\Framework\isFalse;
use Illuminate\Support\Facades\File;


class EventController extends BaseController
{



    public function homeDesign()
    {

        $title = 'Yesvite-Home';
        $page = 'front.home_design';
        $js = ['home_design'];
        $images = TextData::all();
        $categories = TextData::with('categories', 'subcategories')->orderBy('id', 'desc')->get();;
        $getDesignData =  EventDesignCategory::with('subcategory')->get();
        $getDesignData = EventDesignCategory::all();
        $getsubcatData = EventDesignSubCategory::all();
        return view('layout', compact(
            'title',
            'page',
            'images',
            'getDesignData',
            'categories',
            'js'
        ));
    }
    public function searchDesign(Request $request)
    {
        $query = $request->input('search');

        // $categories = EventDesignCategory::where('category_name', 'LIKE', "%$query%")
        //     ->with(['subcategory.textdatas']) // Load subcategories and their textdatas
        //     ->orderBy('id', 'desc')
        //     ->get();

        $query = $request->input('search');
        $categories = EventDesignCategory::where('category_name', 'LIKE', "%$query%")
            ->whereHas('subcategory', function ($query) {
                $query->whereHas('textdatas'); // Ensure subcategories have textdatas
            })
            ->with([
                'subcategory' => function ($query) {
                    $query->whereHas('textdatas') // Only subcategories with textdatas
                        ->with('textdatas'); // Load textdatas
                }
            ])
            ->orderBy('id', 'ASC')
            ->get();


        // Calculate total count of textdatas across all subcategories
        // $totalTextDataCount = $categories->sum(function ($category) {
        //     return $category->subcategory->sum(function ($subcategory) {
        //         return $subcategory->textdatas->count();
        //     });
        // });
        $totalTextDataCount = $categories->count();
        return response()->json([
            'view' => view('front.event.getDesignAjax', compact('categories'))->render(),

            'count' => $totalTextDataCount // Total count of textdatas
        ]);
    }



    public function index(Request $request)
    {

        // dd(config('app.url'));
        // dd(Session::get('shape_image'));
        Session::forget('user_ids');
        Session::forget('contact_ids');
        Session::forget('category');
        Session::forget('category_item');
        Session::forget('gift_registry_data');
        Session::forget('thankyou_card_data');
        $image = Session::get('desgin');
        // $slider_image = Session::get('desgin_slider');
        $slider_image = Session::forget('desgin_slider');
        // $custom_image = Session::get('custom_image');

        $custom_image = Session::forget('custom_image');
        $shape = Session::get('shape_image');

        $useremail = Auth::user()->email;
        // if (isset($shape) && $shape != "" || $shape != NULL) {
        //     if (file_exists(public_path('storage/canvas/') . $shape)) {
        //         $shapePath = public_path('storage/canvas/') . $shape;
        //         unlink($shapePath);
        //     }
        // }
        // if (isset($image) && $image != "" || $image != NULL) {
        //     if (file_exists(public_path('storage/event_design_template/') . $image)) {
        //         $imagePath = public_path('storage/event_design_template/') . $image;
        //         unlink($imagePath);
        //     }
        // }
        // if (isset($custom_image) && $custom_image != "" || $image != NULL) {
        //     if (file_exists(public_path('storage/canvas/') . $custom_image)) {
        //         $imagePath = public_path('storage/canvas/') . $custom_image;
        //         unlink($imagePath);
        //     }
        // }
        // if (isset($slider_image) && !empty($slider_image)) {
        //     foreach ($slider_image as $key => $value) {
        //         if (file_exists(public_path('storage/event_images/') . $value['fileName'])) {
        //             $imagePath = public_path('storage/event_images/') . $value['fileName'];
        //             unlink($imagePath);
        //         }
        //     }
        // }
        Session::forget('desgin');
        Session::forget('desgin_slider');
        Session::forget('custom_image');
        Session::forget('greetingCardData');
        Session::forget('giftRegistryData');
        Session::save();
        $id = Auth::guard('web')->user()->id;
        $thankyou_card_count = EventGreeting::where('user_id', $id)->count();
        $gift_registry_count = EventGiftRegistry::where('user_id', $id)->count();

        $eventDetail = [];
        $eventDetail['user_id'] = $id;
        $eventDetail['id'] = '';
        $eventDetail['thankyou_card_count'] = $thankyou_card_count;
        $eventDetail['gift_registry_count'] = $gift_registry_count;
        $eventDetail['eventeditId'] = isset($request->id) ? $request->id : '';
        $eventDetail['inviteCount'] = 0;
        $eventDetail['isCohost'] = "1";
        $eventDetail['isCopy'] = "";
        $eventDetail['alreadyCount'] = 0;

        if (isset($request->id) && $request->id != '') {
            $title = 'Edit Event';
            $eventID = decrypt($request->id);
            $getEventData = Event::with('event_schedule')->where('id', $eventID)->first();


            if ($eventID != "") {
                // dD();
                $eventDetail['isCohost'] = $getEventData->is_draft_save;



                $userIds = session()->get('user_ids', []);

                $invitedYesviteUsers = EventInvitedUser::with('user')
                    ->where('event_id', $eventID)
                    ->where('is_co_host', '0')
                    ->whereNotNull('user_id')
                    ->whereNull('sync_id')
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
                        )->where('id', $user['user_id'])->first();

                        if ($userVal) {
                            $userEntry = [
                                'id' => strval($userVal->id),
                                'firstname' => $userVal->firstname,
                                'lastname' => $userVal->lastname,
                                'prefer_by' => $user['prefer_by'],
                                'invited_by' => $user['prefer_by'] == 'phone' ? $userVal->phone_number : $userVal->email,
                                'profile' => $userVal->profile ?? '',
                            ];
                            if ($getEventData->is_draft_save == "0" && $request->iscopy == null) {
                                $userEntry['isAlready'] = "1";
                            }

                            $userIds[] = $userEntry;
                        }
                    }
                    session()->put('user_ids', $userIds);
                    Session::save();
                }

                $userIdsSession = session()->get('contact_ids', []);
                $invitedContactUsers = EventInvitedUser::with('user')
                    ->where('event_id', $eventID)
                    ->where('is_co_host', '0')
                    // ->whereNull('user_id')
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

                        )->where('id', $user['sync_id'])->first();
                        if ($userVal) {
                            $userEntry = [
                                'sync_id' => strval($userVal->id),
                                'firstname' => $userVal->firstname,
                                'lastname' => $userVal->lastname,
                                'prefer_by' => $user['prefer_by'],
                                'invited_by' => $user['prefer_by'] == 'phone' ? $userVal->phone : $userVal->email,
                                'profile' => $userVal->photo ?? '',

                            ];
                            if ($getEventData->is_draft_save == "0" && $request->iscopy == null) {
                                $userEntry['isAlready'] = "1";
                            }

                            $userIdsSession[] = $userEntry;
                        }
                    }
                    session()->put('contact_ids', $userIdsSession);
                    Session::save();
                }
            }
            $eventDetail['alreadyCount'] = count(session('contact_ids')) + count(session('user_ids'));
            // dd(session('user_ids'));
            // $getEventData = Event::with('event_schedule')->where('id',decrypt($eventID))->first();
            if ($getEventData != null) {

                if ($request->iscopy != null) {
                    
                    $eventDetail['isCopy'] = $getEventData->id;
                }
                // dd($getEventData );
                $eventDetail['inviteCount'] = EventInvitedUser::with('user')
                    ->where('event_id', $eventID)->where('is_co_host', '0')
                    ->count();
                $eventDetail['id'] = (!empty($getEventData->id) && $getEventData->id != NULL) ? $getEventData->id : "";


                // $eventDetail['event_type_id'] = (!empty($getEventData->event_type_id) && $getEventData->event_type_id != NULL) ? $getEventData->event_type_id : "";
                $eventDetail['event_name'] = (!empty($getEventData->event_name) && $getEventData->event_name != NULL) ? $getEventData->event_name : "";
                $eventDetail['hosted_by'] = (!empty($getEventData->hosted_by) && $getEventData->hosted_by != NULL) ? $getEventData->hosted_by : "";
                $eventDetail['start_date'] = (!empty($getEventData->start_date) && $getEventData->start_date != NULL) ? $getEventData->start_date : "";
                $eventDetail['end_date'] = (!empty($getEventData->end_date) && $getEventData->end_date != NULL) ? $getEventData->end_date : "";
                $eventDetail['rsvp_by_date_set'] =  $getEventData->rsvp_by_date_set;
                $eventDetail['rsvp_by_date'] = (!empty($getEventData->rsvp_by_date) && $getEventData->rsvp_by_date != NULL) ? $getEventData->rsvp_by_date : "";
                $eventDetail['rsvp_start_time'] = (!empty($getEventData->rsvp_start_time) && $getEventData->rsvp_start_time != NULL) ? $getEventData->rsvp_start_time : "";
                $eventDetail['rsvp_start_timezone'] = (!empty($getEventData->rsvp_start_timezone) && $getEventData->rsvp_start_timezone != NULL) ? $getEventData->rsvp_start_timezone : "";
                $eventDetail['rsvp_end_time_set'] = $getEventData->rsvp_end_time_set;
                $eventDetail['rsvp_end_time'] = (!empty($getEventData->rsvp_end_time) && $getEventData->rsvp_end_time != NULL) ? $getEventData->rsvp_end_time : "";
                $eventDetail['rsvp_end_timezone'] = (!empty($getEventData->rsvp_end_timezone) && $getEventData->rsvp_end_timezone != NULL) ? $getEventData->rsvp_end_timezone : "";
                $eventDetail['event_location_name'] = (!empty($getEventData->event_location_name) && $getEventData->event_location_name != NULL) ? $getEventData->event_location_name : "";
                $eventDetail['latitude'] = (!empty($getEventData->latitude) && $getEventData->latitude != NULL) ? $getEventData->latitude : "";
                $eventDetail['longitude'] = (!empty($getEventData->longitude) && $getEventData->longitude != NULL) ? $getEventData->longitude : "";
                $eventDetail['address_1'] = (!empty($getEventData->address_1) && $getEventData->address_1 != NULL) ? $getEventData->address_1 : "";
                $eventDetail['address_2'] = (!empty($getEventData->address_2) && $getEventData->address_2 != NULL) ? $getEventData->address_2 : "";
                $eventDetail['state'] = (!empty($getEventData->state) && $getEventData->state != NULL) ? $getEventData->state : "";
                $eventDetail['zip_code'] = (!empty($getEventData->zip_code) && $getEventData->zip_code != NULL) ? $getEventData->zip_code : "";
                $eventDetail['city'] = (!empty($getEventData->city) && $getEventData->city != NULL) ? $getEventData->city : "";
                $eventDetail['message_to_guests'] = (!empty($getEventData->message_to_guests) && $getEventData->message_to_guests != NULL) ? $getEventData->message_to_guests : "";
                $eventDetail['is_draft_save'] = $getEventData->is_draft_save;
                $eventDetail['step'] = ($getEventData->step != NULL) ? $getEventData->step : 0;
                $eventDetail['subscription_plan_name'] = ($getEventData->subscription_plan_name != NULL) ? $getEventData->subscription_plan_name : "";
                $eventDetail['subscription_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? $getEventData->subscription_invite_count : 0;
                $eventDetail['design_image'] = ($getEventData->design_image != NULL) ? asset('storage/canvas/' . $getEventData->design_image) : null;
                $eventDetail['static_information'] = ($getEventData->static_information != NULL) ? $getEventData->static_information : null;
                $eventDetail['event_images'] = [];
                $getEventImages = EventImage::where('event_id', $getEventData->id)->orderBy('type', 'ASC')->get();
                if (!empty($getEventImages)) {
                    foreach ($getEventImages as $imgVal) {
                        $eventImageData['id'] = $imgVal->id;
                        $eventImageData['image'] = asset('public/storage/event_images/' . $imgVal->image);
                        $eventDetail['event_images'][] = $eventImageData;
                    }
                }
                if ($request->iscopy != null && $request->iscopy) {
                    $eventDetail['id'] = '';
                    $eventDetail['iscopy'] = $request->iscopy;
                    $eventDetail['is_draft_save'] = '';
                }
                $eventDetail['invited_user_id'] = [];

                $eventDetail['invited_guests'] = [];
                $eventDetail['guest_co_host_list'] = [];

                $eventDetail['co_host_list'] = getInvitedCohostList($getEventData->id);
                // if(isset($eventDetail['co_host_list']) && $eventDetail['co_host_list']!=""){
                //     if($eventDetail['co_host_list'][0] !=$id){
                //         redirect('front.home');
                //     }
                // }
                // if($getEventData->user_id != $id){
                //     redirect('front.home');
                // }



                $invitedUser = EventInvitedUser::with('user')->where(['event_id' => $getEventData->id])->get();

                $eventDetail['events_schedule_list'] = null;
                if ($getEventData->event_schedule->isNotEmpty()) {

                    $eventDetail['events_schedule_list'] = new stdClass();
                    if ($getEventData->event_schedule->first()->type == '1') {


                        $eventDetail['events_schedule_list']->start_time =  ($getEventData->event_schedule->first()->start_time != NULL) ? $getEventData->event_schedule->first()->start_time : "";

                        $eventDetail['events_schedule_list']->event_start_date = ($getEventData->event_schedule->first()->event_date != null) ? $getEventData->event_schedule->first()->event_date : "";
                    }

                    $eventDetail['events_schedule_list']->data = [];
                    $totalActivity = 0;
                    $eventDetail['totalActivityByDate'] = [];
                    foreach ($getEventData->event_schedule as $eventsScheduleVal) {
                        if ($eventsScheduleVal->type == '2') {

                            $eventscheduleData["id"] = $eventsScheduleVal->id;
                            $eventscheduleData["activity_title"] = $eventsScheduleVal->activity_title;
                            $eventscheduleData["start_time"] = ($eventsScheduleVal->start_time !== null) ? $eventsScheduleVal->start_time : "";
                            $eventscheduleData["end_time"] = ($eventsScheduleVal->end_time !== null) ? $eventsScheduleVal->end_time : "";
                            $eventscheduleData['event_date'] = ($eventsScheduleVal->event_date != null) ? $eventsScheduleVal->event_date : "";
                            $eventscheduleData["type"] = $eventsScheduleVal->type;
                            // $eventDetail['totalActivity']=$totalActivity;
                            $totalActivity++;
                            if (!empty($eventsScheduleVal->event_date)) {
                                // If this event_date does not exist in the array, initialize it with a count of 0.
                                if (!isset($eventDetail['totalActivityByDate'][$eventsScheduleVal->event_date])) {
                                    $eventDetail['totalActivityByDate'][$eventsScheduleVal->event_date] = 0;
                                }
                                // Increment the count for the specific event date.
                                $eventDetail['totalActivityByDate'][$eventsScheduleVal->event_date]++;
                            }
                            $eventDetail['events_schedule_list']->data[] = $eventscheduleData;
                        }
                    }
                    // $eventDetail['events_schedule_list']->totalActivity= $totalActivity;
                    if ($getEventData->event_schedule->last()->type == '3') {

                        $eventDetail['events_schedule_list']->end_time =  ($getEventData->event_schedule->last()->end_time !== null) ? $getEventData->event_schedule->last()->end_time : "";
                        $eventDetail['events_schedule_list']->event_end_date = ($getEventData->event_schedule->last()->event_date != null) ? $getEventData->event_schedule->last()->event_date : "";
                    }
                }
                // $eventDetail['totalActivity'] = $totalActivity;
                // dd($eventDetail);die;
                $eventDetail['greeting_card_list'] = [];
                Session::get('greetingCardData', []);
                if (!empty($getEventData->greeting_card_id) && $getEventData->greeting_card_id != NULL) {


                    $greeting_card_ids = array_map('intval', explode(',', $getEventData->greeting_card_id));

                    $eventDetail['greeting_card_list'] = $greeting_card_ids;
                    if ($id != $getEventData->user_id) {
                        $eventDetail['thankyou_card_count'] = count($greeting_card_ids) + $thankyou_card_count;
                    }
                    session()->put('greetingCardData', $greeting_card_ids);
                    Session::save();
                }

                $eventDetail['gift_registry_list'] = [];
                Session::get('giftRegistryData', []);
                if (!empty($getEventData->gift_registry_id) && $getEventData->gift_registry_id != NULL) {

                    $gift_registry_ids = array_map('intval', explode(',', $getEventData->gift_registry_id));
                    if ($id != $getEventData->user_id) {
                        $eventDetail['gift_registry_count'] = count($gift_registry_ids) + $gift_registry_count;
                    }
                    $eventDetail['gift_registry_list'] = $gift_registry_ids;
                    session()->put('giftRegistryData', $gift_registry_ids);
                    Session::save();
                }



                $eventDetail['event_setting'] = "";

                $eventSettings = EventSetting::where('event_id', $getEventData->id)->first();

                if ($eventSettings != NULL) {
                    $eventDetail['event_setting'] = [

                        "allow_for_1_more" => $eventSettings->allow_for_1_more,
                        "allow_limit" => strval($eventSettings->allow_limit),
                        "adult_only_party" => $eventSettings->adult_only_party,

                        "rsvp_by_date" => $getEventData->rsvp_by_date,
                        "thank_you_cards" => $eventSettings->thank_you_cards,
                        "add_co_host" => $eventSettings->add_co_host,
                        "gift_registry" => $eventSettings->gift_registry,
                        "events_schedule" => $eventSettings->events_schedule,
                        "event_wall" => $eventSettings->event_wall,
                        "guest_list_visible_to_guests" => $eventSettings->guest_list_visible_to_guests,
                        "podluck" => $eventSettings->podluck,
                        "rsvp_updates" => $eventSettings->rsvp_updates,
                        "event_wall_post" => $eventSettings->event_wall_post,
                        "send_event_dater_reminders" => $eventSettings->send_event_dater_reminders,
                        "request_event_photos_from_guests" => $eventSettings->request_event_photos_from_guests
                    ];
                }


                $eventDetail['podluck_category_list'] = [];



                $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                    $query->with(['users', 'user_potluck_items' => function ($subquery) {
                        $subquery->with('users')->sum('quantity');
                    }]);
                }])->withCount('event_potluck_category_item')->where('event_id', $getEventData->id)->get();

                if (!empty($eventpotluckData)) {
                    $potluckCategoryData = [];
                    $potluckDetail['total_potluck_item'] = EventPotluckCategoryItem::where('event_id', $getEventData->id)->count();
                    $categories = session()->get('category', []);
                    // dd($categories);
                    $categoryNames =  collect($categories)->pluck('category_name')->toArray();
                    $categories_item = Session::get('category_item', []);
                    $totalCategoryItem = 0;
                    foreach ($eventpotluckData as  $key => $value) {

                        $potluckCategory['id'] = $value->id;
                        $potluckCategory['category'] = $value->category;
                        $potluckCategory['created_by'] = $value->users->firstname . ' ' . $value->users->lastname;
                        $potluckCategory['quantity'] = $value->quantity;

                        $categories[$key] = [
                            'category_name' => $value->category,
                            'category_quantity' => $value->quantity,
                            'iscateogry' => "1",
                            'isAlready' => "1",
                        ];
                        // session()->put('category', $categories);
                        $potluckCategory['items'] = [];
                        $categoryQuantity = 0;
                        $remainingQnt = 0;
                        $totalItem = 0;
                        $totalMissing = 0;
                        $totalOver = 0;
                        if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {

                            $itemData = [];
                            foreach ($value->event_potluck_category_item as $itemkey => $itemValue) {
                                $itemData = [
                                    'name' => $itemValue->description,
                                    'self_bring' => $itemValue->self_bring_item,
                                    'self_bring_qty' => $itemValue->self_bring_item == 1 ? $itemValue->quantity : 0,
                                    'quantity' => $itemValue->quantity,
                                    'isAlready' => "1",
                                ];
                                $itmquantity = 0;
                                $innnerUserItem = 0;
                                $userQuantity = 0;
                                $categories[$key]['item'][$itemkey] = $itemData;
                                // Add item to session
                                $categories_item[$value->category][] = $itemData;


                                $potluckItem['id'] =  $itemValue->id;
                                $potluckItem['description'] =  $itemValue->description;
                                $potluckItem['is_host'] = ($itemValue->user_id == $id) ? 1 : 0;
                                $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                                $potluckItem['quantity'] =  $itemValue->quantity;
                                $potluckItem['self_bring_item'] =  $itemValue->self_bring_item;
                                $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                                $potluckItem['spoken_quantity'] =  $spoken_for;

                                $potluckItem['item_carry_users'] = [];

                                foreach ($itemValue->user_potluck_items as $userKey => $itemcarryUser) {
                                    $userPotluckItem['id'] = $itemcarryUser->id;
                                    $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                    $userPotluckItem['is_host'] = ($itemcarryUser->user_id == $id) ? 1 : 0;
                                    $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('public/storage/profile/' . $itemcarryUser->users->profile);
                                    $userPotluckItem['first_name'] = $itemcarryUser->users->firstname;
                                    $userPotluckItem['quantity'] = (!empty($itemcarryUser->quantity) || $itemcarryUser->quantity != NULL) ? $itemcarryUser->quantity : "0";
                                    $userPotluckItem['last_name'] = $itemcarryUser->users->lastname;
                                    $potluckItem['item_carry_users'][] = $userPotluckItem;
                                    if ($itemcarryUser->user_id == $id) {
                                        // Set the user's item at index 0
                                        $categories[$key]['item'][$itemkey]['item_carry_users'][0] = $userPotluckItem;
                                    } else {
                                        // Otherwise, add the other user's item to the array normally
                                        $categories[$key]['item'][$itemkey]['item_carry_users'][] = $userPotluckItem;
                                    }

                                    $itmquantity = $itmquantity +  $itemcarryUser->quantity;
                                    $categoryQuantity = $categoryQuantity + $itemcarryUser->quantity;
                                    if ($itemcarryUser->user_id != $id) {
                                        $innnerUserItem = $innnerUserItem + $itemcarryUser->quantity;
                                    } else {
                                        $userQuantity = $userQuantity + $itemcarryUser->quantity;
                                    }
                                }
                                $userQuantity =  $userQuantity + $innnerUserItem;
                                if ($userQuantity <  $itemValue->quantity) {
                                    $totalMissing +=  $itemValue->quantity - $userQuantity;
                                } else if ($userQuantity >  $itemValue->quantity) {
                                    $totalOver += $userQuantity -  $itemValue->quantity;
                                }
                                $totalItem = $totalItem + 1;
                                $remainingQnt = $remainingQnt + $itemValue->quantity;
                                $potluckItem['itmquantity'] =  $itmquantity;
                                $potluckItem['innerUserQnt'] =  $innnerUserItem;

                                $potluckCategory['items'][] = $potluckItem;
                                $totalCategoryItem++;
                            }
                        }
                        $potluckCategory['totalMissing'] = $totalMissing;
                        $potluckCategory['totalOver'] = $totalOver;
                        $remainingQnt =  $remainingQnt - $categoryQuantity;
                        $potluckCategory['remainingQnt'] = $remainingQnt;
                        $potluckCategory['categoryQuantity'] = $categoryQuantity;
                        $potluckCategory['totalItem'] = $totalItem;
                        // $potluckCategory['innerCategoryUserQnt'] =  $innnerUserItem;
                        $eventDetail['podluck_category_list'][] = $potluckCategory;
                    }
                    // Update session after the loop
                    session()->put('category', $categories);
                    session()->put('category_item', $categories_item);
                    Session::save();
                    $eventDetail['totalCategoryItem'] =  $totalCategoryItem;
                   
                }
            }
        } else {
            $title = 'Create Event';
        }

        $page = 'front.create_event';


        $js = ['design', 'create_event'];

        $user = User::withCount(
            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment',
            ]
        )->findOrFail($id);
        $inviteduser = "";

        $event_type =   EventType::get();
        $yesvite_user = User::select('id', 'firstname', 'lastname', 'phone_number', 'email', 'profile')
            ->where('app_user', '1')
            ->orderBy('firstname')
            ->limit(1)
            ->get();
        $textData = [];
        $design_category = [];
        // $design_category = EventDesignCategory::with(['subcategory' => function ($query) {
        //     $query->select('*')->whereHas('textdatas', function ($ques) {})->with(['textdatas' => function ($que) {
        //         $que->select('*');
        //     }]);
        // }])->orderBy('id', 'DESC')->get();

        $categories = EventDesignCategory::whereHas('subcategory', function ($query) {
            $query->whereHas('textdatas'); // Ensures only subcategories that have related textdatas are included
        })
            ->with([
                'subcategory' => function ($query) {
                    $query->whereHas('textdatas') // Ensures only subcategories with textdatas are retrieved
                        ->with('textdatas'); // Load the textdatas relationship
                }
            ])
            ->get();




        // $totalTextDataCount = $categories->sum(
        //     fn($category) =>
        //     $category->subcategory->sum(
        //         fn($subcategory) =>
        //         $subcategory->textdatas->count()
        //     )
        // );
        $totalTextDataCount = $categories->count();
        $imagecount = $totalTextDataCount;
        // $textData = TextData::select('*')
        //     ->orderBy('id', 'desc')
        //     ->get();

        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;
        $user['coins'] = $user->coins;
        $groups = Group::withCount('groupMembers')
            ->orderBy('name', 'ASC')
            ->where('user_id', $id)
            ->get();
            
        return view('event_layout', compact(
            'title',
            'page',
            'js',
            'user',
            'event_type',
            'yesvite_user',
            'groups',
            'imagecount',
            // 'textData',
            'categories',
            'eventDetail'
        ));
    }

    public function uploadCustomImage(Request $request) {}

    public function store(Request $request)
    {
        $potluck = session('category');
        // dd($request);
        // dd($request);

        Session::forget('desgin');
        Session::forget('custom_image');
        Session::forget('shape_image');
        Session::forget('desgin_slider');
        $user_id =  Auth::guard('web')->user()->id;
        $dateString = (isset($request->event_date)) ? $request->event_date : "";

        // if (strpos($dateString, ' To ') !== false) {
        //     list($startDate, $endDate) = explode(' To ', $dateString);
        // } else {
        //     $startDate = $dateString;
        //     $endDate = $dateString;
        // }

        // $startDateFormat = DateTime::createFromFormat('m-d-Y', $startDate)->format('Y-m-d');
        // $endDateFormat = DateTime::createFromFormat('m-d-Y', $endDate)->format('Y-m-d');
        // if (strpos($dateString, ' To ') !== false) {
        //     list($startDate, $endDate) = explode(' To ', $dateString);
        // } else {
        //     $startDate = $dateString;
        //     $endDate = $dateString;
        // }

        $startDateObj = DateTime::createFromFormat('m-d-Y', $request->start_event_date);
        $endDateObj = DateTime::createFromFormat('m-d-Y', $request->end_event_date);


        $startDateFormat = "";
        $endDateFormat = "";
        if ($startDateObj && $endDateObj) {
            $startDateFormat = @$startDateObj->format('Y-m-d');
            $endDateFormat = @$endDateObj->format('Y-m-d');
        }
        if (isset($request->rsvp_by_date) && $request->rsvp_by_date != '') {
            // dd($request->rsvp_by_date);
            // $rsvp_by_date = Carbon::parse($request->rsvp_by_date)->format('Y-m-d');
            $rsvp_by_date = DateTime::createFromFormat('m-d-Y', $request->rsvp_by_date)->format('Y-m-d');
            $rsvp_by_date_set = '1';
        } else {
            $rsvp_by_date_set = '0';
            $rsvp_by_date = null;
            if ($startDateFormat) {
                // $start = new DateTime($startDateFormat);
                // $start->modify('-1 day');
                // $rsvp_by_date = $start->format('Y-m-d');
            }
        }

        $greeting_card_id = "";
        if (isset($request->thankyou_message) && $request->thankyou_message == '1') {
            if (isset($request->thank_you_card_id) && $request->thank_you_card_id != '') {
                $greeting_card_id =  $request->thank_you_card_id;
            }
        }

        $gift_registry_id = "";
        if (isset($request->gift_registry) && $request->gift_registry == '1') {
            if (!empty($request->gift_registry_data)) {
                $gift_registry_data = collect($request->gift_registry_data)->pluck('gr_id')->toArray();
                $gift_registry_id =  implode(',', $gift_registry_data);
            }
        }


        if (isset($request->event_id) && $request->event_id != NULL) {
            $event_creation = Event::where('id', $request->event_id)->first();
        } else {
            $event_creation = new Event();
        }
        // $event_creation->event_type_id = (isset($request->event_type) && $request->event_type != "") ? (int)$request->event_type : "";
        $event_creation->user_id = $user_id;
        $event_creation->event_name = (isset($request->event_name) && $request->event_name != "") ? $request->event_name : "";
        $event_creation->hosted_by = (isset($request->hosted_by) && $request->hosted_by) ? $request->hosted_by : "";
        $event_creation->start_date = (isset($startDateFormat) && $endDateFormat != "") ? $startDateFormat : null;
        $event_creation->end_date = (isset($endDateFormat) && $endDateFormat != "") ? $endDateFormat : null;
        $event_creation->rsvp_by_date_set =  $rsvp_by_date_set;
        // $event_creation->rsvp_by_date_set = (isset($request->rsvp_by_date_set) && $request->rsvp_by_date_set != "" && $request->rsvp_by_date_set != 'false') ? "1" : "0";
        $event_creation->rsvp_by_date = (isset($rsvp_by_date) && $rsvp_by_date != "") ? $rsvp_by_date : null;
        $event_creation->rsvp_start_time = (isset($request->start_time) && $request->start_time != "") ? $request->start_time : "";
        $event_creation->rsvp_start_timezone = (isset($request->rsvp_start_timezone) && $request->rsvp_start_timezone != "") ? $request->rsvp_start_timezone : "";
        $event_creation->rsvp_end_time = (isset($request->rsvp_end_time) && $request->rsvp_end_time != "") ? $request->rsvp_end_time : "";
        $event_creation->rsvp_end_timezone = (isset($request->rsvp_end_timezone) && $request->rsvp_end_timezone != "") ? $request->rsvp_end_timezone : "";
        $event_creation->rsvp_end_time_set = (isset($request->rsvp_end_time_set) && $request->rsvp_end_time_set != "") ? $request->rsvp_end_time_set : "0";
        $event_creation->event_location_name = (isset($request->event_location) && $request->event_location != "") ? $request->event_location : "";
        $event_creation->address_1 = (isset($request->address1) && $request->address1 != "") ? $request->address1 : "";
        $event_creation->address_2 = (isset($request->address_2) && $request->address_2 != "") ? $request->address_2 : "";
        $event_creation->state = (isset($request->state) && $request->state != "") ? $request->state : "";
        $event_creation->zip_code = (isset($request->zipcode) && $request->zipcode) ? $request->zipcode : "";
        $event_creation->city = (isset($request->city) && $request->city != "") ? $request->city : "";
        $event_creation->message_to_guests = (isset($request->message_to_guests) && $request->message_to_guests != "") ? $request->message_to_guests : "";
        $event_creation->is_draft_save = (isset($request->isdraft) && $request->isdraft != "") ? $request->isdraft : "0";
        $event_creation->latitude = (isset($request->latitude) && $request->latitude != "") ? $request->latitude : "";
        $event_creation->longitude = (isset($request->longitude) && $request->longitude != "") ? $request->longitude : "";
        $event_creation->greeting_card_id = (isset($greeting_card_id) && $greeting_card_id != "") ? $greeting_card_id : "0";
        $event_creation->gift_registry_id = (isset($gift_registry_id) && $gift_registry_id != "") ? $gift_registry_id : "0";
        $event_creation->subscription_plan_name = (isset($request->plan_selected) && $request->plan_selected != "") ? $request->plan_selected : "Pro";
        $event_creation->subscription_invite_count = (isset($request->subscription_invite_count) && $request->subscription_invite_count != "") ? $request->subscription_invite_count : 15;
        $event_creation->save();

        $eventId = $event_creation->id;
        $get_count_invited_user = 0;
        $conatctId = session('contact_ids');
        $invitedCount = session('user_ids');
        $get_count_invited_user = (isset($contactId) ? count($contactId) : 0) + (isset($invitedCount) ? count($invitedCount) : 0);
        if ($request->isCopy != "") {
            $get_count_invited_user = $request->Alreadyguest + $get_count_invited_user;
        }
        if (isset($request->isdraft) && $request->isdraft == "0") {
            debit_coins($user_id, $eventId, $get_count_invited_user);
            $step = $event_creation->step;
            if (isset($request->step) && $request->step != '' && $step < $request->step) {
                $event_creation->step = $request->step;
            }
        } else {
            $event_creation->step = (isset($request->step) && $request->step != '') ? $request->step : 0;
        }
        if (isset($request->shape_image) && $request->shape_image != '') {
            $event_creation->design_inner_image = $request->shape_image;
        }

        if ($request->temp_id != '' && $request->temp_id != null) {
            // dd($request->temp_id);
            $tempData = TextData::where('id', $request->temp_id)->first();
            if ($tempData) {
                $sourceImagePath = asset('storage/canvas/' . $tempData->image);
                $destinationDirectory = public_path('storage/event_images/');
                $destinationImagePath = $destinationDirectory . $tempData->image;
                if (file_exists(public_path('storage/canvas/') . $tempData->image)) {
                    $newImageName = time() . '_' . uniqid() . '.' . pathinfo($tempData->image, PATHINFO_EXTENSION);
                    $destinationImagePath = $destinationDirectory . $newImageName;
                    @File::copy($sourceImagePath, $destinationImagePath);
                    $event_creation->design_image = $tempData->image;
                }
            }
        } else if (isset($request->cutome_image)) {


            if (filter_var($request->cutome_image, FILTER_VALIDATE_URL)) {
                $pathParts = explode('/', $request->cutome_image);
                $event_creation->design_image = end($pathParts);
            } else {
                $event_creation->design_image = $request->cutome_image;
            }
            $sourceImagePath = asset('storage/canvas/' . $request->cutome_image);
        }

        if (isset($request->textData) && json_encode($request->textData) != '') {
            $textElemtents = $request->textData['textElements'];

            foreach ($textElemtents as $key => $textJson) {
                if ($textJson['fontSize'] != '') {
                    $textElemtents[$key]['fontSize'] = (int)$textJson['fontSize'];
                    $textElemtents[$key]['centerX'] = (float)$textJson['centerX'];
                    $textElemtents[$key]['centerY'] = (float)$textJson['centerY'];
                }
                if (isset($textJson['letterSpacing'])) {
                    $textElemtents[$key]['letterSpacing'] = (int)$textJson['letterSpacing'];
                }
                if (isset($textJson['lineHeight'])) {
                    $textElemtents[$key]['lineHeight'] = (float)$textJson['lineHeight'];
                }
                if (isset($textJson['underline'])) {
                    $textElemtents[$key]['underline'] = ($textJson['underline'] === "true" || $textJson['underline'] === true) ? true : false;
                }
            }


            $static_data = [];
            $static_data['textData'] = $textElemtents;
            $static_data['event_design_sub_category_id'] = (int)$request->temp_id;
            $static_data['height'] = (int)490;
            $static_data['width'] = (int)345;
            $static_data['image'] = $event_creation->design_image;
            $static_data['template_url'] = $sourceImagePath;
            $static_data['is_contain_image'] = false;
            if (isset($request->textData['shapeImageData'])) {
                $shapeImageData = [];
                $shapeImageData['shape'] = $request->textData['shapeImageData']['shape'];
                $shapeImageData['centerX'] = (float)$request->textData['shapeImageData']['centerX'];
                $shapeImageData['centerY'] = (float)$request->textData['shapeImageData']['centerY'];
                $shapeImageData['width'] = (float)$request->textData['shapeImageData']['width'];
                $shapeImageData['height'] = (float)$request->textData['shapeImageData']['height'];
                $static_data['shapeImageData'] = $shapeImageData;
                $static_data['is_contain_image'] = true;
            }

            $event_creation->static_information = json_encode($static_data);
        } else {
            $event_creation->static_information = null;
        }
        $event_creation->save();
        if ($eventId != "") {
            $invitedUsers = $request->email_invite;
            $invitedusersession = session('user_ids');
            if (isset($invitedusersession) && !empty($invitedusersession)) {
                foreach ($invitedusersession as $key => $value) {
                    $is_cohost = '0';
                    $invited_user = $value['id'];
                    $prefer_by =  $value['prefer_by'];

                    EventInvitedUser::create([
                        'event_id' => $eventId,
                        'prefer_by' => $prefer_by,
                        'user_id' => $invited_user,
                        'is_co_host' => $is_cohost,
                    ]);
                    $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('user_id', $user_id)->where('id', $eventId)->get();
                    foreach ($invitedusers as $event_detail) {
                        $eventData = [
                            'event_name' => $event_detail->event_name,
                            'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                            'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                            'event_image' => "no_image.png",
                            'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                            'time' => $event_detail->rsvp_start_time,
                            'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                        ];
                    }
                    // if(isset($request->isdraft) && $request->isdraft == '0'){
                    //     $user = User::find($value['id']);
                    //     if ($user) {
                    //         if ($user->email != "") {
                    //             $emailCheck = dispatch(new sendInvitation(array($user->email, $eventData)));
                    //             Session::forget('user_ids');
                    //         }
                    //     }
                    // }
                }
            }

            if (!empty($conatctId)) {
                $invitedGuestUsers = $conatctId;

                foreach ($invitedGuestUsers as $value) {

                    $checkContactExist = contact_sync::where('id', $value['sync_id'])->first();
                    if ($checkContactExist) {
                        $newUserId = NULL;
                        if ($checkContactExist->email != '') {
                            $newUserId = checkUserEmailExist($checkContactExist);
                        }
                        $eventInvite = new EventInvitedUser();
                        $eventInvite->event_id = $eventId;
                        $eventInvite->sync_id = $checkContactExist->id;
                        $eventInvite->user_id = $newUserId;
                        $eventInvite->prefer_by = (isset($value['prefer_by'])) ? $value['prefer_by'] : "email";
                        $eventInvite->save();
                    }
                    // }
                }
            }
            // DD($request);
            if (isset($request->co_host) && $request->co_host != '' && isset($request->co_host_prefer_by)) {
                $is_cohost = '1';
                $invited_user = $request->co_host;
                $prefer_by = $request->co_host_prefer_by;

                if (isset($request->isPhonecontact) && $request->isPhonecontact == 1) {

                    $checkContactExist = contact_sync::where('id', $invited_user)->first();
                    if ($checkContactExist) {
                        $newUserId = NULL;
                        if ($checkContactExist->email != '') {
                            $newUserId = checkUserEmailExist($checkContactExist);
                        }
                        $eventInvite = new EventInvitedUser();
                        $eventInvite->event_id = $eventId;
                        $eventInvite->sync_id = $checkContactExist->id;
                        $eventInvite->user_id = $newUserId;
                        $eventInvite->prefer_by = $prefer_by;
                        $eventInvite->is_co_host = $is_cohost;
                        $eventInvite->save();
                    }
                } else {
                    EventInvitedUser::create([
                        'event_id' => $eventId,
                        'prefer_by' => $prefer_by,
                        'user_id' => $invited_user,
                        'is_co_host' => $is_cohost,
                    ]);
                    $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('id', $eventId)->get();
                    foreach ($invitedusers as $event_detail) {
                        $eventData = [
                            'event_name' => $event_detail->event_name,
                            'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                            'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                            'event_image' => "no_image.png",
                            'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                            'time' => $event_detail->rsvp_start_time,
                            'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                        ];
                    }
                }
            }

            if (isset($request->eventSetting) && $request->eventSetting == "1") {
                $eventSetting = EventSetting::where('event_id', $eventId)->first();
                if ($eventSetting != null) {
                    $eventSetting->allow_for_1_more = (isset($request->allow_for_1_more)) ? $request->allow_for_1_more : "0";
                    $eventSetting->allow_limit = (isset($request->allow_limit_count)) ? (int)$request->allow_limit_count : 0;
                    $eventSetting->adult_only_party = (isset($request->only_adults)) ? $request->only_adults : "0";
                    $eventSetting->thank_you_cards = (isset($request->thankyou_message)) ? $request->thankyou_message : "0";
                    $eventSetting->add_co_host = (isset($request->add_co_host)) ? $request->add_co_host : "0";
                    $eventSetting->gift_registry = (isset($request->gift_registry)) ? $request->gift_registry : "0";
                    $eventSetting->events_schedule = (isset($request->events_schedule)) ? $request->events_schedule : "0";
                    $eventSetting->event_wall = (isset($request->event_wall)) ? $request->event_wall : "0";
                    $eventSetting->guest_list_visible_to_guests = (isset($request->guest_list_visible_to_guest)) ? $request->guest_list_visible_to_guest : "0";
                    $eventSetting->podluck = (isset($request->potluck)) ? $request->potluck : "0";
                    $eventSetting->rsvp_updates = (isset($request->rsvp_update)) ? $request->rsvp_update : "0";
                    $eventSetting->event_wall_post = (isset($request->event_wall_post)) ? $request->event_wall_post : "0";
                    $eventSetting->send_event_dater_reminders = (isset($request->rsvp_remainder)) ? $request->rsvp_remainder : "0";
                    $eventSetting->request_event_photos_from_guests = (isset($request->request_photo)) ? $request->request_photo : "0";
                    $eventSetting->save();
                } else {
                    EventSetting::create([
                        'event_id' => $eventId,
                        'allow_for_1_more' => (isset($request->allow_for_1_more)) ? $request->allow_for_1_more : "0",
                        'allow_limit' => (isset($request->allow_limit_count)) ? (int)$request->allow_limit_count : 0,
                        'adult_only_party' => (isset($request->only_adults)) ? $request->only_adults : "0",
                        'thank_you_cards' => (isset($request->thankyou_message)) ? $request->thankyou_message : "0",
                        'add_co_host' => (isset($request->add_co_host)) ? $request->add_co_host : "0",
                        'gift_registry' => (isset($request->gift_registry)) ? $request->gift_registry : "0",
                        'events_schedule' => (isset($request->events_schedule)) ? $request->events_schedule : "0",
                        'event_wall' => (isset($request->event_wall)) ? $request->event_wall : "0",
                        'guest_list_visible_to_guests' => (isset($request->guest_list_visible_to_guest)) ? $request->guest_list_visible_to_guest : "0",
                        'podluck' => (isset($request->potluck)) ? $request->potluck : "0",
                        'rsvp_updates' => (isset($request->rsvp_update)) ? $request->rsvp_update : "0",
                        'event_wall_post' => (isset($request->event_wall_post)) ? $request->event_wall_post : "0",
                        'send_event_dater_reminders' => (isset($request->rsvp_remainder)) ? $request->rsvp_remainder : "0",
                        'request_event_photos_from_guests' => (isset($request->request_photo)) ? $request->request_photo : "0",
                    ]);
                }
            }

            // if (isset($request->potluck) && $request->potluck == "1") {
            //     $potluck = session('category');
            //     if (isset($potluck) && !empty($potluck)) {
            //         foreach ($potluck as $category) {
            //             $eventPodluck = EventPotluckCategory::create([
            //                 'event_id' => $eventId,
            //                 'user_id' => $user_id,
            //                 'category' => $category['category_name'],
            //                 'quantity' => $category['category_quantity'],
            //             ]);
            //             if (isset($category['item'])) {
            //                 foreach ($category['item'] as $item) {
            //                     $eventPodluckitem = EventPotluckCategoryItem::create([
            //                         'event_id' => $eventId,
            //                         'user_id' => $user_id,
            //                         'event_potluck_category_id' => $eventPodluck->id,
            //                         'self_bring_item' =>  $item['self_bring'],
            //                         'description' => $item['name'],
            //                         'quantity' => $item['quantity'],
            //                     ]);
            //                     if (isset($item['self_bring']) && $item['self_bring'] == '1') {
            //                         UserPotluckItem::Create([
            //                             'event_id' => $eventId,
            //                             'user_id' => $user_id,
            //                             'event_potluck_category_id' => $eventPodluck->id,
            //                             'event_potluck_item_id' => $eventPodluckitem->id,
            //                             'quantity' => (isset($item['self_bring_qty']) && @$item['self_bring_qty'] != "") ? $item['self_bring_qty'] : $item['quantity']
            //                         ]);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }


            if (isset($request->potluck) && $request->potluck == "1") {
                $potluck = session('category');
                // if ($request->isdraft == "1") {
                // EventPotluckCategory::where('event_id', $request->event_id)->delete();
                // EventPotluckCategoryItem::where('event_id', $request->event_id)->delete();
                // UserPotluckItem::where('event_id', $request->event_id)->delete();
                // }
                if (isset($potluck) && !empty($potluck)) {
                    foreach ($potluck as $category) {
                        if ($category['iscateogry'] == '0') {
                            continue;
                        }
                        $eventPodluck = EventPotluckCategory::create([
                            'event_id' => $eventId,
                            'user_id' => $user_id,
                            'category' => $category['category_name'],
                            'quantity' => $category['category_quantity'],
                        ]);
                        if (isset($category['item'])) {
                            foreach ($category['item'] as $item) {
                                $eventPodluckitem = EventPotluckCategoryItem::create([
                                    'event_id' => $eventId,
                                    'user_id' => $user_id,
                                    'event_potluck_category_id' => $eventPodluck->id,
                                    'self_bring_item' =>  $item['self_bring'],
                                    'description' => $item['name'],
                                    'quantity' => $item['quantity'],
                                ]);
                                if (isset($item['item_carry_users'])) {
                                    foreach ($item['item_carry_users'] as $user) {
                                        UserPotluckItem::Create([
                                            'event_id' => $eventId,
                                            'user_id' => $user['user_id'],
                                            'event_potluck_category_id' => $eventPodluck->id,
                                            'event_potluck_item_id' => $eventPodluckitem->id,
                                            'quantity' => $user['quantity']
                                        ]);
                                    }
                                }
                                // else{
                                //     if (isset($item['self_bring']) && $item['self_bring'] == '1') {
                                //         UserPotluckItem::Create([
                                //             'event_id' => $eventId,
                                //             'user_id' => $user_id,
                                //             'event_potluck_category_id' => $eventPodluck->id,
                                //             'event_potluck_item_id' => $eventPodluckitem->id,
                                //             'quantity' => (isset($item['self_bring_qty']) && @$item['self_bring_qty'] != "") ? $item['self_bring_qty'] : $item['quantity']
                                //         ]);
                                //     }
                                // }

                            }
                        }
                    }
                }
            }

            if (isset($request->event_id) && $request->event_id != null && isset($request->events_schedule) && $request->events_schedule == '0') {
                EventSchedule::where('event_id', $request->event_id)->delete();
            }

            if (isset($request->events_schedule) && $request->events_schedule == '1' && isset($request->activity) && !empty($request->activity)) {
                $activities = $request->activity;
                if (isset($request->event_id) && $request->event_id != null) {
                    EventSchedule::where('event_id', $request->event_id)->delete();
                }
                $addStartschedule =  new EventSchedule();
                $addStartschedule->event_id = $eventId;
                $addStartschedule->start_time = isset($request->start_time) ? $request->start_time : '';
                $addStartschedule->event_date = isset($startDateFormat) ? $startDateFormat : '';
                $addStartschedule->type = '1';
                $addStartschedule->save();

                foreach ($activities as $date => $activityList) {
                    $schedule_date = date('Y-m-d', strtotime($date));
                    foreach ($activityList as $activity) {
                        $activity_data[] = [
                            'event_id' => $eventId,
                            'activity_title' => $activity['activity'],
                            'start_time' => $activity['start-time'],
                            'end_time' => $activity['end-time'],
                            'event_date' => $schedule_date,
                            'type' => '2'
                        ];
                    }
                }

                EventSchedule::insert($activity_data);

                $addEndschedule =  new EventSchedule();
                $addEndschedule->event_id = $eventId;
                $addEndschedule->end_time = isset($request->end_time)  ? $request->end_time : '';
                $addEndschedule->event_date = isset($endDateFormat) ? $endDateFormat : '';
                $addEndschedule->type = '3';
                $addEndschedule->save();
            }
            $gift = "0";
            if ($request->gift_registry == "1") {
                $gift_registry = $request->gift_registry_data;
                // $gift = "1";
                //     if (isset($gift_registry) && !empty($gift_registry)) {
                //         foreach ($gift_registry as $data) {
                //             $gift_registry_data[] = [
                //                 'user_id' => $user_id,
                //                 'registry_recipient_name' => $data['registry_name'],
                //                 'registry_link' => $data['registry_link'],
                //                 'created_at' => now(),
                //                 'updated_at' => now(),
                //             ];
                //         }
                //         EventGiftRegistry::insert($gift_registry_data);
                //     }
            }

            if (isset($request->desgin_selected) && $request->desgin_selected != "") {
                EventImage::where('event_id', $eventId)->where('type', 0)->delete();

                EventImage::create([
                    'event_id' => $eventId,
                    'image' => $request->desgin_selected,
                    'type' => 0
                ]);
            }

            if (isset($request->slider_images) && !empty($request->slider_images)) {
                EventImage::where('event_id', $eventId)->where('type', 1)->delete();

                foreach ($request->slider_images as $key => $value) {
                    EventImage::create([
                        'event_id' => $eventId,
                        'image' => $value['fileName'],
                        'type' => 1

                    ]);
                }
            }




            $checkUserInvited = Event::withCount('event_invited_user')->where('id', $eventId)->first();
            if ($request->is_update_event == '0') {
                if ($checkUserInvited->event_invited_user_count != '0' && $checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];

                    sendNotification('invite', $notificationParam);
                    sendNotificationGuest('invite', $notificationParam);
                }
                if ($checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];
                    sendNotification('owner_notify', $notificationParam);
                    $get_count_invited_user = 0;
                    $conatctId = session('contact_ids');
                    $invitedCount = session('user_ids');
                    $get_count_invited_user = (isset($contactId) ? count($contactId) : 0) + (isset($invitedCount) ? count($invitedCount) : 0);
                    if ($request->isCopy != "") {
                        $get_count_invited_user = $request->Alreadyguest + $get_count_invited_user;
                    }
                    debit_coins($user_id, $eventId, $get_count_invited_user);
                }
            }



            // if ($request->thankyou_message == "1") {
            //     $thankyou_card = session('thankyou_card_data');
            //     if (isset($thankyou_card) && !empty($thankyou_card)) {
            //         // dd($gift_registry);
            //         foreach ($thankyou_card as $data) {
            //             $thankyou_card_data[] = [
            //
            //             ];
            //         }
            //         EventGiftRegistry::insert($thankyou_card_data);
            //     }
            // }
            // return  Redirect::to('event')->with('success', 'Event Created successfully');
            Session::forget('desgin');
            Session::forget('shape_image');
        }
        if ($event_creation && $request->isdraft == "1") {
            return 1;
        }

        $registry = [];
        if ($request->isCopy != null) {
            if (isset($request->gift_registry_data) && count($request->gift_registry_data) > 0) {
                foreach ($request->gift_registry_data as $key => $imgVal) {
                    $gr = EventGiftRegistry::where('id', $imgVal['gr_id'])->first();
                    if ($gr) {  // Check if $gr is not null
                        $registry[] = [
                            'registry_link' => $gr->registry_link
                        ];
                    }
                }
            }
        } else {
            $registry = $request->gift_registry_data;
        }

        // dd($registry);
        if (!empty($registry)) {
            $gift = '1';
        }
        Session::save();
        return response()->json([
            'view' => view('front.event.gift_registry.view_gift_registry', compact('registry', 'eventId'))->render(),
            'success' => true,
            'is_registry' => $gift,
            'event_id' => encrypt($eventId)
        ]);
    }

    // public function storeUserId(Request $request)
    // {
    //     $userId = $request->input('user_id');

    //     $user = User::where('id', $userId)->first();
    //     // $userimage = asset('storage/profile/' . $user->profile);
    //     $userimage = $user->profile;
    //     $useremail = $request->input('email');
    //     $phone = $request->input('mobile');
    //     // dd($userProfile);
    //     $isChecked = $request->input('is_checked');
    //     $userIds = session()->get('user_ids', []);
    //     // if ($isChecked == true || $isChecked == "true") {
    //     //     if (!in_array($userId, $userIds)) {
    //     //         $userIds[] = ['id' => $userId, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '', 'email' => $useremail, 'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''];
    //     //         session()->put('user_ids', $userIds);
    //     //         return response()->json(['success' => true, 'data' => $userIds]);
    //     //     }
    //     // }
    //     if (isset($useremail) && $useremail != "") {
    //         $user_invited_by = $useremail;
    //         $prefer_by = "email";
    //     }
    //     if (isset($phone) && $phone != "") {
    //         $user_invited_by = $user->phone_number;
    //         $prefer_by = "phone";
    //     }

    //     $userEntry = [
    //         'id' => $userId,
    //         'firstname' => $user->firstname,
    //         'lastname' => $user->lastname,
    //         // 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '',
    //         // 'email' => $useremail,
    //         'invited_by' => $user_invited_by,
    //         'prefer_by' => $prefer_by,
    //         'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''
    //     ];

    //     // if ($isChecked == true || $isChecked == "true") {
    //     //     // Remove any existing entry with the same user ID
    //     //     $userIds = array_filter($userIds, function ($entry) use ($userId) {
    //     //         return $entry['id'] !== $userId;
    //     //     });

    //     //     // Add the latest user entry
    //     //     $userIds[] = $userEntry;

    //     //     // Save updated array to session
    //     //     session()->put('user_ids', $userIds);


    //     //     // Return only the latest entry
    //     //     return response()->json(['success' => true, 'data' => $userEntry]);
    //     // }
    //     // dd($userIds);

    //     if ($isChecked == true || $isChecked == "true") {
    //         $userExists = array_filter($userIds, function ($entry) use ($userId) {
    //             return $entry['id'] === $userId;
    //         });

    //         $userIds = array_filter($userIds, function ($entry) use ($userId) {
    //             return $entry['id'] !== $userId;
    //         });
    //         $userIds[] = $userEntry;
    //         session()->put('user_ids', $userIds);
    //         if (!empty($userExists)) {
    //             // return response()->json(['success' => false, 'data' => $userEntry, 'is_duplicate' => 1]);
    //             $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
    //             return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(),  'responsive_view' => view('front.event.guest.addguest_responsive', compact('data', 'user_list'))->render(), 'is_duplicate' => 1]);
    //         }
    //         $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
    //         return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(), 'responsive_view' => view('front.event.guest.addguest_responsive', compact('data', 'user_list'))->render(), 'success' => true, 'data' => $userEntry, 'is_duplicate' => 0]);
    //     }
    // }

    public function storeUserId(Request $request)
    {
        $userId = $request->input('user_id');
        $is_contact = $request->input('is_contact');

        if ($is_contact == '0') {
            $user = User::where('id', $userId)->first();
            // $userimage = asset('storage/profile/' . $user->profile);
            $userimage = $user->profile;
            $useremail = $request->input('email');
            $phone = $request->input('mobile');
            // dd($userProfile);
            $isChecked = $request->input('is_checked');
            $userIds = session()->get('user_ids', []);


            // if ($isChecked == true || $isChecked == "true") {
            //     if (!in_array($userId, $userIds)) {
            //         $userIds[] = ['id' => $userId, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '', 'email' => $useremail, 'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''];
            //         session()->put('user_ids', $userIds);
            //         return response()->json(['success' => true, 'data' => $userIds]);
            //     }
            // }
            if (isset($useremail) && $useremail != "") {
                $user_invited_by = $useremail;
                $prefer_by = "email";
            }
            if (isset($phone) && $phone != "") {
                $user_invited_by = $user->phone_number;
                $prefer_by = "phone";
            }

            $userEntry = [
                'id' => $userId,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                // 'phonenumber' => ($user->phone_number != "") ? $user->phone_number : '',
                // 'email' => $useremail,
                'invited_by' => $user_invited_by,
                'prefer_by' => $prefer_by,
                'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''
            ];

            // if ($isChecked == true || $isChecked == "true") {
            //     // Remove any existing entry with the same user ID
            //     $userIds = array_filter($userIds, function ($entry) use ($userId) {
            //         return $entry['id'] !== $userId;
            //     });

            //     // Add the latest user entry
            //     $userIds[] = $userEntry;

            //     // Save updated array to session
            //     session()->put('user_ids', $userIds);


            //     // Return only the latest entry
            //     return response()->json(['success' => true, 'data' => $userEntry]);
            // }
            // dd($userIds);

            if ($isChecked == true || $isChecked == "true") {
                $userExists = array_filter($userIds, function ($entry) use ($userId) {
                    if (isset($entry['id'])) {
                        return $entry['id'] === $userId;
                    }
                });

                $userIds = array_filter($userIds, function ($entry) use ($userId) {
                    if (isset($entry['id'])) {
                        return $entry['id'] !== $userId;
                    }
                });
                $userIds[] = $userEntry;
                session()->put('user_ids', $userIds);
                Session::save();
                $user_list = Session::get('user_ids');
                // dd($user_list);
                if (!empty($userExists)) {
                    // return response()->json(['success' => false, 'data' => $userEntry, 'is_duplicate' => 1]);
                    $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
                    return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(),  'responsive_view' => view('front.event.guest.addguest_responsive', compact('data', 'user_list'))->render(), 'is_duplicate' => 1, "is_yesvite" => 1]);
                }
                $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
                return response()->json(['view' => view('front.event.guest.addGuest', compact('data'))->render(), 'responsive_view' => view('front.event.guest.addguest_responsive', compact('data', 'user_list'))->render(), 'success' => true, 'data' => $userEntry, 'is_duplicate' => 0, "is_yesvite" => 1]);
            }
        } else {
            $user = contact_sync::where('id', $userId)->first();
            $userimage = $user->photo;
            $useremail = $request->input('email');
            $phone = $request->input('mobile');
            $isChecked = $request->input('is_checked');
            // $userIds = session()->get('user_ids', []);
            $userIds = session()->get('contact_ids', []);



            if (isset($useremail) && $useremail != "") {
                $user_invited_by = $useremail;
                $prefer_by = "email";
            }
            if (isset($phone) && $phone != "") {
                $user_invited_by = $user->phoneWithCode;
                $prefer_by = "phone";
            }

            $userEntry = [
                'sync_id' => $userId,
                'firstname' => $user->firstName,
                'lastname' => $user->lastName,
                'invited_by' => $user_invited_by,
                'prefer_by' => $prefer_by,
                'profile' => ''
            ];

            if ($isChecked == true || $isChecked == "true") {
                $userExists = array_filter($userIds, function ($entry) use ($userId) {
                    if (isset($entry['sync_id'])) {
                        return $entry['sync_id'] === $userId;
                    }
                });

                $userIds = array_filter($userIds, function ($entry) use ($userId) {
                    if (isset($entry['sync_id'])) {
                        return $entry['sync_id'] !== $userId;
                    }
                });
                $userIds[] = $userEntry;
                session()->put('contact_ids', $userIds);
                Session::save();

                $user_list = Session::get('contact_ids');



                if (!empty($userExists)) {
                    $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
                    return response()->json(['view' => view('front.event.guest.addContactGuest', compact('data'))->render(),  'responsive_view' => view('front.event.guest.addcontact_responsive', compact('data', 'user_list'))->render(), 'is_duplicate' => 1, "is_phone" => 1]);
                }
                $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
                return response()->json(['view' => view('front.event.guest.addContactGuest', compact('data'))->render(), 'responsive_view' => view('front.event.guest.addcontact_responsive', compact('data', 'user_list'))->render(), 'success' => true, 'data' => $userEntry, 'is_duplicate' => 0, "is_phone" => 1]);
            }
        }
    }

    public function getAllInvitedGuest(Request $request)
    {
        $selected_user = session('user_ids');
        $user_id =  Auth::guard('web')->user()->id;
        $alreadyselectedUser =  collect($selected_user)->pluck('id')->toArray();
        $selected_co_host = (isset($request->selected_co_host) && $request->selected_co_host != '') ? $request->selected_co_host : '';
        $selected_co_host_prefer_by = (isset($request->selected_co_host_prefer_by) && $request->selected_co_host_prefer_by != '') ? $request->selected_co_host_prefer_by : '';

        $users = User::select('id', 'firstname', 'lastname', 'phone_number', 'email', 'profile')
            ->whereNotIn('id', $alreadyselectedUser)
            ->where('id', '!=', $user_id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname')
            ->get();
        return  view('front.event.guest.allGuestList', compact('users', 'selected_co_host', 'selected_co_host_prefer_by'));
    }

    public function removeUserId(Request $request)
    {

        $is_contact = $request->input('is_contact');
        if ($is_contact == '1') {
            $userIds = session()->get('contact_ids');
            $userId = $request->input('user_id');
            foreach ($userIds as $key => $value) {
                if ($value['sync_id'] == $userId) {
                    unset($userIds[$key]);
                }
            }
            $users = $userIds;
            session()->put('contact_ids', $users);
            Session::save();
            $user_list = Session::get('contact_ids');
            return response()->json(['responsive_view' => view('front.event.guest.addcontact_responsive', compact('user_list'))->render(), 'success' => true, 'is_phone' => 1]);
        } else {
            $userIds = session()->get('user_ids');
            $userId = $request->input('user_id');
            // dD($userIds,$userId);
            foreach ($userIds as $key => $value) {
                if ($value['id'] == $userId) {
                    unset($userIds[$key]);
                }
            }
            $users = $userIds;
            session()->put('user_ids', $users);
            Session::save();
            $user_list = Session::get('user_ids');
            return response()->json(['success' => true, 'responsive_view' => view('front.event.guest.addguest_responsive', compact('user_list'))->render(), 'is_yesvite' => 1]);
        }
    }

    public function deleteSession(Request $request)
    {
        // dd($request->input());
        $sessionKeys = $request->input('session_key');
        $image = Session::get('desgin');

        if (isset($image) && $image != "" || $image != NULL) {
            if (file_exists(public_path('storage/event_design_template/') . $image)) {
                $imagePath = public_path('storage/event_design_template/') . $image;
                unlink($imagePath);
                session()->forget('desgin');
            }
        }
        // Session::forget('user_ids');
        // Session::put('user_ids', []);
        // Session::save();
        // if (is_array($sessionKeys)) {
        //     dd($sessionKeys);
        //     foreach ($sessionKeys as $key) {
        //         $request->session()->forget($key);
        //     }
        //     // dd(session('user_ids'));
        //     return response()->json(['success' => true, 'message' => 'Sessions deleted successfully']);
        // }
        return response()->json(['success' => true, 'message' => 'Session deleted successfully']);
    }


    public function storeCategoryitemSession(Request $request)
    {

        $user = Auth::guard('web')->user();
        $id = $user->id;
        $name = $user->firstname . ' ' . $user->lastname;
        $categoryName = $request->input('category_name');
        // $category_quantity = $request->input('category_quantity');
        $itemName = $request->input('itemName');
        $totalmissing = $request->input('totalmissing');
        $selfBring = $request->input('selfbring');
        $selfBringQuantity = $request->input('self_bringQuantity');
        $itemQuantity = $request->input('itemQuantity');
        // dd($totalmissing,$itemQuantity);
        // dd($itemQuantity);
        $category_index = $request->input('category_index');

        $categories_item = Session::get('category_item', []);
        $itemData = [
            'item' => $itemName,
            'self_bring' => $selfBring,
            'quantity' => $itemQuantity
        ];
        if (isset($categories_item[$categoryName])) {
            $categories_item[$categoryName][] = $itemData;
        } else {
            $categories_item[$categoryName] = [$itemData];
        }


        $categories = Session::get('category', []);

        if (isset($categories[$category_index])) {
            if (!isset($categories[$category_index]['item'])) {
                $categories[$category_index]['item'] = [];
            }
            $item = [
                'name' => $itemName,
                'self_bring' => $selfBring,
                'self_bring_qty' => $selfBringQuantity,
                'quantity' => $itemQuantity,
            ];
            if ($selfBringQuantity != "") {
                $item['item_carry_users'][] = [
                    'user_id' => $user->id,
                    'quantity' => $selfBringQuantity,
                ];
            }
            $categories[$category_index]['item'][] = $item;

            // $categories[$category_index]['item'][] = [
            //     'name' => $itemName,
            //     'self_bring' => $selfBring,
            //     'self_bring_qty' => $selfBringQuantity,
            //     'quantity' => $itemQuantity,
            //     if($selfBringQuantity!=""){
            //         'item_carry_users' => [
            //             'user_id' => $user->id,  // Use => for key-value pairs
            //             'quantity' => $itemQuantity,
            //         ]
            //     }
            // ];
        } else {
            // $categories[$category_index] = [
            //     'category_name' => $categoryName, // Use $categoryName from the request
            //     'category_quantity' => $request->input('category_quantity', 0), // Provide a default value if not available
            //     'item' => [
            //         [
            //             'name' => $itemName,
            //             // 'self_bring' => $selfBring,
            //             // 'self_bring_qty' => $selfBringQuantity,
            //             'quantity' => $itemQuantity,
            //             if($selfBringQuantity!=""){
            //             'item_carry_users' => [
            //                 'user_id' => $user->id,  // Use => for key-value pairs
            //                 'quantity' => $itemQuantity,
            //             ]
            //             }
            //         ]
            //     ]
            // ];
            $categoryData = [
                'category_name' => $categoryName,
                'category_quantity' => $request->input('category_quantity', 0),
                'item' => [
                    [
                        'name' => $itemName,
                        'quantity' => $itemQuantity,
                    ]
                ]
            ];

            if ($selfBringQuantity != "") {
                $categoryData['item'][0]['item_carry_users'][] = [
                    'user_id' => $user->id,
                    'quantity' => $selfBringQuantity,
                ];
            }
            $categories[$category_index] = $categoryData;
        }

        //  else {
        //     $categories[$category_index] = [
        //         'category_name' => $categories[$category_index]['category_name'],
        //         'category_quantity' => $categories[$category_index]['category_quantity'],
        //         'item' => [
        //             [
        //                 'name' => $itemName,
        //                 'quantity' => $itemQuantity,
        //             ]
        //         ]
        //     ];
        // }

        Session::put('category', $categories);

        Session::put('category_item', $categories_item);
        Session::save();
        $categories = Session::get('category', []);
        // dD($categories);
        $category_quantity = $categories[$category_index]['category_quantity'];
        // $category_item = count($categories[$category_index]['item']);
        $last_index = count($categories[$category_index]['item']) - 1;
        $total_item = 0;
        $total_quantity = 0;
        if (isset($categories[$category_index]['item']) && !empty($categories[$category_index]['item'])) {
            foreach ($categories[$category_index]['item'] as $key => $value) {
                $total_item = $total_item + $value['quantity'];
                if (isset($categories[$category_index]['item'][$key]['item_carry_users'])) {

                    // foreach ($categories[$category_index]['item'][$key]['item_carry_users'] as $userkey => $userVal) {
                    //     $total_quantity = intva($total_quantity) + intval($userVal['quantity']);
                    //     dd($categories[$category_index]['item'][$key]['item_carry_users']);
                    // }
                    // if (isset($value['self_bring']) && isset($value['self_bring_qty']) && $value['self_bring'] == 1) {
                    //     $total_quantity = $total_quantity + $value['self_bring_qty'];
                    // }
                }
            }
        }
        if ($selfBring != "0" && $selfBringQuantity != "0") {
            $total_item = $totalmissing + 0;
        } else {
            $total_item = $totalmissing + intval($itemQuantity);
            // dd(total_item);

        }
        $qty = 0;
        if ($category_quantity == $last_index) {
            $qty = 1;
        } else {
            $qty = 0;
        }
        $data = [
            "itemdata" => $itemData,
            'user' => $name,
            'profile' => $user->profile,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'category' => $categoryName,
            'self_bring' => $selfBring,
            'self_bring_qty' => $selfBringQuantity,
            'category_index' => $category_index,
            'category_item' => $last_index,
        ];

        // Dd($data)
        // return view('front.event.potluck.potluckCategoryItem', $data);

        return response()->json(['view' => view('front.event.potluck.potluckCategoryItem', $data)->render(), 'qty' => $qty, 'total_item' => $total_item]);
    }

    public function storeCategorySession(Request $request)
    {
        $categoryName = $request->input('category_name');
        $categoryQuantity = $request->input('categoryQuantity');
        $potluckkey = $request->input('potluckkey');
        $edit_category_id = $request->input('edit_category_id');
        $categories = session()->get('category', []);


        $lastItemIndex = count($categories) > 0 && end($categories) === null ? 0 : count($categories);

        $categoryNames =  collect($categories)->pluck('category_name')->toArray();


        if (isset($edit_category_id) && $edit_category_id != '') {
            $status = '2';
            $i = 0;
            foreach ($categories as $key => $value) {
                if ($key == $edit_category_id) {
                    continue;
                }
                if ($value['category_name'] == $categoryName) {
                    $i++;
                }
            }
            if ($i == 0) {
                if (isset($categories[$edit_category_id]['item']) && !empty($categories[$edit_category_id]['item'])) {
                    $item = $categories[$edit_category_id]['item'];
                    $categories[$edit_category_id] = [
                        'category_name' => $categoryName,
                        'category_quantity' => $categoryQuantity,
                        'iscateogry' => "1",
                        'item' => $item
                    ];
                } else {
                    $categories[$edit_category_id] = [
                        'category_name' => $categoryName,
                        'category_quantity' => $categoryQuantity,
                        'iscateogry' => "1",
                        'item' => []
                    ];
                }
                session()->put('category', $categories);
                Session::save();
                $categories = Session::get('category', []);
                $category_quantity = $categories[$edit_category_id]['category_quantity'];
                $category_item = count($categories[$edit_category_id]['item']);
                $qty = 0;
                if ($category_quantity <= $category_item) {
                    $qty = 1;
                } else {
                    $qty = 0;
                }
                return response()->json(['status' => $status, 'qty' => $qty]);
            } else {
                return response()->json(['view' => '', 'status' => '0']);
            }
        } else {
            if (in_array($categoryName, $categoryNames)) {
                return response()->json(['view' => '', 'status' => '0']);
            } else {
                $categories[] = ['category_name' => $categoryName, 'iscateogry' => "1", 'category_quantity' => $categoryQuantity];
            }
            session()->put('category', $categories);
            $status = '1';
            Session::save();
            return response()->json(['view' => view('front.event.potluck.potluckCategory', ['categoryName' => $categoryName, 'categoryQuantity' => 0, 'potluckkey' => $potluckkey, 'lastItemIndex' => $lastItemIndex])->render(), 'status' => $status]);
            // return response()->json(['view' => view('front.event.potluck.potluckCategory', ['categoryName' => $categoryName, 'categoryQuantity' => $categoryQuantity, 'potluckkey' => $potluckkey, 'lastItemIndex' => $lastItemIndex])->render(), 'status' => $status]);
        }

        // return $categoryName;
    }


    public function addActivity(Request $request)
    {
        $dataid = $request->input('dataid');
        $newClass = $request->input('newClass');
        $count = $request->input('count');
        $id = $request->input('id');
        return view('front.event.activity.addActivity', ['dataid' => $dataid, 'newClass' => $newClass, 'count' => $count, 'id' => $id]);
    }

    public function deletePotluckCategory(Request $request)
    {

        $delete_potluck_id = $request->input('potluck_delete_id');
        $category_item = 0;
        if ($delete_potluck_id == 'all_potluck') {
            Session::forget('category');
        } else {
            $category = Session::get('category');
            if (isset($category[$delete_potluck_id]['item'])) {
                $category_item = count($category[$delete_potluck_id]['item']);
            }
            // if (isset($category[$delete_potluck_id])) {
            //     unset($category[$delete_potluck_id]);
            // }
            $category[$delete_potluck_id]['iscateogry'] = '0';
            Session::put('category', $category);
        }

        Session::save();
        // $event_data_display = Session::get('event_date_display');
        // unset($event_data_display[$index]);
        // Session::set('event_data_display', $event_data_display);

        return $category_item;
    }

    public function addNewGiftRegistry(Request $request)
    {
        $user_id =  Auth::guard('web')->user()->id;
        $recipient_name = $request->input('recipient_name');
        $registry_link = $request->input('registry_link');
        $registry_item = $request->input('registry_item');
        $when_to_send = $request->input('when_to_send');

        $giftRegistryData = session()->get('gift_registry_data', []);

        if ($registry_item != null) {

            $gr = EventGiftRegistry::where('id', $registry_item)->first();
            if ($gr != null) {
                $gr->registry_recipient_name = $recipient_name;
                $gr->registry_link = $registry_link;
                $gr->save();
            }
            return response()->json(['message' => "registry updated", 'status' => '1']);
        } else {
            $gr = new EventGiftRegistry();
            $gr->user_id = $user_id;
            $gr->registry_recipient_name = $recipient_name;
            $gr->registry_link = $registry_link;
            $gr->save();
            $gift_registry = EventGiftRegistry::where('id', $gr->id)->get();
            session(['gift_registry_data' => $giftRegistryData]);

            // $data = ['recipient_name' => $recipient_name, 'registry_link' => $registry_link, 'registry_item' => $registry_item];
            return response()->json(['view' => view('front.event.gift_registry.add_gift_registry', compact('gift_registry'))->render()]);
        }

        // if (array_key_exists($registry_item, $giftRegistryData) != null || array_key_exists($registry_item, $giftRegistryData) != "") {
        //     $giftRegistryData[$registry_item]['recipient_name'] = $recipient_name;
        //     $giftRegistryData[$registry_item]['registry_link'] = $registry_link;

        //     session(['gift_registry_data' => $giftRegistryData]);

        // } else {
        //     $giftRegistryData[$registry_item] = [
        //         'recipient_name' => $recipient_name,
        //         'registry_link' => $registry_link,
        //     ];

        // }
    }

    public function removeGiftRegistry(Request $request)
    {
        $registry_item = $request->input('registry_item');
        EventGiftRegistry::where('id', $registry_item)->delete();

        $giftRegistryData = session()->get('gift_registry_data', []);
        if (array_key_exists($registry_item, $giftRegistryData)) {
            unset($giftRegistryData[$registry_item]);
        }
        session(['gift_registry_data' => $giftRegistryData]);

        return response()->json(['message' => 'Gift registry item removed successfully.']);
    }

    public function addNewThankyouCard(Request $request)
    {
        $user_id =  Auth::guard('web')->user()->id;
        // dd($request);
        $template_name = $request->input('template_name');
        $when_to_send = $request->input('when_to_send');
        $thankyou_message = $request->input('thankyou_message');
        $thankyou_template_id = $request->input('thankyou_template_id');

        $thankyouCard = session()->get('thankyou_card_data', []);

        if ($thankyou_template_id != null) {
            $gr = EventGreeting::where('id', $thankyou_template_id)->first();
            if ($gr != null) {
                $gr->template_name = $template_name;
                $gr->custom_hours_after_event = $when_to_send;
                $gr->message = $thankyou_message;
                $gr->save();
            }
            $status = 1;
        } else {

            $gr = new EventGreeting();
            $gr->user_id = $user_id;
            $gr->template_name = $template_name;
            $gr->custom_hours_after_event = $when_to_send;
            $gr->message = $thankyou_message;
            $gr->save();
            $status = 0;
        }

        $thankyou_card = EventGreeting::where('user_id', $user_id)->get();
        // $data = ['name' => $template_name, 'when_to_send' => $when_to_send, 'message' => $thankyou_message, 'thankyou_template_id' => $thankyou_template_id];
        $thankuCardId = '';
        return response()->json(['view' => view('front.event.thankyou_template.add_thankyou_template', compact('thankyou_card', 'thankuCardId'))->render(), 'status' => $status]);
    }

    public function removeThankyouCard(Request $request)
    {
        $thank_you_card_id = $request->input('thank_you_card_id');
        EventGreeting::where('id', $thank_you_card_id)->delete();
        return response()->json(['message' => 'Greeting card deleted']);
    }

    public function updateSelfBring(Request $request)
    {
        $categoryItemKey = $request->categoryItemKey;
        $categoryIndexKey = $request->categoryIndexKey;
        $quantity = (string)$request->quantity;

        $categories = session()->get('category', []);


        $id = Auth::guard('web')->user()->id;
        $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring'] = ($quantity == 0) ? '0' : '1';
        $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring_qty'] = $quantity;
        session()->put('category', $categories);


        $categories = session()->get('category', []);
        // Session::save();



        $total_item = 0;
        $total_quantity = 0;

        if (isset($categories[$categoryIndexKey]['item'][$categoryItemKey]) && !empty($categories[$categoryIndexKey]['item'][$categoryItemKey])) {

            // dD($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users']);
            // if (isset($categories[$categoryIndexKey]['item']) && !empty($categories[$categoryIndexKey]['item'])) {
            // foreach ($categories[$categoryIndexKey]['item'] as $key => $value) {
            if (isset($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'])) {

                foreach ($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'] as $userkey => $userVal) {

                    if ($id == $userVal['user_id']) {
                        $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][$userkey]['quantity'] = ($request->type != 'minus' && $request->type != "plus") ? 0 : $quantity;


                        session()->put('category', $categories);
                        Session::save();
                    }
                    $total_quantity =  $total_quantity + $userVal['quantity'];
                }
                $found = false; // Flag to check if $id is found
                foreach ($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'] as $user) {
                    if ($user['user_id'] == $id) {
                        $found = true;
                        break; // Stop the loop if $id is found
                    }
                }

                if (!$found) {
                    $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][] = [
                        'user_id' => $id,
                        'quantity' => $quantity
                    ];
                    session()->put('category', $categories);
                    Session::save();
                }
                // dd(1,$categories);
            } else {

                $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][0]['quantity'] = $quantity;
                $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][0]['user_id'] = $id;
                session()->put('category', $categories);
                Session::save();
                // dd(2,$categories);
                $total_quantity =  1;
            }


            // $total_item = $total_item + $value['quantity'];

            // if (isset($value['self_bring']) && isset($value['self_bring_qty']) && $value['self_bring'] == 1) {
            //     $total_quantity = $total_quantity + $value['self_bring_qty'];
            // }else{
            //     $total_quantity = $total_quantity + $value['self_bring_qty'];
            // }
            // }
        }



        return $total_item;
    }
    // public function updateSelfBring(Request $request)
    // {
    //     // Retrieving values from the request
    //     $categoryItemKey = $request->categoryItemKey;
    //     $categoryIndexKey = $request->categoryIndexKey;
    //     $quantity = (string)$request->quantity;

    //     // Retrieve the current session category
    //     $categories = session()->get('category', []);

    //     // Get the authenticated user's ID
    //     $id = Auth::guard('web')->user()->id;

    //     // Set self_bring and self_bring_qty for the item in the category
    //     $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring'] = ($quantity == 0) ? '0' : '1';
    //     $categories[$categoryIndexKey]['item'][$categoryItemKey]['self_bring_qty'] = $quantity;
    //     // dd($categories);

    //     // Initialize total quantities
    //     $total_quantity = 0;

    //     // Check if the item exists in the category
    //     if (isset($categories[$categoryIndexKey]['item'][$categoryItemKey]) && !empty($categories[$categoryIndexKey]['item'][$categoryItemKey])) {

    //         // Check if the item has users who are carrying it
    //         if (isset($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'])) {
    //             foreach ($categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'] as $userkey => $userVal) {
    //                 if ($id == $userVal['user_id']) {
    //                     // Update the quantity for the specific user
    //                     $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][$userkey]['quantity'] = ($request->type != 'minus' && $request->type != "plus") ? 0 : $quantity;

    //                     // Save the session data after the modification
    //                     session()->put('category', $categories);
    //                     Session::save();
    //                 }
    //                 // Aggregate the total quantity
    //                 $total_quantity += $userVal['quantity'];
    //             }
    //         } else {
    //             // If no item_carry_users exists, add the current user with the specified quantity
    //             $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][0]['quantity'] = $quantity;
    //             $categories[$categoryIndexKey]['item'][$categoryItemKey]['item_carry_users'][0]['user_id'] = $id;

    //             // Save the session data
    //             session()->put('category', $categories);
    //             Session::save();

    //             // Set total quantity to 1, as this is the first user
    //             $total_quantity = 1;
    //         }
    //     }

    //     // Return the total quantity (not total_item, as that seems more relevant to your logic)
    //     return $total_quantity;
    // }

    public function saveTempDesign(Request $request)
    {
      

        $eventID = $request->eventId;
        if (isset($eventID) && $eventID != "") {
            // EventImage::where('event_id', $eventID)->where('type', 0)->delete();
        }
        $newImageName = '';
        $fileName = '';
        $i = 0;
        if (isset($request->design_inner_image) && isset($request->shapeImageUrl)) {
            if (session()->has('shape_image')) {
                $oldShapeImage = session('shape_image');
                $oldShapeImagePath = public_path('storage/canvas/') . $oldShapeImage;
                if (file_exists($oldShapeImagePath)) {
                    unlink($oldShapeImagePath);
                }
                session()->forget('shape_image');
            }
            if ($request->shapeImageUrl == $request->design_inner_image) {
                $sourceImagePath = $request->shapeImageUrl;
                $destinationDirectory = public_path('storage/canvas/');
                $parts = explode('canvas/', $request->shapeImageUrl);
                $imageName = end($parts);
                $destinationImagePath = $destinationDirectory . $imageName;
                if (file_exists(public_path('storage/canvas/') . $imageName)) {
                    $newImageName = time() . '_' . uniqid() . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
                    $destinationImagePath = $destinationDirectory . $newImageName;
                    File::copy($sourceImagePath, $destinationImagePath);
                    session(['shape_image' => $newImageName]);
                }
            } else {
                list($type, $data) = explode(';', $request->design_inner_image);
                list(, $data) = explode(',', $data);
                $imageData = base64_decode($data);
                $newImageName = time() . $i . '-' . uniqid() . '.jpg';
                $i++;
                $path = public_path('storage/canvas/') . $newImageName;
                file_put_contents($path, $imageData);
                session(['shape_image' => $newImageName]);
            }
        }
        if ($request->hasFile('image')) {
            if (session()->has('desgin')) {
                $oldDesignImage = session('desgin');
                $oldDesignImagePath = public_path('storage/event_images/') . $oldDesignImage;
                if (file_exists($oldDesignImagePath)) {
                    unlink($oldDesignImagePath);
                }
                session()->forget('desgin');
            }
            $file = $request->file('image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/event_images'), $fileName);
            session(['desgin' => $fileName]);
        }
        if ($fileName != '') {
            return response()->json(['status' => 'Image saved successfully', 'image' => $fileName, 'shape_image' => $newImageName]);
        } else {
            return response()->json(['status' => 'No image uploaded'], 400);
        }
    }
    public function saveCustomDesign(Request $request)
    {

        $eventID = $request->eventId;
        if (isset($eventID) && $eventID != "") {
            // EventImage::where('event_id', $eventID)->where('type', 0)->delete();
        }
        $newImageName = '';
        $fileName = '';
        $i = 0;

        if ($request->hasFile('image')) {
            if (session()->has('desgin')) {
                $oldDesignImage = session('desgin');
                $oldDesignImagePath = public_path('storage/canvas') . $oldDesignImage;
                if (file_exists($oldDesignImagePath)) {
                    unlink($oldDesignImagePath);
                }
                session()->forget('desgin');
            }
            $file = $request->file('image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/canvas'), $fileName);
            session(['desgin' => $fileName]);
        }
        if ($fileName != '') {
            return response()->json(['status' => 'Image saved successfully', 'image' => $fileName, 'shape_image' => $newImageName]);
        } else {
            return response()->json(['status' => 'No image uploaded'], 400);
        }
    }

    public function addNewGroup(Request $request)
    {
        $groupname = $request->input('groupname');
        $member = $request->input('groupmember');
        $id = Auth::guard('web')->user()->id;

        $groupmember = [];
        if ($groupname != "" && !empty($member) && $groupname != null) {
            $createGroup = new Group();
            $createGroup->user_id = $id;
            $createGroup->name = $groupname;
            $createGroup->save();

            foreach ($member as $members) {
                $groupmember[] = [
                    'group_id' => $createGroup->id,
                    'user_id' => $members['id'],
                    'prefer_by' => $members['prefer_by'],
                    'created_at' => now(),
                    'updated_at' => now(),

                ];
            }
            // dd($groupmember);
            GroupMember::insert($groupmember);

            $count = count($member);

            $data = ['groupname' => $groupname, 'group_id' => $createGroup->id, "member_count" => $count];

            return response()->json(['view' => view('front.event.guest.add_group', compact('data'))->render(), "status" => "1", "data" => $data]);
        }
    }

    public function deleteGroup(Request $request)
    {
        $group = $request->input('group_id');

        $deleteGroup = Group::where(['id' => $group])->first();
        if ($deleteGroup != null) {

            $deleteGroup->delete();

            return response()->json(['status' => 1, 'message' => 'Group deleted successfully']);
        }
    }

    public function listGroupMember(Request $request)
    {
        $group_id = $request->input('group_id');
        $id = Auth::guard('web')->user()->id;
        // $groupMember = GroupMember::where('group_id', $group_id)->pluck('user_id');

        // $groups = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact')
        //     ->where('id', '!=', $id)->whereIn('id', $groupMember)
        //     ->orderBy('firstname')
        //     ->get();


        $groups = User::select(
            'users.id',
            'users.firstname',
            'users.profile',
            'users.lastname',
            'users.email',
            'users.country_code',
            'users.phone_number',
            'users.app_user',
            'users.prefer_by',
            'users.email_verified_at',
            'users.parent_user_phone_contact',
            'group_members.prefer_by as group_member_prefer_by'
        )
            ->join('group_members', 'users.id', '=', 'group_members.user_id') // Join with group_members table
            ->where('group_members.group_id', $group_id)
            ->where('users.id', '!=', $id)
            ->orderBy('users.firstname')
            ->get();

        // $groups = User::with(['groupMembers' => function($query) use ($groupMember) {
        //     $query->whereIn('user_id', $groupMember); // Make sure we get the correct group
        // }])
        // ->where('id', '!=', $id)
        // ->whereIn('id', $groupMember)
        // ->orderBy('firstname')
        // ->get();

        // $groups = User::select('users.id', 'users.firstname', 'users.profile', 'users.lastname', 'users.email', 'users.country_code', 'users.phone_number', 'users.app_user', 'users.email_verified_at', 'users.parent_user_phone_contact', 'group_members.prefer_by')
        // ->where('users.id', '!=', $id)
        // ->whereIn('users.id', $groupMember)
        // ->leftJoin('group_members', 'group_members.user_id', '=', 'users.id')
        // ->orderBy('users.firstname')
        // ->get();
        // dd($groups);

        // $groups = GroupMember::with(['user' => function ($query) {
        //     $query->select('id', 'lastname', 'firstname', 'email', 'phone_number')
        //     ->orderBy('firstname','ASC');
        // }])
        //     ->where('group_id', $group_id)
        //     ->get();
        $selected_user = Session::get('user_ids');

        // dd($selected_user);

        return response()->json(['view' => view('front.event.guest.list_group_member', compact('groups', 'selected_user'))->render(), "status" => "1"]);
    }

    public function getUserAjax(Request $request)
    {
        $search_user = $request->search_user;
        $id = Auth::guard('web')->user()->id;
        // $invitedUser='';
        $userIds = Session::get('user_ids');
        $selectedId = [];
        if ($userIds != null &&  count($userIds) > 0) {
            $selectedId = array_column($userIds, 'id');
        }


        // array_values

        // if (!empty($userIds)) {
        //     foreach ($userIds as $user) {
        //         $selectedIds[] = $user->id;
        //     }
        // }

        $type = $request->type;
        $emails = [];
        $getAllContacts = contact_sync::where('contact_id', $id)->where('email', '!=', '')->orderBy('firstname')
            ->get();
        if ($getAllContacts->isNotEmpty()) {
            $emails = $getAllContacts->pluck('email')->toArray();
        }
        // $yesvite_users = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
        //     ->where('id', '!=', $id)
        //     ->where(['app_user' => '1'])
        //     ->whereIn('email', $emails)
        //     ->orderBy('firstname')

        //     ->when(!empty($request->limit) && $type != 'group', function ($query) use ($request) {
        //         $query->limit($request->limit)
        //             ->offset($request->offset);
        //     })
        //     ->when(!empty($request->limit) && $type == 'group', function ($query) use ($request) {
        //         $query->limit($request->limit)
        //             ->offset($request->offset);
        //     })
        //     // ->when($type != 'group', function ($query) use ($request) {
        //     //     $query->where(function ($q) use ($request) {
        //     //         $q->limit($request->limit)
        //     //             ->skip($request->offset);
        //     //     });
        //     // })
        //     ->when(!empty($request->search_user), function ($query) use ($search_user) {
        //         $query->where(function ($q) use ($search_user) {
        //             $q->where('firstname', 'LIKE', '%' . $search_user . '%')
        //                 ->orWhere('lastname', 'LIKE', '%' . $search_user . '%');
        //         });
        //     })
        //     ->get();

        // dd($yesvite_users);
        // DB::enableQueryLog();
        $yesvite_users = User::select(
            'id',
            'firstname',
            'profile',
            'lastname',
            'email',
            'country_code',
            'phone_number',
            'app_user',
            'prefer_by',
            'email_verified_at',
            'parent_user_phone_contact',
            'visible',
            'message_privacy'
        )
            ->where(function ($query) use ($emails, $selectedId) {
                $query->whereIn('email', $emails)
                    ->orWhereIn('id', $selectedId);
            })
            ->where('app_user', '1')
            ->where('id', '!=', $id)
            ->when(!empty($request->search_user), function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstname', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->groupBy('id')
            ->orderBy('firstname')
            ->when(!empty($request->limit) && $type != 'group', function ($query) use ($request) {
                $query->limit($request->limit)
                    ->offset($request->offset);
            })
            ->when(!empty($request->limit) && $type == 'group', function ($query) use ($request) {
                $query->limit($request->limit)
                    ->offset($request->offset);
            })
            ->get();


        // dd(DB::getQueryLog());

        $yesvite_user = [];
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
            $yesvite_user[] = (object)$yesviteUserDetail;
        }

        $selected_user = Session::get('user_ids');

        // dd($selected_user);
        return response()->json(view('front.event.guest.get_user', compact('yesvite_user', 'type', 'selected_user'))->render());
    }

    public function getContacts(Request $request)
    {


        $search_user = $request->search_user;
        $id = Auth::guard('web')->user()->id;
        $type = $request->type;
        $emails = [];
        $selected_contact = Session::get('contact_ids');
        $selectedContactId = [];
        if ($selected_contact != null &&  count($selected_contact) > 0) {
            $selectedContactId = array_column($selected_contact, 'sync_id');
        }


        // DB::enableQueryLog();
        $getAllContacts = contact_sync::where(function ($query) use ($id, $selectedContactId) {
            $query->where('contact_id', $id)  // contact_id = 118
                ->orWhereIn('id', $selectedContactId);  // OR id IN (33435)
        })
            ->when(!empty($request->search_user), function ($query) use ($search_user) {
                // Apply the LIKE condition on firstName and lastName
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstName', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastName', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->when(!empty($request->limit), function ($query) use ($request) {
                // Apply limit and offset for pagination
                $query->limit($request->limit)
                    ->offset($request->offset);
            })
            ->orderBy('firstName')  // Sorting by firstName
            ->get();

        //     dd(DB::getQueryLog());
        // dd($getAllContacts);

        $yesvite_user = [];
        foreach ($getAllContacts as $user) {
            $yesviteUserDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : $user->profile,
                'firstname' => (!empty($user->firstName) || $user->firstName != null) ? $user->firstName : "",
                'lastname' => (!empty($user->lastName) || $user->lastName != null) ? $user->lastName : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'phone_number' => (!empty($user->phoneWithCode) || $user->phoneWithCode != null) ? $user->phoneWithCode : "",
            ];
            $yesvite_user[] = (object)$yesviteUserDetail;
        }
        $selected_user = Session::get('contact_ids');
        // dd($yesvite_user);
        // return response()->json(view('front.event.guest.get_contacts', compact('yesvite_user', 'type', 'selected_user'))->render());

        return response()->json([
            'view' => view('front.event.guest.get_contacts', compact('yesvite_user', 'type', 'selected_user'))->render(),
            'scroll' => $request->scroll,
        ]);
    }
    public function getPhoneContact(Request $request)
    {


        $id = Auth::guard('web')->user()->id;
        $type = $request->type;
        $isCohost = $request->isCohost;
        $cohostId = $request->cohostId;
        $app_user = $request->app_user;

        $cohostpreferby = $request->cohostpreferby;
        $isSelectCohost = ($app_user == 0) ? $cohostId : "";
        $isSelectpreferby = ($app_user == 0) ? $cohostpreferby : "";
        $search_user = (isset($request->search_name) && $request->search_name != '') ? $request->search_name : '';
        $selected_co_host = (isset($request->selected_co_host) && $request->selected_co_host != '') ? $request->selected_co_host : $isSelectCohost;
        $selected_co_host_prefer_by = (isset($request->selected_co_host_prefer_by) && $request->selected_co_host_prefer_by != '') ? $request->selected_co_host_prefer_by : $isSelectpreferby;


        $getAllContacts = contact_sync::where('contact_id', $id)
            // ->when($type != 'group', function ($query) use ($request) {
            //     $query->where(function ($q) use ($request) {
            //         $q->limit($request->limit)
            //             ->skip($request->offset);
            //     });
            // })
            ->when(!empty($request->limit), function ($query) use ($request) {
                $query->limit($request->limit)
                    ->offset($request->offset);
            })
            ->when($search_user != '', function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstname', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_user . '%');
                });
            })->orderBy('firstname')

            ->get();

        $yesvite_user = [];
        foreach ($getAllContacts as $user) {
            $yesviteUserDetail = [
                'id' => $user->id,
                'profile' => empty($user->profile) ? "" : $user->profile,
                'firstname' => (!empty($user->firstName) || $user->firstName != null) ? $user->firstName : "",
                'lastname' => (!empty($user->lastName) || $user->lastName != null) ? $user->lastName : "",
                'email' => (!empty($user->email) || $user->email != null) ? $user->email : "",
                'phone_number' => (!empty($user->phoneWithCode) || $user->phoneWithCode != null) ? $user->phoneWithCode : "",
            ];
            $yesvite_user[] = (object)$yesviteUserDetail;
        }

        $selected_user = Session::get('contact_ids');


        return response()->json([
            'view' => view('front.event.guest.get_contact_host', compact('yesvite_user', 'type', 'selected_user', 'selected_co_host', 'selected_co_host_prefer_by', 'isCohost'))->render(),
            'scroll' => $request->scroll,
        ]);
    }
    // public function searchUserAjax(Request $request){
    //     $id = Auth::guard('web')->user()->id;
    //     $searchName = $request->search_name;

    //     if ($request->ajax()) {
    //         $query = User::where('id', '!=', $id)->where(['is_user_phone_contact' => '0'])->orderBy('firstname');

    //         if ($searchName) {
    //             $query->where(function ($q) use ($searchName) {
    //                 $q->where('firstname', 'LIKE', '%' . $searchName . '%')
    //                     ->orWhere('lastname', 'LIKE', '%' . $searchName . '%');
    //             });
    //         }

    //         $yesvite_user = $query->paginate(10);
    //         return view('front.event.guest.get_user', compact('yesvite_user'))->render();
    //     }
    // }


    public function inviteByGroup(Request $request)
    {

        $users = $request->users;
        $unselectusers = $request->unselectedValues;
        $userIds = session()->get('user_ids');

        if (!empty($unselectusers)) {
            foreach ($unselectusers as $value) {
                $id = $value['id'];

                // Use array_filter to remove the user based on the ID
                $userIds = array_filter($userIds, function ($value) use ($id) {
                    // Skip users where 'isAlready' is "1" and the 'id' matches
                    if (isset($value['isAlready']) && $value['isAlready'] == "1" && $value['id'] == $id) {
                        return false;  // Skip this user
                    }

                    // Keep all users except the one with the given ID
                    return $value['id'] !== $id;
                });
            }

            // Reindex the array after filtering
            $userIds = array_values($userIds);

            // Update the session
            session()->put('user_ids', $userIds);
        }


        // dD($users);
        // dd($userIds,$users);
        if (!empty($users)) {
            foreach ($users as $value) {
                $id = $value['id'];
                $user_detail = User::where('id', $id)->first();
                // dd($user_detail->profile);
                $userimage = ($user_detail->profile);
                // $useremail = $user_detail->input('email');
                // $phone = $user_detail->input('mobile');

                if ($user_detail) {


                    $userEntry = [
                        'id' => $id,
                        'firstname' => $user_detail->firstname,
                        'lastname' => $user_detail->lastname,
                        'invited_by' => $value['invited_by'],
                        'prefer_by' => $value['preferby'],
                        'profile' => (isset($userimage) && $userimage != '') ? $userimage : ''
                    ];

                    $userExists = array_filter($userIds, function ($entry) use ($id) {
                        return $entry['id'] === $id;
                    });

                    $userIds = array_filter($userIds, function ($entry) use ($id) {
                        return $entry['id'] !== $id;
                    });

                    $userIds[] = $userEntry;

                    if (!empty($userExists)) {
                        $data[] = ['userdata' => $userEntry, 'is_duplicate' => 1];
                    } else {
                        $data[] = ['userdata' => $userEntry, 'is_duplicate' => 0];
                    }
                }
            }

            session()->put('user_ids', $userIds);
            Session::save();
            $user_list = Session::get('user_ids');
            // Prepare the view and send the response
            return response()->json([
                'view' => view('front.event.guest.addGuest', compact('data'))->render(),
                'responsive_view' => view('front.event.guest.addguest_responsive', compact('data', 'user_list'))->render(),
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([

                'success' => true,
                "isTrue" => 1

            ]);
        }
    }

    public function editEvent(Request $request)
    {
        $userid = Auth::guard('web')->user()->id;
        $getEventData = Event::with('event_schedule')->where('id', $request->event_id)->first();
        if ($getEventData != null) {
            $eventDetail['id'] = (!empty($getEventData->id) && $getEventData->id != NULL) ? $getEventData->id : "";
            // $eventDetail['event_type_id'] = (!empty($getEventData->event_type_id) && $getEventData->event_type_id != NULL) ? $getEventData->event_type_id : "";
            $eventDetail['event_name'] = (!empty($getEventData->event_name) && $getEventData->event_name != NULL) ? $getEventData->event_name : "";
            $eventDetail['hosted_by'] = (!empty($getEventData->hosted_by) && $getEventData->hosted_by != NULL) ? $getEventData->hosted_by : "";
            $eventDetail['start_date'] = (!empty($getEventData->start_date) && $getEventData->start_date != NULL) ? $getEventData->start_date : "";
            $eventDetail['end_date'] = (!empty($getEventData->end_date) && $getEventData->end_date != NULL) ? $getEventData->end_date : "";
            $eventDetail['rsvp_by_date_set'] =  $getEventData->rsvp_by_date_set;
            $eventDetail['rsvp_by_date'] = (!empty($getEventData->rsvp_by_date) && $getEventData->rsvp_by_date != NULL) ? $getEventData->rsvp_by_date : "";
            $eventDetail['rsvp_start_time'] = (!empty($getEventData->rsvp_start_time) && $getEventData->rsvp_start_time != NULL) ? $getEventData->rsvp_start_time : "";
            $eventDetail['rsvp_start_timezone'] = (!empty($getEventData->rsvp_start_timezone) && $getEventData->rsvp_start_timezone != NULL) ? $getEventData->rsvp_start_timezone : "";
            $eventDetail['rsvp_end_time_set'] = $getEventData->rsvp_end_time_set;
            $eventDetail['rsvp_end_time'] = (!empty($getEventData->rsvp_end_time) && $getEventData->rsvp_end_time != NULL) ? $getEventData->rsvp_end_time : "";
            $eventDetail['rsvp_end_timezone'] = (!empty($getEventData->rsvp_end_timezone) && $getEventData->rsvp_end_timezone != NULL) ? $getEventData->rsvp_end_timezone : "";
            $eventDetail['event_location_name'] = (!empty($getEventData->event_location_name) && $getEventData->event_location_name != NULL) ? $getEventData->event_location_name : "";
            $eventDetail['latitude'] = (!empty($getEventData->latitude) && $getEventData->latitude != NULL) ? $getEventData->latitude : "";
            $eventDetail['longitude'] = (!empty($getEventData->longitude) && $getEventData->longitude != NULL) ? $getEventData->longitude : "";
            $eventDetail['address_1'] = (!empty($getEventData->address_1) && $getEventData->address_1 != NULL) ? $getEventData->address_1 : "";
            $eventDetail['address_2'] = (!empty($getEventData->address_2) && $getEventData->address_2 != NULL) ? $getEventData->address_2 : "";
            $eventDetail['state'] = (!empty($getEventData->state) && $getEventData->state != NULL) ? $getEventData->state : "";
            $eventDetail['zip_code'] = (!empty($getEventData->zip_code) && $getEventData->zip_code != NULL) ? $getEventData->zip_code : "";
            $eventDetail['city'] = (!empty($getEventData->city) && $getEventData->city != NULL) ? $getEventData->city : "";
            $eventDetail['message_to_guests'] = (!empty($getEventData->message_to_guests) && $getEventData->message_to_guests != NULL) ? $getEventData->message_to_guests : "";
            $eventDetail['is_draft_save'] = $getEventData->is_draft_save;
            $eventDetail['step'] = ($getEventData->step != NULL) ? $getEventData->step : 0;
            $eventDetail['subscription_plan_name'] = ($getEventData->subscription_plan_name != NULL) ? $getEventData->subscription_plan_name : "";
            $eventDetail['subscription_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? $getEventData->subscription_invite_count : 0;
            $eventDetail['event_images'] = [];
            $getEventImages = EventImage::where('event_id', $getEventData->id)->orderBy('type', 'ASC')->get();
            if (!empty($getEventImages)) {
                foreach ($getEventImages as $imgVal) {
                    $eventImageData['id'] = $imgVal->id;
                    $eventImageData['image'] = asset('public/storage/event_images/' . $imgVal->image);
                    $eventDetail['event_images'][] = $eventImageData;
                }
            }
            $eventDetail['invited_user_id'] = [];
            $eventDetail['co_host_list'] = [];
            $eventDetail['invited_guests'] = [];
            $eventDetail['guest_co_host_list'] = [];

            $invitedUser = EventInvitedUser::with('user')->where(['event_id' => $getEventData->id])->get();

            if (!empty($invitedUser)) {
                foreach ($invitedUser as $guestVal) {
                    if ($guestVal->is_co_host == '0') {
                        if ($guestVal->user->is_user_phone_contact == '1') {
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
                    } else if ($guestVal->is_co_host == '1') {
                        if ($guestVal->user->is_user_phone_contact == '1') {
                            $guestCoHostDetail['first_name'] = (!empty($guestVal->user->firstname) && $guestVal->user->firstname != NULL) ? $guestVal->user->firstname : "";
                            $guestCoHostDetail['last_name'] = (!empty($guestVal->user->lastname) && $guestVal->user->lastname != NULL) ? $guestVal->user->lastname : "";
                            $guestCoHostDetail['email'] = (!empty($guestVal->user->email) && $guestVal->user->email != NULL) ? $guestVal->user->email : "";
                            $guestCoHostDetail['country_code'] = (!empty($guestVal->user->country_code) && $guestVal->user->country_code != NULL) ? strval($guestVal->user->country_code) : "";
                            $guestCoHostDetail['phone_number'] = (!empty($guestVal->user->phone_number) && $guestVal->user->phone_number != NULL) ? $guestVal->user->phone_number : "";
                            $guestCoHostDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                            $eventDetail['guest_co_host_list'][] = $guestCoHostDetail;
                        } elseif ($guestVal->user->is_user_phone_contact == '0') {
                            $coHostDetail['user_id'] = (!empty($guestVal->user_id) && $guestVal->user_id != NULL) ? $guestVal->user_id : "";
                            $coHostDetail['prefer_by'] = (!empty($guestVal->prefer_by) && $guestVal->prefer_by != NULL) ? $guestVal->prefer_by : "";
                            $eventDetail['co_host_list'][] = $coHostDetail;
                        }
                    }
                }
                $eventDetail['remaining_invite_count'] = ($getEventData->subscription_invite_count != NULL) ? ($getEventData->subscription_invite_count - (count($eventDetail['invited_user_id']) + count($eventDetail['invited_guests']))) : 0;
            }
            // $eventDetail['events_schedule_list'] = [];
            $eventDetail['events_schedule_list'] = null;
            if ($getEventData->event_schedule->isNotEmpty()) {

                $eventDetail['events_schedule_list'] = new stdClass();
                if ($getEventData->event_schedule->first()->type == '1') {


                    $eventDetail['events_schedule_list']->start_time =  ($getEventData->event_schedule->first()->start_time != NULL) ? $getEventData->event_schedule->first()->start_time : "";

                    $eventDetail['events_schedule_list']->event_start_date = ($getEventData->event_schedule->first()->event_date != null) ? $getEventData->event_schedule->first()->event_date : "";
                }

                $eventDetail['events_schedule_list']->data = [];
                foreach ($getEventData->event_schedule as $eventsScheduleVal) {
                    if ($eventsScheduleVal->type == '2') {

                        $eventscheduleData["id"] = $eventsScheduleVal->id;
                        $eventscheduleData["activity_title"] = $eventsScheduleVal->activity_title;
                        $eventscheduleData["start_time"] = ($eventsScheduleVal->start_time !== null) ? $eventsScheduleVal->start_time : "";
                        $eventscheduleData["end_time"] = ($eventsScheduleVal->end_time !== null) ? $eventsScheduleVal->end_time : "";
                        $eventscheduleData['event_date'] = ($eventsScheduleVal->event_date != null) ? $eventsScheduleVal->event_date : "";
                        $eventscheduleData["type"] = $eventsScheduleVal->type;
                        $eventDetail['events_schedule_list']->data[] = $eventscheduleData;
                    }
                }
                if ($getEventData->event_schedule->last()->type == '3') {

                    $eventDetail['events_schedule_list']->end_time =  ($getEventData->event_schedule->last()->end_time !== null) ? $getEventData->event_schedule->last()->end_time : "";
                    $eventDetail['events_schedule_list']->event_end_date = ($getEventData->event_schedule->last()->event_date != null) ? $getEventData->event_schedule->last()->event_date : "";
                }
            }
            $eventDetail['greeting_card_list'] = [];
            if (!empty($getEventData->greeting_card_id) && $getEventData->greeting_card_id != NULL) {


                $greeting_card_ids = array_map('intval', explode(',', $getEventData->greeting_card_id));

                $eventDetail['greeting_card_list'] = $greeting_card_ids;
            }

            $eventDetail['gift_registry_list'] = [];
            if (!empty($getEventData->gift_registry_id) && $getEventData->gift_registry_id != NULL) {

                $gift_registry_ids = array_map('intval', explode(',', $getEventData->gift_registry_id));

                $eventDetail['gift_registry_list'] = $gift_registry_ids;
            }

            $eventDetail['event_setting'] = "";

            $eventSettings = EventSetting::where('event_id', $getEventData->id)->first();

            if ($eventSettings != NULL) {
                $eventDetail['event_setting'] = [

                    "allow_for_1_more" => $eventSettings->allow_for_1_more,
                    "allow_limit" => strval($eventSettings->allow_limit),
                    "adult_only_party" => $eventSettings->adult_only_party,

                    "rsvp_by_date" => $getEventData->rsvp_by_date,
                    "thank_you_cards" => $eventSettings->thank_you_cards,
                    "add_co_host" => $eventSettings->add_co_host,
                    "gift_registry" => $eventSettings->gift_registry,
                    "events_schedule" => $eventSettings->events_schedule,
                    "event_wall" => $eventSettings->event_wall,
                    "guest_list_visible_to_guests" => $eventSettings->guest_list_visible_to_guests,
                    "podluck" => $eventSettings->podluck,
                    "rsvp_updates" => $eventSettings->rsvp_updates,
                    "event_wall_post" => $eventSettings->event_wall_post,
                    "send_event_dater_reminders" => $eventSettings->send_event_dater_reminders,
                    "request_event_photos_from_guests" => $eventSettings->request_event_photos_from_guests
                ];
            }


            $eventDetail['podluck_category_list'] = [];



            $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                $query->with(['users', 'user_potluck_items' => function ($subquery) {
                    $subquery->with('users')->sum('quantity');
                }]);
            }])->withCount('event_potluck_category_item')->where('event_id', $getEventData->id)->get();

            if (!empty($eventpotluckData)) {
                $potluckCategoryData = [];
                $potluckDetail['total_potluck_item'] = EventPotluckCategoryItem::where('event_id', $getEventData->id)->count();

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
                            $potluckItem['is_host'] = ($itemValue->user_id == $userid) ? 1 : 0;
                            $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                            $potluckItem['quantity'] =  $itemValue->quantity;
                            $potluckItem['self_bring_item'] =  $itemValue->self_bring_item;
                            $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                            $potluckItem['spoken_quantity'] =  $spoken_for;
                            $potluckItem['item_carry_users'] = [];

                            foreach ($itemValue->user_potluck_items as $itemcarryUser) {
                                $userPotluckItem['id'] = $itemcarryUser->id;
                                $userPotluckItem['user_id'] = $itemcarryUser->user_id;
                                $userPotluckItem['is_host'] = ($itemcarryUser->user_id == $userid) ? 1 : 0;
                                $userPotluckItem['profile'] =  empty($itemcarryUser->users->profile) ?  "" : asset('public/storage/profile/' . $itemcarryUser->users->profile);
                                $userPotluckItem['first_name'] = $itemcarryUser->users->firstname;
                                $userPotluckItem['quantity'] = (!empty($itemcarryUser->quantity) || $itemcarryUser->quantity != NULL) ? $itemcarryUser->quantity : "0";
                                $userPotluckItem['last_name'] = $itemcarryUser->users->lastname;
                                $potluckItem['item_carry_users'][] = $userPotluckItem;
                            }
                            $potluckCategory['items'][] = $potluckItem;
                        }
                    }
                    $eventDetail['podluck_category_list'][] = $potluckCategory;
                }
            }
        }
    }


    public function closeTip(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('closed') && $request->closed) {
                if ($request->tip == "potluck") {
                    session(['potluck_closed' => true]);
                }

                if ($request->tip == "create_new_event") {
                    session(['create_new_event_closed' => true]);
                }

                if ($request->tip == "co_host") {
                    session(['co_host_closed' => true]);
                }

                if ($request->tip == "thankyou_card") {
                    session(['thankyou_card_closed' => true]);
                }

                if ($request->tip == "desgin_tip") {
                    session(['design_closed' => true]);
                }

                if ($request->tip == "edit_desgin_tip") {
                    session(['edit_design_closed' => true]);
                }
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'User not logged in']);
        }
    }

    public function groupSearchAjax(Request $request)
    {
        $search_user = $request->search_name;
        $id = Auth::guard('web')->user()->id;
        $groups = Group::withCount('groupMembers')
            ->orderBy('name', 'ASC')
            ->where('user_id', $id)
            ->when($search_user != '', function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('name', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->get();
        return response()->json(['html' => view('front.event.guest.group_list', compact('groups'))->render(), "status" => "1"]);
    }

    public function group_toggle_search(Request $request)
    {
        $search_user = $request->search_name;
        $id = Auth::guard('web')->user()->id;
        $groups = Group::withCount('groupMembers')
            ->orderBy('name', 'ASC')
            ->where('user_id', $id)
            ->when($search_user != '', function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('name', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->get();
        return response()->json(['html' => view('front.event.guest.group_search_list_toggle', compact('groups'))->render(), "status" => "1"]);
    }

    public function delete_sessions(Request $request)
    {
        if (isset($request->delete_session) && $request->delete_session != '') {
            Session::forget($request->delete_session);
            Session::save();
        }
        return;
    }

    public function get_co_host_list(Request $request)
    {
        // dd($request);
        $cohostId = $request->cohostId;
        $isCohost = $request->isCohost;
        $isCopy = $request->isCopy;
        $app_user = $request->app_user;
        $cohostpreferby = $request->cohostpreferby;
        $selected_user = session('user_ids');
        $user_id =  Auth::guard('web')->user()->id;
        $alreadyselectedUser =  collect($selected_user)->pluck('id')->toArray();
        $search_user = (isset($request->search_name) && $request->search_name != '') ? $request->search_name : '';
        $isSelectCohost = ($app_user == 1) ? $cohostId : "";
        $isSelectpreferby = ($app_user == 1) ? $cohostpreferby : "";
        $selected_co_host = (isset($request->selected_co_host) && $request->selected_co_host != '') ? $request->selected_co_host : $isSelectCohost;
        $selected_co_host_prefer_by = (isset($request->selected_co_host_prefer_by) && $request->selected_co_host_prefer_by != '') ? $request->selected_co_host_prefer_by : $isSelectpreferby;

        $getAllContacts = contact_sync::where('contact_id', $user_id)->where('email', '!=', '')->orderBy('firstname')
            ->get();
        if ($getAllContacts->isNotEmpty()) {
            $emails = $getAllContacts->pluck('email')->toArray();
        }


        $users = User::select('id', 'firstname', 'profile', 'lastname', 'email', 'country_code', 'phone_number', 'app_user', 'prefer_by', 'email_verified_at', 'parent_user_phone_contact', 'visible', 'message_privacy')
            // ->whereNotIn('id', $alreadyselectedUser)
            ->whereIn('email', $emails)
            ->where('id', '!=', $user_id)
            ->where(['app_user' => '1'])
            ->orderBy('firstname')
            ->when(!empty($request->limit), function ($query) use ($request) {
                $query->limit($request->limit)
                    ->offset($request->offset);
            })
            ->when($search_user != '', function ($query) use ($search_user) {
                $query->where(function ($q) use ($search_user) {
                    $q->where('firstname', 'LIKE', '%' . $search_user . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_user . '%');
                });
            })
            ->get();

        return response()->json(['view' => view('front.event.guest.allGuestList', compact('users', 'selected_co_host', 'isCohost', 'isCopy', 'selected_co_host_prefer_by'))->render(), 'scroll' => $request->scroll]);
    }

    public function get_gift_registry(Request $request)
    {
        // $giftRegistryData=session('giftRegistryData');
        // $registry=[];
        // if(isset($giftRegistryData) && $giftRegistryData!=null && count($giftRegistryData) > 0 ){
        //     $registry = $giftRegistryData;
        // }
        // $user_id =  Auth::guard('web')->user()->id;

        // $gift_registry = EventGiftRegistry::where('user_id', $user_id)   ->when(!empty($registry), function ($query) use ($registry) {
        //     $query->orWhereIn('id', $selectedId);
        // })->get();
        $giftRegistryData = session('giftRegistryData');
        $registry = [];

        // Check if giftRegistryData exists and is not empty
        if (isset($giftRegistryData) && $giftRegistryData != null && count($giftRegistryData) > 0) {
            $registry = $giftRegistryData; // Assign the session data to $registry
        }

        $user_id = Auth::guard('web')->user()->id; // Get the authenticated user's ID

        // Query for gift registry
        $gift_registry = EventGiftRegistry::where('user_id', $user_id)
            ->when(!empty($registry), function ($query) use ($registry) {
                // Assuming $registry is an array of IDs to search for in the 'id' column
                $query->orWhereIn('id', $registry); // Use $registry instead of $selectedId
            })
            ->get();

        return response()->json(['view' => view('front.event.gift_registry.add_gift_registry', compact('gift_registry'))->render()]);
    }

    public function get_thank_you_card(Request $request)
    {
        $greetingCardData = session('greetingCardData');
        $greetingCard = [];

        // Check if greetingCardData exists and is not empty
        if (isset($greetingCardData) && $greetingCardData != null && count($greetingCardData) > 0) {
            $greetingCard = $greetingCardData; // Assign the session data to $registry
        }
        Session::get('greetingCardData', []);
        $user_id =  Auth::guard('web')->user()->id;

        $thankyou_card = EventGreeting::where('user_id', $user_id)->when(!empty($greetingCard), function ($query) use ($greetingCard) {
            // Assuming $greetingCard is an array of IDs to search for in the 'id' column
            $query->orWhereIn('id', $greetingCard); // Use $greetingCard instead of $selectedId
        })->get();
        // $thankuCardId = $request->thankuCardId;
        // dd($thankuCardId);
        return response()->json(['view' => view('front.event.thankyou_template.add_thankyou_template', compact('thankyou_card'))->render()]);
    }

    // public function saveSliderImg(Request $request)
    // {
    //     // dd($request->imageSources);
    //     $savedFiles = [];
    //     $i = 0;
    //     foreach ($request->imageSources as $imageSource) {
    //         if (!empty($imageSource)) {
    //             list($type, $data) = explode(';', $imageSource);
    //             list(, $data) = explode(',', $data);
    //             $imageData = base64_decode($data);
    //             $fileName = time() .$i. '-' . uniqid() . '.jpg';
    //             $i++;
    //             $path = public_path('storage/event_images/') . $fileName;;
    //             file_put_contents($path, $imageData);
    //             $savedFiles[] = $fileName;

    //         }
    //     }
    //     if (empty($savedFiles)) {
    //         return response()->json(['status' => 'No valid images to save'], 400);
    //     }
    //     session(['desgin_slider' => $savedFiles]);
    //     return response()->json(['success' => false, 'images' => $savedFiles]);

    // }


    public function saveSliderImg(Request $request)
    {

        $event_id = $request->eventId;

        $savedFiles = [];
        if (isset($event_id) && $event_id != '') {
            $getEventImages = EventImage::where('event_id', $event_id)->get();

            if (!empty($getEventImages)) {
                foreach ($getEventImages as $key => $imgVal) {
                    if ($key == 0) {
                        continue;
                    }
                    $fileName =   $imgVal->image;
                    $savedFiles[] = [
                        'fileName' => $fileName,
                        'deleteId' => $imgVal->id,
                    ];
                }
            }
        }

        $imageSources = $request->imageSources;

        $i = 0;

        // Check if there are existing images in the session and unlink them
        if (session()->has('desgin_slider')) {
            $existingImages = session('desgin_slider');
            foreach ($existingImages as $file) {
                $filePath = public_path('storage/event_images/') . $file['fileName'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        foreach ($imageSources as $imageSource) {
            if (!empty($imageSource['src'])) {
                $parts = explode(';', $imageSource['src']);
                $type = $parts[0];
                if (!isset($parts[1])) {
                    continue;
                }
                $dataParts = explode(',', $parts[1]);
                $data = $dataParts[1];
                $imageData = base64_decode($data);
                $fileName = time() . $i . '-' . uniqid() . '.jpg';
                $i++;

                $path = public_path('storage/event_images/') . $fileName;

                file_put_contents($path, $imageData);
                $savedFiles[] = [
                    'fileName' => $fileName,
                    'deleteId' => $imageSource['deleteId']
                ];
            }
        }
        if (empty($savedFiles)) {
            return response()->json(['status' => 'No valid images to save'], 400);
        }
        // dd($savedFiles);
        session(['desgin_slider' => $savedFiles]);
        return response()->json(['success' => true, 'images' => $savedFiles]);
    }

    public function deleteSliderImg(Request $request)
    {
        $delete_id = $request->delete_id;
        $eventId = $request->eventId;
        $src = $request->src;

        // Extract filename from URL
        $imageFilename = basename(parse_url($src, PHP_URL_PATH));

        // Check if the image exists in the EventImage table
        $eventImage = EventImage::where([
            'event_id' => $eventId,
            'image' => $imageFilename
        ])->first();

        if ($eventImage) {
            // Delete database entry
            $eventImage->delete();

            // Unlink the image file
            $imagePath = public_path('storage/event_images/') . $imageFilename;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        // Remove from session if exists
        $get_slider_data = Session::get('desgin_slider');
        if ($get_slider_data) {
            $filtered_slider_data = array_filter($get_slider_data, function ($slider) use ($imageFilename) {
                if ($slider['fileName'] === $imageFilename) {
                    $imagePath = public_path('storage/event_images/') . $slider['fileName'];
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                    return false; // Remove from session
                }
                return true;
            });

            // Update session data
            Session::put('desgin_slider', array_values($filtered_slider_data));
            Session::save();
        }

        return response()->json(['success' => true, 'message' => 'Slider image deleted successfully.']);
    }

    public function get_design_edit_page(Request $request)
    {

        $eventID = $request->eventID;
        $tempId = $request->id;
        $isDraft = $request->isDraft;
        return view('front.event.design.edit_design', compact('eventID', 'isDraft', 'tempId'))->render();
    }

    public function shape_image(Request $request)
    {
        // dd($request);
        $file = $request->file('image');
        $shape = $request->shape;
        $imageName = 'shape_image' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/canvas'), $imageName);
        $imagePath = asset('storage/canvas/' . $imageName);

        return response()->json(['success' => true, 'imagePath' => $imagePath, 'shape_image' => $imageName, 'shape_name' => $shape]);
    }
    public function see_all(Request $request)
    {
        // dd()
        if ($request->is_contact != "1") {
            $data  = Session::get('user_ids');
            // $data[] = ['userdata' => $userEntry];

            // dd($data);
            return response()->json(['view' => view('front.event.guest.sell_all_invited', compact('data'))->render()]);
        }

        $data = Session::get('contact_ids');
        return response()->json(['view' => view('front.event.guest.sell_all_invited_contact', compact('data'))->render()]);
        //    dd($data);
    }

    public function CancelEvent(Request $request)
    {
        // dd($request);
        $user  = Auth::guard('web')->user();
        $event_id = $request->input('event_id');
        $reason = $request->input('reason');
        // $rawData = $request->getContent();

        // $input = json_decode($rawData, true);
        // if ($input == null) {
        //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
        // }
        // $validator = Validator::make($input, [

        //     // 'event_id' => ['required', 'exists:events,id'],
        //     'reason' => ['required']
        // ]);

        // if ($validator->fails()) {

        //     return response()->json([
        //         'status' => 0,
        //         'message' => $validator->errors()->first(),

        //     ]);
        // }

        try {
            DB::beginTransaction();
            $deleteEvent = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
            // dd($event_id,$user->id);
            if (!empty($event_id)) {
                Notification::where('event_id', $event_id)->delete();
                $deleteEvent->reason = $reason;
                if ($deleteEvent->save()) {
                    if (isset($deleteEvent->design_image) && $deleteEvent->design_image != "") {
                        if (file_exists(public_path('storage/canvas') . $deleteEvent->design_image)) {
                            $design_imagedesign_image_imagePath = public_path('storage/canvas') . $deleteEvent->design_image;
                            unlink($design_imagedesign_image_imagePath);
                        }
                    }
                    // if (file_exists(public_path('storage/canvas') . $deleteEvent->design_inner_image)) {
                    //     $design_inner_image_imagePath = public_path('storage/canvas') . $deleteEvent->design_inner_image;
                    //     unlink($design_inner_image_imagePath);
                    // }

                    UserReportToPost::where('event_id', $event_id)->delete();

                    $event_images = EventImage::where('event_id', $event_id)->get();
                    if (isset($event_images) && !empty($event_images)) {
                        foreach ($event_images as $eventImage) {
                            if (file_exists(public_path('storage/event_images/') . $eventImage->image)) {
                                $imagePath = public_path('storage/event_images/') . $eventImage->image;
                                unlink($imagePath);
                            }
                        }
                        EventImage::where('event_id', $event_id)->delete();
                    }
                    $event_post_image = EventPostImage::where('event_id', $event_id)->get();
                    if (isset($event_post_image) && !empty($event_post_image)) {
                        foreach ($event_post_image as $postImage) {
                            if ($postImage->type == "video") {
                                if (file_exists(public_path('storage/post_image/') . $postImage->post_image)) {
                                    $videoPath = public_path('storage/post_image/') . $postImage->post_image;
                                    unlink($videoPath);
                                }
                                if (file_exists(public_path('storage/thumbnails/') . $postImage->thumbnail)) {
                                    $thumbnailPath = public_path('storage/thumbnails/') . $postImage->thumbnail;
                                    unlink($thumbnailPath);
                                }
                            } elseif ($postImage->type == "audio") {
                                if (file_exists(public_path('storage/event_post_recording') . $postImage->post_image)) {
                                    $audioPath = public_path('storage/event_post_recording/') . $postImage->post_image;
                                    unlink($audioPath);
                                }
                            } else {
                                if (file_exists(public_path('storage/post_image') . $postImage->post_image)) {
                                    $postImagePath = public_path('storage/post_image/') . $postImage->post_image;
                                    unlink($postImagePath);
                                }
                            }
                        }
                        EventPostImage::where('event_id', $event_id)->delete();
                    }
                    EventPost::where('event_id', $event_id)->delete();
                    EventInvitedUser::where('event_id', $event_id)->delete();
                    EventSchedule::where('event_id', $event_id)->delete();
                    EventSetting::where('event_id', $event_id)->delete();
                    EventPotluckCategoryItem::where('event_id', $event_id)->delete();
                    EventPotluckCategory::where('event_id', $event_id)->delete();
                    EventPostComment::where('event_id', $event_id)->delete();
                    EventPostPoll::where('event_id', $event_id)->delete();
                    EventUserStory::where('event_id', $event_id)->delete();
                }
                $deleteEvent->delete();

                DB::commit();
                return response()->json(['status' => 1, 'event_id' => $event_id, 'message' => "Event deleted successfully"]);
            } else {
                return response()->json(['status' => 0, 'message' => "data is incorrect"]);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            // dd($e);
            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
    public function getCategory(Request $request)
    {
        $search_user = $request->search_user;

        $design_category = EventDesignCategory::with([
            'subcategory' => function ($query) use ($search_user) {
                $query->select('*')
                    ->where('subcategory_name', 'LIKE', "%$search_user%")
                    ->whereHas('textdatas', function ($ques) {})
                    ->with([
                        'textdatas' => function ($que) {
                            $que->select('*');
                        }
                    ]);
            }
        ])
            ->where('category_name', 'LIKE', "%$search_user%")
            ->orderBy('id', 'DESC')
            ->get();
        if ($design_category->isEmpty()) {
            return response()->json([
                'view' => '<p>No result found matching your search.</p>',
                'success' => false
            ]);
        }
        //   dd($design_category);
        return response()->json([
            'view' => view('front.event.guest.get_category', compact('design_category'))->render(),
            'success' => true
        ]);
    }


    public function  editStore(Request $request)
    {

        // dd($request->slider_images);


        Session::forget('desgin');
        Session::forget('shape_image');
        Session::forget('custom_image');
        Session::forget('desgin_slider');

        // dd(session('user_ids'),session('contact_ids'));
        $conatctId = session('contact_ids');
        $potluck = session('category');
        // dd($potluck);
        $invitedCount = session('user_ids');
        $get_count_invited_user = (isset($contactId) ? count($contactId) : 0) + (isset($invitedCount) ? count($invitedCount) : 0);
        // $potluck = session('category');

        $user_id =  Auth::guard('web')->user()->id;
        $dateString = (isset($request->event_date)) ? $request->event_date : "";
        $startDate = (isset($request->start_event_date)) ? $request->start_event_date : "";
        $endDate = (isset($request->end_event_date)) ? $request->end_event_date : "";


        // if (strpos($dateString, ' To ') !== false) {
        //     list($startDate, $endDate) = explode(' To ', $dateString);
        // } else {
        //     $startDate = $dateString;
        //     $endDate = $dateString;
        // }

        // $startDateFormat = DateTime::createFromFormat('m-d-Y', $startDate)->format('Y-m-d');
        // $endDateFormat = DateTime::createFromFormat('m-d-Y', $endDate)->format('Y-m-d');
        // if (strpos($dateString, ' To ') !== false) {
        //     list($startDate, $endDate) = explode(' To ', $dateString);
        // } else {
        //     $startDate = $dateString;
        //     $endDate = $dateString;
        // }
        $startDateObj = DateTime::createFromFormat('m-d-Y', $startDate);
        $endDateObj = DateTime::createFromFormat('m-d-Y', $endDate);
        $rsvpdateObj = DateTime::createFromFormat('m-d-Y', $request->rsvp_by_date);


        $startDateFormat = "";
        $endDateFormat =  $endDate;
        if ($startDateObj && $endDateObj) {
            $startDateFormat = $startDateObj->format('Y-m-d');
            $endDateFormat = $endDateObj->format('Y-m-d');
        }
        // dd($request->rsvp_by_date);
        if (isset($request->rsvp_by_date) && $request->rsvp_by_date != '') {
            // $carbonDate = Carbon::createFromFormat('Y-m-d', $request->rsvp_by_date);
            // dd($carbonDate);
            // if ($carbonDate && $carbonDate->format('Y-m-d') === $request->rsvp_by_date) {
            //     $rsvp_by_date = $request->rsvp_by_date;
            // } else {
            if ($rsvpdateObj) {
                $rsvp_by_date = DateTime::createFromFormat('m-d-Y', $request->rsvp_by_date)->format('Y-m-d');
            } else {
                $rsvp_by_date = $request->rsvp_by_date;
            }
            // }
            // $rsvp_by_date = Carbon::parse($request->rsvp_by_date)->format('Y-m-d');

            $rsvp_by_date_set = '1';
        } else {
            $rsvp_by_date_set = '0';
            $rsvp_by_date = null;
            // if ($startDateFormat) {

            //     $start = new DateTime($startDateFormat);
            //     $start->modify('-1 day');
            //     $rsvp_by_date = $start->format('Y-m-d');
            // }
        }



        $greeting_card_id = "";
        if (isset($request->thankyou_message) && $request->thankyou_message == '1') {
            if (isset($request->thank_you_card_id) && $request->thank_you_card_id != '') {
                $greeting_card_id =  $request->thank_you_card_id;
            }
        }

        $gift_registry_id = "";
        if (isset($request->gift_registry) && $request->gift_registry == '1') {
            if (!empty($request->gift_registry_data)) {
                $gift_registry_data = collect($request->gift_registry_data)->pluck('gr_id')->toArray();
                $gift_registry_id =  implode(',', $gift_registry_data);
            }
        }


        if (isset($request->event_id) && $request->event_id != NULL) {
            $event_creation = Event::where('id', $request->event_id)->first();
        } else {
            $event_creation = new Event();
        }


        $oldAddress = $event_creation->address_1 . ' ' . $event_creation->address_2 . ' ' . $event_creation->state  . ' ' . $event_creation->city . ' ' . $event_creation->zip_code;
        $newAddress = $request->address1 . ' ' . $request->address_2 . ' ' . $request->state  . ' ' . $request->city . ' ' . $request->zipcode;
        $isaddress = 0;
        if ($oldAddress !== $newAddress) {
            $isaddress = 1;
        }


        $newstart_time = $request->start_time;
        $oldstart_time = $event_creation->rsvp_start_time;
        $istime = 0;

        if ($oldstart_time !== $newstart_time) {
            $istime = 1;
        }


        $newstart_date = (isset($startDate) && $startDate != "" && $startDateObj != false) ? $startDateFormat : $startDate;
        $newend_date = (isset($endDate) && $endDate != "" && $endDateObj != false) ? $endDateFormat : $endDate;
        $oldstart_date = $event_creation->start_date;
        $oldend_date = $event_creation->end_date;
        $isupdatedate = 0;

        if ($newstart_date !== $oldstart_date || $newend_date !== $oldend_date) {
            $isupdatedate = 1;
        }


        // $event_creation->user_id = $user_id;
        $event_creation->event_name = (isset($request->event_name) && $request->event_name != "") ? $request->event_name : "";
        $event_creation->hosted_by = (isset($request->hosted_by) && $request->hosted_by) ? $request->hosted_by : "";
        $event_creation->start_date = (isset($startDate) && $startDate != "" && $startDateObj != false) ? $startDateFormat : $startDate;
        $event_creation->end_date = (isset($endDate) && $endDate != "" && $endDateObj != false) ? $endDateFormat : $endDate;
        $event_creation->rsvp_by_date_set =  $rsvp_by_date_set;
        // $event_creation->rsvp_by_date_set = (isset($request->rsvp_by_date_set) && $request->rsvp_by_date_set != "" && $request->rsvp_by_date_set != 'false') ? "1" : "0";
        // dd($request->rsvp_by_date_set,$event_creation->rsvp_by_date_set);
        $event_creation->rsvp_by_date = (isset($rsvp_by_date) && $rsvp_by_date != "") ? $rsvp_by_date : null;
        $event_creation->rsvp_start_time = (isset($request->start_time) && $request->start_time != "") ? $request->start_time : "";
        $event_creation->rsvp_start_timezone = (isset($request->rsvp_start_timezone) && $request->rsvp_start_timezone != "") ? $request->rsvp_start_timezone : "";
        $event_creation->rsvp_end_time = (isset($request->rsvp_end_time) && $request->rsvp_end_time != "") ? $request->rsvp_end_time : "";
        $event_creation->rsvp_end_timezone = (isset($request->rsvp_end_timezone) && $request->rsvp_end_timezone != "") ? $request->rsvp_end_timezone : "";
        $event_creation->rsvp_end_time_set = (isset($request->rsvp_end_time_set) && $request->rsvp_end_time_set != "") ? $request->rsvp_end_time_set : "0";
        $event_creation->event_location_name = (isset($request->event_location) && $request->event_location != "") ? $request->event_location : "";
        $event_creation->address_1 = (isset($request->address1) && $request->address1 != "") ? $request->address1 : "";
        $event_creation->address_2 = (isset($request->address_2) && $request->address_2 != "") ? $request->address_2 : "";
        $event_creation->state = (isset($request->state) && $request->state != "") ? $request->state : "";
        $event_creation->zip_code = (isset($request->zipcode) && $request->zipcode) ? $request->zipcode : "";
        $event_creation->city = (isset($request->city) && $request->city != "") ? $request->city : "";
        $event_creation->message_to_guests = (isset($request->message_to_guests) && $request->message_to_guests != "") ? $request->message_to_guests : "";
        $event_creation->is_draft_save = (isset($request->isdraft) && $request->isdraft != "") ? $request->isdraft : "0";
        $event_creation->latitude = (isset($request->latitude) && $request->latitude != "") ? $request->latitude : "";
        $event_creation->longitude = (isset($request->longitude) && $request->longitude != "") ? $request->longitude : "";
        $event_creation->greeting_card_id = (isset($greeting_card_id) && $greeting_card_id != "") ? $greeting_card_id : "0";
        $event_creation->gift_registry_id = (isset($gift_registry_id) && $gift_registry_id != "") ? $gift_registry_id : "0";
        $event_creation->subscription_plan_name = (isset($request->plan_selected) && $request->plan_selected != "") ? $request->plan_selected : "Pro";
        $event_creation->subscription_invite_count = (isset($request->subscription_invite_count) && $request->subscription_invite_count != "") ? $request->subscription_invite_count : 15;

        $event_creation->save();



        $eventId = $event_creation->id;
        $get_count_invited_user = 0;


        // debit_coins($user_id, $eventId, $get_count_invited_user);
        if (isset($request->event_id) && $request->event_id != NULL) {
            $step = $event_creation->step;

            if (isset($request->step) && $request->step != '' && $step < $request->step) {
                $event_creation->step = $request->step;
            }
        } else {
            $event_creation->step = (isset($request->step) && $request->step != '') ? $request->step : 0;
        }
        if (isset($request->shape_image) && $request->shape_image != '') {
            $event_creation->design_inner_image = $request->shape_image;
        }






        if ($request->temp_id != '' && $request->temp_id != null) {
            // dd($request->temp_id);
            $tempData = TextData::where('id', $request->temp_id)->first();
            if ($tempData) {
                $sourceImagePath = asset('storage/canvas/' . $tempData->image);
                $destinationDirectory = public_path('storage/event_images/');
                $destinationImagePath = $destinationDirectory . $tempData->image;
                if (file_exists(public_path('storage/canvas/') . $tempData->image)) {
                    $newImageName = time() . '_' . uniqid() . '.' . pathinfo($tempData->image, PATHINFO_EXTENSION);
                    $destinationImagePath = $destinationDirectory . $newImageName;

                    File::copy($sourceImagePath, $destinationImagePath);
                    $event_creation->design_image = $tempData->image;
                }
            }
        } else if (isset($request->cutome_image)) {


            if (filter_var($request->cutome_image, FILTER_VALIDATE_URL)) {
                $pathParts = explode('/', $request->cutome_image);
                $event_creation->design_image = end($pathParts);
            } else {
                $event_creation->design_image = $request->cutome_image;
            }
            $sourceImagePath = asset('storage/canvas/' . $request->cutome_image);
        }

        if (isset($request->textData) && json_encode($request->textData) != '') {
            $textElemtents = $request->textData['textElements'];

            foreach ($textElemtents as $key => $textJson) {
                if ($textJson['fontSize'] != '') {
                    $textElemtents[$key]['fontSize'] = (int)$textJson['fontSize'];
                    $textElemtents[$key]['centerX'] = (float)$textJson['centerX'];
                    $textElemtents[$key]['centerY'] = (float)$textJson['centerY'];
                }
                if (isset($textJson['letterSpacing'])) {
                    $textElemtents[$key]['letterSpacing'] = (int)$textJson['letterSpacing'];
                }
                if (isset($textJson['lineHeight'])) {
                    $textElemtents[$key]['lineHeight'] = (float)$textJson['lineHeight'];
                }
                if (isset($textJson['underline'])) {
                    $textElemtents[$key]['underline'] = ($textJson['underline'] === "true" || $textJson['underline'] === true) ? true : false;
                }
            }


            $static_data = [];
            $static_data['textData'] = $textElemtents;
            $static_data['event_design_sub_category_id'] = (int)$request->temp_id;
            $static_data['height'] = (int)490;
            $static_data['width'] = (int)345;
            $static_data['image'] = $event_creation->design_image;
            $static_data['template_url'] = $sourceImagePath;
            $static_data['is_contain_image'] = false;
            if (isset($request->textData['shapeImageData'])) {
                $shapeImageData = [];
                $shapeImageData['shape'] = $request->textData['shapeImageData']['shape'];
                $shapeImageData['centerX'] = (float)$request->textData['shapeImageData']['centerX'];
                $shapeImageData['centerY'] = (float)$request->textData['shapeImageData']['centerY'];
                $shapeImageData['width'] = (float)$request->textData['shapeImageData']['width'];
                $shapeImageData['height'] = (float)$request->textData['shapeImageData']['height'];
                $static_data['shapeImageData'] = $shapeImageData;
                $static_data['is_contain_image'] = true;
            }

            $event_creation->static_information = json_encode($static_data);
        } else {
            $event_creation->static_information = null;
        }
        $event_creation->save();

        if ($eventId != "") {
            if ($request->isdraft == "1" || (isset($request->isDraftEdit) && $request->isDraftEdit == "1")) {
                EventInvitedUser::where('event_id', $request->event_id)->delete();
            }

            $invitedUsers = $request->email_invite;
            $invitedusersession = session('user_ids');
            if (isset($invitedusersession) && !empty($invitedusersession)) {

                foreach ($invitedusersession as $key => $value) {
                    $is_cohost = '0';
                    $invited_user = $value['id'];
                    $prefer_by =  $value['prefer_by'];
                    if (isset($value['isAlready']) && $value['isAlready'] == "1") {
                        continue;
                    }
                    EventInvitedUser::create([
                        'event_id' => $eventId,
                        'prefer_by' => $prefer_by,
                        'user_id' => $invited_user,
                        'is_co_host' => $is_cohost,
                    ]);
                    $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('user_id', $user_id)->where('id', $eventId)->get();
                    foreach ($invitedusers as $event_detail) {
                        $eventData = [
                            'event_name' => $event_detail->event_name,
                            'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                            'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                            'event_image' => "no_image.png",
                            'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                            'time' => $event_detail->rsvp_start_time,
                            'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                        ];
                    }
                }
            }
            // dd($conatctId);
            if (!empty($conatctId)) {
                $invitedGuestUsers = $conatctId;

                foreach ($invitedGuestUsers as $value) {
                    if (isset($value['isAlready']) && $value['isAlready'] == "1") {
                        continue;
                    }
                    $checkContactExist = contact_sync::where('id', $value['sync_id'])->first();

                    if ($checkContactExist) {
                        $newUserId = NULL;
                        if ($checkContactExist->email != '') {
                            $newUserId = checkUserEmailExist($checkContactExist);
                        }
                        $eventInvite = new EventInvitedUser();
                        $eventInvite->event_id = $eventId;
                        $eventInvite->sync_id = $checkContactExist->id;
                        $eventInvite->user_id = $newUserId;
                        $eventInvite->prefer_by = (isset($value['prefer_by'])) ? $value['prefer_by'] : "email";
                        $eventInvite->save();
                    }
                    // }
                }
            }


            if (isset($request->co_host) && $request->co_host != '' && isset($request->co_host_prefer_by)) {
                // if ($request->is_update_event == '0' && isset($request->isDraftEdit) && $request->isDraftEdit == "1") {
                if ($request->isdraft == "1" || (isset($request->isDraftEdit) && $request->isDraftEdit == "1") || (isset($request->isCheckOldcoHost) && $request->isCheckOldcoHost == "0")) {
                    $is_cohost = '1';
                    $invited_user = $request->co_host;
                    $prefer_by = $request->co_host_prefer_by;

                    if (isset($request->isPhonecontact) && $request->isPhonecontact == 1) {

                        $checkContactExist = contact_sync::where('id', $invited_user)->first();
                        if ($checkContactExist) {
                            $newUserId = NULL;
                            if ($checkContactExist->email != '') {
                                $newUserId = checkUserEmailExist($checkContactExist);
                            }
                            $eventInvite = new EventInvitedUser();
                            $eventInvite->event_id = $eventId;
                            $eventInvite->sync_id = $checkContactExist->id;
                            $eventInvite->user_id = $newUserId;
                            $eventInvite->prefer_by = $prefer_by;
                            $eventInvite->is_co_host = $is_cohost;
                            $eventInvite->save();
                        }
                    } else {
                        EventInvitedUser::create([
                            'event_id' => $eventId,
                            'prefer_by' => $prefer_by,
                            'user_id' => $invited_user,
                            'is_co_host' => $is_cohost,
                        ]);
                        $invitedusers = Event::with(['user'])->whereHas('user', function ($query) {})->where('id', $eventId)->get();
                        foreach ($invitedusers as $event_detail) {
                            $eventData = [
                                'event_name' => $event_detail->event_name,
                                'hosted_by' => $event_detail->user->firstname . ' ' . $event_detail->user->lastname,
                                'profileUser' => ($event_detail->user->profile != NULL || $event_detail->user->profile != "") ? $event_detail->user->profile : "no_profile.png",
                                'event_image' => "no_image.png",
                                'date' =>   date('l - M jS, Y', strtotime($event_detail->start_date)),
                                'time' => $event_detail->rsvp_start_time,
                                'address' => $event_detail->event_location_name . ' ' . $event_detail->address_1 . ' ' . $event_detail->state . ' ' . $event_detail->city . ' - ' . $event_detail->zip_code,
                            ];
                        }
                    }
                }
            }
            if (isset($request->eventSetting) && $request->eventSetting == "1") {
                $eventSetting = EventSetting::where('event_id', $eventId)->first();

                if ($eventSetting != null) {
                    $eventSetting->allow_for_1_more = (isset($request->allow_for_1_more)) ? $request->allow_for_1_more : "0";
                    $eventSetting->allow_limit = (isset($request->allow_limit_count)) ? (int)$request->allow_limit_count : 0;
                    $eventSetting->adult_only_party = (isset($request->only_adults)) ? $request->only_adults : "0";
                    $eventSetting->thank_you_cards = (isset($request->thankyou_message)) ? $request->thankyou_message : "0";
                    $eventSetting->add_co_host = (isset($request->add_co_host)) ? $request->add_co_host : "0";
                    $eventSetting->gift_registry = (isset($request->gift_registry)) ? $request->gift_registry : "0";
                    $eventSetting->events_schedule = (isset($request->events_schedule)) ? $request->events_schedule : "0";
                    $eventSetting->event_wall = (isset($request->event_wall)) ? $request->event_wall : "0";
                    $eventSetting->guest_list_visible_to_guests = (isset($request->guest_list_visible_to_guest)) ? $request->guest_list_visible_to_guest : "0";
                    $eventSetting->podluck = (isset($request->potluck)) ? $request->potluck : "0";
                    $eventSetting->rsvp_updates = (isset($request->rsvp_update)) ? $request->rsvp_update : "0";
                    $eventSetting->event_wall_post = (isset($request->event_wall_post)) ? $request->event_wall_post : "0";
                    $eventSetting->send_event_dater_reminders = (isset($request->rsvp_remainder)) ? $request->rsvp_remainder : "0";
                    $eventSetting->request_event_photos_from_guests = (isset($request->request_photo)) ? $request->request_photo : "0";
                    $eventSetting->save();
                } else {
                    EventSetting::create([
                        'event_id' => $eventId,
                        'allow_for_1_more' => (isset($request->allow_for_1_more)) ? $request->allow_for_1_more : "0",
                        'allow_limit' => (isset($request->allow_limit_count)) ? (int)$request->allow_limit_count : 0,
                        'adult_only_party' => (isset($request->only_adults)) ? $request->only_adults : "0",
                        'thank_you_cards' => (isset($request->thankyou_message)) ? $request->thankyou_message : "0",
                        'add_co_host' => (isset($request->add_co_host)) ? $request->add_co_host : "0",
                        'gift_registry' => (isset($request->gift_registry)) ? $request->gift_registry : "0",
                        'events_schedule' => (isset($request->events_schedule)) ? $request->events_schedule : "0",
                        'event_wall' => (isset($request->event_wall)) ? $request->event_wall : "0",
                        'guest_list_visible_to_guests' => (isset($request->guest_list_visible_to_guest)) ? $request->guest_list_visible_to_guest : "0",
                        'podluck' => (isset($request->potluck)) ? $request->potluck : "0",
                        'rsvp_updates' => (isset($request->rsvp_update)) ? $request->rsvp_update : "0",
                        'event_wall_post' => (isset($request->event_wall_post)) ? $request->event_wall_post : "0",
                        'send_event_dater_reminders' => (isset($request->rsvp_remainder)) ? $request->rsvp_remainder : "0",
                        'request_event_photos_from_guests' => (isset($request->request_photo)) ? $request->request_photo : "0",
                    ]);
                }
            }
            if (isset($request->potluck) && $request->potluck == "0") {
                EventPotluckCategory::where('event_id', $request->event_id)->delete();
                EventPotluckCategoryItem::where('event_id', $request->event_id)->delete();
                UserPotluckItem::where('event_id', $request->event_id)->delete();
            }

            if (isset($request->potluck) && $request->potluck == "1") {
                $potluck = session('category');
                // if ($request->isdraft == "1") {
                EventPotluckCategory::where('event_id', $request->event_id)->delete();
                EventPotluckCategoryItem::where('event_id', $request->event_id)->delete();
                UserPotluckItem::where('event_id', $request->event_id)->delete();
                // }
                if (isset($potluck) && !empty($potluck)) {

                    foreach ($potluck as $category) {
                        if ($category['iscateogry'] == '0') {
                            continue;
                        }
                        $eventPodluck = EventPotluckCategory::create([
                            'event_id' => $eventId,
                            'user_id' => $user_id,
                            'category' => $category['category_name'],
                            'quantity' => $category['category_quantity'],
                        ]);
                        if (isset($category['item'])) {
                            foreach ($category['item'] as $item) {
                                $eventPodluckitem = EventPotluckCategoryItem::create([
                                    'event_id' => $eventId,
                                    'user_id' => $user_id,
                                    'event_potluck_category_id' => $eventPodluck->id,
                                    'self_bring_item' =>  $item['self_bring'],
                                    'description' => $item['name'],
                                    'quantity' => $item['quantity'],
                                ]);
                                if (isset($item['item_carry_users'])) {
                                    foreach ($item['item_carry_users'] as $user) {
                                        UserPotluckItem::Create([
                                            'event_id' => $eventId,
                                            'user_id' => $user['user_id'],
                                            'event_potluck_category_id' => $eventPodluck->id,
                                            'event_potluck_item_id' => $eventPodluckitem->id,
                                            'quantity' => $user['quantity']
                                        ]);
                                    }
                                }
                                // else{
                                //     if (isset($item['self_bring']) && $item['self_bring'] == '1') {
                                //         UserPotluckItem::Create([
                                //             'event_id' => $eventId,
                                //             'user_id' => $user_id,
                                //             'event_potluck_category_id' => $eventPodluck->id,
                                //             'event_potluck_item_id' => $eventPodluckitem->id,
                                //             'quantity' => (isset($item['self_bring_qty']) && @$item['self_bring_qty'] != "") ? $item['self_bring_qty'] : $item['quantity']
                                //         ]);
                                //     }
                                // }

                            }
                        }
                    }
                }
            }
            if (isset($request->event_id) && $request->event_id != null && isset($request->events_schedule) && $request->events_schedule == '0') {
                EventSchedule::where('event_id', $request->event_id)->delete();
            }

            if (isset($request->events_schedule) && $request->events_schedule == '1' && isset($request->activity) && !empty($request->activity)) {
                $activities = $request->activity;
                if (isset($request->event_id) && $request->event_id != null) {
                    EventSchedule::where('event_id', $request->event_id)->delete();
                }
                $addStartschedule =  new EventSchedule();
                $addStartschedule->event_id = $eventId;
                $addStartschedule->start_time = isset($request->start_time) ? $request->start_time : '';
                $addStartschedule->event_date = isset($startDate) ? $startDateFormat : '';
                $addStartschedule->type = '1';
                $addStartschedule->save();

                foreach ($activities as $date => $activityList) {
                    $schedule_date = date('Y-m-d', strtotime($date));
                    foreach ($activityList as $activity) {
                        $activity_data[] = [
                            'event_id' => $eventId,
                            'activity_title' => $activity['activity'],
                            'start_time' => $activity['start-time'],
                            'end_time' => $activity['end-time'],
                            'event_date' => $schedule_date,
                            'type' => '2'
                        ];
                    }
                }

                EventSchedule::insert($activity_data);

                $addEndschedule =  new EventSchedule();
                $addEndschedule->event_id = $eventId;
                $addEndschedule->end_time = isset($request->end_time)  ? $request->end_time : '';
                $addEndschedule->event_date = isset($endDate) ? $endDateFormat : '';
                $addEndschedule->type = '3';
                $addEndschedule->save();
            }
            $gift = "0";
            if ($request->gift_registry == "1") {
                $gift_registry = $request->gift_registry_data;
            }
            if (isset($request->desgin_selected) && $request->desgin_selected != "") {
                // Handle the design image
                $image = EventImage::where('event_id', $eventId)->where('type', 0)->first();
                if ($image) {
                    $image->delete();
                    if ($request->desgin_selected != $image->image) {
                        $oldDesignImagePath = public_path('storage/event_images/') . $image->image;
                        if (file_exists($oldDesignImagePath)) {
                            @unlink($oldDesignImagePath);
                        }
                    }
                }

                // Save the new design image
                EventImage::create([
                    'event_id' => $eventId,
                    'image' => $request->desgin_selected,
                    'type' => 0
                ]);
            }

            if (!empty($request->slider_images)) {
                // Extract only filenames if slider_images contains an array of objects
                $newSliderImages = array_map(fn($image) => is_array($image) ? $image['fileName'] : $image, $request->slider_images);

                // Get current slider image names from the database
                $oldSliderImages = EventImage::where('event_id', $eventId)->where('type', 1)->pluck('image')->toArray();

                // Determine images to delete (present in DB but not in new request)
                $imagesToDelete = array_diff($oldSliderImages, $newSliderImages);
                EventImage::where('event_id', $eventId)
                    ->where('type', 1)
                    ->whereIn('image', $imagesToDelete)
                    ->delete();

                // Determine images to insert (present in request but not in DB)
                $imagesToInsert = array_diff($newSliderImages, $oldSliderImages);
                foreach ($imagesToInsert as $sliderImage) {
                    EventImage::create([
                        'event_id' => $eventId,
                        'image' => $sliderImage,
                        'type' => 1
                    ]);
                }
            }


            $get_count_invited_user = 0;
            $conatctId = session('contact_ids');
            $invitedCount = session('user_ids');
            $get_count_invited_user = (isset($contactId) ? count($contactId) : 0) + (isset($invitedCount) ? count($invitedCount) : 0);
            if ($request->is_update_event == '1') {
                $get_count_invited_user = $get_count_invited_user - intval($request->Alreadyguest);
            }

            if ($request->isDraftEdit == "1" || $request->is_update_event == '1') {
                debit_coins($user_id, $eventId, $get_count_invited_user);
            }


            $checkUserInvited = Event::withCount('event_invited_user')->where('id', $eventId)->first();
            dd($invitedusersession);
            if ($request->is_update_event == '0' && isset($request->isDraftEdit) && $request->isDraftEdit == "1") {
                if ($checkUserInvited->event_invited_user_count != '0' && $checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];
                    sendNotificationGuest('invite', $notificationParam);
                    sendNotification('invite', $notificationParam);
                }
                if ($checkUserInvited->is_draft_save == '0') {
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'post_id' => ""
                    ];
                    sendNotification('owner_notify', $notificationParam);
                    sendNotificationGuest('invite', $notificationParam);
                }
            }
            if ($request->is_update_event == '1') {
                if ($isaddress != 0 && $request->address1 != "") {
                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );

                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'from_addr' => $oldAddress,
                        'to_addr' => $newAddress,
                        'newUser' => $filteredIds,
                    ];

                    sendNotification('update_address', $notificationParam);
                }

                if (isset($request->IsPotluck) && $request->IsPotluck == 1) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );

                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'from_time' => $oldstart_time,
                        'to_time' =>  $newstart_time,
                        'newUser' => $filteredIds
                    ];

                    sendNotification('update_potluck', $notificationParam);
                }

                if (isset($istime) && $istime == 1) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'from_time' => $oldstart_time,
                        'to_time' =>  $newstart_time,
                        'newUser' => $filteredIds
                    ];

                    sendNotification('update_time', $notificationParam);
                }


                if (isset($isupdatedate) && $isupdatedate == 1) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );

                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'old_start_end_date' => $oldstart_date,
                        'new_start_end_date' => $newstart_date,
                        'newUser' => $filteredIds
                    ];

                    sendNotification('update_date', $notificationParam);
                }
                // dd($istime,$isupdatedate,$isaddress,$request->IsPotluck);
                if (isset($istime) && $istime == 0 && isset($isupdatedate) && $isupdatedate == 0 && $isaddress == 0 && isset($request->IsPotluck) && ($request->IsPotluck == 0 || $request->IsPotluck == "0")) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );
                    // dd($filteredIds)
                    $notificationParam = [
                        'sender_id' => $user_id,
                        'event_id' => $eventId,
                        'from_time' => $oldstart_time,
                        'to_time' =>  $newstart_time,
                        'newUser' => $filteredIds
                    ];

                    sendNotification('update_event', $notificationParam);
                }
                if (isset($invitedusersession)) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($invitedusersession, fn($guest) => !isset($guest['isAlready']))
                    );
                    if (isset($filteredIds) && count($filteredIds) != 0) {


                        $notificationParam = [
                            'sender_id' => $user_id,
                            'event_id' => $eventId,
                            'newUser' => $filteredIds
                        ];

                        sendNotification('invite', $notificationParam);
                        sendNotificationGuest('invite', $notificationParam);

                    }

            
                }
                if (isset($conatctId)) {

                    $filteredIds = array_map(
                        fn($guest) => $guest['id'],
                        array_filter($conatctId, fn($guest) => !isset($guest['isAlready']))
                    );
                    if (isset($filteredIds) && count($filteredIds) != 0) {


                        $notificationParam = [
                            'sender_id' => $user_id,
                            'event_id' => $eventId,
                            'newUser' => $filteredIds
                        ];

                        sendNotification('invite', $notificationParam);
                        sendNotificationGuest('invite', $notificationParam);

                    }

            
                }
            }
            Session::forget('desgin');
            Session::forget('shape_image');
        }
        if ($event_creation && $request->isdraft == "1") {
            return 1;
        }


        $registry = [];
        if (isset($request->gift_registry_data) && count($request->gift_registry_data) > 0) {
            foreach ($request->gift_registry_data as $key => $imgVal) {
                $gr = EventGiftRegistry::where('id', $imgVal['gr_id'])->first();
                if ($gr) {  // Check if $gr is not null
                    $registry[] = [
                        'registry_link' => $gr->registry_link
                    ];
                }
            }
        }

        // $registry = $request->gift_registry_data;

        // dd($registry);
        if (!empty($registry)) {
            $gift = '1';
        }
        Session::save();
        if ($request->is_update_event == '0' && isset($request->isDraftEdit) && $request->isDraftEdit == "1") {
            return response()->json([
                'view' => view('front.event.gift_registry.view_gift_registry', compact('registry'))->render(),
                'success' => true,
                'isupadte' => false,
                'is_registry' => $gift,
                'event_id' => encrypt($eventId)
            ]);
        } else {
            return response()->json([
                // 'view' => view('front.event.gift_registry.view_gift_registry', compact('registry'))->render(),
                'success' => true,
                'isupadte' => true,
                'is_registry' => $gift
            ]);
        }
    }

    public function getSliderImage(Request $request)
    {
        $event_id = $request->id;
        $getEventImages = EventImage::where('event_id', $event_id)->get();
        $savedFiles = [];
        $designImg = '';
        // dd($getEventImages)
        if (!empty($getEventImages)) {
            $i = 1;
            foreach ($getEventImages as $key => $imgVal) {
                if ($imgVal->type == 0) {
                    $designImg =   $imgVal->image;
                    continue;
                } else {
                    $fileName =   $imgVal->image;
                    $savedFiles[] = [
                        'fileName' => $fileName,
                        'deleteId' => strval($i),
                    ];
                    $i++;
                }
            }

            // if (empty($savedFiles)) {
            //     return response()->json(['status' => 'No valid images to save'], 400);
            // }

            return response()->json(['success' => true, 'images' => $savedFiles, 'designImg' => $designImg]);
        }
    }

    public function store_notification_filter(Request $request)
    {
        $status = $request->status;
        $event_id = $request->event_id;
        if ($status == 1) {
            $eventIds = session('notification_event_ids', []);
            if (!in_array($event_id, $eventIds)) {
                $eventIds[] = $event_id;
            }
            session(['notification_event_ids' => $eventIds]);
        } else {
            $eventIds = session('notification_event_ids', []);
            $eventIds = array_filter($eventIds, function ($id) use ($event_id) {
                return $id != $event_id;
            });
            if (!empty($eventIds)) {
                session(['notification_event_ids' => $eventIds]);
            } else {
                session()->forget('notification_event_ids');
            }
        }

        if ($status == null && $event_id == null) {
            Session::forget('notification_event_ids');
        }
    }
    public function notification_on_off(Request $request)
    {
        $status = $request->status;
        $is_owner = $request->is_owner;
        $event_id = $request->event_id;
        $user  = Auth::guard('web')->user();

        if ($is_owner == "1") {
            $event = Event::where(['id' => $event_id, 'user_id' => $user->id])->first();
            $event->notification_on_off = $status;
            $event->save();
        } else {
            $Guest = EventInvitedUser::where(['event_id' => $event_id, 'user_id' => $user->id])->first();
            $Guest->notification_on_off = $status;
            $Guest->save();
        }

        if ($status == "1") {
            return response()->json(['status' => 1, 'message' => 'Notification turned on']);
        } else {
            return response()->json(['status' => 1, 'message' => 'Notification turned off']);
        }
    }
}

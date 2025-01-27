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
    User
};

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventPotluckController extends Controller
{
    public function index(String $id)
    {
        $title = 'event potluck';
        $page = 'front.event_wall.event_potluck';
        $user  = Auth::guard('web')->user();
        $event = decrypt($id);
        $js = ['event_potluck'];
        if ($event == null) {
            return response()->json(['status' => 0, 'message' => "Json invalid"]);
        }
        try {
            $eventpotluckData =  EventPotluckCategory::with(['users', 'event_potluck_category_item' => function ($query) {
                $query->with(['users', 'user_potluck_items' => function ($subquery) {
                    $subquery->with('users')->sum('quantity');
                }]);
            }])->withCount('event_potluck_category_item')->where('event_id', $event)->get();
            $totalItems = EventPotluckCategoryItem::where('event_id', $event)->sum('quantity');
            $spoken_for = UserPotluckItem::where('event_id', $event)->sum('quantity');
            $checkEventOwner = Event::FindOrFail($event);
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
                //    {{     dd($eventpotluckData);}}
                $potluckDetail['item_summary'] = $potluckItemsSummury;
                foreach ($eventpotluckData as $value) {
                    $potluckCategory['id'] = $value->id;
                    $potluckCategory['category'] = $value->category;
                    $potluckCategory['created_by'] = $value->users->firstname . ' ' . $value->users->lastname;
                    $potluckCategory['quantity'] = $value->quantity;
                    $potluckCategory['items'] = [];
                    if (!empty($value->event_potluck_category_item) || $value->event_potluck_category_item != null) {
                        foreach ($value->event_potluck_category_item as $itemValue) {
                            // dd($itemValue);
                            $potluckItem['id'] =  $itemValue->id;
                            $potluckItem['description'] =  $itemValue->description;
                            $potluckItem['is_host'] = ($checkEventOwner->user_id == $itemValue->user_id) ? 1 : 0;
                            $potluckItem['requested_by'] =  $itemValue->users->firstname . ' ' . $itemValue->users->lastname;
                            $potluckItem['quantity'] =  $itemValue->quantity;
                            $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
                            $potluckItem['spoken_quantity'] =  $spoken_for;
                            $missing_quantity = $itemValue->quantity - $spoken_for;
                            $over_quantity = $spoken_for > $itemValue->quantity ? $spoken_for - $itemValue->quantity : 0;
                            $potluckItem['missing_quantity'] = $missing_quantity > 0 ? $missing_quantity : 0;
                            $potluckItem['over_quantity'] = $over_quantity > 0 ? $over_quantity : 0;
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

                $eventDetail = Event::with(['user', 'event_image', 'event_schedule', 'event_settings' => function ($query) {
                    $query->select('event_id', 'podluck', 'allow_limit', 'adult_only_party');
                },  'event_invited_user' => function ($query) {
                    $query->where('is_co_host', '0')->with('user');
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
                $eventDetails['podluck'] = $eventDetail->event_settings->podluck;
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
                $eventDetails['allow_limit'] = $eventDetail->event_settings->allow_limit;
                $eventDetails['adult_only_party'] = $eventDetail->event_settings->adult_only_party;
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
                        $guestData = EventInvitedUser::with('user') // Eager load the related 'user' model
                            ->where('event_id', $eventDetail->id)

                            ->get();



                        $eventData[] = "Number of guests : " . $numberOfGuest;
                        $eventData['guests'] = $guestData;
                    }
                    $eventDetails['event_detail'] = $eventData;
                }
                $eventDetails['total_limit'] = $eventDetail->event_settings->allow_limit;
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

                $current_page = "potluck";
                $login_user_id  = $user->id;
                return view('layout', compact('page', 'title', 'event', 'js', 'login_user_id', 'eventDetails', 'eventInfo','potluckDetail', 'current_page')); // return compact('eventInfo');
                // return compact('potluckDetail');
                // return response()->json(['status' => 1, 'data' => $potluckDetail, 'message' => " Potluck data"]);
            } else {

                return response()->json(['status' => 0, 'message' => "No data in potluck"]);
            }
        } catch (QueryException $e) {

            DB::rollBack();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception $e) {

            return response()->json(['status' => 0, 'message' => "Something went wrong"]);
        }
    }


    public function addPotluckCategory(Request $request)
    {
        $user  = Auth::guard('web')->user();



        // $input = json_decode($rawData, true);
        // if ($input == null) {
        //     return response()->json(['status' => 0, 'message' => "Json invalid"]);
        //}


        DB::beginTransaction();
        EventPotluckCategory::Create([
            'event_id' => $request->event_id,
            'user_id' => $user->id,
            'category' => $request->category,
            'quantity' => $request->quantity
        ]);
        DB::commit();
        return redirect()->back()->with('Potluck category created');
    }
    public function getCategory($id)
    {
        $category = EventPotluckCategory::findOrFail($id);
        return response()->json($category);
    }

    public function updateCategory(Request $request, $id)
    {

        $request->validate([
            'category' => 'required|string|max:30',
            'quantity' => 'required|integer|min:0',
        ]);

        $category = EventPotluckCategory::findOrFail($id);
        $category->category = $request->input('category');
        $category->quantity = $request->input('quantity');
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    public function deleteCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $eventId = $request->input('event_id');

        // Find the category by ID
        $category = EventPotluckCategory::find($categoryId);

        if ($category) {
            // Delete related items if necessary (e.g., deleting items under the category)
            // For example, assuming you have a relation called items() on the Category model
            // $category->items()->delete(); // Delete items related to the category
            $category->delete(); // Delete the category itself

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }
    public function addPotluckCategoryItem(Request $request)
    {
        // dd($request);
        $user = Auth::guard('web')->user();
        $login_user_id = $user->id;

        // Create the event potluck category item
        $eventPotluckItem = EventPotluckCategoryItem::create([
            'event_id' => $request['event_id'],
            'event_potluck_category_id' => $request['category_id'],
            'self_bring_item' => $request['self_bring_item'],
            'user_id' => $user->id,
            'description' => $request['description'],
            'quantity' => $request['quantity'],
        ]);

        // If 'self_bring_item' is 1, create the UserPotluckItem
        if ($request['self_bring_item'] == '1' || $request['self_bring_item'] == '0') {
            // Use the 'self_quantity' if provided, otherwise use the 'quantity'
            $selfQuantity = $request->has('self_quantity') ? $request['self_quantity'] : $request['quantity'];

            UserPotluckItem::create([
                'event_id' => $request['event_id'],
                'user_id' => $user->id,
                'event_potluck_category_id' => $request['category_id'],
                'event_potluck_item_id' => $eventPotluckItem->id,
                'quantity' => $selfQuantity,
            ]);
        }
        // $spoken_for = UserPotluckItem::where('event_potluck_item_id', $itemValue->id)->sum('quantity');
        // $potluckItem['spoken_quantity'] =  $spoken_for;
        $accordionItemHtml = view('front.event_wall.potluck_item', [
            'category_id' => $eventPotluckItem->event_potluck_category_id,
            'item_id' => $eventPotluckItem->id,
            'description' => $request['description'],

            'quantity' => $request['quantity'],
            'self_bring_item' => $request['self_bring_item'],
            'login_user_id' => $login_user_id,
            'user' => [
                'first_name' => $user->firstname,
                'last_name' => $user->lastname,
                'profile' => $user->profile,
                'user_id' => $eventPotluckItem['user_id'] // Assuming profile_image is the field storing the image
            ],
        ])->render();

        return response()->json([
            'status' => 'success',
            'data' => $accordionItemHtml,
        ]);
    }

    public function editPotluckCategoryItem(Request $request)
    {
        $user  = Auth::guard('web')->user();

        $eventPotluckItem = EventPotluckCategoryItem::where('id', $request['category_item_id'])->first();

        if ($eventPotluckItem != "") {
            // $eventPotluckItem->event_id = $request['event_id'];
            $eventPotluckItem->event_potluck_category_id = $request['category_id'];
            // $eventPotluckItem->description = $request['description'];
            // $eventPotluckItem->self_bring_item = $request['self_bring_item'];

            $eventPotluckItem->quantity = $request['quantity'];
            $eventPotluckItem->save();
        }



        return redirect()->back()->with('success', "Potluck category item updated");
    }

    public function editUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('web')->user();

        $checkCarryQty = UserPotluckItem::where(['event_potluck_category_id' => $request['category_id'], 'event_id' => $request['event_id'], 'event_potluck_item_id' => $request['category_item_id']])->first();

        // if ($input['quantity'] <= $checkQty) {
        $checkIsExist = UserPotluckItem::where([
            'id' => $checkCarryQty['id']
        ])->first();
        if ($checkIsExist != null) {
            $checkIsExist->quantity = $request['quantity'];
            $checkIsExist->save();
        }

        $getUserItemData = UserPotluckItem::with('users')->where(['id' => $checkCarryQty['id']])->first();
        $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $request['category_item_id']])->sum('quantity');

        $getCarryUser =  [
            "id" => $getUserItemData->id,
            "user_id" => $getUserItemData->user_id,
            "is_host" => ($getUserItemData->user_id == $user->id) ? 1 : 0,
            "profile" => empty($getUserItemData->users->profile) ?  "" : asset('storage/profile/' . $getUserItemData->users->profile),
            "first_name" => $getUserItemData->users->firstname,
            "quantity" => (!empty($getUserItemData->quantity) || $getUserItemData->quantity != NULL) ? $getUserItemData->quantity : "0",

            "last_name" =>  $getUserItemData->users->lastname
        ];

        return response()->json(['status' => 1, "spoken_for" => $spoken_for, 'data' => $getCarryUser, 'message' => "Potluck item updated"]);
    }
    public function fetchUserDetails(Request $request)
    {
        $categoryId = $request->category_id;
        $itemId = $request->item_id;
        $userProfile = $request->user_profile;
        $loginUserId = $request->login_user_id;
        $quantity = $request->quantity;
        $user = Auth::guard('web')->user();


        // Check if the item quantity is available
        $checkQty = EventPotluckCategoryItem::where('id', $itemId)->value('quantity');
        $checkCarryQty = intval(UserPotluckItem::where([
            'event_potluck_category_id' => $categoryId,
            'event_potluck_item_id' => $itemId
        ])->sum('quantity'));

        // Check if there's enough quantity available
        if ($checkCarryQty < $checkQty) {
            // Check if the user has already added this item
            $checkIsExist = UserPotluckItem::where([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
                'event_potluck_category_id' => $categoryId,
                'event_potluck_item_id' => $itemId
            ])->first();

            // If the user has not added the item, create a new entry
            if (!$checkIsExist) {
                $newUserItem = UserPotluckItem::create([
                    'event_id' => $request->event_id,
                    'user_id' => $user->id,
                    'event_potluck_category_id' => $categoryId,
                    'event_potluck_item_id' => $itemId,
                    'quantity' => $quantity
                ]);
                $checkIsExist = $newUserItem;
            } else {
                // If the item already exists, update the quantity
                $checkIsExist->quantity = $quantity;
                $checkIsExist->save();
            }



            // Fetch the user item data
            $getUserItemData = UserPotluckItem::with('users')->where('id', $checkIsExist->id)->first();
            $spokenFor = UserPotluckItem::where('event_potluck_item_id', $itemId)->sum('quantity');
            // dd($getUserItemData);
            // Prepare the view to be sent in the response
            $getCarryUser = view('front.event_wall.potluck_user_categoryItem', [
                'id' => $getUserItemData->id,
                'user_id' => $getUserItemData->user_id,
                'is_host' => ($getUserItemData->user_id == $user->id) ? 1 : 0,
                'profile' => $getUserItemData->users->profile ? asset('storage/profile/' . $getUserItemData->users->profile) : '',
                'first_name' => $getUserItemData->users->firstname,
                'quantity' => $getUserItemData->quantity ?? '0',
                'last_name' => $getUserItemData->users->lastname,
                'category_id' => $categoryId,
                'item_id' => $itemId,
                'spoken_for' => $spokenFor,
            ])->render();

            // Return the response
            return response()->json([
                'status' => 'success',
                'data' => $getCarryUser,


            ]);
        } else {
            // If the potluck is full, return a message
            return response()->json([
                'status' => 1,
                'message' => "Potluck is full!!!"
            ]);
        }
    }
    public function deleteUserPotluckItem(Request $request)
    {
        $user  = Auth::guard('web')->user();
        $checkCarryQty = UserPotluckItem::where(['event_potluck_category_id' => $request['category_id'], 'event_id' => $request['event_id'], 'event_potluck_item_id' => $request['category_item_id']])->first();
        $checkIsExist = UserPotluckItem::where([
            'id' =>  $checkCarryQty['id']
        ])->first();

        $event_potluck_item_id = $checkIsExist->event_potluck_item_id;
        if ($checkIsExist != null) {

            $checkIsExist->delete();
        }


        $spoken_for = UserPotluckItem::where(['event_potluck_item_id' => $event_potluck_item_id])->sum('quantity');
        return response()->json([
            'success' => true,
            'spoken_for' => $spoken_for,
            'message' => 'Potluck item deleted successfully!',
            'redirect_url' => route('event.event_potluck',  encrypt($request['event_id']))  // Optionally send a redirect URL back
        ]);
    }
}

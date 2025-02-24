<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use Location;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Exception\RuntimeException;

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
    UserReportToPost,
    Group,
    GroupMember,
    UserNotificationType,
    UserProfilePrivacy,
    UserReportChat,
    UserSeenStory,
    UserSubscription,
    VersionSetting,
    TextData,
};
use Illuminate\Support\Facades\Http;
// Rules //
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Rules\CheckUserEvent;
use App\Rules\checkUserEventPost;
use App\Rules\checkUserGreetingId;
use App\Rules\checkIsUserEvent;
use App\Rules\checkUserGiftregistryId;

use App\Rules\checkInvitedUser;
use Illuminate\Support\Facades\Hash;
// Rules //
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Validator;

use DateTime;
// use Validator;
use Laravel\Passport\Token;

use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
use Illuminate\Support\Facades\Log;


use Carbon\Carbon;

use App\Mail\InvitationEmail;
use DateException;
use Exception as GlobalException;
use FFI\Exception as FFIException;
use Illuminate\Support\Facades\Mail;
use LogicException;
use Illuminate\Database\Query\Builder;
use App\Jobs\SendInvitationMailJob as sendInvitation;
use App\Jobs\SendNotificationJob;
use App\Services\GooglePlayService;
use Illuminate\Support\Facades\Session;
use stdClass;
use Spatie\Image\Image;
use App\Services\GooglePlayServices;

class ApiContactController extends Controller
{
    public function sync_contact(Request $request)
    {
        $user = Auth::guard('api')->user();
        $rawData = $request->getContent();
        $contacts = json_decode($rawData, true);
        
        if (empty($contacts)) {
            return response()->json(['message' => 'No contacts provided.'], 400);
        }
        $new_phone_contact = [];
        $new_email_contact = [];
        foreach ($contacts as $key => $value) {
            
            if(isset($value['phoneWithCode']) && isset($value['email']) && $value['phoneWithCode'] != ''){
                $new_phone_contact[] = $value;
            }
            if( isset($value['phoneWithCode']) && isset($value['email']) && $value['email'] != ''){
                $new_email_contact[] = $value;
            }
        }
        $phone_contact = collect($new_phone_contact)->unique(function ($item) {
       
                return $item['phoneWithCode'];
            
        })->values()->toArray();

        $email_contact = collect($new_email_contact)->unique(function ($item) {

                return $item['email'];
            
        })->values()->toArray();
        // return response()->json([
        //     'without_sync_count' => count($contacts),
        //     'phone' => $phone_contact,
        //     'email' => $email_contact,
        //     'sync_contact' =>'phone -> '. count($phone_contact) .', email -> '. count($email_contact)
        // ], 200);
        // dd($contacts);
        $contacts = array_merge($phone_contact, $email_contact);
        $insertedContacts = [];
        $duplicateContacts = [];
        // dd($contacts);
        // Process each contact from the request
        foreach ($contacts as $contact) {
            $contact['email'] = @strtolower($contact['email']);
            if (!empty($contact['firstName']) && (!empty($contact['phoneWithCode']) || !empty($contact['email']))) {
                if((isset($contact['email']) && $contact['email'] != '' && $user->email == $contact['email']) || (isset($contact['phoneWithCode']) && $contact['phoneWithCode'] != '' && $user->phone_number == $contact['phoneWithCode']) ){
                    continue;
                }
                if(!empty($contact['phoneWithCode']) && !empty($contact['email'])){
                    $existingContact = contact_sync::where('contact_id', $user->id)
                    ->where(function ($query) use ($contact) {
                        $query->where('email', $contact['email'])
                            ->where('phoneWithCode', $contact['phoneWithCode']);
                    })
                    ->first();
                }elseif (empty($contact['phoneWithCode']) && !empty($contact['email'])) {
                    $existingContact = contact_sync::where('contact_id', $user->id)
                        ->where(function ($query) use ($contact) {
                            $query->where('email', $contact['email'])
                                ->where('phoneWithCode','');
                        })
                        ->first();
                }elseif (!empty($contact['phoneWithCode']) && empty($contact['email'])) {
                    $existingContact = contact_sync::where('contact_id', $user->id)
                        ->where(function ($query) use ($contact) {
                            $query->where('phoneWithCode', $contact['phoneWithCode'])
                                ->where('email', '');
                        })
                        ->first();
                }

                // $existingContact = contact_sync::where('contact_id', $user->id)
                //     ->where(function ($query) use ($contact) {
                //         $query->where('email', $contact['email'])
                //             ->orWhere('phoneWithCode', $contact['phoneWithCode']);
                //     })
                //     ->first();

                if ($existingContact) {
                    
                    $existingContact->update([
                        'firstName' => (isset($contact['firstName']) && $contact['firstName'] !='')?$contact['firstName']:'',
                        'lastName' => (isset($contact['lastName']) && $contact['lastName'] != '')?$contact['lastName']:'',
                        'photo' => ($contact['photo']!='')?$contact['photo']:'',
                        'phoneWithCode' => (isset($contact['phoneWithCode']) && $contact['phoneWithCode'] !='')?$contact['phoneWithCode']:'',
                        'isAppUser' => (int)($contact['isAppUser'] ?? 0),
                        'visible' => (int)($contact['visible'] ?? 0),
                        'preferBy' => (isset($contact['preferBy']) && $contact['preferBy'] != '')?$contact['preferBy']:'',
                        'updated_at' => now(),
                    ]);
                    
                    $duplicateContacts[] = [
                        'sync_id' => $existingContact->id,
                        'userId' => null,
                        'contact_id' => $user->id,
                        'firstName' => (isset($contact['firstName']) && $contact['firstName'] !='')?$contact['firstName']:'',
                        'lastName' => (isset($contact['lastName']) && $contact['lastName'] != '')?$contact['lastName']:'',
                        'phone' => (isset($contact['phone']) && $contact['phone'] != '')?$contact['phone']:'',
                        'email' => (isset($contact['email']) && $contact['email'] != '')?$contact['email']:'',
                        'photo' => ($contact['photo']!='')?$contact['photo']:'',
                        'phoneWithCode' => (isset($contact['phoneWithCode']) && $contact['phoneWithCode'] !='')?$contact['phoneWithCode']:'',
                        'isAppUser' => (int)($contact['isAppUser'] ?? 0),
                        'visible' => (int)($contact['visible'] ?? 0),
                        'preferBy' => (isset($contact['preferBy']) && $contact['preferBy'] != '')?$contact['preferBy']:'',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    // Insert new contact if it doesn't exist
                    $tempId = (string) Str::uuid();
                    $insertedContacts[] = [
                        'temp_id' => $tempId,
                        'userId' => null,
                        'contact_id' => $user->id,
                        'firstName' => (isset($contact['firstName']) && $contact['firstName'] !='')?$contact['firstName']:'',
                        'lastName' => (isset($contact['lastName']) && $contact['lastName'] != '')?$contact['lastName']:'',
                        'phone' => (isset($contact['phone']) && $contact['phone'] != '')?$contact['phone']:'',
                        'email' => (isset($contact['email']) && $contact['email'] != '')?$contact['email']:'',
                        'photo' => (isset($contact['photo']) && $contact['photo'] != '')?$contact['photo']:'',
                        'phoneWithCode' => (isset($contact['phoneWithCode']) && $contact['phoneWithCode'] !='')?$contact['phoneWithCode']:'',
                        'isAppUser' => (string)($contact['isAppUser'] ?? 0),
                        'visible' => (string)($contact['visible'] ?? 0),
                        'preferBy' => (isset($contact['preferBy']) && $contact['preferBy'] != '')?$contact['preferBy']:'',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Bulk insert new contacts
        if (!empty($insertedContacts)) {
            // contact_sync::insert($insertedContacts);
            // $insertedIds = contact_sync::latest('id')
            // ->take(count($insertedContacts))     
            // ->pluck('id')                        
            // ->toArray();
            // foreach ($insertedContacts as $index => &$contact) {
            //     $contact['sync_id'] = $insertedIds[$index] ?? null;
            // }

            contact_sync::insert($insertedContacts);

            // Fetch inserted rows using temp_id
            $tempIds = array_column($insertedContacts, 'temp_id');
            $insertedRows = contact_sync::whereIn('temp_id', $tempIds)->get(['id', 'temp_id']);

            // Map the sync_id back to the original array
            foreach ($insertedContacts as &$contact) {
                $contact['sync_id'] = $insertedRows->firstWhere('temp_id', $contact['temp_id'])->id ?? null;
                unset($contact['temp_id']); // Remove the temporary identifier
            }

            unset($contact);
        } 

        $emails = array_filter(array_column($contacts, 'email'));

        $mergeArray = array_merge($insertedContacts, $duplicateContacts);
        $userDetails = User::select('id', 'email', 'phone_number', 'firstname', 'lastname', 'profile','app_user','visible')
        ->whereIn('email', $emails)
        ->where('app_user','1')
        ->get();
        // ->orWhereIn('phone_number', $phoneNumbers)
        
        

        foreach ($userDetails as $userDetail) {
            // Update existing contacts in the database
            contact_sync::where('contact_id', $user->id)
                ->where(function ($query) use ($userDetail) {
                    $query->where('email', $userDetail->email);
                        // ->orWhere('phone', $userDetail->phone_number);
                })
                ->update([
                    'userId' => $userDetail->id,
                    'firstName' => $userDetail->firstname,
                    'lastName' => $userDetail->lastname
                ]);

            // Find matching contacts using array_search()
            $index = array_search(true, array_map(function ($duplicateContact) use ($userDetail) {
                if($duplicateContact['email'] == $userDetail->email || $duplicateContact['phone'] == $userDetail->phone_number){
                    if($duplicateContact['email'] == $userDetail->email){
                        return $duplicateContact['email'] === $userDetail->email;
                    }

                    if($userDetail->phone_number !='' && $duplicateContact['phone'] == $userDetail->phone_number){
                        // dd($duplicateContact);
                        return $duplicateContact['phone'] === $userDetail->phone_number;
                    }
                }
                // return $duplicateContact['email'] === $userDetail->email || $duplicateContact['phone'] === $userDetail->phone_number;
            }, $mergeArray));

            if ($index !== false) {
               
                // Update the matching contact
                $mergeArray[$index]['userId'] = (int)$userDetail->id;
                $mergeArray[$index]['isAppUser'] = (int)$userDetail->app_user;
                $mergeArray[$index]['firstName'] = $userDetail->firstname;
                $mergeArray[$index]['lastName'] = $userDetail->lastname;
                $mergeArray[$index]['visible'] = (int)$userDetail->visible;
                $mergeArray[$index]['email'] = $userDetail->email;
                $mergeArray[$index]['phone'] = $userDetail->phone_number;
                $mergeArray[$index]['photo'] = $userDetail->profile ? asset('storage/profile/' . $userDetail->profile) : '';

                // if($userDetail->phone_number == '7567264803'){
                //     dd($duplicateContacts);
                // }
            }
        }
        // foreach ($userDetails as $userDetail) {
        //     contact_sync::where('contact_id', $user->id)
        //         ->where(function ($query) use ($userDetail) {
        //             $query->where('email', $userDetail->email)
        //                 ->orWhere('phone', $userDetail->phone_number);
        //         })
        //         ->update([
        //             'userId' => $userDetail->id,
        //             'firstName' => $userDetail->firstname,
        //             'lastName' => $userDetail->lastname
        //         ]);



        //     // Update duplicate contacts array with user details
        //     foreach ($duplicateContacts as &$duplicateContact) {
        //         if ($duplicateContact['email'] === $userDetail->email || $duplicateContact['phone'] === $userDetail->phone_number) {
        //             $duplicateContact['userId'] = $userDetail->id;
        //             $duplicateContact['firstName'] = $userDetail->firstname;
        //             $duplicateContact['lastName'] = $userDetail->lastname;
        //             $duplicateContact['photo'] = $userDetail->profile ? asset('storage/contact_profile/' . $userDetail->profile) : '';
        //         }
        //     }
        // }



        // Filter out contacts that have a user ID (i.e., contacts that were matched with an existing user)
        // dd($duplicateContacts);
        $updatedDuplicateContacts = array_filter($mergeArray);
       
        $updatedDuplicateContacts = array_map(function($item) {
            // dd($item);
            $item['isAppUser'] = (int)$item['isAppUser'];
            $item['visible'] = (int)$item['visible'];
            if ($item['userId'] === null) {
                $item['userId'] = 0;
            }
            if ($item['phone'] === null) {
                $item['phone'] = '';
            }
            return $item;
        }, $updatedDuplicateContacts);

        $uniqContact = [];
        $seenSyncIds = [];

        foreach ($updatedDuplicateContacts as $contact) {
            if ($contact['isAppUser'] == "0") {
                if (!in_array($contact['sync_id'], $seenSyncIds)) {
                    $uniqContact[] = $contact;
                    $seenSyncIds[] = $contact['sync_id'];
                }
            } else {
                $uniqContact[] = $contact;
            }
        }
// dd($seenSyncIds)
        // return response()->json([
        //     'message' => empty($updatedDuplicateContacts) ? 'Contacts inserted successfully.' : 'Some contacts already exist.',
        //     'all_contacts' => $updatedDuplicateContacts,
        // ], 200);
        return response()->json([
            'message' => empty($uniqContact) ? 'Contacts inserted successfully.' : 'Some contacts already exist.',
            'all_contacts' => $uniqContact,
        ], 200);
    }



}

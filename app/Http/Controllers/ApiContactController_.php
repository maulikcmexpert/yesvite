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

        // Arrays to track inserted and duplicate contacts
        $insertedContacts = [];
        $duplicateContacts = [];

        // Process each contact
        foreach ($contacts as $contact) {

            if (!empty($contact['firstName']) && !empty($contact['phone'])) {


                // Check if contact already exists in `contact_sync`
                $existingContact = contact_sync::where('contact_id', $user->id)
                    ->where(function ($query) use ($contact) {
                        $query->where('email', $contact['email'] ?? "")
                            ->orWhere('phone', $contact['phone']);
                    })
                    ->first();

                if ($existingContact) {
                    // Update the existing contact
                    $existingContact->update([

                        'firstName' => $contact['firstName'],
                        'lastName' => $contact['lastName'],
                        'photo' => $existingContact['photo'] ?? "" ,
                        'phoneWithCode' => $contact['phoneWithCode'] ?? "",
                        'isAppUser' => (int)isset($contact['isAppUser']) ?  $contact['isAppUser'] : '0',
                        'visible' => (int)isset($contact['visible']) ?  $contact['visible'] : '0',
                        'prefer_by' => isset($contact['prefer_by']) ? (string) $contact['prefer_by'] : 'phone',
                        'updated_at' => now(),
                    ]);

                    // Add to duplicates array
                    $duplicateContacts[] = [
                        'id' => $existingContact['id'],
                        'userId' => 0, // To be updated later
                        'contact_id' => $user->id,
                        'firstName' => $contact['firstName'] ?? "",
                        'lastName' => $contact['lastName'] ?? "",
                        'phone' => $contact['phone'],
                        'email' => $contact['email'] ?? "",
                        'photo' => $existingContact['photo'] ?? "" ,
                        'phoneWithCode' => $contact['phoneWithCode'] ?? "",
                        'isAppUser' => (int)isset($contact['isAppUser']) ?  $contact['isAppUser'] : '0',
                        'visible' => (int)isset($contact['visible']) ?  $contact['visible'] : '0',
                        'prefer_by' => isset($contact['prefer_by']) ? (string) $contact['prefer_by'] : 'phone',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    // Handle photo upload if it is an instance of UploadedFile

                    // Prepare contact for bulk insert
                    $insertedContacts[] = [
                        'userId' => null,

                        'contact_id' => $user->id,
                        'firstName' => $contact['firstName'] ?? "",
                        'lastName' => $contact['lastName'] ?? "",
                        'phone' => $contact['phone'],
                        'email' => $contact['email'] ?? "",
                        'photo'  => $contact['photo'] ?? "",
                        'phoneWithCode' => $contact['phoneWithCode'] ?? "",
                        'isAppUser' => isset($contact['isAppUser']) ? (string)$contact['isAppUser'] : '0',
                        'visible' => isset($contact['visible']) ?  (string)$contact['visible'] : '0',
                        'prefer_by' => isset($contact['prefer_by']) ? (string) $contact['prefer_by'] : 'phone',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Perform bulk insert
        if (!empty($insertedContacts)) {
            contact_sync::insert($insertedContacts);
        }

        // Fetch matching users from `users` table
        $emails = array_filter(array_column($contacts, 'email'));
        $phoneNumbers = array_filter(array_column($contacts, 'phone'));

        $userDetails = User::select('id', 'email', 'phone_number', 'firstname', 'lastname', 'profile','app_user')
            ->whereIn('email', $emails)
            ->orWhereIn('phone_number', $phoneNumbers)
            ->get();

        // Update `user_id` in duplicateContacts based on `userDetails`
        foreach ($userDetails as $userDetail) {
            contact_sync::where('contact_id', $user->id)
                ->where(function ($query) use ($userDetail) {
                    $query->where('email', $userDetail->email)
                        ->orWhere('phone', $userDetail->phone_number);
                })->update([
                    'userId' =>  $userDetail->id ?? 0,
                    'firstName' => $userDetail->firstname,
                    'lastName' => $userDetail->lastname
                ]);
            foreach ($duplicateContacts as &$duplicateContact) {
                if (
                    ($duplicateContact['email'] === $userDetail->email) ||
                    ($duplicateContact['phone'] === $userDetail->phone_number)
                ) {
                    $duplicateContact['userId'] = $userDetail->id; // Update user_id
                    $duplicateContact['firstName'] = $userDetail->firstname;
                    $duplicateContact['lastName'] = $userDetail->lastname;
                    $duplicateContact['photo'] = (isset($userDetail->profile) && $userDetail->profile != '')?asset('storage/profile/' . $userDetail->profile):'';
                    $duplicateContact['isAppUser'] = (isset($userDetail->app_user) && $userDetail->app_user != '')?(int)$userDetail->app_user:0;
                }
            }
        }


        // Filter duplicate contacts to include only those with updated user_id
        $updatedDuplicateContacts = array_filter($duplicateContacts, function ($contact) {
            return !is_null($contact['userId']);
        });

        // Prepare the response
        $message = empty($updatedDuplicateContacts)
            ? 'Contacts inserted successfully.'
            : 'Some contacts already exist.';

        $allContacts = $insertedContacts ? $insertedContacts : $duplicateContacts;

        return response()->json([
            'message' => $message,
            'all_contacts' => $allContacts,
        ], 200); // Prepare the response
    }
}

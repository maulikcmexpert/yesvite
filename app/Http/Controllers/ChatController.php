<?php

namespace App\Http\Controllers;

use App\Models\contact_sync;
use App\Models\User;
use App\Models\UserReportChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChatController extends BaseController
{
    protected $database;
    protected $chatRoom;
    protected $firebase;
    protected $usersReference;

    public function __construct()
    {
        parent::__construct();
        $this->firebase = Firebase::database();
        $this->usersReference = $this->firebase->getReference('users');
        // $this->database = $database;
        // $this->chatRoom = $this->database->getReference();
    }
    public function updateUserinFB(Request $request)
    {
        $userId = $request->userId;
        $userData = User::findOrFail($userId);
        // dd($userData);
        $userName =  $userData->firstname . ' ' . $userData->lastname;
        $updateData = [
            'userChatId' => '',
            'userCountryCode' => (string)$userData->country_code,
            'userGender' => (string)$userData->gender,
            'userEmail' => $userData->email,
            'userId' => (string)$userId,
            'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
            'userName' => $userName,
            'userPhone' => (string)$userData->phone_number,
            'userProfile' => url('/public/storage/profile/' . $userData->profile),
            'userStatus' => '',
            'userTypingStatus' => 'Not typing...'
        ];

        // Create a new user node with the userId
        $userRef = $this->usersReference->getChild((string)$userId);
        $userSnapshot = $userRef->getValue();

        if ($userSnapshot) {
            // User exists, update the existing data
            $userRef->update($updateData);
        } else {
            // User does not exist, create a new user node
            $userRef->set($updateData);
        }
        return true;
    }
    public function index($id = null, $is_host = null)
    {
        $hosts_id = "";
        $hosts_name = "";
        $hosts_profile = "";
        if ($id !== null) {
            $hosts_id = decrypt($id);
            $hosts_data = User::where('id', $hosts_id)->first();
            if (!$hosts_data) {
                $hosts_data = contact_sync::where('id', $hosts_id)->first();
            }

            if ($hosts_data) {
                $hosts_name = trim(($hosts_data->firstName ?? $hosts_data->firstname) . ' ' . ($hosts_data->lastName ?? $hosts_data->lastname));
                $photo_field = isset($hosts_data->photo) && !empty($hosts_data->photo) ? $hosts_data->photo : $hosts_data->profile;
                $hosts_profile = !empty($photo_field) ? url('/public/storage/profile/' . $photo_field) : "";
            }
        }

        $userId = auth()->id();
        $userData = User::findOrFail($userId);

        // dd($userData);
        $userName =  $userData->firstname . ' ' . $userData->lastname;
        $updateData = [
            'userChatId' => '',
            'userCountryCode' => (string)$userData->country_code,
            'userGender' => (string)$userData->gender,
            'userEmail' => $userData->email,
            'userId' => (string)$userId,
            'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
            'userName' => $userName,
            'userPhone' => (string)$userData->phone_number,
            'userProfile' => url('/public/storage/profile/' . $userData->profile),
            'userStatus' => 'Online',
            'userTypingStatus' => 'Not typing...'
        ];

        // Create a new user node with the userId
        $userRef = $this->usersReference->getChild((string)$userId);
        $userSnapshot = $userRef->getValue();
        $updateFirebase = false;

        if ($userSnapshot) {
            if ($userSnapshot['userName'] != $userData->firstname . ' ' . $userData->lastname || $userSnapshot['userProfile'] != url('/public/storage/profile/' . $userData->profile)) {
                $updateFirebase = true;
            }
            // User exists, update the existing data
            $userRef->update($updateData);
        } else {
            // User does not exist, create a new user node
            $userRef->set($updateData);
        }

        $reference = $this->firebase->getReference('overview/' . $userId);
        $messages = $reference->getValue();
        $updateData = [
            'contactName' => $userName,
            'receiverProfile' => url('/public/storage/profile/' . $userData->profile)
        ];
        $updateGroupData = [
            'name' => $userName,
            'image' => url('/public/storage/profile/' . $userData->profile)
        ];
        if ($updateFirebase == true) {
            if (!empty($messages)) {

                foreach ($messages as $message) {
                    if (isset($message['group'])  && ($message['group'] == "true" || $message['group'] == true)) {
                        $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles');
                        $profiles = $reference->getValue();
                        if ($profiles) {

                            foreach ($profiles as $key => $profile) {
                                if ($profile['id'] == $userId) {
                                    $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles/' . $key);
                                    $reference->update($updateGroupData);
                                    break;
                                }
                            }
                        }
                    } else {
                        if (isset($message['contactId'])) {
                            $reference = $this->firebase->getReference('overview/' . $message['contactId'] . '/' . $message['conversationId']);
                            if ($reference) {
                                $reference->update($updateData);
                            }
                        }
                    }
                }
            }
        }
        if ($messages) {
            uasort($messages, function ($a, $b) {
                // Check if either of the items has 'isPin' set to '1'
                $isPinA = isset($a['isPin']) && $a['isPin'] == '1';
                $isPinB = isset($b['isPin']) && $b['isPin'] == '1';

                // If both have the same 'isPin' status, sort by 'timeStamp'
                if ($isPinA == $isPinB) {
                    $timeStampA = isset($a['timeStamp']) ? $a['timeStamp'] : PHP_INT_MAX;
                    $timeStampB = isset($b['timeStamp']) ? $b['timeStamp'] : PHP_INT_MAX;
                    return $timeStampB <=> $timeStampA;
                }

                // Otherwise, prioritize the item with 'isPin' set to '1'
                return $isPinB <=> $isPinA;
            });
        }
        // dd($messages);
        $title = 'Messages';
        $page = 'front.chat.messages';
        $css = 'message.css';
        $css1 = 'audio.css';
        if ($messages == null) {
            $messages = [];
        }
        return view('layout', compact(
            'title',
            'page',
            'css',
            'css1',
            'messages',
            'userId',
            'userName',
            'hosts_id',
            'hosts_name',
            'hosts_profile'
        ));
    }



    public function get_user_by_name(Request $request)
    {
        $name = $request->input('username');
        $userId = auth()->id();

        $userData = User::findOrFail($userId);
        $userName =  $userData->firstname . ' ' . $userData->lastname;
        // Get the "overview" node for the authenticated user
        $overviewRef = $this->firebase->getReference('overview/' . $userId);
        $overviewData = $overviewRef->getValue();

        $updateData = [
            'contactName' => $userName,
            'receiverProfile' => url('/public/storage/profile/' . $userData->profile)
        ];
        $updateGroupData = [
            'name' => $userName,
            'image' => url('/public/storage/profile/' . $userData->profile)
        ];



        $messages = [];

        // Loop through the overview contacts and search by name
        // dd($overviewData);
        if ($overviewData) {
            foreach ($overviewData as $conversationId => $contactData) {
                if (
                    isset($contactData['contactName']) &&
                    strpos(strtolower($contactData['contactName']), strtolower($name)) !== false
                ) {
                    $messages[] = [
                        "contactId" => isset($contactData['contactId']) ? $contactData['contactId'] : '',
                        "conversationId" => isset($contactData['conversationId']) ? $contactData['conversationId'] : '',
                        "group" => isset($contactData['group']) ? $contactData['group'] : false,
                        'contactName' => isset($contactData['contactName']) ? $contactData['contactName'] : 'Unknown',
                        'receiverProfile' => isset($contactData['receiverProfile']) ? $contactData['receiverProfile'] : '',
                        'lastSeen' => isset($contactData['lastSeen']) ? $contactData['lastSeen'] : 'Unavailable',
                        'status' => isset($contactData['status']) ? $contactData['status'] : 'Offline',
                        'lastMessage' => isset($contactData['lastMessage']) ? $contactData['lastMessage'] : '',
                        "lastSenderId" => isset($contactData['lastSenderId']) ? $contactData['lastSenderId'] : '',
                        "unRead" => isset($contactData['unRead']) ? $contactData['unRead'] : 0,
                        "unReadCount" => isset($contactData['unReadCount']) ? $contactData['unReadCount'] : 0,
                        "timeStamp" => isset($contactData['timeStamp']) ? $contactData['timeStamp'] : 0
                    ];
                }
            }
        }

        if (!empty($messages)) {

            foreach ($messages as $message) {
                if (isset($message['group'])  && ($message['group'] == "true" || $message['group'] == true)) {
                    $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles');
                    $profiles = $reference->getValue();
                    if ($profiles) {

                        foreach ($profiles as $key => $profile) {
                            if ($profile['id'] == $userId) {
                                $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles/' . $key);
                                $reference->update($updateGroupData);
                                break;
                            }
                        }
                    }
                } else {
                    if (isset($message['contactId'])) {
                        $reference = $this->firebase->getReference('overview/' . $message['contactId'] . '/' . $message['conversationId']);
                        if ($reference) {
                            $reference->update($updateData);
                        }
                    }
                }
            }
        }

        if ($messages) {
            uasort($messages, function ($a, $b) {
                // Check if either of the items has 'isPin' set to '1'
                $isPinA = isset($a['isPin']) && $a['isPin'] == '1';
                $isPinB = isset($b['isPin']) && $b['isPin'] == '1';

                // If both have the same 'isPin' status, sort by 'timeStamp'
                if ($isPinA == $isPinB) {
                    $timeStampA = isset($a['timeStamp']) ? $a['timeStamp'] : PHP_INT_MAX;
                    $timeStampB = isset($b['timeStamp']) ? $b['timeStamp'] : PHP_INT_MAX;
                    return $timeStampB <=> $timeStampA;
                }

                // Otherwise, prioritize the item with 'isPin' set to '1'
                return $isPinB <=> $isPinA;
            });
        }

        // return response()->json($message);
        return view('front.chat.getUserByName', compact('messages'));
    }



    // public function get_user_by_name(Request $request)
    // {
    //     $userId = auth()->id();
    //     $userData = User::findOrFail($userId);

    //     // dd($userData);
    //     $userName =  $userData->firstname . ' ' . $userData->lastname;
    //     $updateData = [
    //         'userChatId' => '',
    //         'userCountryCode' => (string)$userData->country_code,
    //         'userGender' => (string)$userData->gender,
    //         'userEmail' => $userData->email,
    //         'userId' => (string)$userId,
    //         'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
    //         'userName' => $userName,
    //         'userPhone' => (string)$userData->phone_number,
    //         'userProfile' => url('/public/storage/profile/' . $userData->profile),
    //         'userStatus' => 'Online',
    //         'userTypingStatus' => 'Not typing...'
    //     ];

    //     // Create a new user node with the userId
    //     $userRef = $this->usersReference->getChild((string)$userId);
    //     $userSnapshot = $userRef->getValue();
    //     $updateFirebase = false;

    //     // if ($userSnapshot) {
    //     //     if ($userSnapshot['userName'] != $userData->firstname . ' ' . $userData->lastname || $userSnapshot['userProfile'] != url('/public/storage/profile/' . $userData->profile)) {
    //     //         $updateFirebase = true;
    //     //     }
    //     //     // User exists, update the existing data
    //     //     $userRef->update($updateData);
    //     // } else {
    //     //     // User does not exist, create a new user node
    //     //     $userRef->set($updateData);
    //     // }

    //     $reference = $this->firebase->getReference('overview/' . $userId);
    //     $messages = $reference->getValue();
    //     $updateData = [
    //         'contactName' => $userName,
    //         'receiverProfile' => url('/public/storage/profile/' . $userData->profile)
    //     ];
    //     $updateGroupData = [
    //         'name' => $userName,
    //         'image' => url('/public/storage/profile/' . $userData->profile)
    //     ];
    //     if ($updateFirebase == true) {
    //         if (!empty($messages)) {

    //             foreach ($messages as $message) {
    //                 if (isset($message['group'])  && ($message['group'] == "true" || $message['group'] == true)) {
    //                     $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles');
    //                     $profiles = $reference->getValue();
    //                     if ($profiles) {

    //                         foreach ($profiles as $key => $profile) {
    //                             if ($profile['id'] == $userId) {
    //                                 $reference = $this->firebase->getReference('Groups/' . $message['conversationId'] . '/groupInfo/profiles/' . $key);
    //                                 $reference->update($updateGroupData);
    //                                 break;
    //                             }
    //                         }
    //                     }
    //                 } else {
    //                     if (isset($message['contactId'])) {
    //                         $reference = $this->firebase->getReference('overview/' . $message['contactId'] . '/' . $message['conversationId']);
    //                         if ($reference) {
    //                             $reference->update($updateData);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     if ($messages) {
    //         uasort($messages, function ($a, $b) {
    //             // Check if either of the items has 'isPin' set to '1'
    //             $isPinA = isset($a['isPin']) && $a['isPin'] == '1';
    //             $isPinB = isset($b['isPin']) && $b['isPin'] == '1';

    //             // If both have the same 'isPin' status, sort by 'timeStamp'
    //             if ($isPinA == $isPinB) {
    //                 $timeStampA = isset($a['timeStamp']) ? $a['timeStamp'] : PHP_INT_MAX;
    //                 $timeStampB = isset($b['timeStamp']) ? $b['timeStamp'] : PHP_INT_MAX;
    //                 return $timeStampB <=> $timeStampA;
    //             }

    //             // Otherwise, prioritize the item with 'isPin' set to '1'
    //             return $isPinB <=> $isPinA;
    //         });
    //     }
    //     // dd($messages);
    //     $title = 'Home';
    //     $page = 'front.chat.messages';
    //     $css = 'message.css';
    //     $css1 = 'audio.css';
    //     if ($messages == null) {
    //         $messages = [];
    //     }
    //     return view('layout', compact(
    //         'title',
    //         'page',
    //         'css',
    //         'css1',
    //         'messages',
    //         'userId',
    //         'userName'
    //     ));
    // }


    public function sendMessage(Request $request)
    {
        $message = [
            'sender' => $request->input('sender'),
            'message' => $request->input('message'),
            'timestamp' => now()->timestamp,
        ];

        $this->chatRoom->push($message);

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function getMessages($userId1, $userId2)
    {
        $messagesRef = $this->firebase->getReference('Messages');
        $messages = $messagesRef->getValue();

        $result = [];

        foreach ($messages as $key => $message) {
            if (isset($message['users']) && in_array($userId1, $message['users']) && in_array($userId2, $message['users'])) {
                $result[$key] = $message;
            }
        }

        return $result;
    }

    public function getChat(Request $request)
    {
        $userId = auth()->id();
        // $chatLists = $this->getMessages($userId, $request->user_id);
        $chatLists = $this->getMessages($userId, $request->user_id);

        // dd($chatLists);
        return view('front.chat.ajaxList', compact('chatLists', 'userId'));
    }
    public function getConversation(Request $request)
    {
        $message = $request->messages;
        return view('front.chat.conversationList', compact('message'));
    }

    public function autocomplete(Request $request)
    {
        $search = $request->get('term');
        $selectedUserIds = $request->get('selectedUserIds', []);
        $currentUserId = auth()->id();
        $profilePath = asset('storage/profile/');

        $users = User::select(
            'id',
            DB::raw("CONCAT(firstname, ' ', lastname) as name"),
            DB::raw("CASE WHEN profile IS NOT NULL THEN CONCAT('$profilePath/', profile) ELSE NULL END as profile"),
            'email'
        )
            ->where(DB::raw("CONCAT(firstname, ' ', lastname)"), 'LIKE', '%' . $search . '%')
            ->where('id', '!=', $currentUserId)
            // ->where('app_user', '=', '1')
            ->where('email', '!=', '')
            ->where('message_privacy', '=', '1')
            ->whereNotIn('id', $selectedUserIds) // Filter out selected users
            ->get();

        return response()->json($users);
    }

    public function chatReport(Request $request)
    {
        $user = Auth::guard('web')->user();

        // ✅ Correct Validation Rules
        $request->validate([
            'to_be_reported_user_id' => 'required|integer|exists:users,id',
            'report_conversation_id' => 'required|string',
            'report_type' => 'required|string',
            'report_description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // ✅ Create Report
            $reportCreate = new UserReportChat();
            $reportCreate->reporter_user_id = $user->id;
            $reportCreate->to_be_reported_user_id = $request->to_be_reported_user_id;
            $reportCreate->conversation_id = $request->report_conversation_id;
            $reportCreate->report_type = $request->report_type;
            $reportCreate->report_description = $request->report_description;
            $reportCreate->save();

            DB::commit();

            // ✅ Fetch Report Data
            $getName = UserReportChat::with(['reporter_user', 'to_reporter_user'])
                ->where('id', $reportCreate->id)
                ->first();

            // ✅ Prepare Email Data
            $data = [
                'reporter_username' => $getName->reporter_user->firstname . ' ' . $getName->reporter_user->lastname,
                'reported_username' => $getName->to_reporter_user->firstname . ' ' . $getName->to_reporter_user->lastname,
                'report_type' => $request->report_type,
                'report_description' => $request->report_description,
                'report_time' => $reportCreate->created_at->format('Y-m-d h:i A'),
                'report_from' => "chat"
            ];

            // ✅ Send Email
            Mail::send('emails.reportEmail', ['userdata' => $data], function ($messages) {
                $messages->to(env('SUPPORT_MAIL'))
                    ->subject('Chat Report Mail');
            });

            return redirect('messages')->with('msg', 'Report submitted successfully!');
        } catch (QueryException $e) {


            DB::rollBack();
            return redirect('messages')->with('msg_error', 'Database error occurred!');
        } catch (\Exception $e) {

            return redirect('messages')->with('msg_error', 'Something went wrong!');
        }
    }

    public function sendAppLink(Request $request){
        $user_data = Auth::guard('web')->user();
        $reciever_name=$user_data->firstname.' '.$user_data->lastname;
        $userdata = ['send_by' => $reciever_name];
        // dd($user_data,$request->userId);
        // $email=$request->email;

        $send_by=$request->send_by;
    

        $user = User::where('id', $request->userId)->first();
        $email=$user->email;
        if (isset($user->id)) {
            $user_id = $user->id;

            try {
                $checkNotificationSetting = checkNotificationSetting($user_id);
                if (count($checkNotificationSetting) != 0 && $checkNotificationSetting['private_message']['email'] == '1') {
                    Mail::send('emails.app_inivite_link', ['userdata' => $userdata], function ($message) use ($email,$send_by) {
                        $message->to($email);
                        // $message->subject('Yesvite Invite');
                       $message->subject('Yesvite: You have a new message by ' . $send_by);

                    });
                    return response()->json(['status' => 1, 'message' => 'Mail sent successfully']);
                } elseif (count($checkNotificationSetting) == 0) {


                    add_user_firebase($user_id);    // Add User in Firebase


                    Mail::send('emails.app_inivite_link', ['userdata' => $userdata], function ($message) use ($email,$send_by) {
                        $message->to($email);
                        // $message->subject('Yesvite Invite');
                        $message->subject('Yesvite: You have a new message by ' . $send_by);

                    });
                    return response()->json(['status' => 1, 'message' => 'Mail sent successfully']);
                }
            } catch (\Exception $e) {
                return response()->json(['status' => 0, 'message' => 'Mail not sent', 'error' => $e->getMessage()]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use DB;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    protected $database;
    protected $chatRoom;
    protected $firebase;
    protected $usersReference;

    public function __construct()
    {
        $this->firebase = Firebase::database();
        $this->usersReference = $this->firebase->getReference('users');
        // $this->database = $database;
        // $this->chatRoom = $this->database->getReference();
    }
    public function index()
    {

        $userId = auth()->id();
        $userData = User::findOrFail($userId);
        // dd($userData);
        $userName =  $userData->firstname . ' ' . $userData->lastname;
        $updateData = [
            'userChatId' => '',
            'userCountryCode' => (string)$userData->country_code,
            'userGender' => 'male',
            'userEmail' => $userData->email,
            'userId' => (string)$userId,
            'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
            'userName' => $userName,
            'userPhone' => (string)$userData->phone_number,
            'userProfile' => request()->server('HTTP_HOST') . '/public/storage/profile/' . $userData->profile,
            'userStatus' => 'Online',
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

        $reference = $this->firebase->getReference('overview/' . $userId);
        $messages = $reference->getValue();
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
        $title = 'Home';
        $page = 'front.chat.messages';
        $css = 'message.css';
        if ($messages == null) {
            $messages = [];
        }
        return view('layout', compact(
            'title',
            'page',
            'css',
            'messages',
            'userId',
            'userName'
        ));
    }

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
            ->where('app_user', '=', '1')
            ->whereNotIn('id', $selectedUserIds) // Filter out selected users
            ->get();

        return response()->json($users);
    }
}

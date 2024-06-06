<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
        $userId = decrypt(session()->get('user')['id']);
        $userData = User::findOrFail($userId);
        // dd($userData);
        $updateData = [
            'userChatId' => '',
            'userCountryCode' => $userData->country_code,
            'userGender' => 'male',
            'userId' => $userId,
            'userLastSeen' => now()->timestamp * 1000, // Convert to milliseconds
            'userName' => $userData->firstname . ' ' . $userData->lastname,
            'userPhone' => $userData->phone_number,
            'userProfile' => request()->server('HTTP_HOST') . '/public/storage/profile/' . $userData->profile,
            'userStatus' => 'online',
            'userToken' => '',
            'userTypingStatus' => 'Not typing...'
        ];

        // Create a new user node with the userId
        $this->usersReference->getChild($userId)->set($updateData);
        // $messages = $this->chatRoom->getValue();
        // dd($id);
        $reference = $this->firebase->getReference('overview/' . 56);
        $messages = $reference->getValue();

        $title = 'Home';
        $page = 'front.chat.messages';
        $css = 'message.css';

        return view('layout', compact(
            'title',
            'page',
            'css',
            'messages'
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
    public function getChat(Request $request)
    {
        $message = [
            'sender' => $request->input('sender'),
            'message' => $request->input('message'),
            'timestamp' => now()->timestamp,
        ];

        $this->chatRoom->push($message);

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}

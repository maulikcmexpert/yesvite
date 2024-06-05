<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;

class ChatController extends Controller
{
    protected $database;
    protected $chatRoom;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->chatRoom = $this->database->getReference('chat');
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

    public function getMessages()
    {
        $messages = $this->chatRoom->getValue();

        return response()->json(['messages' => $messages]);
    }
}

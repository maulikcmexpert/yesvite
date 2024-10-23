<?php

namespace App\Http\Controllers;

use App\Models\user;

use Illuminate\Http\Request;

class sendNotificationController extends Controller
{
    public function index()
    {
        $title = 'send notification';
        $page = 'admin.sendNotification.sendData';
        $js = 'admin.sendNotification.notificationjs';
        return view('admin.includes.layout', compact('title', 'page', 'js'));
        // return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    public function send(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create the notification data
        $notificationData = [
            'title' => $request->input('title'),
            'message' => $request->input('message'),
        ];

        $postData = [
            'message' => $notificationData['message'],
            'title' => $notificationData['title'],
        ];
        adminNotification('broadcast_message', $postData);

        $res=  [
            'status' => 'success',
            'message' => 'Notification and mail  sent successfully!'
        ];
        return $res;
    }
}

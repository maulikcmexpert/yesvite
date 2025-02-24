<?php

namespace App\Http\Controllers;

use App\Models\user;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class sendNotificationController extends BaseController
{
    public function index()
    {
        $title = 'Send Notification';
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
        // Artisan::call('queue:work');
        adminNotification('broadcast_message', $postData);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Notification and mail  sent successfully!'
            ],
        );
    }
}

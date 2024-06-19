<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserNotificationType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Illuminate\Support\Facades\DB;

class AccountSettingController extends Controller
{



    public function index()
    {

        $id = decrypt(session()->get('user')['id']);
        $user = User::with('user_profile_privacy')->withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                }, 'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment'

            ]
        )->findOrFail($id);
        $title = 'Account Settings';
        $page = 'front.account_setting';
        $js = ['account_setting'];
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) :"";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;
        $user['photo_via_wifi'] = $user->photo_via_wifi;
        $user['show_profile_photo_only_frds'] = $user->show_profile_photo_only_frds;


        return view('layout', compact(
            'title',
            'page',
            'user',
            'js'
        ));
    }

    public function updateAccountSetting(Request $request)
    {
        try {
            $user = Auth::guard('web')->user();

            if ($request->setting == 'photo_via_wifi') {
                $user->photo_via_wifi = $request->value;
                if ($user->save()) {

                    return response()->json([
                        'status' => 1,
                        'message' => "Upload photos only via Wi-Fi changed",

                    ]);
                }
            }

            if ($request->setting == 'show_profile_photo_only_frds') {
                $user->show_profile_photo_only_frds = $request->value;
                if ($user->save()) {

                    return response()->json([
                        'status' => 1,
                        'message' => "Show profile photo only to friends changed",

                    ]);
                }
            }


            if ($request->setting == 'visible') {
                $user->visible = $request->value;
                if ($user->save()) {

                    return response()->json([
                        'status' => 1,
                        'message' => "privacy changed",

                    ]);
                }
            }
        } catch (QueryException $e) {
            DB::Rollback();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function deleteAccount()
    {
        try {
            $loginUser = Auth::guard('web')->user();

            $user = User::where('id', $loginUser->id)->first();
            $user->delete();
            Session::forget('user');

            return redirect('login');
        } catch (QueryException $e) {
            DB::Rollback();

            toastr()->error('db error');
            return redirect('account_setting');
        } catch (Exception  $e) {
            toastr()->error('something went wrong');
            return redirect('account_setting');
        }
    }


    public function notificationSetting()
    {
        try {
            $id = decrypt(session()->get('user')['id']);
            $user = User::with(['user_profile_privacy', 'user_notification_type'])->withCount(

                [
                    'event' => function ($query) {
                        $query->where('is_draft_save', '0');
                    }, 'event_post' => function ($query) {
                        $query->where('post_type', '1');
                    },
                    'event_post_comment'

                ]
            )->findOrFail($id);
            $title = 'Notification Settings';
            $page = 'front.notification_setting';
            $js = ['account_setting'];
            $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) :"";
            $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
            $date = Carbon::parse($user->created_at);
            $formatted_date = $date->format('F, Y');
            $user['join_date'] = $formatted_date;


            return view('layout', compact(
                'title',
                'page',
                'user',
                'js'
            ));
        } catch (QueryException $e) {
            DB::Rollback();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function updateNotificationSetting(Request $request)
    {
        try {
            $user = Auth::guard('web')->user();


            $notificationSetting = UserNotificationType::where(['user_id' => $user->id, 'type' => $request->type])->first();
            if ($request->setting == 'push') {
                $notificationSetting->push = $request->value;
            }

            if ($request->setting == 'email') {
                $notificationSetting->email = $request->value;
            }

            if ($notificationSetting->save()) {

                return response()->json([
                    'status' => 1,
                    'message' => "Notification set successfully",

                ]);
            }
        } catch (QueryException $e) {
            DB::Rollback();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }

    public function messagePrivacy()
    {


        try {
            $id = decrypt(session()->get('user')['id']);
            $user = User::with(['user_profile_privacy', 'user_notification_type'])->withCount(

                [
                    'event' => function ($query) {
                        $query->where('is_draft_save', '0');
                    }, 'event_post' => function ($query) {
                        $query->where('post_type', '1');
                    },
                    'event_post_comment'

                ]
            )->findOrFail($id);
            $title = 'Message Privacy';
            $page = 'front.message_privacy';
            $js = ['account_setting'];
            $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) :"";
            $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
            $date = Carbon::parse($user->created_at);
            $formatted_date = $date->format('F, Y');
            $user['join_date'] = $formatted_date;


            return view('layout', compact(
                'title',
                'page',
                'user',
                'js'
            ));
        } catch (QueryException $e) {
            DB::Rollback();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }


    public function updateMessagePrivacy(Request $request)
    {

        try {


            $user = Auth::guard('web')->user();
            $user->message_privacy = $request->message_privacy;

            if ($user->save()) {

                return response()->json([
                    'status' => 1,
                    'message' => "Message Privacy set successfully",

                ]);
            }
        } catch (QueryException $e) {
            DB::Rollback();

            return response()->json(['status' => 0, 'message' => "db error"]);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => "something went wrong"]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Coin_transactions;
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

class AccountSettingController extends BaseController
{



    public function index()
    {

        $id = Auth::guard('web')->user()->id;
        $user = User::with('user_profile_privacy')->withCount(

            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment',
                'user_subscriptions' => function ($query) {
                    $query->orderBy('id', 'DESC')->limit(1);
                }


            ]
        )->findOrFail($id);
        $title = 'Account Settings';
        $page = 'front.account_setting';
        $js = ['account_setting'];
        $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
        $user['bg_profile'] = ($user->bg_profile != null) ? asset('storage/bg_profile/' . $user->bg_profile) : asset('assets/front/image/Frame 1000005835.png');
        $date = Carbon::parse($user->created_at);
        $formatted_date = $date->format('F, Y');
        $user['join_date'] = $formatted_date;
        $user['photo_via_wifi'] = $user->photo_via_wifi;
        $user['show_profile_photo_only_frds'] = $user->show_profile_photo_only_frds;

        $user['subscribe_status'] = checkSubscription($user->id);




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
            // $id = decrypt(session()->get('user')['id']);
            $id = Auth::guard('web')->user()->id;

            $user = User::with(['user_profile_privacy', 'user_notification_type'])->withCount(

                [
                    'event' => function ($query) {
                        $query->where('is_draft_save', '0');
                    },
                    'event_post' => function ($query) {
                        $query->where('post_type', '1');
                    },
                    'event_post_comment'

                ]
            )->findOrFail($id);
            $title = 'Notification Settings';
            $page = 'front.notification_setting';
            $js = ['account_setting'];
            $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
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
            $id = Auth::guard('web')->user()->id;
            $user = User::with(['user_profile_privacy', 'user_notification_type'])->withCount(

                [
                    'event' => function ($query) {
                        $query->where('is_draft_save', '0');
                    },
                    'event_post' => function ($query) {
                        $query->where('post_type', '1');
                    },
                    'event_post_comment'

                ]
            )->findOrFail($id);
            $title = 'Message Privacy';
            $page = 'front.message_privacy';
            $js = ['account_setting'];
            $user['profile'] = ($user->profile != null) ? asset('storage/profile/' . $user->profile) : "";
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

    public function transactions()
    {
        $title = 'Transaction';
        $page = 'front.transaction';
        $id = Auth::guard('web')->user()->id;

        $user = User::with('user_profile_privacy')->withCount(
            [
                'event' => function ($query) {
                    $query->where('is_draft_save', '0');
                },
                'event_post' => function ($query) {
                    $query->where('post_type', '1');
                },
                'event_post_comment',
                'user_subscriptions' => function ($query) {
                    $query->orderBy('id', 'DESC')->limit(1);
                }


            ]
        )->findOrFail($id);

        $groupCount = Coin_transactions::with(['users', 'event', 'user_subscriptions'])->where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->count();
        //  $total_page = ceil($groupCount / 20);



        $groupList = Coin_transactions::with(['users', 'event', 'user_subscriptions'])->where('user_id', $id)
            ->orderBy('id', 'DESC')
            // ->limit(20)
            ->get();
        // dd($groupList);

        $transcation = [];
        foreach ($groupList as $value) {
            $group['id'] = $value->id;
            $group['type'] = $value->type;
            $group['coins'] = $value->coins;
            $group['current_balance'] = $value->current_balance;
            $group['description'] = $value->description;
            $group['event_name'] = (isset($value->event->event_name) && $value->event->event_name != '') ? $value->event->event_name : '';
            $group['date'] = Carbon::parse($value->created_at)->format('M d, Y');
            $group['time'] = Carbon::parse($value->created_at)->format('g:i A');
            $transcation[] = $group;
        }


        $lastSevenMonths = collect();
        for ($i = 6; $i >= 0; $i--) {
            $lastSevenMonths->push(Carbon::now()->subMonths($i)->format('M'));
        }

        $transactionData = Coin_transactions::selectRaw('
                DATE_FORMAT(created_at, "%b") as month, 
                current_balance
            ')
            ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->where('user_id', $id)
            ->whereIn('id', function ($query) use ($id) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('coin_transactions')
                    ->whereRaw('user_id = ?', [$id])
                    ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'));
            })
            ->pluck('current_balance', 'month');
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;

        $debitSums = Coin_transactions::selectRaw("
            SUM(CASE WHEN YEAR(created_at) = ? AND type = 'debit' THEN coins ELSE 0 END) as current_year_coins,
            SUM(CASE WHEN YEAR(created_at) = ? AND type = 'debit' THEN coins ELSE 0 END) as last_year_coins
        ", [$currentYear, $lastYear])->where('user_id', $id)->first();

        $lastBalance = 0;
        $result = $lastSevenMonths->map(function ($month) use ($transactionData, &$lastBalance) {
            $currentBalance = $transactionData->get($month, $lastBalance);
            $lastBalance = $currentBalance;
            return [
                'month' => strtoupper($month),
                'current_balance' => $currentBalance,
            ];
        });

        $lastTwoItems = $result->slice(-2)->values();
        $lastMonthBalance = (isset($lastTwoItems[0]['current_balance']) && $lastTwoItems[0]['current_balance'] != '') ? $lastTwoItems[0]['current_balance'] : 0;
        $thisMonthBalance = (isset($lastTwoItems[1]['current_balance']) && $lastTwoItems[1]['current_balance'] != '') ? $lastTwoItems[1]['current_balance'] : 0;
        $percentageIncrease = 0;
        if ($lastMonthBalance > 0) {
            // $percentageIncrease = (($thisMonthBalance - $lastMonthBalance) * 100) / $lastMonthBalance;
            $percentageIncrease = round((($thisMonthBalance - $lastMonthBalance) * 100) / $lastMonthBalance, 2);
        }

        $percentageIncreaseByYear = 0;
        if ($debitSums->last_year_coins > 0) {
            // $percentageIncreaseByYear = (($debitSums->current_year_coins - $debitSums->last_year_coins) * 100) / $debitSums->last_year_coins;
            $percentageIncreaseByYear = round((($debitSums->current_year_coins - $debitSums->last_year_coins) * 100) / $debitSums->last_year_coins, 2);
        }

        $userSubscription = User::where('id', $id)->first();

        $data = [
            'status' => 1,
            'message' => 'Coin Transactions',
            'graph_data' => $result,
            'last_month_balance' => (string)$lastMonthBalance,
            'last_month_comparison_percentage' => (string)$percentageIncrease,
            'last_year_comparison' => (string)$percentageIncreaseByYear,
            'credit_use_this_year' => (string)$debitSums->current_year_coins,
            'coins' => (int)$userSubscription->coins
        ];



        return view('layout', compact(
            'title',
            'page',
            'user',
            'transcation',
            'data'
        ));
    }
}

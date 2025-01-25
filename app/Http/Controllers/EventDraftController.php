<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


use App\Models\{
    Event
};

use Illuminate\Validation\Rule;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as Exception;
use Throwable;
class EventDraftController extends Controller
{

    public function index()
    {
        try {
            $user  = Auth::guard('web')->user();
            $subscribe_status = checkSubscription($user->id);
            $plan="";
            if($subscribe_status == false){
                $plan="Free";
            }
            else{
                $plan="Pro Year";
            }
            $profileData = [
                'id' =>  empty($user->id) ? "" : $user->id,
                'profile' =>  empty($user->profile) ?  "" : asset('storage/profile/' . $user->profile),
                'bg_profile' =>  empty($user->bg_profile) ? "" : asset('storage/bg_profile/' . $user->bg_profile),
                'firstname' => empty($user->firstname) ? "" : $user->firstname,        
                'email' => empty($user->email) ? "" : $user->email,
                'lastname' => empty($user->lastname) ? "" : $user->lastname,
                'created_at' => empty($user->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($user->created_at))),
                'subscribe_status'=>$plan
            ];
            // dd($profileData['firstname']);
            $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')->get();
            $draftEventArray = [];
            if (!empty($draftEvents) && count($draftEvents) != 0) {
                foreach ($draftEvents as $value) {
                    $eventDetail['id'] = $value->id;
                    $eventDetail['event_name'] = ($value->event_name!="")?$value->event_name:"No name";
                    // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                    // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y - g:i A');
                    $deviceTimezone = request()->header('X-User-Timezone'); 

                    // dd($deviceTimezone);
                    $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)
                    ->setTimezone('Asia/Kolkata') // Set your desired timezone here
                    ->format('F j, Y - g:i A');

                    $eventDetail['saved_date'] = $$value->updated_at;
                    $eventDetail['step'] = ($value->step != NULL) ? $value->step : 0;
                    $eventDetail['event_plan_name'] = $value->subscription_plan_name;

                    $draftEventArray[] = $eventDetail;
                }
                $eventDraftdata= $draftEventArray;
            } else {
                $eventDraftdata= [];
            }
            // return compact(
            //         'eventDraftdata',
            //         'profileData', 
            //     );

            $title = 'Drafts';
            $js = ['event'];
            $page = 'front.event_drafts';
            return view('layout', compact(
                'title',
                'page','js',
                            'profileData','eventDraftdata'
            )); 
        } catch (QueryException $e) {
            return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            return response()->json(['status' => 0, 'message' => 'something went wrong']);
        }
        // $title = 'faq';
        // $page = 'front.faq';
        // $faqs = Faq::all();
        // //    $js = ['contact'];

        // return view('layout', compact(
        //     'title',
        //     'page',
        //     'faqs'
        // ));
    }


}

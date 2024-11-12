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

            $draftEvents = Event::where(['user_id' => $user->id, 'is_draft_save' => '1'])->orderBy('id', 'DESC')->get();
            $draftEventArray = [];
            if (!empty($draftEvents) && count($draftEvents) != 0) {

                foreach ($draftEvents as $value) {
                    $eventDetail['id'] = $value->id;
                    $eventDetail['event_name'] = $value->event_name;
                    $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('F j, Y');
                    $eventDetail['saved_date'] = $formattedDate;
                    $eventDetail['step'] = ($value->step != NULL) ? $value->step : 0;

                    $draftEventArray[] = $eventDetail;
                }
    
                foreach($draftEventArray as $draft){
                    dd($draft['id']);
                }
                // return response()->json(['status' => 1, 'message' => "Draft Events", "data" => $draftEventArray]);
            } else {
                // return response()->json(['status' => 0, 'message' => "No Draft Events", "data" => $draftEventArray]);
            }
        } catch (QueryException $e) {

            // return response()->json(['status' => 0, 'message' => 'db error']);
        } catch (Exception  $e) {
            // return response()->json(['status' => 0, 'message' => 'something went wrong']);
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

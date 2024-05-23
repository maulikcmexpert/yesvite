<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

use Carbon\Carbon;

class HomeFrontController extends Controller
{
    public function index()
    {

        $eventData = Event::with('user')->where('is_draft_save', '1')->get();
        $currentDate = Carbon::now()->toDateString();
        if (count($eventData) != 0) {

            foreach ($eventData as $value) {
                echo $value->created_at;
                $dateAfterSevenDays = Carbon::parse($value->created_at)->addDays(7)->toDateString();
                echo $dateAfterSevenDays;
                exit;
            }
        }


        $title = 'Home';
        $page = 'front.homefront';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
}

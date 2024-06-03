<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Services\CSVImportService;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {

        $eventData = Event::with(['user', 'event_schedule', 'event_image'])
            ->where('start_date', '>', now())
            ->where(['is_draft_save' => '0'])->get();

        $currentDate = Carbon::now()->toDateString();
        $eventsWithDayDifference = $eventData->map(function ($event) use ($currentDate) {
            // Calculate the difference in days
            $daysDifference = $currentDate->diffInDays(Carbon::parse($event->start_date), false);

            // Add the days difference to the event object (optional)
            $event->days_difference = $daysDifference;

            return $event;
        });

        dd($eventsWithDayDifference);
        // $title = 'Home';
        // $page = 'front.home';
        // return view('layout', compact(
        //     'title',
        //     'page',
        // ));
    }


    public function importCSV(Request $request, CSVImportService $importService)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Validate file type and size
        ]);

        if ($validator->fails()) {
            // Validation failed
            $errors = $validator->errors()->first();
            // Handle the validation errors, log them, or return a response
            return  redirect()->route('home')->with('error', $errors);
        }

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filePath =  $file->move(public_path('temp'),  $file->getClientOriginalName());
        }
        $filePath = public_path('temp/' . $file->getClientOriginalName()); // Adjust path to your CSV file
        $importService->import($filePath);

        return  redirect()->route('home')->with('success', 'Contact imported successfully.');
    }
}

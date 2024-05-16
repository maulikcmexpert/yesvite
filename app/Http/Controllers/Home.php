<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Models\User;

class Home extends Controller
{


    public function index()
    {


        $title = 'Home';
        $page = 'front.home';
        return view('layout', compact(
            'title',
            'page',
        ));
    }
    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Validate file type and size
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            // Store the file temporarily
            $filePath =  $file->move(public_path('temp'),  $file->getClientOriginalName());

            // Parse CSV and store data in the database
            $this->parseAndStoreCSV($filePath);

            // Optionally, you can delete the temporary file after processing
            $imagePath = public_path('temp/' . $file->getClientOriginalName() . '.csv');
            unlink($imagePath);

            return redirect()->back()->with('success', 'File uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Please select a file to upload.');
    }


    private function parseAndStoreCSV($filePath)
    {
        // Parse CSV and store data in the database
        $csvData = array_map('str_getcsv', file(public_path('temp/' . $filePath)));

        // Skip the header row (assuming the first row contains column headers)
        $headers = array_shift($csvData);
        $parent_userid =  decrypt(Session::get('user')['id']);
        foreach ($csvData as $row) {
            // Assuming each row contains data to be stored in the database
            // Modify this part according to your CSV structure and database schema
            $checkUserExist = User::where('phone_number', $row[3])->first();
            if ($checkUserExist == null) {

                $addUser = new User();
                $addUser->firstname = $row[0];
                $addUser->lastname = $row[1];
                $addUser->country_code = $row[2];
                $addUser->phone_number = $row[3];
                $addUser->app_user =  '0';
                $addUser->prefer_by =  'phone';
                $addUser->user_parent_id =  $parent_userid;
                $addUser->is_user_phone_contact =  '1';
                $addUser->parent_user_phone_contact =  $parent_userid;
                $addUser->save();
            }
        }
    }
}

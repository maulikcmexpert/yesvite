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
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file);
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            dd($rows);
            // foreach ($rows as $row) {
            //     YourModelName::create([
            //         'column1' => $row[0], // Assuming CSV has data in this format
            //         'column2' => $row[1],
            //         // Add more columns as needed
            //     ]);
            // }

            return redirect()->back()->with('success', 'CSV data has been imported successfully');
        }
        return redirect()->back()->with('error', 'No CSV file found');
    }


    private function parseAndStoreCSV($filePath)
    {
        // Parse CSV and store data in the database
        $csvData = array_map('str_getcsv', file(storage_path('app/' . $filePath)));

        // Skip the header row (assuming the first row contains column headers)
        $headers = array_shift($csvData);

        foreach ($csvData as $row) {
            // Assuming each row contains data to be stored in the database
            // Modify this part according to your CSV structure and database schema
            User::create([
                'firstname' => $row[0],
                'lastname' => $row[1],
                'country_code' => $row[2],
                'phone_number' => $row[3],
                'app_user' => '0',
                'prefer_by' => 'phone',
                'user_parent_id' => 'phone',
                // Add more columns as needed
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

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
}

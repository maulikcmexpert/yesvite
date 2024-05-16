<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Models\User;

use App\Services\CSVImportService;

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


    public function importCSV(Request $request, CSVImportService $importService)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048', // Validate file type and size
        ]);
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filePath =  $file->move(public_path('temp'),  $file->getClientOriginalName());
        }
        $filePath = public_path('temp/' . $file->getClientOriginalName()); // Adjust path to your CSV file
        $importService->import($filePath);

        return  redirect()->route('home')->with('success', 'Contact imported successfully.');
    }
}

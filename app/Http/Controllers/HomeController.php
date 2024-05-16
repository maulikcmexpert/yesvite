<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Services\CSVImportService;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\TextData;

class DesignController extends Controller
{
    public function index()
    {

        $title = 'Create Event';
        $page = 'front.edit-design';
        $textData = DB::table('text_data')
            ->orderBy('id', 'desc')
            ->first();

        return view('event_layout', compact(
            'title',
            'page',
            'textData'


        ));
    }


    public function AllImage()
    {




        return view('templates.view-image');
    }
    public function viewAllImages()
    {

        $images = TextData::all();


        return view('templates.view-image', compact('images'));
    }
    public function uploadImage(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048', // Adjust validation as needed
        ]);

        // Get the uploaded file
        $file = $request->file('image');

        // Generate a unique file name for the image
        $imageName = 'canvas_image_' . time() . '.' . $file->getClientOriginalExtension();

        // Move the file to the desired directory
        $file->move(public_path('assets/images'), $imageName);

        // Return the image name as plain text
        return response($imageName, 200)->header('Content-Type', 'text/plain');
    }

    public function saveData(Request $request)
    {

        // Validate the image name
        $request->validate([
            'image' => 'required|string', // Ensure it's a string
        ]);

        // Get the image name from the request
        $imageName = $request->input('image');

        // Save the image name in the database
        // Assume `TextData` has an `image` column to store the image name
        $textData = TextData::create([
            'image' => $imageName // Save the image name directly
        ]);


        return response()->json(['message' => 'Image name saved successfully', 'id' => $textData->id]);
    }
    public function user_image(Request $request)
    {
        // dd($request);
        // Validate the image file (optional, uncomment if needed)
        // $request->validate([
        //     'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        // ]);

        // Get the file and id from the request
        $file = $request->file('image');
        // print_r($file);
        // exit;
        $id = $request->id;
        $shape = $request->shape;
        $imageName = 'user_image_' . time() . '.' . $file->getClientOriginalExtension(); // Assuming PNG format
        $file->move(public_path('storage/canvas'), $imageName);
        $imagePath = asset('storage/canvas/' . $imageName);
        // $textElements = [
        //     [
        //         'shape' => $shape // Add shape information
        //     ],
        // ];


        // Save the image name directly in the `filed_image` column


        // $textData = TextData::where('id', $id)->first();
        // $existingData = $textData->static_information;

        // if (!isset($existingData['textElements'])) {
        //     $existingData['textElements'] = [];
        // }

        // $existingData['textElements'][] = [
        //     'shape' => $shape,   // Add shape information
        // ];
        TextData::where('id', $id)->update([
            'shape_image' => $imageName,
            // 'static_information' => $existingData,
        ]);



        // Return a JSON response
        return response()->json(['message' => 'Image saved successfully', 'imagePath' => $imagePath]);
    }


    public function save_shape(Request $request)
    {

        $id = $request->id;
        $shape = $request->shape;
        $centerX = $request->centerX;
        $centerY = $request->centerY;
        $height = $request->height;
        $width = $request->width;
        $textData = TextData::where('id', $id)->first();
        $existingData = $textData->static_information;

        // $existingData['shapeImageData'] = [];

        if (!isset($existingData['shapeImageData'])) {
            $existingData['shapeImageData'] = [];
        }
        dd($existingData['shapeImageData']);

        $existingData['shapeImageData'][] = [
            'shape' => $shape,
            'centerX' => $centerX,
            'centerY' => $centerY,
            'height' => $height,
            'width' => $width
        ];

        if ($centerY != "" && $centerX != "" && $height != "" && $width != "") {
            TextData::where('id', $id)->update([
                'static_information' => $existingData,
            ]);
        }
        return response()->json(['message' => 'Shape and position saved successfully']);
    }

    // public function save_shape(Request $request)
    // {
    //     $id = $request->id;
    //     $shape = $request->shape;
    //     $centerX = $request->centerX; // Capture the centerX position
    //     $centerY = $request->centerY; // Capture the centerY position
    //     $height = $request->height;
    //     $width = $request->width;

    //     // Fetch the current 'static_information' from the database
    //     $textData = TextData::where('id', $id)->first();

    //     // Decode the existing 'static_information' JSON
    //     $existingData = json_decode($textData->static_information, true);

    //     // If 'textElements' doesn't exist, initialize it as an empty array
    //     if (!isset($existingData['textElements'])) {
    //         $existingData['textElements'] = [];
    //     }

    //     // Append the new shape data along with position to the 'textElements' array
    //     $existingData['textElements'][] = [
    //         'shape' => $shape,      // Add shape information
    //         'centerX' => $centerX,  // Add centerX position
    //         'centerY' => $centerY,  // Add centerY position
    //         'height' => $height,    // Add height
    //         'width' => $width       // Add width
    //     ];

    //     // If 'shapeImageData' doesn't exist, initialize it
    //     if (!isset($existingData['shapeImageData'])) {
    //         $existingData['shapeImageData'] = [];
    //     }

    //     // Append the new shapeImageData (for shapes like stars) to 'shapeImageData' array
    //     $existingData['shapeImageData'][] = [
    //         'shape' => $shape,
    //         'centerX' => $centerX,
    //         'centerY' => $centerY,
    //         'height' => $height,
    //         'width' => $width
    //     ];

    //     // Save the updated array back to the database
    //     TextData::where('id', $id)->update([
    //         'static_information' => json_encode($existingData),
    //     ]);

    //     // Return JSON response
    //     return response()->json(['message' => 'Shape and position saved successfully']);
    // }


    public function saveTextData(Request $request)
    {

        // dd($request);
        // Validate incoming request data
        $validated = $request->validate([
            'id' => 'required|integer',
            'textElements' => 'required|array',
            'shapeImageData' => 'required|array',
        ]);



        // Find the template record by ID
        $template = TextData::find($validated['id']);

        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        // Convert text elements array to JSON
        $staticInformation = [
            'textElements' => $validated['textElements'],
            'shapeImageData' => $validated['shapeImageData'],
        ];

        // Update the template record
        $template->static_information = $staticInformation;

        // Save the updated record
        $template->save();

        return response()->json(['message' => 'Data saved successfully'], 200);
    }
    // public function loadTextData($id)
    // {

    //     $data = DB::table('text_data')
    //         ->orderBy('id', 'desc')
    //         ->when($id != '', function ($query) use ($id) {
    //             $query->where('id', $id);
    //         })
    //         ->first();


    //     if ($data) {
    //         $id = $data->id;
    //         $imageName = $data->image;
    //         $static_information = $data->static_information;

    //         if (isset($imageName)) {

    //             $imagePath = asset('storage/canvas/' . $imageName);


    //             return response()->json([
    //                 'imagePath' => $imagePath,
    //                 'id' => $id,
    //                 'static_information' => $static_information,

    //             ]);
    //         }
    //     }

    //     // Return an empty response if no data or image path is found
    //     return response()->json(null);
    // }

    // public function loadTextData($id)
    // {


    //     // $data = DB::table('text_data')
    //     //     ->orderBy('id', 'desc')
    //     //     // ->when($id != '', function ($query) use ($id) {
    //     //     //     $query->where('id', $id);
    //     //     // })
    //     //     ->first();

    //     $data = TextData::orderBy('id', 'desc')
    //         ->when($id != '', function ($query) use ($id) {
    //             $query->where('id', $id);
    //         })
    //         ->first();


    //     if ($data) {
    //         $id = $data->id;
    //         $imageName = $data->image;
    //         $filled_image  = $data->filled_image;
    //         $static_information = $data->static_information;

    //         if (isset($imageName) && $filled_image != "") {
    //             $imagePath = asset('storage/canvas/' . $imageName);
    //         } else {
    //             $imagePath = null;
    //         }

    //         // Check if the filed_image exists
    //         if (isset($filled_image) && $filled_image != "") {
    //             $filedImagePath = asset('storage/canvas/' . $filled_image);
    //         } else {
    //             $filedImagePath = null;
    //         }

    //         // Return all data in the response
    //         return response()->json([
    //             'id' => $id,
    //             'image' => $imagePath,
    //             'filled_image' => $filedImagePath,
    //             'static_information' => $static_information
    //         ]);
    //     }

    //     // Return an empty response if no data or image path is found
    //     return response()->json(null);
    // }

    public function loadTextData($id)
    {
        $data = DB::table('text_data')
            ->orderBy('id', 'desc')
            ->when($id != '', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();


        if ($data) {
            $id = $data->id;
            $imageName = $data->image;
            $filed_image  = $data->shape_image;
            $static_information = $data->static_information;

            if (isset($imageName)) {
                $imagePath = asset('storage/canvas/' . $imageName);
            } else {
                $imagePath = null;
            }

            if (isset($filed_image)) {
                $filedImagePath = asset('storage/canvas/' . $filed_image);
            } else {
                $filedImagePath = null;
            }
            return response()->json([
                'id' => $id,
                'imagePath' => $imagePath,
                'filedImagePath' => $filedImagePath,
                'static_information' => $static_information
            ]);
        }
        return response()->json(null);
    }

    public function loadAllData()
    {
        $data = DB::table('text_data')
            ->orderBy('id', 'desc')
            ->get();  // Fetch all records

        // Return all data as JSON
        // dd($data);
        return response()->json($data);
    }
}

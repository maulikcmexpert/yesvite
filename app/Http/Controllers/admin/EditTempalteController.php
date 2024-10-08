<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TextData;

class EditTempalteController extends Controller
{
    public function index(string $id)
    {
        $id = decrypt($id);

        $title = 'Create Event';
        $page = 'admin.create_template.edit-design';
        $js = [
            'admin.create_template.editjs',
            // 'admin.create_template.imageEditorjs',
            'admin.create_template.shapejs'
        ];
        // $textData =  $textData = DB::table('text_data')
        //     ->where('id', $id)
        //     ->first();

        $textData = TextData::where('id', $id)->get()->first();
        // ->where('id', $id)
        // ->first();

        return view('admin.includes.layout', compact(
            'title',
            'page',
            'textData',
            'js',
            'id'
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




        // $request->validate([
        //     'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        // ]);

        //     $file = $request->file('image');

        //     // Create a unique image name
        //     $imageName = 'user_image_' . time() . '.' . $file->getClientOriginalExtension(); // Assuming PNG format


        //     $file->move(public_path('assets/user/images'), $imageName);
        //     $imagePath = public_path('assets/user/images/'.$imageName);

        //     // Save the image

        //         // Save image path in database
        //         TextData::create([
        //             'image' => $imageName // Store relative path as JSON
        //         ]);



        //         return response()->json(['message' => 'Image saved successfully', 'imagePath' => 'assets/user/images/'.$imageName]);
        $file = $request->file('image');
        $id = $request->id;

        // Create a unique image name
        $imageName = 'user_image_' . time() . '.' . $file->getClientOriginalExtension(); // Assuming PNG format
        $imageData = [
            [
                'image' => $imageName

            ],

        ];






        $file->move(public_path('assets/user/images'), $imageName);
        $imagePath = public_path('assets/user/images/' . $imageName);

        // Save the image
        TextData::where('id', $id)->update([
            'filed_image' => json_encode($imageData),

        ]);

        // Define your textElements array


        // Return JSON response
        return response()->json(['message' => 'Image saved successfully', 'imagePath' => 'assets/user/images/' . $imageName]);
    }

    public function saveTextData(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'id' => 'required|integer',
            'textElements' => 'required|array',

        ]);

        // Find the template record by ID
        $template = TextData::find($validated['id']);

        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        // Convert text elements array to JSON
        $staticInformation = [
            'textElements' => $validated['textElements'],

        ];

        // Update the template record
        $template->static_information = $staticInformation;
        $template->width = 345;
        $template->height = 490;
        // Save the updated record
        $template->save();

        return response()->json(['message' => 'Data saved successfullysss'], 200);
    }
    public function loadTextData($id)
    {


        // Fetch data from the text_data table, filter by ID if provided
        $data = DB::table('text_data')
            ->orderBy('id', 'desc')
            ->when($id != '', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();

        // Check if data is found
        if ($data) {
            $id = $data->id;
            $imageName = $data->image;
            $static_information = $data->static_information;

            // Check if the image name exists
            if (isset($imageName)) {
                // Construct the full image path using the asset() helper

                $imagePath = asset('assets/images/' . $imageName);

                // Return a JSON response with the image path, ID, and static information
                return response()->json([
                    'imagePath' => $imagePath,
                    'id' => $id,
                    'static_information' => $static_information,
                ]);
            }
        }

        // Return an empty response if no data or image path is found
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

    // public function get_all_subcategory(Request $request)
    // {
    //     dd(1);
    //     $category_id = $request->input('category_id');
    //     dd($category_id);
    // }
}

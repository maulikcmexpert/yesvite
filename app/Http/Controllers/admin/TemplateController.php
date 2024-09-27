<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EventDesignCategory;
use App\Models\EventDesignStyle;
use App\Models\EventDesignSubCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;




use App\Models\TextData;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->ajax());
        if ($request->ajax()) {
            $data = TextData::with('categories')->orderBy('id', 'desc')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('number', function ($row) {
                    static $count = 1;
                    return $count++;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->categories->category_name;
                })
                ->addColumn('subcategory_name', function ($row) {
                    return $row->subcategories->subcategory_name;
                })
                ->addColumn('image', function ($template) {
                    return '<img src="' . asset('storage/canvas/' . $template->image) . '" width="50" height="50" />';
                })
                ->addColumn('action', function ($row) {
                    $cryptId = encrypt($row->id);
                    $edit_url = route('create_template.edit', $cryptId);
                    $delete_url = route('create_template.destroy', $cryptId);
                    $template_url = route('create_template.edit_template', $cryptId);
                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                      <a class="" href="' . $template_url . '" title="Edit"><i class="fa fa-eye"></i></a>
                        </div>';
                    return $actionBtn;
                })

                ->rawColumns(['number', 'category_name', 'subcategory_name', 'image', 'action'])
                ->make(true);
        }


        $title = 'Design Style';

        $page = 'admin.create_template.list';

        $js = 'admin.create_template.templatejs';
        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Template';
        $page = 'admin.create_template.add';
        $js = 'admin.subcategory.subcategoryjs';
        $getDesignData = EventDesignCategory::all();
        $getsubcatData = EventDesignSubCategory::all();
        return view('admin.includes.layout', compact('title', 'page', 'js', 'getDesignData', 'getsubcatData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            dd($request);
            DB::beginTransaction();

            // Initialize $imageName to avoid undefined variable errors
            $imageName = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Ensure file is not null before accessing methods
                if ($image) {
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    // Save the image in the public/assets/images folder
                    $image->move(public_path('storage/canvas'), $imageName);
                }
            }
            // Store the template with design ID and the uploaded image's filename

            TextData::create([
                'image' => $imageName, // Save the uploaded image filename
            ]);
            $template_id = $textData->id;

            $textdata = TextData::where('id', $template_id);
            $textdata->event_design_category_id = $request->event_design_category_id;
            $textdata->event_design_sub_category_id = $request->event_design_sub_category_id;
            $textdata->save();

            // Log::info(DB::getQueryLog());

            DB::commit();

            return redirect()->route('create_template.index')->with('success', 'Template added successfully!');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error: ' . $e->getMessage());
            return redirect()->route('create_template.index')->with('danger', 'Something went wrong!');
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template_id = decrypt($id);

        // Get the template data by ID
        $getTemData = TextData::findOrFail($template_id);

        $title = 'Edit template';
        $page = 'admin.create_template.edit';
        $js = 'admin.create_template.templatejs';
        $subcatId = $id;

        // Get all design and subcategory data
        $getDesignData = EventDesignStyle::all();
        $getSubCatDetail = EventDesignSubCategory::all();

        // Pass the data to the view
        return view('admin.includes.layout', compact(
            'title',
            'page',
            'js',
            'getTemData',
            'getSubCatDetail',
            'subcatId',
            'getDesignData'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Begin the transaction
            DB::beginTransaction();

            // Find the template by its ID
            $template = TextData::findOrFail($id);

            // Validate the request data
            $request->validate([
                'design_id' => 'required',
                'event_design_subcategory_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // optional image validation
            ]);

            // Update the template fields
            $template->design_id = $request->design_id;
            $template->event_design_subcategory_id = $request->event_design_subcategory_id;

            // Handle image upload (if a new image is uploaded)
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($template->image && file_exists(public_path('assets/images/' . $template->image))) {
                    unlink(public_path('assets/images/' . $template->image));
                }

                // Store the new image
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('assets/images'), $imageName);
                $template->image = $imageName;
            }

            // Save the updated template data
            $template->save();

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('create_template.index')->with('success', 'Template updated successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();

            // Log the error message
            Log::error('Template Update Error: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->back()->with('error', 'Failed to update template. Error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::beginTransaction();

            $id = decrypt($id);
            $user = TextData::find($id)->delete();

            DB::commit();

            return redirect()->route('create_template.index')
                ->with('success', 'create_template deleted successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            return redirect()->route('create_template.index')
                ->with('danger', 'create_template not deleted');
        }
    }
    public function View_template($id)
    {
        // Retrieve the template by its ID
        $template = TextData::find($id);

        // Handle the case if the template is not found
        if (!$template) {
            return redirect()->route('create_template.edit_template')->with('error', 'Template not found.');
        }

        // Return the view to display the template
        return view('template.view', compact('template'));
    }
}

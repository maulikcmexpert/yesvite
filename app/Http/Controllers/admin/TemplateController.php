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

                ->addColumn('filled_image', function ($template) {
                    return '<img src="' . asset('storage/canvas/' . $template->filled_image) . '" width="50" height="50" />';
                })
                ->addColumn('action', function ($row) {
                  
                    $cryptId = encrypt($row->id);
                    $template_delete=decrypt($cryptId);
                    $category_id = encrypt($row->categories->category_name);

                    // dd($Category_id);

                    // $edit_url = route('create_template.edit', ['id' => $cryptId, 'category' => $category_id]);
                    $edit_url = route('create_template.edit', $cryptId);
                    $delete_url = route('create_template.destroy', $cryptId);
                    $template_url = route('create_template.edit_template', $cryptId);
                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form id="delete_template_form'.$template_delete.'" action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="button" data-id="'.$template_delete.'" class="btn bg-transparent delete_template"><i class="fas fa-trash"></i></button></form>
                      <a class="" href="' . $template_url . '" title="Edit"><i class="fa fa-eye"></i></a>
                        </div>';
                    return $actionBtn;
                })

                ->rawColumns(['number', 'category_name', 'subcategory_name', 'image', 'filled_image', 'action'])
                ->make(true);
        }


        $title = 'Create Design Template';

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
        $js = 'admin.create_template.templatejs';
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
            DB::beginTransaction();
            $imageName = null;
            $filledImage = null;
            $i = 0;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . $i . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/canvas'), $imageName);
                $i++;
            }
            if ($request->hasFile('filled_image')) {
                $i++;
                $filled_image = $request->file('filled_image');
                $filledImage = time() . $i . '.' . $filled_image->getClientOriginalExtension();
                $filled_image->move(public_path('storage/canvas'), $filledImage);
            }
            $textData = TextData::create([
                'image' => $imageName,
            ]);
            $textData->filled_image = $filledImage;
            $textData->event_design_category_id = $request->event_design_category_id;
            $textData->event_design_sub_category_id = $request->event_design_sub_category_id;
            $textData->save();
            DB::commit();

            return redirect()->route('create_template.edit_template', encrypt($textData->id))->with('msg', 'Template added successfully!');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error: ' . $e->getMessage());
            return redirect()->route('create_template.index')->with('msg_error', 'Something went wrong!');
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
    public function edit(String $id)
    {
        // dd($request);
        $template_id = decrypt($id);
        // $category_id= decrypt($category);

        // Get the template data by ID
        $getTemData = TextData::findOrFail($template_id);

        $title = 'Edit template';
        $page = 'admin.create_template.edit';
        $js = 'admin.create_template.templatejs';
        $subcatId = $id;

        // Get all design and subcategory data
        $getDesignData = EventDesignCategory::all();
        $getSubCatDetail = EventDesignSubCategory::where('event_design_category_id',$getTemData->event_design_category_id)->get();
        // $getSubCatDetail = EventDesignSubCategory::where('event_design_category_id',$category_id)->get();

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
                'event_design_category_id' => 'required',
                'event_design_sub_category_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // optional image validation
            ]);

            // Update the template fields
            $template->event_design_category_id = $request->event_design_category_id;
            $template->event_design_sub_category_id = $request->event_design_sub_category_id;
            $i = 0;
            // Handle image upload (if a new image is uploaded)
            if ($request->hasFile('image')) {
                if ($template->image && file_exists(public_path('storage/canvas/' . $template->image))) {
                    unlink(public_path('storage/canvas/' . $template->image));
                }
                $imageName = time() . $i . '.' . $request->image->extension();
                $i++;
                $request->image->move(public_path('storage/canvas'), $imageName);
                $template->image = $imageName;
            }
            if ($request->hasFile('filled_image')) {
                $i++;
                if ($template->filled_image && file_exists(public_path('storage/canvas/' . $template->filled_image))) {
                    unlink(public_path('storage/canvas/' . $template->filled_image));
                }
                $FilledimageName = time() . $i . '.' . $request->filled_image->extension();
                $request->filled_image->move(public_path('storage/canvas'), $FilledimageName);
                $template->filled_image = $FilledimageName;
            }

            // Save the updated template data
            $template->save();

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->route('create_template.index')->with('msg', 'Template updated successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();

            // Log the error message
            Log::error('Template Update Error: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->back()->with('msg_error', 'Failed to update template. Error: ' . $e->getMessage());
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
            // dd($id);
            $user = TextData::find($id)->delete();

            DB::commit();

            return redirect()->route('create_template.index')
                ->with('msg', 'Template deleted successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            return redirect()->route('create_template.index')
                ->with('msg_error', 'Template not deleted');
        }
    }
    public function View_template($id)
    {
        // Retrieve the template by its ID
        $template = TextData::find($id);

        // Handle the case if the template is not found
        if (!$template) {
            return redirect()->route('create_template.edit_template')->with('msg_error', 'Template not found.');
        }

        // Return the view to display the template
        return view('template.view', compact('template'));
    }

    public function get_all_subcategory(Request $request)
    {
        $category_id = $request->input('category_id');
        $sub_category = EventDesignSubCategory::where('event_design_category_id', $category_id)->get();

        $response = [];

        foreach ($sub_category as $subcategory) {
            $response[] = [
                'sub_category_id' => $subcategory->id,
                'sub_category_name' => $subcategory->subcategory_name,
            ];
        }

        return response()->json($response);
    }
}

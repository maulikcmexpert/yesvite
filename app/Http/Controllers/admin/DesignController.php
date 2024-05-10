<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


use App\Http\Requests\CreateDesignPost;
use App\Http\Requests\UpdateDesignPost;


use App\Models\EventDesign;
use App\Models\EventDesignCategory;
use App\Models\EventDesignSubCategory;
use App\Models\EventDesignStyle;
use App\Models\EventDesignColor;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
        if ($request->ajax()) {

            $data = EventDesign::with(['category', 'subcategory', 'design_style', 'design_colors'])->orderBy('id', 'desc');

            return Datatables::of($data)


                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })

                ->addColumn('category_name', function ($row) {
                    return $row->category->category_name;
                })

                ->addColumn('subcategory_name', function ($row) {
                    return $row->subcategory->subcategory_name;
                })

                ->addColumn('design_name', function ($row) {
                    return $row->design_style->design_name;
                })
                ->addColumn('templete', function ($row) {

                    $imageUrl = asset('public/storage/no_image.png');
                    if (!empty($row->image) || $row->image != null) {

                        $imageUrl = asset('public/storage/event_design_template/' . $row->image);
                    }
                    return '<div class="symbol-label">
                    <img src="' . $imageUrl . '" alt="No Image" width="50px">
                </div>';
                })

                ->addColumn('design_color', function ($row) {

                    $colorHtml = "";
                    foreach ($row->design_colors as $val) {
                        $colorHtml .= ' <div class="color-box" style="background-color:' . $val->event_design_color . ';"></div>';
                    }

                    return $colorHtml;
                })

                ->addColumn('action', function ($row) {

                    $cryptId = encrypt($row->id);

                    $edit_url = route('design.edit', $cryptId);

                    $delete_url = route('design.destroy', $cryptId);

                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                        </div>';

                    return $actionBtn;
                })
                ->rawColumns([
                    'number',
                    'category_name',
                    'subcategory_name',
                    'design_name',
                    'templete',
                    'design_color',
                    'action'
                ])
                ->make(true);
        }



        $title = 'Design';

        $page = 'admin.design.list';

        $js = 'admin.design.designjs';
        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    public function getSubCatData(Request $request)
    {
        $catId = $request->catId;

        $html = "";
        $result = EventDesignSubCategory::where('event_design_category_id', $catId)->get();
        if (count($result) != 0) {
            foreach ($result as $val) {
                $html .= '<option value="' . $val->id . '">' . $val->subcategory_name . '</option>';
            }
        }
        echo $html;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Design';
        $page = 'admin.design.add';
        $js = 'admin.design.designjs';
        $getCatData = EventDesignCategory::all();

        $getDesignStyleData = EventDesignStyle::all();
        return view('admin.includes.layout', compact('title', 'page', 'js', 'getCatData', 'getDesignStyleData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDesignPost $request)
    {


        try {

            DB::beginTransaction();

            $storeDesignTemplate = new EventDesign;


            if ($request->hasFile('image')) {

                $file = $request->file('image');


                $imageName = time() . '_' . $file->getClientOriginalName();

                $file->move(public_path('storage/event_design_template'), $imageName);
                $storeDesignTemplate->image = $imageName;
            }


            $storeDesignTemplate->event_design_category_id = $request->event_design_category_id;
            $storeDesignTemplate->event_design_subcategory_id = $request->event_design_subcategory_id;
            $storeDesignTemplate->event_design_style_id = $request->event_design_style_id;

            if ($storeDesignTemplate->save()) {
                $insertedId = $storeDesignTemplate->id;

                foreach ($request->event_design_color as $val) {

                    EventDesignColor::create([

                        'event_design_id' => $insertedId,
                        'event_design_color' => $val

                    ]);
                }
            }


            DB::commit();
            return redirect()->route('design.index')->with("success", "Design Add successfully !");
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error' . $e->getMessage());
            return redirect()->route('design.index')->with("danger", "Something went wrong !");
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
        $design_id =  decrypt($id);

        $title = 'Edit Design';
        $page = 'admin.design.edit';
        $js = 'admin.design.designjs';
        $designId = $id;
        $getDesignDetail = EventDesign::where('id', $design_id)->first();


        $getDesignColors = EventDesignColor::where('event_design_id', $design_id)->pluck('event_design_color')->toArray();

        $getDesignStyleData = EventDesignStyle::all();
        $getCatData = EventDesignCategory::all();
        return view('admin.includes.layout', compact('title', 'page', 'js', 'getCatData', 'getDesignDetail', 'getDesignColors', 'getDesignStyleData', 'designId'));
    }



    public function getSelectedSubcatdata(Request $request)
    {
        $catId = $request->catId;
        $subcat = $request->subcat;

        $html = "";
        $result = EventDesignSubCategory::where('event_design_category_id', $catId)->get();
        if (count($result) != 0) {
            foreach ($result as $val) {

                $selected = "";
                if ($subcat == $val->id) {
                    $selected = "selected";
                }

                $html .= '<option ' . $selected . ' value="' . $val->id . '">' . $val->subcategory_name . '</option>';
            }
        }
        echo $html;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignPost $request, string $id)
    {
        try {

            DB::beginTransaction();

            $designId = decrypt($id);
            $updateDesign = EventDesign::findOrFail($designId);
            if ($request->hasFile('image')) {

                if (file_exists(public_path('storage/event_design_template/') . $request->oldImage)) {
                    $imagePath = public_path('storage/event_design_template/') . $request->oldImage;
                    unlink($imagePath);
                }


                $file = $request->file('image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/event_design_template'), $imageName);

                $updateDesign->image = $imageName;
            }

            $updateDesign->event_design_category_id = $request->event_design_category_id;
            $updateDesign->event_design_subcategory_id = $request->event_design_subcategory_id;
            $updateDesign->event_design_style_id = $request->event_design_style_id;

            $updateDesign->save();


            EventDesignColor::where('event_design_id', $designId)->delete();

            foreach ($request->event_design_color as $val) {

                EventDesignColor::create([

                    'event_design_id' => $designId,
                    'event_design_color' => $val

                ]);
            }

            DB::commit();



            return redirect()->route('design.index')->with("success", "Design updated successfully !");
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Database query error' . $e->getMessage());
            return redirect()->route('design.edit', $id)->with("success", "Database query error." . $e->getMessage());
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
            $design_image = EventDesign::select('image')->find($id);
            if (Storage::disk('public')->exists('event_design_template/' . $design_image->image)) {
                Storage::disk('public')->delete('event_design_template/' . $design_image->image);
            }

            EventDesign::find($id)->delete();
            DB::commit();

            return redirect()->route('design.index')
                ->with('success', 'Design deleted successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            return redirect()->route('design.index')
                ->with('danger', 'Design not deleted');
        }
    }
}

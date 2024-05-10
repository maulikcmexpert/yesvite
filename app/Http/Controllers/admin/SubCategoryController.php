<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


use App\Http\Requests\CreateSubcategoryPost;
use App\Http\Requests\UpdateSubCategoryPost;
use App\Models\EventDesignCategory;
use App\Models\EventDesignSubCategory;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EventDesignSubCategory::with('category')->orderBy('id', 'desc')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('number', function ($row) {
                    static $count = 1;
                    return $count++;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->category_name;
                })
                ->addColumn('action', function ($row) {
                    $cryptId = encrypt($row->id);
                    $edit_url = route('subcategory.edit', $cryptId);
                    $delete_url = route('subcategory.destroy', $cryptId);
                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                        </div>';
                    return $actionBtn;
                })
                ->rawColumns(['number', 'category_name', 'action'])
                ->make(true);
        }

        $title = 'Subcategory';
        $page = 'admin.subcategory.list';
        $js = 'admin.subcategory.subcategoryjs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Subcategory';
        $page = 'admin.subcategory.add';
        $js = 'admin.subcategory.subcategoryjs';
        $getCatData = EventDesignCategory::all();
        return view('admin.includes.layout', compact('title', 'page', 'js', 'getCatData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubcategoryPost $request)
    {
        try {

            DB::beginTransaction();


            foreach ($request->subcategory_name as $catVal) {

                EventDesignSubCategory::create([
                    'event_design_category_id' => $request->event_design_category_id,
                    'subcategory_name' => $catVal,
                ]);
            }

            DB::commit();
            return redirect()->route('subcategory.index')->with("success", "Subcategory Add successfully !");
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error' . $e->getMessage());
            return redirect()->route('subcategory.index')->with("danger", "Something went wrong !");
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
        $subcategory_id =  decrypt($id);
        $title = 'Edit Subcategory';
        $page = 'admin.subcategory.edit';
        $js = 'admin.subcategory.subcategoryjs';
        $subcatId = $id;
        $getSubCatDetail = EventDesignSubCategory::where('id', $subcategory_id)->first();
        $getCatData = EventDesignCategory::all();
        return view('admin.includes.layout', compact('title', 'page', 'js', 'getCatData', 'getSubCatDetail', 'subcatId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryPost $request, string $id)
    {

        try {


            DB::beginTransaction();

            $subcategroyId = decrypt($id);

            $updateSubCategory = EventDesignSubCategory::findOrFail($subcategroyId);
            $updateSubCategory->event_design_category_id = $request->event_design_category_id;
            $updateSubCategory->subcategory_name = $request->subcategory_name;
            $updateSubCategory->save();

            DB::commit();



            return redirect()->route('subcategory.index')->with("success", "Subcategory updated successfully !");
        } catch (QueryException $e) {

            DB::rollBack();
            Log::error('Database query error' . $e->getMessage());
            return redirect()->route('subcategory.edit', $id)->with("success", "Database query error." . $e->getMessage());
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
            $user = EventDesignSubCategory::find($id)->delete();

            DB::commit();

            return redirect()->route('subcategory.index')
                ->with('success', 'Subcategory deleted successfully');
        } catch (QueryException $e) {

            DB::rollBack();
            return redirect()->route('subcategory.index')
                ->with('danger', 'Subcategory not deleted');
        }
    }

    public function checkSubCategoryIsExist(Request $request)
    {

        try {

            $category = EventDesignSubCategory::where(['subcategory_name' => $request->subcategory_name])->get();

            if (count($category) > 0) {
                if (isset($request->id) && !empty($request->id)) {

                    if ($category[0]->id == decrypt($request->id)) {

                        $return =  true;
                        echo json_encode($return);
                        exit;
                    }
                }
                $return =  false;
            } else {
                $return = true;
            }
            echo json_encode($return);
            exit;
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(false);
        }
    }
}

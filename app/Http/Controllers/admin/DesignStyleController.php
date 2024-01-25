<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use App\Http\Requests\CreateDesignStylePost;
use App\Http\Requests\UpdateDesignStylePost;


use App\Models\EventDesignStyle;

class DesignStyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
        if ($request->ajax()) {

            $data = EventDesignStyle::orderBy('id', 'desc');



            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })



                ->addColumn('action', function ($row) {

                    $cryptId = encrypt($row->id);

                    $edit_url = route('design_style.edit', $cryptId);

                    $delete_url = route('design_style.destroy', $cryptId);

                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="submit" class="btn bg-transparent"><i class="fas fa-trash"></i></button></form>
                        </div>';

                    return $actionBtn;
                })
                ->rawColumns(['number', 'action'])
                ->make(true);
        }



        $title = 'Design Style';

        $page = 'admin.design_style.list';

        $js = 'admin.design_style.design_stylejs';
        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Design Style';

        $page = 'admin.design_style.add';

        $js = 'admin.design_style.design_stylejs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDesignStylePost $request)
    {
        try {

            DB::beginTransaction();

            foreach ($request->design_name as $catVal) {



                EventDesignStyle::create([

                    'design_name' => $catVal,

                ]);
            }

            DB::commit();

            return redirect()->route('design_style.index')->with('success', 'design style Add successfully !');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error' . $e->getMessage());

            return redirect()->route('design_style.create')->with('danger', 'design style not added' . $e->getMessage());
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

        $title = 'Edit Design Style';

        $page = 'admin.design_style.edit';

        $js = 'admin.design_style.design_stylejs';

        $designId = $id;

        $getDesignDetail = EventDesignStyle::where('id', $design_id)->first();

        return view('admin.includes.layout', compact('title', 'page', 'js', 'getDesignDetail', 'designId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignStylePost $request, string $id)
    {

        try {


            DB::beginTransaction();

            $designId = decrypt($id);

            $updateDesignStyle = EventDesignStyle::findOrFail($designId);



            $updateDesignStyle->design_name = $request->design_name;

            $updateDesignStyle->save();

            DB::commit();

            return redirect()->route('design_style.index')->with('success', 'Design style updated successfully!');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error' . $e->getMessage());

            return redirect()->route('design_style.edit', $id)->with('danger', 'Design style not updated!' . $e->getMessage());
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

            $user = EventDesignStyle::find($id)->delete();

            DB::commit();

            return redirect()->route('design_style.index')
                ->with('success', 'Design style deleted successfully');
        } catch (QueryException $e) {

            DB::rollBack();

            return redirect()->route('design_style.index')
                ->with('danger', 'Design style not deleted');
        }
    }

    public function checkDesignStyleIsExist(Request $request)

    {

        try {



            $category = EventDesignStyle::where(['design_name' => $request->design_name])->get();



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

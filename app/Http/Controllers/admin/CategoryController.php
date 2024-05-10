<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;



use App\Http\Requests\CreateCategoryPost;

use App\Http\Requests\UpdateCategoryPost;

use App\Models\EventDesignCategory;


use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class CategoryController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {



        if ($request->ajax()) {

            $data = EventDesignCategory::orderBy('id', 'desc');

            return Datatables::of($data)



                ->addIndexColumn()

                ->addColumn('number', function ($row) {

                    static $count = 1;

                    return $count++;
                })



                ->addColumn('action', function ($row) {

                    $cryptId = encrypt($row->id);

                    $edit_url = route('category.edit', $cryptId);

                    $delete_url = route('category.destroy', $cryptId);

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



        $title = 'Category';

        $page = 'admin.category.list';

        $js = 'admin.category.categoryjs';





        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }







    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $title = 'Add Category';

        $page = 'admin.category.add';

        $js = 'admin.category.categoryjs';

        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(CreateCategoryPost $request)

    {

        try {



            DB::beginTransaction();





            foreach ($request->category_name as $catVal) {



                EventDesignCategory::create([

                    'category_name' => $catVal,

                ]);
            }

            DB::commit();

            return redirect()->route('category.index')->with('success', 'Category Add successfully !');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error' . $e->getMessage());

            return redirect()->route('category.create')->with('danger', 'Category not added' . $e->getMessage());
        }
    }







    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $category_id =  decrypt($id);

        $title = 'Edit Category';

        $page = 'admin.category.edit';

        $js = 'admin.category.categoryjs';

        $catId = $id;

        $getCatDetail = EventDesignCategory::where('id', $category_id)->first();

        return view('admin.includes.layout', compact('title', 'page', 'js', 'getCatDetail', 'catId'));
    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(UpdateCategoryPost $request, $id)

    {

        try {



            DB::beginTransaction();

            $categroyId = decrypt($id);

            $updateCategory = EventDesignCategory::findOrFail($categroyId);



            $updateCategory->category_name = $request->category_name;

            $updateCategory->save();

            DB::commit();

            return redirect()->route('category.index')->with('success', 'Category updated successfully!');
        } catch (QueryException $e) {

            DB::rollBack();

            Log::error('Database query error' . $e->getMessage());

            return redirect()->route('category.edit', $id)->with('danger', 'Category not updated!' . $e->getMessage());
        }
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        try {

            DB::beginTransaction();



            $id = decrypt($id);

            $user = EventDesignCategory::find($id)->delete();

            DB::commit();

            return redirect()->route('category.index')
                ->with('success', 'Category deleted successfully');
        } catch (QueryException $e) {



            DB::rollBack();

            return redirect()->route('category.index')
                ->with('danger', 'Category not deleted');
        }
    }



    public function checkCategoryIsExist(Request $request)

    {

        try {



            $category = EventDesignCategory::where(['category_name' => $request->category_name])->get();



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

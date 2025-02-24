<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\faq;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class faqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->ajax());
        if ($request->ajax()) {
            $data = faq::orderBy('id', 'desc')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('number', function ($row) {
                    static $count = 1;
                    return $count++;
                })
                ->addColumn('question', function ($row) {
                    return strlen($row->question) > 20 ? substr($row->question, 0, 25) . '...' : $row->question;
                })
                ->addColumn('answer', function ($row) {
                    return strlen($row->answer) > 20 ? substr($row->answer, 0, 40) . '...' : $row->answer;
                })

                ->addColumn('action', function ($row) {
                    $cryptId = encrypt($row->id);
                    $faq_id = decrypt($cryptId);

                    $edit_url = route('faq.edit', $cryptId);
                    $delete_url = route('faq.destroy', $cryptId);

                    $actionBtn = '<div class="action-icon">
                        <a class="" href="' . $edit_url . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <form id="delete_faq_from'.$faq_id.'"action="' . $delete_url . '" method="POST">' .
                        csrf_field() . // Changed from @csrf to csrf_field()
                        method_field("DELETE") . // Changed from @method to method_field()
                        '<button type="button" data-id="'.$faq_id.'" class="btn bg-transparent delete_faq"><i class="fas fa-trash"></i></button></form>

                        </div>';
                    return $actionBtn;
                })

                ->rawColumns(['number', 'question', 'answer',  'action'])
                ->make(true);
        }


        $title = 'FAQ';

        $page = 'admin.faq.list';

        // $js = ['admin.faq.faqjs', 'admin.faq.editiorjs'];
        $js = ['admin.faq.faqjs'];

        $css= 'ckeditor';
        return view('admin.includes.layout', compact('title', 'page', 'js','css'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add FAQ';
        $page = 'admin.faq.add';
        // $js = ['admin.faq.faqjs', 'admin.faq.editiorjs'];
        $js = ['admin.faq.faqjs'];
        $css= 'ckeditor';


        $getDesignData = faq::all();

        return view('admin.includes.layout', compact('title', 'page', 'js','css', 'getDesignData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            $textData = new faq();
            $textData->question = $request->question;
            $textData->answer = $request->answer;
            $textData->save();

            DB::commit();

            return redirect()->route('faq.index')->with('msg', 'FAQ added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('faq.index')->with('msg_error', 'Something went wrong!');
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
        $faq_id = decrypt($id);

        // Get the template data by ID
        $getTemData = faq::findOrFail($faq_id);

        $title = 'Edit FAQ';
        $page = 'admin.faq.edit';
        // $js = ['admin.faq.editiorjs', 'admin.faq.faqjs'];
        $js = ['admin.faq.faqjs'];

        $css= 'ckeditor';



        // Pass the data to the view
        return view('admin.includes.layout', compact(
            'title',
            'page',
            'js',
            'css',
            'getTemData',

        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // Validate the incoming request data
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // Find the FAQ record by ID
        $faq = Faq::findOrFail($id);

        // Update the FAQ record with new data
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->save();

        // Redirect back to the FAQ list with a success message
        return redirect()->route('faq.index')->with('msg', 'FAQ updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::beginTransaction();

            $id = decrypt($id);
            $user = faq::find($id)->delete();

            DB::commit();

            return redirect()->route('faq.index')
                ->with('msg', 'FAQ deleted successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->route('faq.index')
                ->with('msg_error', 'FAQ not deleted');
        }
    }
}

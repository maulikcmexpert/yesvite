<?php

namespace App\Http\Controllers\admin;

use App\DataTables\PrivacyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Privacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Privacy_policyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(PrivacyDataTable $DataTable)
    // {


    //     $title = 'privacy policy';
    //     $page = 'admin.privacy_policy.list';
    //     $js = 'admin.privacy_policy.privacyjs';
    //     return $DataTable->render('admin.includes.layout', compact('title', 'page', 'js'));
    //     // return view('admin.includes.layout', compact('title', 'page', 'js'));
    // }

    public function index()
{
    $title = 'Privacy & Policy';
    $page = 'admin.privacy_policy.add';
    $js = 'admin.privacy_policy.privacyjs';

    // Fetch existing privacy policy data if available
    $privacyPolicies = Privacy::all()->where('type', 0); // Assuming you have a model named PrivacyPolicy

    return view('admin.includes.layout', compact('title', 'page', 'js', 'privacyPolicies'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'privacy';

        $page = 'admin.privacy_policy.add';

        $js = 'admin.privacy_policy.privacyjs';





        return view('admin.includes.layout', compact('title', 'page', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'title.*' => 'required|string',
            'description.*' => 'required|string',
            'id.*' => 'nullable|exists:privacy_policy,id',
        ]);

        DB::beginTransaction();
        try {
            // Loop through the titles and descriptions
            foreach ($validatedData['title'] as $key => $title) {
                // Check if the privacy policy already exists
                if (isset($request->id[$key])) {
                    // Update existing policy
                    $privacyPolicy = Privacy::findOrFail($request->id[$key]);
                    $privacyPolicy->update([
                        'title' => $title,
                        'description' => $validatedData['description'][$key],
                        'type' => '0',
                    ]);
                } else {
                    // Create new Privacy entry
                    Privacy::create([
                        'title' => $title,
                        'description' => $validatedData['description'][$key],
                        'type' => '0',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('privacy_policy.index')->with('success', 'Privacy Policies saved successfully!');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return redirect()->route('privacy_policy.index')->with('error', 'Privacy Policies creation failed!');
        }
    }

    // The rest of the methods (show, edit, update, destroy) can be implemented as needed.



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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

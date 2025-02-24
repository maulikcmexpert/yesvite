<?php

namespace App\Http\Controllers;

use App\Models\LegalAgreement;
use App\Models\Privacy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $title = 'Privacy Policy';
        $page = 'front.privacy_policy';

        $privacy = Privacy::where('type', '0')->first();

        // dd($privacy);
        return view('layout', compact(
            'title',
            'page',
            'privacy'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

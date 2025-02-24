<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privacy;
use App\Models\LegalAgreement;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {

        $title = 'Terms and Conditions';
        $page = 'front.term_and_condition';
        $terms = Privacy::where('type', '1')->first();
        return view('layout', compact(
            'title',
            'page',
            'terms'
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

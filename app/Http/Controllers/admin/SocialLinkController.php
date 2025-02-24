<?php

namespace App\Http\Controllers\admin;
use App\Models\Social_link;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Social Links';
        $page = 'admin.social_link.list';
        // $js = 'admin.subcategory.subcategoryjs';
        $data=Social_link::first();

        return view('admin.includes.layout', compact('title', 'page','data'));
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
        $validated = $request->validate([
            'url' => 'required|url', // URL is required and must be a valid URL
        ], [
            'url.required' => 'Please enter the link.',
            'url.url' => 'Please enter a valid URL.',
        ]);
        
        $column = $request->input('column_name'); 
        $link = $request->input('url'); 
        $existingRecord = Social_link::first();
    
        if ($existingRecord) {
            $existingRecord->$column = $link;
            $existingRecord->save();
            return redirect()->route('social_link.index')->with('msg', 'Link Updated Successfully!');

        } else {
            $saveLink = new Social_link();
            $saveLink->$column = $link;
            $saveLink->save();
            return redirect()->route('social_link.index')->with('msg', 'Link Added Successfully!');

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
    public function edit(string $url)
    {
        $title = 'Social Link Edit';
        $page = 'admin.social_link.edit';
        $link=decrypt($url) ;
        $js = 'admin.social_link.social_linkjs';
        $lable = '';
        if($link == 'x_link'){
            $lable = 'X Link';
        }elseif ($link == 'facebook_link') {
            $lable = 'Facebook Link';
        }elseif ($link == 'instagram_link') {
            $lable = 'Instagram Link';
        }elseif ($link == 'linkedin_link') {
            $lable = 'Linkedin Link';
        }elseif ($link == 'playstore_link') {
            $lable = 'Playstore Link';
        }elseif ($link == 'appstore_link') {
            $lable = 'Appstore Link';
        }


        $data = Social_link::select($link)->first();
        $value = $data ? ($data->$link ?? "") : "";
        return view('admin.includes.layout', compact('title', 'page','link','value','js','lable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

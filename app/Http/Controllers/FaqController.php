<?php

namespace App\Http\Controllers;
use App\Models\faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $title = 'faq';
        $page = 'front.faq';
        $faqs = Faq::all();
        //    $js = ['contact'];

        return view('layout', compact(
            'title',
            'page',
            'faqs'
        ));
    }
}

<?php

namespace App\View\Components\main_menu\drafts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class draft_list extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.main_menu.drafts.draft_list');
    }
}

<?php

namespace App\View\Components\front;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class footer extends Component
{
    /**
     * Create a new component instance.
     */
    public $page = "";
    public $js = "";
    public function __construct($js,$page)
    {
        $this->js = $js;
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.front.footer');
    }
}

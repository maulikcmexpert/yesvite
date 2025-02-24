<?php

namespace App\View\Components\main_menu\events;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class event_past extends Component
{
    /**
     * Create a new component instance.
     */
    public $from_page;

    public function __construct($from_page)
    {
        $this->from_page = $from_page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.main_menu.events.event_past');
    }
}

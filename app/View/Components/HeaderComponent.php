<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Auth;
use App\User;

class HeaderComponent extends Component
{
    public $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.header-component');
    }
}

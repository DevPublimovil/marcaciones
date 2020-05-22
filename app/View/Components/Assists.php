<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Auth;
use App\Employee;
use App\Marking;
use App\User;

class Assists extends Component
{

    public $employe;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $varo = 'hola';
        return view('components.assists', compact('varo'));
    }
}

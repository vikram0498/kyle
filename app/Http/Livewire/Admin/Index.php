<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Index extends Component
{
    protected $layout = null;
    
    public function render()
    {
        return view('livewire.admin.index');
    }
}

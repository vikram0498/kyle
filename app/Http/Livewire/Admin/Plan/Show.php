<?php

namespace App\Http\Livewire\Admin\Plan;

use App\Models\Plan;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($plan_id){
        $this->details = Plan::find($plan_id);
    }

    public function render()
    {
        return view('livewire.admin.plan.show');
    }
}

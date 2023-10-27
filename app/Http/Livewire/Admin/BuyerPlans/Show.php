<?php

namespace App\Http\Livewire\Admin\BuyerPlans;

use App\Models\BuyerPlan;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($plan_id){
        $this->details = BuyerPlan::find($plan_id);
    }

    public function render()
    {
        return view('livewire.admin.buyer-plans.show');
    }

    public function cancel(){
        $this->emit('cancel');
    }
}


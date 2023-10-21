<?php

namespace App\Http\Livewire\Admin\Support;

use Livewire\Component;
use App\Models\Support as CustomerSupport;

class Show extends Component
{

    public $support;

    public function mount($support_id){
        $this->support = CustomerSupport::find($support_id);
    }

    public function render()
    {
        return view('livewire.admin.support.show');
    }

    public function cancel(){
        $this->emit('cancel');
    }
}

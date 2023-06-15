<?php

namespace App\Http\Livewire\Admin\Addon;

use App\Models\Addon;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($addon_id){
        $this->details = Addon::find($addon_id);
    }

    public function render()
    {
        return view('livewire.admin.addon.show');
    }
}

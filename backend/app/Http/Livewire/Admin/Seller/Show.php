<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public $seller;

    public function mount($user_id){
        $this->seller = User::find($user_id);
    }
    public function render()
    {
        return view('livewire.admin.seller.show');
    }
}

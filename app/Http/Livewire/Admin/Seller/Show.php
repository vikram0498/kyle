<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public $seller;
    public $selfBuyerCount;
    public $purchasedBuyerCount;

    public function mount($user_id){
        $this->seller = User::find($user_id);
        $this->selfBuyerCount = User::find($user_id)->buyers()->count();
        $this->purchasedBuyerCount = 0;
    }
    public function render()
    {
        return view('livewire.admin.seller.show');
    }
}

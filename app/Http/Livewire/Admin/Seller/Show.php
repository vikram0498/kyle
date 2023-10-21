<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Livewire\Component;
use App\Models\PurchasedBuyer;


class Show extends Component
{
    public $seller;
    public $selfBuyerCount;
    public $purchasedBuyerCount;

    public function mount($user_id){
        $this->seller = User::find($user_id);
        $this->selfBuyerCount = $this->seller->buyers()->count();
        $this->purchasedBuyerCount = PurchasedBuyer::select('user_id','buyer_id')->with(['buyer'=>function($query){
            $query->where('user_id',1);
        }])
        ->where('user_id',$this->seller->id)->get()->count();
    }
    public function render()
    {
        return view('livewire.admin.seller.show');
    }
}

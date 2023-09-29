<?php

namespace App\Http\Livewire\Admin\DeletedUsers;

use Livewire\Component;
use App\Models\User;
use App\Models\PurchasedBuyer;

class Show extends Component
{

    public $seller;
    public $selfBuyerCount;
    public $purchasedBuyerCount;

    public function mount($user_id){
       
        $this->seller = User::where('id',$user_id)->onlyTrashed()->first();
        $this->selfBuyerCount = $this->seller->buyers()->whereNotNull('deleted_at')->count();
        $this->purchasedBuyerCount = PurchasedBuyer::select('user_id','buyer_id')->with(['buyer'=>function($query){
            $query->where('user_id',1);
        }])
        ->where('user_id',$this->seller->id)->onlyTrashed()->get()->count();
    }

    public function render()
    {
        return view('livewire.admin.deleted-users.show');
    }

    public function cancel(){
        $this->emit('cancel');
    }
}

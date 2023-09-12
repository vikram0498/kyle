<?php

namespace App\Http\Livewire\Admin;

use App\Models\Buyer;
use App\Models\PurchasedBuyer;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    protected $layout = null;
    
    public function render()
    {
        $sellerCount = User::whereHas('roles', function($q){
            $q->where('id', 2);
        })->count();
        $buyerCount = Buyer::count();

        $purchasedBuyers = PurchasedBuyer::select('user_id','buyer_id', \DB::raw('MAX(created_at) as max_created_at'))->with(['user','buyer'])->where('user_id','!=',1)->groupBy('buyer_id')->orderBy('max_created_at', 'desc')->get();

        // $purchasedBuyers = PurchasedBuyer::where('user_id','!=',1)->orderBy('created_at', 'desc')->take(5)->get();

        // dd($purchasedBuyers);
        return view('livewire.admin.index', compact('buyerCount', 'sellerCount','purchasedBuyers'));
    }
}

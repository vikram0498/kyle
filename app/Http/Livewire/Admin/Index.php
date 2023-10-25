<?php

namespace App\Http\Livewire\Admin;

use App\Models\Buyer;
use App\Models\PurchasedBuyer;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    protected $layout = null;
    
    public function render()
    {
        $sellerCount = User::whereHas('roles', function($q){
            $q->where('id', 2);
        })->count();
        $buyerCount = Buyer::count();

        $purchasedBuyers = DB::table('purchased_buyers')
        ->join('buyers', function ($join) {
            $join->on('purchased_buyers.buyer_id', '=', 'buyers.id')
                 ->where('buyers.user_id', '=', 1);
        })
        ->join('users', 'purchased_buyers.user_id', '=', 'users.id')
        ->where('purchased_buyers.user_id', '!=', 1)
        ->groupBy('purchased_buyers.buyer_id')
        ->select(
            'purchased_buyers.buyer_id',
            'purchased_buyers.user_id',
            'users.first_name AS user_first_name',
            'users.last_name AS user_last_name',
            DB::raw('MAX(purchased_buyers.created_at) AS max_created_at')
        )
        ->orderByDesc('max_created_at')
        ->limit(5)
        ->get();
    
        
        // dd($purchasedBuyers);

        return view('livewire.admin.index', compact('buyerCount', 'sellerCount','purchasedBuyers'));
    }
}

<?php

namespace App\Http\Livewire\Admin;

use App\Models\Buyer;
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
        return view('livewire.admin.index', compact('buyerCount', 'sellerCount'));
    }
}

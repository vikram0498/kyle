<?php

namespace App\Http\Livewire\Admin\BuyerTransactions;

use Livewire\Component;
use App\Models\BuyerTransaction;

class Show extends Component
{
    public $details;
    
    public function mount($transaction_id){
        $this->details = BuyerTransaction::find($transaction_id);
    }
    
    public function render()
    {
        return view('livewire.admin.buyer-transactions.show');
    }
    
    public function cancel(){
     $this->emit('cancel');
    }
}


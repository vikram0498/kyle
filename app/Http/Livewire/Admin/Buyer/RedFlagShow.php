<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RedFlagShow extends Component
{
    use LivewireAlert;

    protected $layout = null;
    
    public $data;

    protected $listeners = [
        'resolveAllFlag','resolveFlag', 'rejectFlag',
     ];

    public function mount($buyer_id){
        $this->data = Buyer::find($buyer_id);
    }

    public function render()
    {
        return view('livewire.admin.buyer.red-flag-show');
    }

    public function cancel(){
        $this->emitUp('cancel');
    }

    public function resolveAllFlag($id){
        $buyer = Buyer::find($id);
        
        $buyer->redFlagedData()->wherePivot('buyer_id', $id)->update(['status' => 1]);
        // $buyer->redFlagedData()->updateExistingPivot(['status' => 1]);

        $this->alert('success', trans("Buyer's all flag is resolved"));

        $this->cancel();
    }

    public function resolveFlag($data){
        $buyer = Buyer::find($data['buyer_id']);
        
        // $buyer->redFlagedData()->wherePivot('buyer_id', $id)->update(['status' => 1]);
        $buyer->redFlagedData()->updateExistingPivot($data['seller_id'], ['status' => 1]);        

        $buyerFlagCount = $buyer->redFlagedData()->where('status', 0)->count();

        $this->alert('success', trans("Flag is resolved"));

        if($buyerFlagCount == 0){
            $this->cancel();
        }
    }

    public function rejectFlag($data){
        $buyer = Buyer::find($data['buyer_id']);
        
        // $buyer->redFlagedData()->wherePivot('buyer_id', $id)->update(['status' => 1]);
        $buyer->redFlagedData()->updateExistingPivot($data['seller_id'], ['status' => 2]);

        $buyerFlagCount = $buyer->redFlagedData()->where('status', 0)->count();

        $this->alert('success', trans("Flag is rejected"));

        if($buyerFlagCount == 0){
            $this->cancel();
        }
    }
}

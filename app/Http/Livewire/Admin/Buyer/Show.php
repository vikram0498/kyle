<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null, $radioButtonFields = null;


    public function mount($buyer_id){
        $this->details = Buyer::find($buyer_id);

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->buyerTypes = config('constants.buyer_types');
        $this->buildingClassValue =config('constants.building_class_values');
        $this->purchaseMethods = config('constants.purchase_methods');

        $this->radioButtonFields = config('constants.radio_buttons_fields');
    }

    public function render()
    {
        return view('livewire.admin.buyer.show');
    }
}

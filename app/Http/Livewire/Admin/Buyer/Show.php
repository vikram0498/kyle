<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null, $radioButtonFields = null,  $zonings= null,$utilities = null, $sewers = null,$market_preferances = null, $contact_preferances =null;


    public function mount($buyer_id){
        $this->details = Buyer::find($buyer_id);

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->buyerTypes = config('constants.buyer_types');
        $this->buildingClassValue =config('constants.building_class_values');
        $this->purchaseMethods = config('constants.purchase_methods');

        $this->radioButtonFields = config('constants.radio_buttons_fields');

        $this->zonings= config('constants.zonings');
        $this->utilities = config('constants.utilities');
        $this->sewers = config('constants.sewers');
        $this->market_preferances = config('constants.market_preferances');
        $this->contact_preferances = config('constants.contact_preferances');




    }

    public function render()
    {
        return view('livewire.admin.buyer.show');
    }
}

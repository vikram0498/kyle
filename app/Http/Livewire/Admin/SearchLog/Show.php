<?php

namespace App\Http\Livewire\Admin\SearchLog;

use App\Models\SearchLog;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $purchaseMethods = null;

    public function mount($search_log_id){
        $this->details = SearchLog::find($search_log_id);

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->purchaseMethods = config('constants.purchase_methods');
    }

    public function render()
    {
        return view('livewire.admin.search-log.show');
    }
}

<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use App\Models\SearchLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Validator;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;

Class SearchBuyer extends Component {

    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;
    
    public $state =[],  $showResult=false, $allBuyers;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $purchaseMethods = null, $radioButtonFields = null;

    public function mount(){
        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->purchaseMethods = config('constants.purchase_methods');
        $this->radioButtonFields = config('constants.radio_buttons_fields');
    }

    public function rules (){
        $rules = [
            'address' => ['required'], 
            'city' => ['required'], 
            'state' => ['required'], 
            'zip_code' => ['required'],

            'bedroom_min' => ['required', !empty($this->state['bedroom_max']) ? new CheckMinValue($this->state['bedroom_max'], 'bedroom_max') : ''], 
            'bedroom_max' => ['required', !empty($this->state['bedroom_max']) ? new CheckMaxValue($this->state['bedroom_min'], 'bedroom_min') : ''], 

            'bath_min' => ['nullable', !empty($this->state['bath_max']) ? new CheckMinValue($this->state['bath_max'], 'bath_max') : ''], 
            'bath_max' => ['nullable', !empty($this->state['bath_min']) ? new CheckMaxValue($this->state['bath_min'], 'bath_min') : ''], 

            'size_min' => ['required', !empty($this->state['size_max']) ? new CheckMinValue($this->state['size_max'], 'size_max') : ''], 
            'size_max' => ['required', !empty($this->state['size_min']) ? new CheckMaxValue($this->state['size_min'], 'size_min') : ''], 

            'lot_size_min' => ['nullable', !empty($this->state['lot_size_max']) ? new CheckMinValue($this->state['lot_size_max'], 'lot_size_max') : ''], 
            'lot_size_max' => ['nullable', !empty($this->state['lot_size_min']) ? new CheckMaxValue($this->state['lot_size_min'], 'lot_size_min') : ''], 

            'build_year_min' => ['nullable', !empty($this->state['build_year_max']) ? new CheckMinValue($this->state['build_year_max'], 'build_year_max') : ''], 
            'build_year_max' => ['nullable', !empty($this->state['build_year_min']) ? new CheckMaxValue($this->state['build_year_min'], 'build_year_min') : ''], 

            
            'arv_min' => ['nullable', !empty($this->state['arv_max']) ? new CheckMinValue($this->state['arv_max'], 'arv_max') : ''], 
            'arv_max' => ['nullable', !empty($this->state['arv_min']) ? new CheckMaxValue($this->state['arv_min'], 'arv_min') : ''], 

            'parking' => ['nullable', 'in:'.implode(',', array_keys($this->parkingValues))],
            'property_type' => ['required', 'in:'.implode(',', array_keys($this->propertyTypes))],
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys($this->propertyFlaws))],
            'purchase_method' => ['required','array', 'in:'.implode(',', array_keys($this->purchaseMethods))]            
        ];
        return $rules;
    }

    public function render()
    {
        return view('livewire.admin.buyer.search-buyer');
    }

    public function searchBuyers(){

        // dd($this->state);   

        // Validator::make($this->state, $this->rules())->validate();

        // $this->state['user_id'] = auth()->user()->id;
        
        // SearchLog::create($this->state);

        $this->showResult = true;

        $data = $this->state;

        $buyers = Buyer::where('status', 1);

        if(isset($data['address']) && !empty($data['address'])){
            $buyers = $buyers->where('address', 'like', '%'.$data['address'].'%');
        }
        if(isset($data['city']) && !empty($data['city'])){
            $buyers = $buyers->where('city', 'like', '%'.$data['city'].'%');
        }
        if(isset($data['state']) && !empty($data['state'])){
            $buyers = $buyers->where('state', 'like', '%'.$data['state'].'%');
        }
        if(isset($data['zip_code']) && !empty($data['zip_code'])){
            $buyers = $buyers->where('zip_code', $data['zip_code']);
        }

        // filter by bedroom min max  
        if(isset($data['bedroom_min']) && !empty($data['bedroom_min']) && is_numeric($data['bedroom_min'])){
            $buyers = $buyers->where('bedroom_min', '>=', $data['bedroom_min']);
        } 
        if(isset($data['bedroom_max']) && !empty($data['bedroom_max']) && is_numeric($data['bedroom_max'])){
            $buyers = $buyers->where('bedroom_max', '<=', $data['bedroom_max']);
        }

        // filter by bath min max  
        if(isset($data['bath_min']) && !empty($data['bath_min']) && is_numeric($data['bath_min'])){
            $buyers = $buyers->where('bath_min', '>=', $data['bath_min']);
        } 
        if(isset($data['bath_max']) && !empty($data['bath_max']) && is_numeric($data['bath_max'])){
            $buyers = $buyers->where('bath_max', '<=', $data['bath_max']);
        }

        // filter by size min max  
        if(isset($data['size_min']) && !empty($data['size_min']) && is_numeric($data['size_min'])){
            $buyers = $buyers->where('size_min', '>=', $data['size_min']);
        } 
        if(isset($data['size_max']) && !empty($data['size_max']) && is_numeric($data['size_max'])){
            $buyers = $buyers->where('size_max', '<=', $data['size_max']);
        }

        // filter by lot_size min max  
        if(isset($data['lot_size_min']) && !empty($data['lot_size_min']) && is_numeric($data['lot_size_min'])){
            $buyers = $buyers->where('lot_size_min', '>=', $data['lot_size_min']);
        } 
        if(isset($data['lot_size_max']) && !empty($data['lot_size_max']) && is_numeric($data['lot_size_max'])){
            $buyers = $buyers->where('lot_size_max', '<=', $data['lot_size_max']);
        }

        // filter by build_year min max  
        if(isset($data['build_year_min']) && !empty($data['build_year_min']) && is_numeric($data['build_year_min'])){
            $buyers = $buyers->where('build_year_min', '>=', $data['build_year_min']);
        } 
        if(isset($data['build_year_max']) && !empty($data['build_year_max']) && is_numeric($data['build_year_max'])){
            $buyers = $buyers->where('build_year_max', '<=', $data['build_year_max']);
        }

        // filter by arv min max  
        if(isset($data['arv_min']) && !empty($data['arv_min']) && is_numeric($data['arv_min'])){
            $buyers = $buyers->where('arv_min', '>=', $data['arv_min']);
        } 
        if(isset($data['arv_max']) && !empty($data['arv_max']) && is_numeric($data['arv_max'])){
            $buyers = $buyers->where('arv_max', '<=', $data['arv_max']);
        }

        if(isset($data['parking']) && !empty($data['parking']) && is_numeric($data['parking'])){
            $buyers = $buyers->whereJsonContains('parking', $data['parking']);
        }

        if(isset($data['property_type']) && !empty($data['property_type']) && is_numeric($data['property_type'])){
            $buyers = $buyers->whereJsonContains('property_type', $data['property_type']);
        }

        if(isset($data['property_flaw']) && !empty($data['property_flaw']) && is_array($data['property_flaw'])){
            foreach($data['property_flaw'] as $pf_val){
                $buyers = $buyers->orWhereJsonContains('property_flaw', $pf_val);
            }
        }

        if(isset($data['solar']) && !empty($data['solar']) && is_numeric($data['solar'])){
            $buyers = $buyers->where('solar', [$data['solar']]);
        }
        if(isset($data['pool']) && !empty($data['pool']) && is_numeric($data['pool'])){
            $buyers = $buyers->where('pool', [$data['pool']]);
        }
        if(isset($data['septic']) && !empty($data['septic']) && is_numeric($data['septic'])){
            $buyers = $buyers->where('septic', [$data['septic']]);
        }
        if(isset($data['well']) && !empty($data['well']) && is_numeric($data['well'])){
            $buyers = $buyers->where('well', [$data['well']]);
        }
        if(isset($data['age_restriction']) && !empty($data['age_restriction']) && is_numeric($data['age_restriction'])){
            $buyers = $buyers->where('age_restriction', [$data['age_restriction']]);
        }
        if(isset($data['rental_restriction']) && !empty($data['rental_restriction']) && is_numeric($data['rental_restriction'])){
            $buyers = $buyers->where('rental_restriction', [$data['rental_restriction']]);
        }
        if(isset($data['hoa']) && !empty($data['hoa']) && is_numeric($data['hoa'])){
            $buyers = $buyers->where('hoa', [$data['hoa']]);
        }
        if(isset($data['tenant']) && !empty($data['tenant']) && is_numeric($data['tenant'])){
            $buyers = $buyers->where('tenant', [$data['tenant']]);
        }
        if(isset($data['post_possession']) && !empty($data['post_possession']) && is_numeric($data['post_possession'])){
            $buyers = $buyers->where('post_possession', [$data['post_possession']]);
        }
        if(isset($data['building_required']) && !empty($data['building_required']) && is_numeric($data['building_required'])){
            $buyers = $buyers->where('building_required', [$data['building_required']]);
        }
        if(isset($data['foundation_issues']) && !empty($data['foundation_issues']) && is_numeric($data['foundation_issues'])){
            $buyers = $buyers->where('foundation_issues', [$data['foundation_issues']]);
        }
        if(isset($data['mold']) && !empty($data['mold']) && is_numeric($data['mold'])){
            $buyers = $buyers->where('mold', [$data['mold']]);
        }
        if(isset($data['fire_damaged']) && !empty($data['fire_damaged']) && is_numeric($data['fire_damaged'])){
            $buyers = $buyers->where('fire_damaged', [$data['fire_damaged']]);
        }
        if(isset($data['rebuild']) && !empty($data['rebuild']) && is_numeric($data['rebuild'])){
            $buyers = $buyers->where('rebuild', [$data['rebuild']]);
        }
        if(isset($data['purchase_method']) && !empty($data['purchase_method']) && is_array($data['purchase_method'])){
            foreach($data['purchase_method'] as $pm_val){
                $buyers = $buyers->orWhereJsonContains('purchase_method', $pm_val);
            }
        }

        $buyers = $buyers->get();
        $this->allBuyers = $buyers;
        // dd($buyers);
        // return to_route('admin.buyer');
    }
}
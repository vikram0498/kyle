<?php

namespace App\Http\Livewire\Admin\Buyer;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Buyer;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;

use Illuminate\Support\Facades\DB; 

class Index extends Component
{
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false, $redFlagView = false;

    public $creativeBuyer = false, $multiFamilyBuyer = false;

    public $buyerFormLink = null;

    public $state = [
        'status' => 1,
        'buyer_type' => []
    ];

    protected $listeners = [
       'cancel','show', 'edit', 'confirmedToggleAction','deleteConfirm', 'changeBuyerType', 'banConfirmedToggleAction', 'updateProperty', 'redFlagView', 'getStates', 'getCities','initializePlugins'
       
    ];

    protected $buyer = null;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null, $radioButtonFields = null, $zonings= null,$utilities = null, $sewers = null,$market_preferances = null, $contact_preferances =null;


    public  $status = 1, $viewMode = false;

    public $countries = [], $states = [], $cities = [];

    public $buyer_id =null;

    public function mount(){
        abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->buyerTypes = config('constants.buyer_types');
        $this->buildingClassValue =config('constants.building_class_values');
        $this->purchaseMethods = config('constants.purchase_methods');
        $this->radioButtonFields = config('constants.radio_buttons_fields');

        $this->countries = DB::table('countries')->pluck('name', 'id');

        $this->zonings= config('constants.zonings');
        $this->utilities = config('constants.utilities');
        $this->sewers = config('constants.sewers');
        $this->market_preferances = config('constants.market_preferances');
        $this->contact_preferances = config('constants.contact_preferances');

        $url = env('FRONTEND_URL');
        $encryptedId = encrypt(auth()->user()->id);
        $this->buyerFormLink = $url.'add/buyer?token='.$encryptedId;
    }

    private function rules (){
        $rules = [
            'first_name' => ['required'], 
            'last_name' => ['required'], 
            'email' => ['required', 'email', 'unique:buyers,email,NULL,id,deleted_at,NULL'],
            'phone' => ['required', 'numeric', 'digits:10'], 
            'address' => ['required'], 
            'country' => ['required', 'exists:countries,id'], 
            // 'state' => [/*'required', 'exists:states,id'*/], 
            // 'city' => [/*'required', 'exists:cities,id'*/], 
            'zip_code' => ['required','min:6','max:10'],

            'bedroom_min' => ['required','numeric', !empty($this->state['bedroom_max']) ? new CheckMinValue($this->state['bedroom_max'], 'bedroom_max') : ''], 
            'bedroom_max' => ['required', 'numeric', !empty($this->state['bedroom_min']) ? new CheckMaxValue($this->state['bedroom_min'], 'bedroom_min') : ''], 

            'bath_min' => ['nullable','numeric', !empty($this->state['bath_max']) ? new CheckMinValue($this->state['bath_max'], 'bath_max') : ''], 
            'bath_max' => ['nullable','numeric', !empty($this->state['bath_min']) ? new CheckMaxValue($this->state['bath_min'], 'bath_min') : ''], 

            'size_min' => ['required','numeric', !empty($this->state['size_max']) ? new CheckMinValue($this->state['size_max'], 'size_max') : ''], 
            'size_max' => ['required','numeric', !empty($this->state['size_min']) ? new CheckMaxValue($this->state['size_min'], 'size_min') : ''], 

            'lot_size_min' => ['nullable','numeric', !empty($this->state['lot_size_max']) ? new CheckMinValue($this->state['lot_size_max'], 'lot_size_max') : ''], 
            'lot_size_max' => ['nullable', 'numeric', !empty($this->state['lot_size_min']) ? new CheckMaxValue($this->state['lot_size_min'], 'lot_size_min') : ''], 

            'build_year_min' => ['nullable', 'numeric', !empty($this->state['build_year_max']) ? new CheckMinValue($this->state['build_year_max'], 'build_year_max') : ''], 
            'build_year_max' => ['nullable', 'numeric', !empty($this->state['build_year_min']) ? new CheckMaxValue($this->state['build_year_min'], 'build_year_min') : ''], 

            
            'arv_min' => ['nullable', 'numeric', !empty($this->state['arv_max']) ? new CheckMinValue($this->state['arv_max'], 'arv_max') : ''], 
            'arv_max' => ['nullable', 'numeric', !empty($this->state['arv_min']) ? new CheckMaxValue($this->state['arv_min'], 'arv_min') : ''], 

            
            'stories_min' => ['required','numeric','max:3', !empty($this->state['stories_max']) ? new CheckMinValue($this->state['stories_max'], 'stories_max') : ''], 

            'stories_max' => ['required', 'numeric', 'max:3', !empty($this->state['stories_min']) ? new CheckMaxValue($this->state['stories_min'], 'stories_min') : ''],

            
            'price_min' => ['required','numeric', !empty($this->state['price_max']) ? new CheckMinValue($this->state['price_max'], 'price_max') : ''], 
            'price_max' => ['required', 'numeric', !empty($this->state['price_min']) ? new CheckMaxValue($this->state['price_min'], 'price_min') : ''], 

            'parking' => ['nullable','numeric','in:'.implode(',', array_keys($this->parkingValues))],
            'property_type' => ['required','array', 'in:'.implode(',', array_keys($this->propertyTypes))],
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys($this->propertyFlaws))],
            'buyer_type' => ['required','numeric', 'in:'.implode(',', array_keys($this->buyerTypes))],
            'purchase_method' => ['required','array', 'in:'.implode(',', array_keys($this->purchaseMethods))],

            
            'zoning' => ['nullable','array', 'in:'.implode(',', array_keys(config('constants.zonings')))],
            'utilities' => ['nullable','numeric','in:'.implode(',', array_keys(config('constants.utilities')))],
            'sewer' => ['nullable','numeric','in:'.implode(',', array_keys(config('constants.sewers')))],
            'market_preferance' => ['required','numeric','in:'.implode(',', array_keys(config('constants.market_preferances')))],
            'contact_preferance' => ['required','numeric','in:'.implode(',', array_keys(config('constants.contact_preferances')))],
            
        ];

        if(!empty($this->state['buyer_type']) && (1 == $this->state['buyer_type'])){
            $rules['max_down_payment_percentage'] = ['required','numeric','between:0,100'];
            $rules['max_interest_rate'] = ['required','numeric','between:0,100'];
            $rules['balloon_payment'] = ['required','numeric'];
        }
        if(!empty($this->state['buyer_type']) && (3 == $this->state['buyer_type'])){
            $rules['unit_min'] = ['required', 'numeric', !empty($this->state['unit_max']) ? new CheckMinValue($this->state['unit_max'], 'unit_max') : ''];
            $rules['unit_max'] = ['required', 'numeric', !empty($this->state['unit_min']) ? new CheckMaxValue($this->state['unit_min'], 'unit_min') : ''];
            $rules['value_add'] = ['required'];
            $rules['building_class'] = ['required','array', 'in:'.implode(',', array_keys($this->buildingClassValue))];
        }

        if(isset($this->state['state']) && !empty($this->state['state'])){
            $rules['state'] = ['exists:states,id'];
        }

        if(isset($this->state['city']) && !empty($this->state['city'])){
            $rules['city'] = ['exists:cities,id'];
        }

        return $rules;
    }

    public function updateProperty($data) {
        $this->state[$data['property']] = $data['pr_vals'];
        // $this->validatiionForm();
        $this->initializePlugins();

    }

    private function validatiionForm(){
        if(!$this->updateMode){
            Validator::make($this->state, $this->rules())->validate();
        } else {
            $rules = $this->rules();

            $rules['email'] = ['required', 'email', 'unique:buyers,email,'. $this->buyer_id.',id,deleted_at,NULL'];
            Validator::make($this->state, $rules)->validate();

        }
    }

    public function render() {
        return view('livewire.admin.buyer.index');
    }

    public function create(){
        $this->resetInputFields();
        $this->resetValidation();
        $this->formMode = true;
        $this->initializePlugins();
    }

    public function store() {  
        $this->validatiionForm();
        
        $this->state['user_id'] = auth()->user()->id;

        $this->state['country'] = DB::table('countries')->where('id', $this->state['country'])->first()->name;

        if(isset($this->state['state']) && !empty($this->state['state'])){
            $this->state['state']   = DB::table('states')->where('id', $this->state['state'])->first()->name;
        }

        if(isset($this->state['city']) && !empty($this->state['city'])){
            $this->state['city']    = DB::table('cities')->where('id', $this->state['city'])->first()->name;
        }

        if(isset($this->state['zoning']) && !empty($this->state['zoning'])){
            $this->state['zoning'] = json_encode($this->state['zoning']);
        }

        if(isset($this->state['parking']) && !empty($this->state['parking'])){
            $this->state['parking'] = (int)$this->state['parking'];
        }

        if(isset($this->state['buyer_type']) && !empty($this->state['buyer_type'])){
            $this->state['buyer_type'] = (int)$this->state['buyer_type'];
        }

        if(isset($this->state['property_type']) && !empty($this->state['property_type'])){
            $this->state['property_type'] = array_map('intval', $this->state['property_type']);
        }

        if(isset($this->state['purchase_method']) && !empty($this->state['purchase_method'])){
            $this->state['purchase_method'] = array_map('intval', $this->state['purchase_method']);
        }

        if(isset($this->state['property_flaw']) && !empty($this->state['property_flaw'])){
            $this->state['property_flaw'] = array_map('intval', $this->state['property_flaw']);
        }
    
        $createdBuyer = Buyer::create($this->state);

        if($createdBuyer){
             //Purchased buyer
             $syncData['buyer_id'] = $createdBuyer->id;
             $syncData['created_at'] = \Carbon\Carbon::now();
     
             auth()->user()->purchasedBuyers()->create($syncData);
        }

        $this->formMode = false;

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.buyer');
       
    }

    public function edit($id) {
        $buyer = Buyer::findOrFail($id);
        $stateId = null;
        $cityId = null;

        $this->buyer = $buyer;
        $this->state = $buyer->toArray();

        $this->state['zoning'] = json_decode($this->state['zoning'],true);
         
        $countryName = $buyer->country;
        $stateName = $buyer->state;
        $cityName = $buyer->city;

        $countryId = DB::table('countries')->where('name', $countryName)->first()->id;
        
        if($stateName){
            $stateId = DB::table('states')->where('country_id', $countryId)->where('name', $stateName)->first()->id;
        }

        if($cityName){
            $cityId = DB::table('cities')->where('state_id', $stateId)->where('name', $cityName)->first()->id;
        }

        $this->state['country'] = $countryId;
        $this->state['state'] = $stateId;
        $this->state['city'] = $cityId;

        $this->states = DB::table('states')->where('country_id', $countryId)->pluck('name', 'id');
        $this->cities = DB::table('cities')->where('state_id', $stateId)->pluck('name', 'id');

        $this->buyer_id = $id;

        $this->formMode = true;
        $this->updateMode = true;
        
        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update() {
        $this->validatiionForm();

        $this->state['country'] = DB::table('countries')->where('id', $this->state['country'])->first()->name;
        
        if(isset($this->state['state']) && !empty($this->state['state'])){
            $this->state['state']   = DB::table('states')->where('id', $this->state['state'])->first()->name;
        }

        if(isset($this->state['city']) && !empty($this->state['city'])){
            $this->state['city']    = DB::table('cities')->where('id', $this->state['city'])->first()->name;
        }

        if(isset($this->state['zoning']) && !empty($this->state['zoning'])){
            $this->state['zoning'] = json_encode($this->state['zoning']);
        }

        if(isset($this->state['parking']) && !empty($this->state['parking'])){
            $this->state['parking'] = (int)$this->state['parking'];
        }

        if(isset($this->state['buyer_type']) && !empty($this->state['buyer_type'])){
            $this->state['buyer_type'] = (int)$this->state['buyer_type'];
        }

        if(isset($this->state['property_type']) && !empty($this->state['property_type'])){
            $this->state['property_type'] = array_map('intval', $this->state['property_type']);
        }

        if(isset($this->state['purchase_method']) && !empty($this->state['purchase_method'])){
            $this->state['purchase_method'] = array_map('intval', $this->state['purchase_method']);
        }


        $buyer = Buyer::find($this->buyer_id);
        $buyer->update($this->state);
  
        $this->formMode = false;
        $this->updateMode = false;
  
        $this->flash('success',trans('messages.edit_success_message'));
        $this->resetInputFields();
        return redirect()->route('admin.buyer');
    }

    public function show($id) {
        $this->buyer_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
        $this->redFlagView = false;
    }

    public function redFlagView($id) {
        // dd($id);
        $this->buyer_id = $id;
        $this->formMode = false;
        $this->viewMode = false;
        $this->redFlagView = true;
    }

    public function deleteConfirm($id) {
        $model = Buyer::find($id);
        $model->delete();
        
        $this->emit('refreshLivewireDatatable');

        $this->alert('success', trans('messages.delete_success_message'));
    }
    
    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->redFlagView = false;
        $this->resetInputFields();
        $this->resetValidation();
    }
    public function confirmedToggleAction($data){
        $id = $data['id'];
        $type = $data['type'];

        $model = Buyer::find($id);

        $model->update([$type => !$model->$type]);
        $this->alert('success', trans('messages.change_status_success_message'));
    }
    public function changeStatus($statusVal){
        $this->state['status'] = (!$statusVal) ? 1 : 0;
    }
    public function changeBuyerType($values){
        $this->creativeBuyer = false;
        $this->multiFamilyBuyer = false;
        if(in_array(1, $values)){
            $this->creativeBuyer = true;
        } else {
            $this->state = Arr::except($this->state,['max_down_payment_percentage', 'max_interest_rate', 'balloon_payment', 'max_down_payment_money']);
        }
        if(in_array(3, $values)){
            $this->multiFamilyBuyer = true;
        } else {
            $this->state = Arr::except($this->state,['unit_min', 'unit_max', 'value_add', 'building_class']);
        }
        $this->initializePlugins();
    } 

    public function getStates($countryId){
        $this->cities = [];
        if($countryId){
            $stateData = DB::table('states')->where('country_id', $countryId)->orderBy('name', 'asc')->pluck('name', 'id');
            if($stateData->count() > 0){
                $this->states = $stateData;
            } else {
                $this->states = [];
                // $this->addError('country', 'Please select valid country');
            }
        } else {
            $this->states = [];
            $this->cities = [];
        }
        
        $this->initializePlugins();
    }

    public function getCities($stateId){
        if($stateId){
            $cityData = DB::table('cities')->where('state_id', $stateId)->orderBy('name', 'asc')->pluck('name', 'id');
            if($cityData->count() > 0){
                $this->cities = $cityData;
            } else {
                $this->cities = [];
                // $this->addError('state', 'Please select valid state');
            }
        } else {
            $this->cities = [];
        }
        $this->initializePlugins();
    }

    private function resetInputFields(){
        $this->state = [
            'status' => 1
        ];
    }

    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }
}

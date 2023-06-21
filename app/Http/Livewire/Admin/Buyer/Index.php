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

class Index extends Component
{
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    public $creativeBuyer = false, $multiFamilyBuyer = false;

    public $buyerFormLink = null;

    public $sortColumnName = 'created_at', $sortDirection = 'desc', $row_list = 10, $numberOfrowsList;

    public $state = [
        'status' => 1,
        'buyer_type' => []
    ];

    protected $listeners = [
        'confirmedToggleAction','deleteConfirm', 'changeBuyerType', 'banConfirmedToggleAction'
    ];

    protected $buyer = null;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null, $radioButtonFields = null;

    public  $status = 1, $viewMode = false;

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

        $this->numberOfrowsList = config('constants.number_of_rows');

        $url = config('constants.frontend_url');
        $encryptedId = encrypt(auth()->user()->id);
        $this->buyerFormLink = $url.'?token='.$encryptedId;
    }

    private function rules (){
        $rules = [
            'first_name' => ['required'], 
            'last_name' => ['required'], 
            'email' => ['required', 'email', 'unique:buyers,email'],
            'phone' => ['required', 'numeric', 'digits:10'], 
            'address' => ['required'], 
            'city' => ['required'], 
            'state' => ['required'], 
            'zip_code' => ['required'],

            'bedroom_min' => ['required', !empty($this->state['bedroom_max']) ? new CheckMinValue($this->state['bedroom_max'], 'bedroom_max') : ''], 
            'bedroom_max' => ['required', !empty($this->state['bedroom_min']) ? new CheckMaxValue($this->state['bedroom_min'], 'bedroom_min') : ''], 

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

            'parking' => ['nullable','array', 'in:'.implode(',', array_keys($this->parkingValues))],
            'property_type' => ['required','array', 'in:'.implode(',', array_keys($this->propertyTypes))],
            'property_flaw' => ['nullable','array', 'in:'.implode(',', array_keys($this->propertyFlaws))],
            'buyer_type' => ['required','array', 'in:'.implode(',', array_keys($this->buyerTypes))],
            'purchase_method' => ['required','array', 'in:'.implode(',', array_keys($this->purchaseMethods))],
            
        ];

        if(!empty($this->state['buyer_type']) && in_array(1, $this->state['buyer_type'])){
            $rules['max_down_payment_percentage'] = ['required'];
            $rules['max_interest_rate'] = ['required'];
            $rules['balloon_payment'] = ['required'];
        }
        if(!empty($this->state['buyer_type']) && in_array(3, $this->state['buyer_type'])){
            $rules['unit_min'] = ['required', !empty($this->state['unit_max']) ? new CheckMinValue($this->state['unit_max'], 'unit_max') : ''];
            $rules['unit_max'] = ['required', !empty($this->state['unit_min']) ? new CheckMaxValue($this->state['unit_min'], 'unit_min') : ''];
            $rules['value_add'] = ['required'];
            $rules['building_class'] = ['required','array', 'in:'.implode(',', array_keys($this->buildingClassValue))];
        }
        return $rules;
    }

    public function changeNumberOfList($val)  {
        $this->row_list = $val;
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumnName = $columnName;
    }

    public function render() {
        $this->search = str_replace(',', '', $this->search);
        $allBuyers = Buyer::query()
            ->where('first_name', 'like', '%'.$this->search.'%')
            ->OrWhere('last_name', 'like', '%'.$this->search.'%')
            ->OrWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy($this->sortColumnName, $this->sortDirection)
            ->paginate($this->row_list);
        return view('livewire.admin.buyer.index',compact('allBuyers'));
    }


    public function create(){
        $this->resetInputFields();
        $this->formMode = true;
        $this->initializePlugins();
    }

    public function store() {  
        Validator::make($this->state, $this->rules())->validate();
        
        $this->state['user_id'] = auth()->user()->id;
        
        Buyer::create($this->state);
    
        $this->formMode = false;

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.buyer');
       
    }

    public function edit($id) {
        $buyer = Buyer::findOrFail($id);

        $this->buyer = $buyer;
        $this->state = $buyer->toArray();
        $this->buyer_id = $id;

        $this->formMode = true;
        $this->updateMode = true;
        $this->initializePlugins();
    }

    public function update() {
        $rules = $this->rules();
        $rules['email'] = ['required', 'email', 'unique:buyers,email,'. $this->buyer_id];
        Validator::make($this->state, $rules)->validate();

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
    }

    public function deleteConfirm($id) {
        $model = Buyer::find($id);
        $model->delete();
        $this->alert('success', trans('messages.delete_success_message'));
    }
    
    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
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

    private function resetInputFields(){
        $this->state = [
            'status' => 1
        ];
    }

    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }
}

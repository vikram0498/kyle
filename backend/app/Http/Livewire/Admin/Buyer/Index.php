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

use App\Rules\ValidateMultiSelectValues;

class Index extends Component
{
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    public $creativeBuyer = false, $multiFamilyBuyer = false;

    public $state = [
        'status' => 1
    ];

    protected $listeners = [
        'confirmedToggleAction','deleteConfirm', 'changeBuyerType', 
    ];

    protected $buyers = null;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null;

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

    }

    public function render()
    {
        $this->search = str_replace(',', '', $this->search);
        $this->buyers = Buyer::query()
            ->where('first_name', 'like', '%'.$this->search.'%')
            ->OrWhere('last_name', 'like', '%'.$this->search.'%')
            ->OrWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate(10);

        $allBuyers = $this->buyers;
        return view('livewire.admin.buyer.index',compact('allBuyers'));
    }


    public function create()
    {
        $this->resetInputFields();
        $this->formMode = true;
        $this->initializePlugins();
    }

    public function store()
    {
        // dd($this->state);
        $rules = [
            'first_name' => ['required'], 
            'last_name' => ['required'], 
            'email' => ['required', 'email', 'unique:buyers,email'],
            'phone' => ['required', 'numeric', 'digits:10'], 
            'address' => ['required'], 
            'city' => ['required'], 
            'state' => ['required'], 
            'zip_code' => ['required'], 
            'company_name' => ['required'], 
            'occupation' => ['required'], 
            'bedroom_min' => ['required'], 'bedroom_max' => ['required'], 
            'bath_min' => ['required'], 'bath_max' => ['required'], 
            'size_min' => ['required'], 'size_max' => ['required'], 
            'lot_size_min' => ['required'], 'lot_size_max' => ['required'], 
            'build_year_min' => ['required'], 'build_year_max' => ['required'], 

            'parking' => [new ValidateMultiSelectValues],
            'property_flaw' => [new ValidateMultiSelectValues],
            'property_type' => [new ValidateMultiSelectValues],
            'buyer_type' => [new ValidateMultiSelectValues],
            'purchase_method' => [new ValidateMultiSelectValues],
        ];

        if(!empty($this->state['buyer_type']) && in_array(1, $this->state['buyer_type'])){
            $rules['max_down_payment_percentage'] = ['required'];
            $rules['max_interest_rate'] = ['required'];
            $rules['balloon_payment'] = ['required'];
        }
        if(!empty($this->state['buyer_type']) && in_array(3, $this->state['buyer_type'])){
            $rules['unit_min'] = ['required'];
            $rules['unit_max'] = ['required'];
            $rules['value_add'] = ['required'];
            $rules['building_class'] = [new ValidateMultiSelectValues];
        } 
        Validator::make($this->state, $rules)->validate();

        dd("dgsdgsd");
        
        $this->state['user_id'] = auth()->user()->id;
        
        $buyer = Buyer::create($this->state);
    
        $this->formMode = false;

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.buyer');
       
    }

    public function changeStatus($statusVal){
        $this->state['status'] = (!$statusVal) ? 1 : 0;
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;

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

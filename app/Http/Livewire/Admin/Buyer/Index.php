<?php

namespace App\Http\Livewire\Admin\Buyer;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Buyer;
use App\Models\ProfileVerification;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;
use Illuminate\Support\Facades\Log;
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
        'buyer_type' => [],
    ];

    protected $listeners = [
       'cancel','show', 'edit', 'confirmedToggleAction','deleteConfirm', 'changeBuyerType', 'banConfirmedToggleAction', 'updateProperty', 'redFlagView', 'getStates', 'getCities','initializePlugins','updateRedFlagVaribale','updateUser'
       
    ];

    protected $buyer = null;

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $buyerTypes = null, $buildingClassValue = null, $purchaseMethods = null, $radioButtonFields = null, $zonings= null,$utilities = null, $sewers = null,$market_preferances = null, $contact_preferances =null;


    public  $status = 1, $viewMode = false;

    public $countries = [], $states = [], $cities = [];

    public $buyer_id =null, $buyer_user_id = null;

    public function mount(){
        abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->buyerTypes = config('constants.buyer_types');
        $this->buildingClassValue =config('constants.building_class_values');
        $this->purchaseMethods = config('constants.purchase_methods');
        $this->radioButtonFields = config('constants.radio_buttons_fields');

        // $this->countries = DB::table('countries')->pluck('name', 'id');

        $this->zonings= config('constants.zonings');
        $this->utilities = config('constants.utilities');
        $this->sewers = config('constants.sewers');
        $this->market_preferances = config('constants.market_preferances');
        $this->park = config('constants.park');
       
        // $this->market_preferances = array_values($this->market_preferances);
        $this->contact_preferances = config('constants.contact_preferances');
        // $this->park = config('constants.park');

        $url = env('FRONTEND_URL');
        $encryptedId = encrypt(auth()->user()->id);
        $this->buyerFormLink = $url.'add/buyer?token='.$encryptedId;

        // $this->states =  DB::table('states')->where('country_id', 233)->orderBy('name', 'asc')->pluck('name', 'id');
        
    }

    private function rules (){
       
        $rules = [
            'first_name' => ['required'], 
            'last_name' => ['required'], 
            // 'email' => ['required', 'email', 'unique:buyers,email,NULL,id,deleted_at,NULL'],
            // 'phone' => ['required', 'numeric', 'digits:10'], 
            'email'       => ['required', 'email', 'unique:users,email,NULL,id'],
            'phone'       => ['required', 'numeric','digits:10','not_in:-','unique:users,phone,NULL,id'], 
            // 'address' => ['required'], 
            // 'country' => ['required', 'exists:countries,name'], 
            'state' => ['required', /*'exists:states,id'*/], 
            'city' => ['required', /*'exists:cities,id'*/], 
            'company_name' => [], 

            // 'zip_code' => ['nullable', 'regex:/^[0-9]*$/'],
            'lot_size_min' => ['required','numeric', !empty($this->state['lot_size_max']) ? new CheckMinValue($this->state['lot_size_max'], 'lot_size_max') : ''], 
            'lot_size_max' => ['required', 'numeric', !empty($this->state['lot_size_min']) ? new CheckMaxValue($this->state['lot_size_min'], 'lot_size_min') : ''], 
            
            'price_min' => ['required','numeric', !empty($this->state['price_max']) ? new CheckMinValue($this->state['price_max'], 'price_max') : ''], 
            'price_max' => ['required', 'numeric', !empty($this->state['price_min']) ? new CheckMaxValue($this->state['price_min'], 'price_min') : ''], 

            'parking' => ['required','array','in:'.implode(',', array_keys($this->parkingValues))],
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
       
        if(!isset($this->state['property_type']) || (!empty($this->state['property_type']) && !array_intersect([14], $this->state['property_type']))){
            if(!isset($this->state['property_type']) || (!empty($this->state['property_type']) && !array_intersect([15], $this->state['property_type']))){                
                $rules['bedroom_min'] = ['required', 'numeric', !empty($this->state['bedroom_max']) ? new CheckMinValue($this->state['bedroom_max'], 'bedroom_max') : ''];
                $rules['bedroom_max'] = ['required', 'numeric', !empty($this->state['bedroom_min']) ? new CheckMaxValue($this->state['bedroom_min'], 'bedroom_min') : ''];
                
                $rules['bath_min'] = ['required', 'numeric', !empty($this->state['bath_max']) ? new CheckMinValue($this->state['bath_max'], 'bath_max') : ''];
                $rules['bath_max'] = ['required', 'numeric', !empty($this->state['bath_min']) ? new CheckMaxValue($this->state['bath_min'], 'bath_min') : ''];
                
            }
            
                $rules['size_min'] = ['required', 'numeric', !empty($this->state['size_max']) ? new CheckMinValue($this->state['size_max'], 'size_max') : ''];
                $rules['size_max'] = ['required', 'numeric', !empty($this->state['size_min']) ? new CheckMaxValue($this->state['size_min'], 'size_min') : ''];
                $rules['build_year_min'] = ['required', !empty($this->state['build_year_max']) ? new CheckMinValue($this->state['build_year_max'], 'build_year_max') : ''];
                $rules['build_year_max'] = ['required', !empty($this->state['build_year_min']) ? new CheckMaxValue($this->state['build_year_min'], 'build_year_min') : ''];
                // $rules['arv_min'] = ['required', 'numeric', !empty($this->state['arv_min']) ? new CheckMinValue($this->state['arv_min'], 'arv_min') : ''];
                // $rules['arv_max'] = ['required', 'numeric', !empty($this->state['arv_max']) ? new CheckMaxValue($this->state['arv_max'], 'arv_max') : ''];

        }
        
        if(!isset($this->state['property_type']) || (!empty($this->state['property_type']) && !array_intersect([7,14], $this->state['property_type']))){          
            $rules['stories_min'] = ['required', 'numeric', !empty($this->state['stories_max']) ? new CheckMinValue($this->state['stories_max'], 'stories_max') : ''];
            $rules['stories_max'] = ['required', 'numeric', !empty($this->state['stories_min']) ? new CheckMaxValue($this->state['stories_min'], 'stories_min') : ''];
        }

        if(!empty($this->state['buyer_type']) && (1 == $this->state['buyer_type'])){
            $rules['max_down_payment_percentage'] = ['required','numeric','between:0,100'];
            $rules['max_interest_rate'] = ['required','numeric','between:0,100'];
            $rules['balloon_payment'] = ['required','numeric'];
        }

        if(!empty($this->state['property_type']) && array_intersect([7], $this->state['property_type'])){
           
            $rules['zoning'] = ['required','array', 'in:'.implode(',', array_keys($this->zonings))];
            $rules['utilities'] = ['required', 'in:'.implode(',', array_keys($this->utilities))];
            $rules['sewer'] = ['required','in:'.implode(',', array_keys($this->sewers))];

        }

        if(!empty($this->state['property_type']) && array_intersect([10,11,14,15], $this->state['property_type'])){
           
            $rules['unit_min'] = ['required', 'numeric', !empty($this->state['unit_max']) ? new CheckMinValue($this->state['unit_max'], 'unit_max') : ''];
            $rules['unit_max'] = ['required', 'numeric', !empty($this->state['unit_min']) ? new CheckMaxValue($this->state['unit_min'], 'unit_min') : ''];
            
            $rules['value_add'] = ['required'];
            $rules['building_class'] = ['required','array', 'in:'.implode(',', array_keys($this->buildingClassValue))];

        }

        if(!empty($this->state['property_type']) && array_intersect([15], $this->state['property_type'])){
            $rules['rooms'] = ['required'];
        }
      
        if(!empty($this->state['property_type']) && array_intersect([14], $this->state['property_type'])){
            $rules['park'] = ['required','in:'.implode(',', array_keys($this->park))];
        }

        // if(isset($this->state['state']) && !empty($this->state['state'])){
        //     $rules['state'] = ['exists:states,id'];
        // }

        // if(isset($this->state['city']) && !empty($this->state['city'])){
        //     $rules['city'] = ['exists:cities,id'];
        // }

        return $rules;
    }

    public function updateProperty($data) {  
       
        // $this->validatiionForm();
        if(isset($data['property'])){
            $this->state[$data['property']] = $data['pr_vals'];
            if($data['property'] == 'property_type'){

                
                //09-07-2024
                $cleanedString = str_replace('"', '', $data['pr_vals']);
                $data['pr_vals'] = [(int) $cleanedString];
                
                $this->state[$data['property']] = $data['pr_vals'];
                //end 

                if(in_array(10, $data['pr_vals']) || in_array(11, $data['pr_vals']) || in_array(2, $data['pr_vals']) || in_array(14, $data['pr_vals']) || in_array(15, $data['pr_vals'])){
                    $this->multiFamilyBuyer = true;
                } else {
                    $this->multiFamilyBuyer = false;
                    $this->state = Arr::except($this->state,['unit_min', 'unit_max', 'value_add', 'building_class']);
                }
            }
        }
        $this->initializePlugins();

    }

    private function validatiionForm(){
        if(!$this->updateMode){
          
            $validator = Validator::make($this->state, $this->rules(),[
                'phone.required' => 'The phone number field is required',
                'size_min.required' => 'The sq ft min field is required',
                'size_max.required' => 'The sq ft max field is required',
                'lot_size_min.required' => 'The lot size sq ft (min) field is required',
                'lot_size_max.required' => 'The lot size sq ft (max) field is required',
                'market_preferance.required' => 'The mls status field is required',
                'contact_preferance.required' => 'The contact preference field is required',
                'company_name.required' => 'The company/llc field is required',
            ],[
                'unit_min'=>'minimum units',
                'unit_max'=>'maximum units',
                'park'=>'park owned/tenant owned',
                'state' => strtolower(trans('cruds.buyer.fields.state')),
                'city' => strtolower(trans('cruds.buyer.fields.city')),
            ])->validate();

            // if ($validator->fails()) {
            //     // Get the messages from the Validator instance
            //     $messages = $validator->messages();
            
            //     dd($messages);
               
            // }

        } else {
            $rules = $this->rules();

            // $rules['email'] = ['required', 'email', 'unique:buyers,email,'. $this->buyer_id.',id,deleted_at,NULL'];
            $rules['email'] = ['required', 'email', 'unique:users,email,'. $this->buyer_user_id.',id,deleted_at,NULL'];
            $rules['phone'] = ['required', 'numeric','digits:10','not_in:-','unique:users,phone,'. $this->buyer_user_id.',id,deleted_at,NULL'];

            Validator::make($this->state, $rules,[
                'phone.required' => 'The phone number field is required',
                'size_min.required' => 'The sq ft min field is required',
                'size_max.required' => 'The sq ft max field is required',
                'lot_size_min.required' => 'The lot size sq ft (min) field is required',
                'lot_size_max.required' => 'The lot size sq ft (max) field is required',
                'market_preferance.required' => 'The mls status field is required',
                'contact_preferance.required' => 'The contact preference field is required',
                'company_name.required' => 'The company/llc field is required',
            ],[
                'unit_min'=>'minimum units',
                'unit_max'=>'maximum units',
                'park'=>'park owned/tenant owned',
                'state' => strtolower(trans('cruds.buyer.fields.state')),
                'city' => strtolower(trans('cruds.buyer.fields.city')),
            ])->validate();

        }
    }

    public function render() {
        $allStates =  DB::table('states')->where('country_id', 233)->orderBy('name', 'asc')->where('flag',1)->pluck('name', 'id');
        $allCities = $this->cities;
        return view('livewire.admin.buyer.index',compact('allStates','allCities'));
    }

    public function create(){
        $this->resetInputFields();
        $this->resetValidation();
        $this->formMode = true;

        $this->state['country'] = 'United States';

        $this->initializePlugins();
    }

    public function store() {  
        // dd($this->all());
        $this->initializePlugins();   
        $this->validatiionForm();   

        DB::beginTransaction();
        try {
            // Start create users table
            $userDetails =  [
                'first_name'     => $this->state['first_name'],
                'last_name'      => $this->state['last_name'],
                'name'           => ucwords($this->state['first_name'].' '.$this->state['last_name']),
                'email'          => $this->state['email'], 
                'phone'          => $this->state['phone'], 
            ];
            $createUser = User::create($userDetails);
            // End create users table
            
            if($createUser){

                // Buyer verification entry
                $createUser->buyerVerification()->create(['user_id'=>$createUser->id]);

                //Assign buyer role
                $createUser->roles()->sync(3);

                $this->state['user_id'] = auth()->user()->id;

                $this->state['buyer_user_id'] = $createUser->id;

                $countryId= config('constants.default_country');
                $this->state['country'] = DB::table('countries')->where('id', $countryId)->first()->name;

                if(isset($this->state['state']) && !empty($this->state['state'])){
                    $this->state['state']   =  array_map('intval',$this->state['state']);
                }

                if(isset($this->state['city']) && !empty($this->state['city'])){
                    $this->state['city']    =  array_map('intval',$this->state['city']);
                }

              
                if(isset($this->state['zoning']) && !empty($this->state['zoning'])){
                    $this->state['zoning'] = json_encode(array_map('intval',$this->state['zoning']));
                }
    
                if(isset($this->state['building_class']) && !empty($this->state['building_class'])){
                    $this->state['building_class'] = array_map('intval',$this->state['building_class']);
                }

                if(isset($this->state['parking']) && !empty($this->state['parking'])){
                    // $this->state['parking'] = (int)$this->state['parking'];
                    $this->state['parking'] = array_map('intval', $this->state['parking']);
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

                $this->state = collect($this->state)->except(['first_name', 'last_name','email','phone'])->all();              
              
                $this->state['buyer_user_id'] = $createUser->id;
                $this->state['state'] = json_encode($this->state['state']);
                $this->state['city'] = json_encode($this->state['city']);

                $createdBuyer = Buyer::create($this->state);

                if($createUser->buyerDetail){
                    //Purchased buyer
                    $syncData['buyer_id'] = $createUser->buyerDetail->id;
                    $syncData['created_at'] = \Carbon\Carbon::now();
            
                    auth()->user()->purchasedBuyers()->create($syncData);
                }

                //Verification mail sent
                $createUser->NotificationSendToBuyerVerifyEmail();

                DB::commit();

                $this->formMode = false;

                $this->resetInputFields();

                $this->flash('success',trans('messages.auth.buyer.admin_register_success_alert'));
                
                return redirect()->route('admin.buyer');
            }
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());

            Log::error('Livewire -> Buyer-> Index -> Store()'.$e->getMessage().'->'.$e->getLine());
            
            $this->alert('error',trans('messages.error_message'));
        }

    }

    public function edit($id) {
        $buyer = Buyer::findOrFail($id);
       
        $stateId = null;
        $cityId = null;

        $this->buyer = $buyer;
        $this->state = $buyer->toArray();
        $this->state['first_name'] = $buyer->userDetail->first_name;
        $this->state['last_name'] = $buyer->userDetail->last_name;
        $this->state['email'] = $buyer->userDetail->email;
        $this->state['phone'] = $buyer->userDetail->phone;

        $this->state['zoning'] = json_decode($this->state['zoning'],true);
        

        if(!empty($this->state['property_type']) && array_intersect([10,11,14,15], $this->state['property_type'])){
            $this->multiFamilyBuyer = true;
        }

        $countryName = $buyer->country;
        $stateId = json_decode($buyer->state,true);
        $cityId = json_decode($buyer->city,true);

        // $countryId = DB::table('countries')->where('name', $countryName)->first()->id;

        $countryId = 233;

        $this->state['country'] = $buyer->country;
        $this->state['state'] = $stateId;
        $this->state['city'] = $cityId;

        $this->states = DB::table('states')->where('country_id', $countryId)->pluck('name', 'id');
        $this->cities = DB::table('cities')->whereIn('state_id', $stateId)->pluck('name', 'id');

        $this->buyer_id = $id;
        $this->buyer_user_id = $buyer->buyer_user_id;

        $this->formMode = true;
        $this->updateMode = true;
        
        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update() {
        
        $this->initializePlugins();   
        $this->validatiionForm();

        DB::beginTransaction();
        try {
           
            $isSendMail = false;

            $user = User::find($this->buyer_user_id);

            if($user->email !== $this->state['email']){
                $isSendMail = true;
                $user->email_verified_at = null;
                $user->save();
            }else{
                $isSendMail = false;
            }
            
            // Start create users table
            $userDetails =  [
                'first_name'     => $this->state['first_name'],
                'last_name'      => $this->state['last_name'],
                'name'           => ucwords($this->state['first_name'].' '.$this->state['last_name']),
                'email'          => $this->state['email'], 
                'phone'          => $this->state['phone'],
                'status'         => $this->state['status'] 
            ];
            $updateUser =  $user->update($userDetails);
            // End create users table

            $this->state['country'] = DB::table('countries')->where('id', 233)->first()->name;
                    
            if(isset($this->state['state']) && !empty($this->state['state'])){
                $this->state['state']   =  array_map('intval',$this->state['state']);
            }

            if(isset($this->state['city']) && !empty($this->state['city'])){
                $this->state['city']    =  array_map('intval',$this->state['city']);
            }

            if(isset($this->state['zoning']) && !empty($this->state['zoning'])){
                $this->state['zoning'] = json_encode(array_map('intval',$this->state['zoning']));
            }

            if(isset($this->state['building_class']) && !empty($this->state['building_class'])){
                $this->state['building_class'] = array_map('intval',$this->state['building_class']);
            }

            if(isset($this->state['parking']) && !empty($this->state['parking'])){
                $this->state['parking'] = array_map('intval', $this->state['parking']);
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

	    if(isset($this->state['property_type'])){

                if(!in_array(7,$this->state['property_type'])){
                    $this->state['zoning'] = null;
                    $this->state['utilities'] = null;
                    $this->state['sewer'] =  null;
                }

                if(in_array(7, $this->state['property_type']) || in_array(14, $this->state['property_type'])){
                    $this->state['stories_min'] = null;
                    $this->state['stories_max'] = null;
                }

		if(!in_array(8,$this->state['property_type'])){
                    $this->state['permanent_affix'] = 0;
                }

		if((!in_array(10,$this->state['property_type'])) &&  (!in_array(11,$this->state['property_type'])) && (!in_array(14,$this->state['property_type'])) && (!in_array(15,$this->state['property_type'])) ){
                    $this->state['unit_min'] = null;
                    $this->state['unit_max'] = null;
                    $this->state['building_class'] = null;
                    $this->state['value_add'] = null;
                }

		if(in_array(14, $this->state['property_type']) || in_array(15, $this->state['property_type'])){
                    $this->state['bedroom_min'] = null;
                    $this->state['bedroom_max'] = null;
                    $this->state['bath_min'] = null;
                    $this->state['bath_max'] = null;
                }

                if(in_array(14, $this->state['property_type'])){
                    $this->state['size_min'] = null;
                    $this->state['size_max'] = null;
                    $this->state['build_year_min'] = null;
                    $this->state['build_year_max'] = null;
                }
                
                if(!in_array(14,$this->state['property_type'])){
                    $this->state['park'] = null;
                }

                if(!in_array(15,$this->state['property_type'])){
                    $this->state['rooms'] = null;
                }
            }

            $buyerPropertyRecord = Arr::except($this->state,['status']);

            $buyer = Buyer::find($this->buyer_id);
            $buyer->update($buyerPropertyRecord);
    
            $this->formMode = false;
            $this->updateMode = false;
    
           if($isSendMail){
                //Verification mail sent
                $user->NotificationSendToBuyerVerifyEmail();
                DB::commit();
                $this->flash('success',trans('messages.auth.buyer.admin_update_success_with_mail_sent_alert'));
           }else{
                DB::commit();
                $this->flash('success',trans('messages.auth.buyer.update_buyer_success_alert'));
           }
            
            $this->resetInputFields();
            return redirect()->route('admin.buyer');
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            Log::error('Livewire -> Buyer-> Index -> Update()'.$e->getMessage().'->'.$e->getLine());
            $this->alert('error',trans('messages.error_message'));
        }
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
        $model->userDetail()->where('id',$model->buyer_user_id)->delete();
        // $model->buyersPurchasedByUser()->where('buyer_id',$id)->delete();
        $model->delete();
        
        $this->emit('refreshTable');
        
        $this->alert('success', trans('messages.delete_success_message'));
    }
    
    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->redFlagView = false;
        $this->resetInputFields();
        $this->resetValidation();
        $this->resetPage();
    }

    public function confirmedToggleAction($data){
        $id = $data['id'];
        $type = $data['type'];

        $model = User::where('id',$id)->first();
        $model->status = !$model->$type;
        $model->save();

        $this->emit('refreshTable');

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

        // if(in_array(3, $values)){
        //     $this->multiFamilyBuyer = true;
        // } else {
        //     $this->state = Arr::except($this->state,['unit_min', 'unit_max', 'value_add', 'building_class']);
        // }
        $this->initializePlugins();
    } 

    // public function getStates($countryId){
    //     $this->cities = [];
    //     if($countryId){
    //         $stateData = DB::table('states')->where('country_id', $countryId)->orderBy('name', 'asc')->pluck('name', 'id');
    //         if($stateData->count() > 0){
    //             $this->states = $stateData;
    //         } else {
    //             $this->states = [];
    //             // $this->addError('country', 'Please select valid country');
    //         }
    //     } else {
    //         $this->states = [];
    //         $this->cities = [];
    //     }
        
    //     $this->initializePlugins();
    // }

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

    public function updateRedFlagVaribale($columns)
    {
        foreach($columns as $columnName){
            if($columnName == 'name'){
                $this->emit('setIsNameUpdate', true);
            }

            if($columnName == 'email'){
                $this->emit('setIsEmailUpdate', true);
            }

            if($columnName == 'phone'){
                $this->emit('setIsPhoneUpdate', true); 
            }
        }
    }

    public function updateUser($userId)
    {
        $this->emit('setUserId', $userId); 
    }
}

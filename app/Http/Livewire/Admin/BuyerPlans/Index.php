<?php

namespace App\Http\Livewire\Admin\BuyerPlans;

use Livewire\Component;
use App\Models\BuyerPlan;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Stripe\Stripe;
use Stripe\Plan as StripPlan;
use Stripe\Product as StripProduct;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class Index extends Component
{
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    protected $plans = null;

    public  $title, $type, $position, $amount, $description='', $status = 1, $image=null, $viewMode = false,$originalImage, $removeImage=false;

    public $plan_id =null;

    protected $listeners = [
        'show', 'edit', 'cancel','confirmedToggleAction','deleteConfirm'
    ];

    public function mount(){
        abort_if(Gate::denies('buyer_plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.buyer-plans.index');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->resetValidation();
        $this->formMode = true;
        $this->initializePlugins();
    }

    public function store()
    {   
        $validatedData = $this->validate(
            [
                'title'       => ['required'],
                'amount'      => ['required', 'numeric', 'min:0', 'max:99999999.99'],
                'type'        => ['required', 'in:monthly,yearly'],
                'position'    => ['required', 'numeric', 'min:0', 'max:99999'],
                'description' => ['required', 'without_spaces'],
                'status'      => 'required',
                'image' => ['required', 'image', 'valid_extensions:svg','max:'.config('constants.img_max_size')],
            ],['without_spaces' => 'The :attribute field is required'],['title'  => 'plan name']
        );
        
        $validatedData['status'] = $this->status;

        $insertRecord = $this->except(['search','formMode','updateMode','plan_id','image','originalImage','page','paginators']);

        Stripe::setApiKey(config('app.stripe_secret_key'));

        $stripePlan = StripPlan::create([
            'amount' => (float)$this->amount * 100,
            'currency' => config('constants.default_currency'),
            'interval' => $this->type == 'monthly' ? 'month' : 'year',
            'product' => [
                'name' => $this->title,
            ],
            'nickname' => $this->title, // Set a nickname for your plan
            'metadata' => [
                'plan_type'=>'buyer plan',
                'description' => strip_tags($this->description), // Set a description for your plan
            ],
        ]);

        if($stripePlan){
            $insertRecord['plan_stripe_id'] = $stripePlan->id;
            $insertRecord['plan_json']  = json_encode($stripePlan);
    
            $plan = BuyerPlan::create($insertRecord);
        
            if($this->image){
                $uploadedImage = uploadImage($plan, $this->image, 'buyer-plan/image/',"buyer-plan", 'original', 'save', null);
            }
          
            $this->formMode = false;
            $this->resetInputFields();
    
            $this->flash('success',trans('messages.add_success_message'));
            
            return redirect()->route('admin.buyer-plans');
        }else{
            $this->alert('error',trans('messages.error_message'));
        }
    }

    public function edit($id)
    {
        $plan = BuyerPlan::findOrFail($id);

        $this->plan_id = $id;
        $this->title  = $plan->title;
        $this->amount  = $plan->amount;
        $this->position = $plan->position;
        $this->type   = $plan->type;
        $this->description = $plan->description;
        $this->status = $plan->status;
        $this->originalImage = $plan->image_url;

        $this->formMode = true;
        $this->updateMode = true;

        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update(){
        
         $validateArr = [
              'title' => 'required',
              'amount' => 'required|numeric|min:0|max:99999999.99',
              'type'  => 'required|in:monthly,yearly',
              'position'    => ['required', 'numeric', 'min:0', 'max:99999'],
              'description' => 'required|without_spaces',
              'status' => 'required',
          ];
  
          if($this->image || $this->removeImage){
              $validateArr['image'] = 'required|image|max:'.config('constants.img_max_size');
          }
    
          $validatedData = $this->validate($validateArr, ['without_spaces' => 'The :attribute field is required'],['title'  => 'plan name']);
  
          $validatedData['status'] = $this->status;
  
          $plan = BuyerPlan::find($this->plan_id);
  
          // Check if the photo has been changed
          $uploadId = null;
          if ($this->image) {
              $uploadId = $plan->packageImage->id;
              uploadImage($plan, $this->image, 'plan/image/',"plan", 'original', 'update', $uploadId);
          }
          
          $updateRecord = $this->except(['search','formMode','updateMode','plan_id','image','originalImage','page','paginators']);
  
          if($plan){
              Stripe::setApiKey(config('app.stripe_secret_key'));
  
              $stripePlan = StripPlan::update(
                  $plan->plan_stripe_id,
                  [ 
                      'nickname' => $this->title,
                  ]
              );
  
              $updateRecord['plan_json']  = json_encode($stripePlan);
      
              $plan->update($updateRecord);
        
              $this->formMode = false;
              $this->updateMode = false;
        
              $this->flash('success',trans('messages.edit_success_message'));
              $this->resetInputFields();
              return redirect()->route('admin.buyer-plans');
          }else{
              $this->alert('error',trans('messages.error_message'));
          }
         
      }
  
      public function deleteConfirm($id){
          try{
              $model = BuyerPlan::find($id);
              
              $stripe = Stripe::setApiKey(config('app.stripe_secret_key'));
      
              $stripePlan = StripPlan::retrieve($model->plan_stripe_id);
  
              $stripePlan->delete();
  
              if($model->uploads()->first()){
                  $upload_id = $model->uploads()->first()->id;
                  if($upload_id &&  deleteFile($upload_id)){
                      $model->uploads()->delete();
                  }
              }
              
              $model->delete();
      
              $this->emit('refreshTable');
  
              $this->alert('success', trans('messages.delete_success_message'));
          }catch(\Exception $e){
              $this->alert('error', $e->getMessage());
          } 
    }
  
    public function show($id){
        $this->plan_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    private function resetInputFields(){
        $this->title = '';
        $this->amount = '';
        $this->type = '';
        $this->position = '';
        $this->description = '';
        $this->status = 1;
        $this->image =null;
        $this->originalImage = '';
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function confirmedToggleAction($data){
        $id = $data['id'];
        $type = $data['type'];
        
        $model = BuyerPlan::find($id);
        $model->update([$type => !$model->$type]);
        $this->alert('success', trans('messages.change_status_success_message'));
    }

    public function changeStatus($statusVal){
        $this->status = (!$statusVal) ? 1 : 0;
    }
    
    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }
}

// Custom validation rule
Validator::extend('without_spaces', function ($attribute, $value, $parameters, $validator) {
    $cleanValue = trim(strip_tags($value));
    $replacedVal = trim(str_replace(['&nbsp;', '&ensp;', '&emsp;'], ['','',''], $cleanValue));
    
    if (empty($replacedVal)) {
        return false;
    }
    return true;
});

// Custom validation rule
Validator::extend('valid_extensions', function ($attribute, $value, $parameters, $validator) {
    $allowedExtensions = $parameters;
    $fileExtension = strtolower($value->getClientOriginalExtension());

    return in_array($fileExtension, $allowedExtensions);
});

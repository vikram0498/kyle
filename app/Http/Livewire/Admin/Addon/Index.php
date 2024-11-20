<?php

namespace App\Http\Livewire\Admin\Addon;

use Illuminate\Support\Facades\Gate;
use App\Models\Addon;
use Stripe\Stripe;
use Stripe\Product as StripProduct;
use Stripe\Price as StripPrice;
use Stripe\Subscription;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;


class Index extends Component
{
   
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    protected $addons = null;

    public  $title, $price, $credit,$position, $status = 1, $viewMode = false;

    public $addon_id =null;

    protected $listeners = [
       'show', 'edit', 'confirmedToggleAction','deleteConfirm'
    ];

    public function mount(){
        abort_if(Gate::denies('addon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {        
        return view('livewire.admin.addon.index');
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
        $validatedData = $this->validate([
            'title'  => 'required',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'credit' => 'required|numeric',
            'position'    => ['required', 'numeric', 'min:0', 'max:99999','unique:addons,position,NULL,id,deleted_at,NULL'],
            'status' => 'required',
        ],[],['title' => 'name','position'  => 'rank']);
        
        $validatedData['status'] = $this->status;

        $insertRecord = $this->except(['search','formMode','updateMode','addon_id','image','originalImage','page','paginators']);

        Stripe::setApiKey(config('app.stripe_secret_key'));
        // Get the customer's subscription.
        $stripProduct = StripProduct::create([
            'name' => $this->title,
            'description' =>'Additional Credits',
            // 'price' => (float)$this->price * 100,
        ]);

        if($stripProduct){

            $stripPrice = StripPrice::create([
                'unit_amount' => (float)$this->price * 100, // Amount in cents
                'currency' => config('constants.default_currency'),
                'product' => $stripProduct->id, // ID of the custom product you created
            ]);

            $insertRecord['product_stripe_id'] = $stripProduct->id;
            $insertRecord['product_json']  = json_encode($stripProduct);

            $insertRecord['price_stripe_id'] = $stripPrice->id;
            $insertRecord['price_json']  = json_encode($stripPrice);
    
            $addon = Addon::create($insertRecord);
        
            $this->formMode = false;

            $this->resetInputFields();

            $this->flash('success',trans('messages.add_success_message'));
            
            return redirect()->route('admin.addon');
        }else{
            $this->alert('error',trans('messages.error_message'));
        }
       
    }


    public function edit($id)
    {
        $addon = Addon::findOrFail($id);

        $this->addon_id = $id;
        $this->title  = $addon->title;
        $this->price = $addon->price;
        $this->credit = $addon->credit;
        $this->position = $addon->position;
        $this->status = $addon->status;

        $this->formMode = true;
        $this->updateMode = true;

        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update(){
        $validatedData = $this->validate([
            'title' => 'required',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'credit' => 'required|numeric',
            'position'    => ['required', 'numeric', 'min:0', 'max:99999'],
            'status' => 'required',
        ],[],['title' => 'name']);
  
        $validatedData['status'] = $this->status;

        $addon = Addon::find($this->addon_id);

        if($addon){
            $updateRecord = $this->except(['search','formMode','updateMode','addon_id','image','originalImage','page','paginators']);

            Stripe::setApiKey(config('app.stripe_secret_key'));
    

            $product = StripProduct::retrieve($addon->product_stripe_id);

            $product->name = $this->title;
            $product->description = 'Updated Additional Credits';
            $product->save();

            // $price = StripPrice::retrieve($addon->price_stripe_id);
            // $price->unit_amount = (float)$this->price * 100; // Updated amount in cents
            // $price->save();

            $updateRecord['product_json']  = json_encode($product);

            $updateRecord['price_json']  = json_encode($price);
            
            $addon->update($updateRecord);
      
            $this->formMode = false;
            $this->updateMode = false;
      
            $this->flash('success',trans('messages.edit_success_message'));
            $this->resetInputFields();
            return redirect()->route('admin.addon');
        }else{
            $this->alert('error',trans('messages.error_message'));
        }
    }

    public function delete($id)
    {
        $this->confirm('Are you sure you want to delete it?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' => 'Yes, Delete!',
            'cancelButtonText' => 'No, cancel!',
            'onConfirmed' => 'deleteConfirm',
            'onCancelled' => function () {
                // Do nothing or perform any desired action
            },
            'inputAttributes' => ['deleteId' => $id],
        ]);
    }

    public function deleteConfirm($id){
        $model = Addon::find($id);

        Stripe::setApiKey(config('app.stripe_secret_key'));
        
        $product = new StripProduct( $model->product_stripe_id);
        $prices = StripProduct::retrieve($model->product_stripe_id)->prices;
        // Set the product ID
  
        if($prices){
            // Delete the prices
            foreach ($prices as $price) {
                try {
                    Stripe::setApiKey(config('app.stripe_secret_key'));
                    $price->delete();
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
        
        // Delete the product
        $product->delete();

        $model->delete();

        $this->emit('refreshTable');
        
        $this->emit('refreshLivewireDatatable');

        $this->alert('success', trans('messages.delete_success_message'));
    }

    public function show($id){
        $this->addon_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    private function resetInputFields(){
        $this->title = '';
        $this->price = '';
        $this->credit = '';
        $this->status = 1;
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function confirmedToggleAction($data)
    {
        $id = $data['id'];
        $type = $data['type'];

        $model = Addon::find($id);
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

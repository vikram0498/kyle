<?php

namespace App\Http\Livewire\Admin\Plan;

use Illuminate\Support\Facades\Gate;
use App\Models\Plan;
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

    protected $plans = null;

    public  $title, $month_amount, $year_amount, $status = 1, $description='',$image=null,$viewMode = false,$originalImage;

    public $plan_id =null;

    protected $listeners = [
        'show', 'edit', 'confirmedToggleAction','deleteConfirm'
    ];

    public function mount(){
        abort_if(Gate::denies('plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.plan.index');
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
                'title'  => 'required',
                'month_amount' => 'required|numeric|min:0|max:99999999.99',
                'year_amount' => 'required|numeric|min:0|max:99999999.99',
                'description' => 'required',
                'status' => 'required',
                'image' => 'required|image|max:'.config('constants.img_max_size'),
            ],[],['title'  => 'plan name']
        );
        
        $validatedData['status'] = $this->status;

        $insertRecord = $this->except(['search','formMode','updateMode','plan_id','image','originalImage','page','paginators']);

        $plan = Plan::create($insertRecord);
    
        uploadImage($plan, $this->image, 'plan/image/',"plan", 'original', 'save', null);

        $this->formMode = false;

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.plan');
       
    }


    public function edit($id)
    {
        $plan = Plan::findOrFail($id);

        $this->plan_id = $id;
        $this->title  = $plan->title;
        $this->month_amount = $plan->month_amount;
        $this->year_amount = $plan->year_amount;
        $this->description = $plan->description;
        $this->status = $plan->status;
        $this->originalImage = $plan->image_url;

        $this->formMode = true;
        $this->updateMode = true;

        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update(){
        $validatedData = $this->validate([
            'title' => 'required',
            'month_amount' => 'required|numeric|min:0|max:99999999.99',
            'year_amount' => 'required|numeric|min:0|max:99999999.99',
            'description' => 'required',
            'status' => 'required',
        ],[],['title'  => 'plan name']);

        if($this->image){
            $validatedData['image'] = 'required|image|max:'.config('constants.img_max_size');
        }
  
        $validatedData['status'] = $this->status;

        $plan = Plan::find($this->plan_id);

        // Check if the photo has been changed
        $uploadId = null;
        if ($this->image) {
            $uploadId = $plan->packageImage->id;
            uploadImage($plan, $this->image, 'plan/image/',"plan", 'original', 'update', $uploadId);
        }
        
        $updateRecord = $this->except(['search','formMode','updateMode','plan_id','image','originalImage','page','paginators']);

        $plan->update($updateRecord);
  
        $this->formMode = false;
        $this->updateMode = false;
  
        $this->flash('success',trans('messages.edit_success_message'));
        $this->resetInputFields();
        return redirect()->route('admin.plan');

    }

    public function deleteConfirm($id){
        $model = Plan::find($id);
        
        $upload_id = $model->uploads()->first()->id;
        if($upload_id &&  deleteFile($upload_id)){
            $model->uploads()->delete();
        }
        
        $model->delete();
        $this->alert('success', trans('messages.delete_success_message'));
    }

    public function show($id){
        $this->plan_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    private function resetInputFields(){
        $this->title = '';
        $this->month_amount = '';
        $this->year_amount = '';
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
        
        $model = Plan::find($id);
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

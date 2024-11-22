<?php

namespace App\Http\Livewire\Admin\AdvertiseBanner;

use App\Models\AdvertiseBanner;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{   
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    protected $adBanners = null;

    public  $advertiser_name, $ad_name, $target_url, $impressions_purchased,$impressions_served, $impressions_count,$click_count,$start_date,$end_date,$page_type,$start_time,$end_time, $status = 0, $image=null, $viewMode = false,$adPerformanceLogsViewMode = false,$originalImage;

    public $adBanner_id =null;

    public $removeImage=false;

    protected $listeners = [
        'show','showAdPerformanceLog', 'edit', 'confirmedToggleAction','deleteConfirm'
    ];

    public function mount(){
        abort_if(Gate::denies('ad_banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.advertise-banner.index');
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
                'advertiser_name'       => ['required'],
                'ad_name'               => ['required'],
                'target_url'            => ['required','url'],
                'impressions_purchased'     => ['required', 'numeric', 'min:0', 'max:999999'],
                'start_date'                => ['required', 'date','after_or_equal:today','before_or_equal:end_date'],
                'end_date'                  => ['required', 'date','after_or_equal:start_date'],               
                'image'                     => ['required', 'image', 'max:'.config('constants.img_max_size')],
                'status'                    => ['required', 'numeric', 'in:' . implode(',', array_keys(config('constants.ad_banner_status')))],
            ],['without_spaces' => 'The :attribute field is required'],['advertiser_name'  => 'advertiser name', 'ad_name' => 'ad name','target_url'  => 'target url']
        );
        
        $insertRecord = $this->except(['search','formMode','updateMode','adBanner_id','impressions_served','impressions_count','click_count','image','originalImage','page','paginators']);

        $adBanner = AdvertiseBanner::create($insertRecord);
    
        $uploadedImage = uploadImage($adBanner, $this->image, 'adBanner/image/',"adBanner", 'original', 'save', null);

        $this->formMode = false;
        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.ad-banner');
    }


    public function edit($id)
    {
        $adBanner = AdvertiseBanner::findOrFail($id);

        $this->adBanner_id = $id;
        $this->advertiser_name  = $adBanner->advertiser_name;
        $this->ad_name  = $adBanner->ad_name;
        $this->target_url   = $adBanner->target_url;
        $this->impressions_purchased = $adBanner->impressions_purchased;
        $this->start_date = $adBanner->start_date->format(config('constants.date_format'));
        $this->end_date = $adBanner->end_date->format(config('constants.date_format'));
        $this->start_time = $adBanner->start_time->format('H:i');
        $this->end_time = $adBanner->end_time->format('H:i');
        $this->page_type   = $adBanner->page_type;
        $this->status = $adBanner->status;
        $this->originalImage = $adBanner->image_url;

        $this->formMode = true;
        $this->updateMode = true;

        $this->resetValidation();
        $this->initializePlugins();
    }

    public function update(){
       
        $rules =  [
            'advertiser_name'       => ['required'],
            'ad_name'               => ['required'],
            'target_url'            => ['required','url'],
            'impressions_purchased'     => ['required', 'numeric', 'min:0', 'max:999999'],
            'start_date'                => ['required', 'date','after_or_equal:today','before_or_equal:end_date'],
            'end_date'                  => ['required', 'date','after_or_equal:start_date'],               
            'image'                     => ['nullable', 'image', 'max:'.config('constants.img_max_size')],
            'status'                    => ['required', 'numeric', 'in:' . implode(',', array_keys(config('constants.ad_banner_status')))],
        ];

        $starDateTime = Carbon::parse($this->start_date.' '.$this->start_time);
        $endDateTime = Carbon::parse($this->start_date.' '.$this->end_time);
        $currentDateTime = Carbon::now();

        // Check if start time is greater than current time
        if ($starDateTime->lt($currentDateTime)) {
            $rules['start_time'] = '|after:now';
        }

        // Check if end time is greater than start time
        if ($endDateTime->lt($starDateTime)) {
            $rules['end_time'] = '|after:start_time';
        }

        $validatedData = $this->validate(
            $rules,['without_spaces' => 'The :attribute field is required'],['advertiser_name'  => 'advertiser name', 'ad_name' => 'ad name','target_url'  => 'target url']
        );

        $adBanner = AdvertiseBanner::find($this->adBanner_id);

        // Check if the photo has been changed
        $uploadId = null;
        if ($this->image) {
            $uploadId = $adBanner->adBannerImage?->id;
            $uploadId ? uploadImage($adBanner, $this->image, 'adBanner/image/', "adBanner", 'original', 'update', $uploadId) : uploadImage($adBanner, $this->image, 'adBanner/image/', "adBanner", 'original', 'save');
        }
        
        $updateRecord = $this->except(['search','formMode','updateMode','adBanner_id','impressions_served','impressions_count','click_count','image','originalImage','page','paginators']);

        if($adBanner){
    
            $adBanner->update($updateRecord);
      
            $this->formMode = false;
            $this->updateMode = false;
      
            $this->flash('success',trans('messages.edit_success_message'));
            $this->resetInputFields();
            return redirect()->route('admin.ad-banner');
        }else{
            $this->alert('error',trans('messages.error_message'));
        }       
    }

    public function deleteConfirm($id){
        try{
            $model = AdvertiseBanner::find($id);
            
            if($model->uploads()->first()){
                $upload_id = $model->uploads()->first()->id;
                if($upload_id &&  deleteFile($upload_id)){
                    $model->uploads()->delete();
                }
            }
            
            $model->delete();
    
            $this->emit('refreshTable');

            $this->emit('refreshLivewireDatatable');
    
            $this->alert('success', trans('messages.delete_success_message'));
        }catch(\Exception $e){
            $this->alert('error', $e->getMessage());
        }       
    }

    public function show($id){
        $this->adBanner_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    public function showAdPerformanceLog($id)
    {
        $this->adBanner_id = $id;
        $this->formMode = false;
        $this->adPerformanceLogsViewMode = true;
    }

    private function resetInputFields(){
        $this->advertiser_name = '';
        $this->ad_name = '';
        $this->target_url = '';
        $this->impressions_purchased = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->page_type = '';
        $this->status = 0;
        $this->image =null;
        $this->originalImage = '';
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->adPerformanceLogsViewMode = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function confirmedToggleAction($data){
        $id = $data['id'];
        $type = $data['type'];
        
        $model = AdvertiseBanner::find($id);
        $model->update([$type => !$model->$type]);
        $this->alert('success', trans('messages.change_status_success_message'));
    }

    // public function changeStatus($statusVal){
    //     $this->status = (!$statusVal) ? 1 : 0;
    // }
    
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
    
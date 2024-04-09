<?php

namespace App\Http\Livewire\Admin\Setting;

use Gate;
use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;


class Index extends Component
{
    use LivewireAlert, WithFileUploads;

    // protected $layout = null;

    public $tab = 'upload_buyer_video', $state = [], $removeFile = [], $previewVideoValue = [];

    protected $listeners = [
        'changeTab','previewVideo',
    ];

    public function mount(){
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->state = Setting::where('group',$this->tab)->where('status',1)->pluck('value','key')->toArray();

        $this->removeFile['remove_buyer_video'] = false;
        $this->previewVideoValue['buyer_video']['video_url'] = null;
        $this->previewVideoValue['buyer_video']['video_extenstion'] = null;


    }

    public function changeTab($tab){
        $this->tab = $tab;
        $this->mount();
        $this->initializePlugins();
    }


    public function render()
    {
        $settings = Setting::where('group',$this->tab)->where('status',1)->get();
        $allSettingType = Setting::groupBy('group')->where('status',1)->pluck('group');

        return view('livewire.admin.setting.index',compact('allSettingType','settings'));
    }


    public function update(){
        $settings = Setting::where('group',$this->tab)->where('status',1)->get();
        $rules = [];
        foreach ($settings as $setting) {
            if($setting){

                if ($setting->type == 'text') {
                    $rules['state.'.$setting->key] = 'required|string';
                }

                if ($setting->type == 'number') {
                    $rules['state.'.$setting->key] = 'required|numeric';
                }

                if ($setting->type == 'text_area') {
                    if($setting->group == 'mail'){
                        $rules['state.'.$setting->key] = 'nullable';
                    }else{
                        $textAreaValidation = 'required|';
                        $rules['state.'.$setting->key] = $textAreaValidation.'strip_tags';
                    }
                }

                if($setting->type == 'image' && (!$this->removeFile['remove_'.$setting->key])){
                    $dimensions = explode(' × ',$setting->details);
                    $dimensionsDetails[$setting->key] = $setting->details;

                    if(isset($dimensions[0]) && isset($dimensions[1])){
                        $rules['state.'.$setting->key] = 'nullable|image|dimensions:max_width='.$dimensions[0].',max_height='.$dimensions[1].'|max:'.config('constants.img_max_size').'|mimes:jpeg,png,jpg,svg,PNG,JPG,SVG|';
                    }else{
                        $rules['state.'.$setting->key] = 'nullable|image|max:'.config('constants.img_max_size').'|mimes:jpeg,png,jpg,svg,PNG,JPG,SVG|';
                    }
                }elseif($setting->type == 'image' && $this->removeFile['remove_'.$setting->key]){
                    $rules['state.'.$setting->key] = '';
                }
                
                if($setting->type == 'video' && (!$this->removeFile['remove_'.$setting->key])){
                    $rules['state.'.$setting->key] = 'nullable|max:'.config('constants.video_max_size').'|mimetypes:video/webm,video/mp4, video/avi,video/wmv,video/flv,video/mov';
                }elseif($setting->type == 'video' && $this->removeFile['remove_'.$setting->key]){
                    $rules['state.'.$setting->key] = '';
                }

                if ($setting->type == 'toggle') {
                    $rules['state.'.$setting->key] = 'required|in:'.$setting->details;
                }

            }
        }

        $videoMaxSize = (int)config('constants.video_max_size')/1024;

        $customMessages = [
            'required' => 'The field is required.',
            'strip_tags'=>'The field is required.',
          
            'state.introduction_video.video' => 'The introduction video must be an video.',
            'state.introduction_video.mimes' => 'The introduction video must be webm, mp4, avi, wmv, flv, mov.',
            'state.introduction_video.max'   => 'The favicon icon maximum size is '.$videoMaxSize.' MB.',

            'state.reminder_one_mail_content.string'=> 'The reminder 1 mail content must be a string.',
            'state.reminder_two_mail_content.string'=> 'The reminder 2 mail content must be a string.',
            'state.reminder_three_mail_content.string'=> 'The reminder 3 mail content must be a string.',
        ];

        $validatedData = $this->validate($rules,$customMessages);

        DB::beginTransaction();
        try {
           
            foreach($validatedData['state'] as $key=>$stateVal){
                $setting = Setting::where('key',$key)->where('status',1)->first();

                $setting_value = $stateVal;

                if($setting->type == 'image'){

                    $uploadId = $setting->image ? $setting->image->id : null;

                    if ($stateVal) {
                        if($uploadId){
                            uploadImage($setting, $stateVal, 'settings/images/',"setting", 'original', 'update', $uploadId);
                        }else{
                            uploadImage($setting, $stateVal, 'settings/images/',"setting", 'original', 'save', null);
                        }
                    }else{
                        if($uploadId && $this->removeFile['remove_'.$key]){
                            deleteFile($uploadId);
                        }
                    }

                    $setting_value = null;
                }

                if($setting->type == 'video'){

                    $uploadId = $setting->video ? $setting->video->id : null;
                    if ($stateVal) {
                        $uploadvideoFile = null;
                        if($uploadId){
                            $uploadvideoFile = uploadImage($setting, $stateVal, 'settings/videos/',"setting", 'original', 'update', $uploadId);
                        }else{
                            $uploadvideoFile = uploadImage($setting, $stateVal, 'settings/videos/',"setting", 'original', 'save', null);
                        }

                        if($uploadvideoFile){
                            $this->previewVideoValue[$setting->key]['video_url'] = $uploadvideoFile->file_url;
                            $this->previewVideoValue[$setting->key]['video_extension'] = $uploadvideoFile->extension;
                        }
                        
                    }else{
                        if($uploadId && $this->removeFile['remove_'.$key]){
                            deleteFile($uploadId);

                            $this->previewVideoValue[$setting->key]['video_url'] = null;
                            $this->previewVideoValue[$setting->key]['video_extenstion'] = null;
                        }
                    }

                    $setting_value = null;
                }

                if($setting->type == 'text_area'){
                    if (!is_null($setting_value) && $setting_value !== '') {
                        $cleanValue = trim(strip_tags($setting_value));
                        $replacedVal = trim(str_replace(['&nbsp;', '&ensp;', '&emsp;'], ['','',''], $cleanValue));
                        if (empty($replacedVal)) {
                            $setting_value = null;
                        } else {
                            $data[$key] = $setting_value;
                        }
                    }
                }

                $setting->value = $setting_value;
                $setting->save();

                DB::commit();
            }

            $this->alert('success',trans('messages.edit_success_message'));

        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            $this->alert('error',trans('messages.error_message'));
        }

    }

    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }

    public function previewVideo($keyName){
        $setting = Setting::where('group',$this->tab)->where('key',$keyName)->where('status',1)->first();
       
        if($setting->video || ($this->previewVideoValue[$setting->key]['video_url'] && $this->previewVideoValue[$setting->key]['video_extension'])){
            $this->previewVideoValue[$setting->key]['video_url'] = $setting->video ? $setting->video_url : $this->previewVideoValue[$setting->key]['video_url'];
            $this->previewVideoValue[$setting->key]['video_extension'] = $setting->video ? $setting->video->extension : $this->previewVideoValue[$setting->key]['video_extension'];

            if($this->previewVideoValue[$setting->key]['video_url'] && $this->previewVideoValue[$setting->key]['video_extension']){
                $this->dispatchBrowserEvent('openVideoPreviewModal',['videoUrl'=>$this->previewVideoValue[$setting->key]['video_url'],'extension'=>$this->previewVideoValue[$setting->key]['video_extension']]);
            }else{
                $this->alert('warning','"Sorry, this video is currently unavailable.');
            }
        }else{
            $this->alert('warning','"Sorry, this video is currently unavailable.');
        }

    }
}

<?php

namespace App\Http\Livewire\Admin\Video;

use Illuminate\Support\Facades\Gate;
use App\Models\Video;
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

    protected $videos = null;

    public  $title, $status = 1, $description='', $video=null,$viewMode = false;

    public $video_id =null;

    protected $listeners = [
        'confirmedToggleAction','deleteConfirm'
    ];

    public $sortColumnName = 'created_at', $sortDirection = 'desc', $row_list = 10, $numberOfrowsList;

    public function mount(){
        abort_if(Gate::denies('video_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $this->numberOfrowsList = config('constants.number_of_rows');
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


    public function render()
    {
        $this->search = str_replace(',', '', $this->search);
         $this->videos = Video::query()
            ->where('title', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orderBy($this->sortColumnName, $this->sortDirection)
            ->paginate($this->row_list);

        $allVideos = $this->videos;
        return view('livewire.admin.video.index',compact('allVideos'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->formMode = true;
        $this->initializePlugins();
    }

    public function store()
    {
        $validatedData = $this->validate([
            'title'  => 'required',
            'description' => 'required',
            'status' => 'required',
            'video' => 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv|max:'.config('constants.video_max_size'),
        ]);
        
        $validatedData['status'] = $this->status;

        $insertRecord = $this->except(['search','formMode','updateMode','video_id','video','page','paginators']);

        $video = Video::create($insertRecord);
    
        uploadImage($video, $this->video, 'video_management/video/',"video", 'original', 'save', null);

        $this->formMode = false;

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
        
        return redirect()->route('admin.video');
       
    }


    public function edit($id)
    {
        $video = Video::findOrFail($id);

        $this->video_id = $id;
        $this->title  = $video->title;
        $this->description = $video->description;
        $this->status = $video->status;

        $this->formMode = true;
        $this->updateMode = true;
        $this->initializePlugins();
    }

    public function update(){
        $validatedData = $this->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($this->video){
            $validatedData['video'] = 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv|max:'.config('constants.video_max_size');
        }
  
        $validatedData['status'] = $this->status;

        $video = Video::find($this->video_id);

        // Check if the photo has been changed
        $uploadId = null;
        if ($this->video) {
            $uploadId = $video->packageImage->id;
            uploadImage($video, $this->video, 'video_management/video/',"video", 'original', 'update', $uploadId);
        }
        
        $updateRecord = $this->except(['search','formMode','updateMode','video_id','video','page','paginators']);

        $video->update($updateRecord);
  
        $this->formMode = false;
        $this->updateMode = false;
  
        $this->flash('success',trans('messages.edit_success_message'));
        $this->resetInputFields();
        return redirect()->route('admin.video');

    }

    public function delete($id)
    {
        $this->confirm('Are you sure you want to delete it?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' => 'Yes, change it!',
            'cancelButtonText' => 'No, cancel!',
            'onConfirmed' => 'deleteConfirm',
            'onCancelled' => function () {
                // Do nothing or perform any desired action
            },
            'inputAttributes' => ['deleteId' => $id],
        ]);
    }

    public function deleteConfirm($id){
        $model = Video::find($id);
        
        $upload_id = $model->uploads()->first()->id;
        if($upload_id &&  deleteFile($upload_id)){
            $model->uploads()->delete();
        }
        
        $model->delete();
        $this->alert('success', trans('messages.delete_success_message'));
    }

    public function show($id){
        $this->video_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    private function resetInputFields(){
        $this->title = '';
        $this->description = '';
        $this->status = 1;
        // $this->video_image =null;
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;

    }

    public function confirmedToggleAction($data)
    {
        $id = $data['id'];
        $type = $data['type'];
        
        $model = Video::find($id);
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

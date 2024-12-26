<?php

namespace App\Http\Livewire\Admin\ChatReports;

use App\Models\Report;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;
    public $search = null, $viewMode = false;
    protected $reports = null; 
    public $report_id =null;
    protected $listeners = [
        'show','cancel'
    ];
   
    public function mount(){
        abort_if(Gate::denies('chat_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {     
        return view('livewire.admin.chat-reports.index');
    }

    public function show($id){
        $this->report_id = $id;
        $this->viewMode = true;
    }

    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }    
    
    public function cancel(){
        $this->viewMode = false;
        // $this->resetInputFields();
        // $this->resetValidation();
    }
}

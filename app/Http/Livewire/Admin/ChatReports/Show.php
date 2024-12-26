<?php

namespace App\Http\Livewire\Admin\ChatReports;

use App\Models\Report;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($report_id){
        $this->details = Report::find($report_id);
    }

    public function render()
    {
        return view('livewire.admin.chat-reports.show');
    }
}

<?php

namespace App\Http\Livewire\Admin\Video;

use App\Models\Video;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($video_id){
        $this->details = Video::find($video_id);
    }

    public function render()
    {
        return view('livewire.admin.video.show');
    }
}

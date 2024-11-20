<?php

namespace App\Http\Livewire\Admin\AdvertiseBanner;

use App\Models\AdvertiseBanner;
use Livewire\Component;

class Show extends Component
{
    protected $layout = null;
    
    public $details;

    public function mount($adBanner_id){
        $this->details = AdvertiseBanner::find($adBanner_id);
    }

    public function render()
    {
        return view('livewire.admin.advertise-banner.show');
    }
}

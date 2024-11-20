<?php

namespace App\Http\Livewire\Admin\AdvertiseBanner;

use App\Models\AdPerformanceLog;
use App\Models\AdvertiseBanner;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;


class ShowAdPerformaceLogs extends Component
{
    use  LivewireAlert, WithPagination;
    protected $layout = null;
    public $search = null;
    public $sortColumnName = 'created_at', $sortDirection = 'desc', $perPage = 10;

    protected  $allEventRequest= null;
    public $adBanner_id = null;

    public function mount($adBanner_id)
    {        
        $this->adBanner_id = $adBanner_id;             
    }

    public function render()
    {
        $searchValue = $this->search;
        $statusSearch = null;

        $adPerformaceLogs = AdPerformanceLog::query()
        ->where(function ($query) use ($searchValue,$statusSearch) {
            $query->where('event_type','like','%'.$searchValue.'%')
            ->orWhere('user_ip','like',$searchValue.'%')
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->where('advertise_banner_id',$this->adBanner_id)->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);

        $adBanner = AdvertiseBanner::findOrFail($this->adBanner_id);

        return view('livewire.admin.advertise-banner.show-ad-performace-logs',compact('adPerformaceLogs','adBanner'));
    }  


    public function updatedPerPage(){
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function sortBy($columnName)
    {
        $this->resetPage();

        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumnName = $columnName;
    }
    
    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } 

}

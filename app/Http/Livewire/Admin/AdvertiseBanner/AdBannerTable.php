<?php

namespace App\Http\Livewire\Admin\AdvertiseBanner;

use App\Models\AdvertiseBanner;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;


class AdBannerTable extends Component
{
    use WithPagination;

    public $search = null;
    
    public $sortColumnName = 'created_at', $sortDirection = 'desc', $perPage = 10;
    
    protected $listeners = [
        'refreshTable' =>'render'
    ];

    public function render()
    {
        $searchValue = $this->search;
        $statusSearch = null;

        $bannerPageTypes = config('constants.banner_page_type');
        // Check if the search value matches a readable page type.
        $searchPageType = array_search(ucwords($searchValue), $bannerPageTypes);

        $adBanners = AdvertiseBanner::query()
        ->where(function ($query) use ($searchValue,$searchPageType) {
            $query->where('advertiser_name','like','%'.$searchValue.'%')
            ->orWhere('ad_name','like',$searchValue.'%')
            ->orWhere('page_type','like',$searchValue.'%')
            ->orWhere('impressions_purchased','like',$searchValue.'%')
            ->orWhereRaw("date_format(start_date, '".config('constants.search_date_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(end_date, '".config('constants.search_date_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(start_time, '".config('constants.search_time_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(end_time, '".config('constants.search_time_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);

             // Add condition for page_type if a match is found
             if ($searchPageType !== false) {
                $query->orWhere('page_type', $searchPageType);
            }

        })->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.advertise-banner.ad-banner-table',compact('adBanners'));
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

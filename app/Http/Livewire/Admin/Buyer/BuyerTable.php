<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class BuyerTable extends Component
{
    use WithPagination;

    public $search = null;
    
    public $sortColumnName = 'updated_at', $sortDirection = 'desc', $perPage = 10;
    
    protected $listeners = [
        
    ];

    public function render()
    {
        $searchValue = $this->search;
        $statusSearch = null;
        if(Str::contains('active', strtolower($searchValue))){
            $statusSearch = 1;
        }else if(Str::contains('inactive', strtolower($searchValue))){
            $statusSearch = 0;
        }

        $buyers = Buyer::query()
        ->selectRaw('*, CONCAT(first_name, " ", last_name) as name')
        ->where(function ($query) use ($searchValue,$statusSearch) {
            $query->whereRaw("concat(first_name, ' ', last_name) like ?", ["%{$searchValue}%"])
            ->orWhere('status',$statusSearch)
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(updated_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.buyer.buyer-table',compact('buyers'));
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

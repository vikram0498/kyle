<?php

namespace App\Http\Livewire\Admin\SearchLog;

use App\Models\SearchLog;
use Livewire\Component;
use Livewire\WithPagination;

class SearchLogTable extends Component
{
    use WithPagination;

    public $search = null;
    
    public $sortColumnName = 'created_at', $sortDirection = 'desc', $perPage = 10;
    
    protected $listeners = [
        
    ];

    public function render()
    {
        $searchValue = $this->search;

        $searchLogs = SearchLog::query()
        ->join('users', 'search_logs.user_id', '=', 'users.id')
        ->where(function ($query) use ($searchValue) {
            $query->whereRelation('seller','name','like','%'.$searchValue.'%')
            ->orWhereRaw("date_format(search_logs.created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->orderBy($this->sortColumnName, $this->sortDirection)
        ->select('search_logs.*')
        ->paginate($this->perPage);

        return view('livewire.admin.search-log.search-log-table',compact('searchLogs'));
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

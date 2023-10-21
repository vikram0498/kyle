<?php

namespace App\Http\Livewire\Admin\Support;

use App\Models\Support;
use Livewire\Component;
use Livewire\WithPagination;

class SupportTable extends Component
{
    use WithPagination;

    public $search = null;
    
    public $sortColumnName = 'created_at', $sortDirection = 'desc', $perPage = 10;
    
    protected $listeners = [
        
    ];

    public function render()
    {
        $searchValue = strtolower($this->search);
        $supports = Support::query()
        ->where(function ($query) use ($searchValue) {
            $query->where('name','like','%'.$searchValue.'%')
            ->orWhere('email','like','%'.$searchValue.'%')
            ->orWhere('phone_number','like','%'.$searchValue.'%')
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.support.support-table',compact('supports'));
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

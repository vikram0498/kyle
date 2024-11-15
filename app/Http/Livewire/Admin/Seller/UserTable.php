<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class UserTable extends Component
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
        
        //Search status
        $statusSearch = null;
        if(Str::contains('active', strtolower($searchValue))){
            $statusSearch = 1;
        }else if(Str::contains('block', strtolower($searchValue))){
            $statusSearch = 0;
        }

        //Search status
        $levelTypeSearch = null;
        $level_3_status = null;
        if(Str::contains('level 1', strtolower($searchValue))){
            $levelTypeSearch = 1;
        }else if(Str::contains('level 2', strtolower($searchValue))){
            $levelTypeSearch = 2;
        }else if(Str::contains('level 3', strtolower($searchValue))){
            $levelTypeSearch = 3;
        }

        $users = User::query()->withCount(['purchasedBuyers','buyers as buyers_count' => function($q) {
            $q->whereColumn('user_id', '!=', 'users.id');
        }])->where(function ($query) use($searchValue,$statusSearch,$levelTypeSearch) {
            $query->where('name', 'like', '%'.$searchValue.'%')
            ->orWhere('email', 'like', '%'.$searchValue.'%')
            ->orWhere('is_active', $statusSearch)
            ->orWhere('level_type', $levelTypeSearch)
            ->orWhereRaw("date_format(created_at, '".config('constants.search_date_format')."') like ?", ['%'.$searchValue.'%']);
        })
        ->whereHas('roles',function($query){
            $query->whereIn('id',[config('constants.roles.seller')]);
        })
        ->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);


        return view('livewire.admin.seller.user-table',compact('users'));
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

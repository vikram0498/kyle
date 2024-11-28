<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\User;

class BuyerTable extends Component
{
    use WithPagination;

    public $search = null;
    
    public $sortColumnName = 'buyers.updated_at', $sortDirection = 'desc', $perPage = 10;
    
    protected $listeners = [
        'refreshTable' =>'render'
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
        ->where(function ($query) use ($searchValue,$statusSearch) {
            $query->whereRelation('userDetail', 'name', 'like',  ["%{$searchValue}%"])
            ->orWhereRelation('userDetail', 'email', 'like',  ["%{$searchValue}%"])
            ->orWhereRelation('userDetail', 'is_active', '=',  $statusSearch)
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(updated_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        });
        
        /*->whereHas('userDetail', function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('id', config('constants.roles.buyer'));
            });
        });*/

        if($this->sortColumnName == 'name' || $this->sortColumnName == 'users.status'){
            $buyers = $buyers->orderBy(User::select($this->sortColumnName)->whereColumn('users.id', 'buyers.buyer_user_id'), $this->sortDirection);
        } else {
            $buyers = $buyers->orderBy($this->sortColumnName, $this->sortDirection);
        }
        $buyers = $buyers->paginate($this->perPage);

        return view('livewire.admin.buyer.buyer-table',compact('buyers'));
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

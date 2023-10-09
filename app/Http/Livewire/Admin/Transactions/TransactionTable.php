<?php

namespace App\Http\Livewire\Admin\Transactions;

use Illuminate\Support\Str;
use App\Models\Transaction;
use Livewire\Component;

class TransactionTable extends Component
{
    public $search = '';
    
    public $sortColumnName = 'created_at', $sortDirection = 'desc', $paginationLength = 10;
    
    protected $listeners = [
        'updatePaginationLength',
    ];
    
    public function render()
    {
        $statusSearch = null;
        $searchValue = $this->search;
        if(Str::contains('active', strtolower($searchValue))){
            $statusSearch = 1;
        }else if(Str::contains('inactive', strtolower($searchValue))){
            $statusSearch = 0;
        }

        $transactions = Transaction::query()->with(['user','plan','addonPlan'])->where(function ($query) use($searchValue,$statusSearch) {
            $query->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })
        ->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->paginationLength);


        return view('livewire.admin.transactions.transaction-table',compact('transactions'));
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

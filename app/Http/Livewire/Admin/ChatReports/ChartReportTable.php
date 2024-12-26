<?php

namespace App\Http\Livewire\Admin\ChatReports;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ChartReportTable extends Component
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

        $reports = Report::query()
        ->where(function ($query) use ($searchValue,$statusSearch) {
            $query->whereRelation('reasonDetail', 'name', 'like',  ["%{$searchValue}%"])
            ->orWhereRelation('reportedBy', 'name', 'like',  ["%{$searchValue}%"])
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->with(['reasonDetail','reportedBy']);


        if ($this->sortColumnName == 'reportedBy') {
            $reports = $reports->whereHas('reportedBy', function ($query) {
                $query->orderBy('name', $this->sortDirection);
            });
        } elseif ($this->sortColumnName == 'reasonDetail') {
            $reports = $reports->whereHas('reasonDetail', function ($query) {
                $query->orderBy('name', $this->sortDirection);
            });
        } else {
            $reports = $reports->orderBy($this->sortColumnName, $this->sortDirection);
        }

        $reports = $reports->paginate($this->perPage);

        return view('livewire.admin.chat-reports.chart-report-table',compact('reports'));
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

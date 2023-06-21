<?php

namespace App\Http\Livewire\Admin\SearchLog;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\SearchLog;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;

class Index extends Component
{
    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false;

    public $sortColumnName = 'created_at', $sortDirection = 'desc', $row_list = 10, $numberOfrowsList;

    protected $listeners = [];

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $purchaseMethods = null;

    public  $status = 1, $viewMode = false;

    public $search_log_id =null;

    public function mount(){
        // abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->purchaseMethods = config('constants.purchase_methods');
        
        $this->numberOfrowsList = config('constants.number_of_rows');
    }

    public function changeNumberOfList($val)  {
        $this->row_list = $val;
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumnName = $columnName;
    }

    public function render() {
        $keyword = str_replace(',', '', $this->search);
        $allSearchLogs = SearchLog::query()
            ->whereHas('seller', function($query) use($keyword){
                $query->where('name', 'like', '%'.$keyword.'%');
            })
            ->orderBy($this->sortColumnName, $this->sortDirection)
            ->paginate($this->row_list);
        return view('livewire.admin.search-log.index',compact('allSearchLogs'));
    }

    public function show($id) {
        $this->search_log_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }
}

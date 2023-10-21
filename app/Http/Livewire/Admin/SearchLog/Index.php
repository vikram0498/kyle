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

    protected $listeners = ['show'];

    public $parkingValues = null, $propertyTypes = null, $propertyFlaws = null, $purchaseMethods = null;

    public  $status = 1, $viewMode = false;

    public $search_log_id =null;

    public function mount(){
        // abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->parkingValues = config('constants.parking_values'); 
        $this->propertyTypes = config('constants.property_types');
        $this->propertyFlaws = config('constants.property_flaws');
        $this->purchaseMethods = config('constants.purchase_methods');
        
    }

    public function render() {
        return view('livewire.admin.search-log.index');
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->resetValidation();
    }

    public function show($id) {
        $this->search_log_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }
}

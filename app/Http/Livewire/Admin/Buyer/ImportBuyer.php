<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Livewire\Component;
use App\Imports\BuyersImport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Validator;

Class ImportBuyer extends Component {

    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;
    
    public $details;

    public $state =[];

    public function mount(){
        
    }

    public function render()
    {
        return view('livewire.admin.buyer.import-buyer');
    }

    public function importBuyers(){

        Validator::make($this->state, [
            'csv_file' => ['required', 'mimes:csv,xlsx,xls,txt']
        ])->validate();

        Excel::import(new BuyersImport, $this->state['csv_file']->store('temp'));
        $this->flash('success',trans('messages.add_success_message'));
        return to_route('admin.buyer');
    }
}
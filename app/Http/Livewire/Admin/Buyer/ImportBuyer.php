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
            'csv_file' => ['required', 'mimes:csv,xlsx,xls']
        ])->validate();

        $import = new BuyersImport;
        Excel::import($import, $this->state['csv_file']->store('temp'));

        $totalCount         = $import->totalRowCount();
        $insertedRowCount   = $import->insertedCount();
        $skippedCount       = $totalCount - $insertedRowCount;

        // dd($totalCount, $insertedRowCount, $skippedCount);

        if($insertedRowCount == 0){
            $this->flash('error',trans('No rows inserted during the import process.'));
            return to_route('admin.import-buyers');
        } else if($skippedCount > 0 && $insertedRowCount > 0){
            $message = "{$insertedRowCount} out of {$totalCount} rows inserted successfully.";
            $this->flash('success',trans($message));
            return to_route('admin.buyer');
        } else if($skippedCount == 0) {
            $this->flash('success',trans('messages.add_success_message'));
            return to_route('admin.buyer');
        }

        // $this->flash('success',trans('messages.add_success_message'));
        // return to_route('admin.buyer');
    }
}
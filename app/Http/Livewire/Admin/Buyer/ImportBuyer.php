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
use Illuminate\Support\Facades\DB;

Class ImportBuyer extends Component {

    use WithPagination, LivewireAlert,WithFileUploads;

    protected $layout = null;
    
    public $details;

    public $state =[];

    public function render()
    {
        return view('livewire.admin.buyer.import-buyer');
    }

    public function importBuyers(){

        Validator::make($this->state, [
            'csv_file' => ['required', 'mimes:csv,xlsx,xls']
        ])->validate();

        try {
            DB::beginTransaction();
            
            $import = new BuyersImport;
            
            Excel::import($import, $this->state['csv_file']->store('temp'));

            //Store log in log file
            $import->logSummary();

            $totalCount          = $import->totalRowCount();
            $insertedRowCount    = $import->insertedCount();
            $softDeletedRowCount = $import->softDeletedCount();
            $skippedCount        = $import->skippedRowCount();


            if ($insertedRowCount == 0) {
                // No rows were inserted during the import process
                $this->flash('error', 'No rows inserted during the import process.');
                return to_route('admin.import-buyers');
            } elseif ($insertedRowCount > 0 && ($skippedCount > 0 || $softDeletedRowCount > 0)) {
                // Rows inserted, some rows skipped or soft-deleted
                $message = "{$insertedRowCount} out of {$totalCount} rows inserted successfully, {$softDeletedRowCount} soft-deleted, {$skippedCount} skipped.";
                $this->flash('success', $message);
                return to_route('admin.buyer');
            } elseif ($insertedRowCount > 0 && $skippedCount == 0 && $softDeletedRowCount == 0) {
                // Only rows inserted, nothing skipped or soft-deleted
                $this->flash('success', "All {$insertedRowCount} rows inserted successfully.");
                return to_route('admin.buyer');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            Log::error('Livewire -> Buyer-> ImportBuyer -> importBuyers()'.$e->getMessage().'->'.$e->getLine());
            $this->alert('error',trans('messages.error_message'));
        }

    }
}
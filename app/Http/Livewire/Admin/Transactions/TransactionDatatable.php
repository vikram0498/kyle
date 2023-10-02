<?php

namespace App\Http\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TransactionDatatable extends LivewireDatatable
{
    public function mount($model = false, $include = [], $exclude = [], $hide = [], $dates = [], $times = [], $searchable = [], $sort = null, $hideHeader = null, $hidePagination = null, $perPage = null, $exportable = false, $hideable = false, $beforeTableSlot = false, $buttonsSlot = false, $afterTableSlot = false, $params = [])
    {
        parent::mount($model, $include, $exclude, $hide, $dates, $times, $searchable, $sort, $hideHeader, $hidePagination, $perPage, $exportable, $hideable, $beforeTableSlot, $buttonsSlot, $afterTableSlot, $params);

        // $this->resetTable();
        $this->perPage = config('livewire-datatables.default_per_page', 10);
        $this->sort(0, 'desc');
        $this->search = null;
        $this->setPage(1);
    }

    public function builder()
    {
        return Transaction::query();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return $id;
            })->sortable()->defaultSort('desc')->hide(),

            Column::index($this)->unsortable(),

            Column::name('payment_intent_id')->label('Payment Intent Id')->sortable()->searchable(),

            Column::callback(['user.name'], function ($name) {
                return ucwords($name);
            })->label(trans('cruds.transaction.fields.user'))->sortable()->searchable(),

            
            Column::callback(['amount'], function ($amount) {
                return "<i class='fa fa-dollar'></i>".number_format($amount,2);
            })->label(trans('cruds.transaction.fields.amount'))->sortable()->searchable(),

            Column::callback(['currency'],function($currency){
                return strtoupper($currency);
            })->label(trans('cruds.transaction.fields.currency'))->sortable()->searchable(),

            Column::callback(['payment_method'],function($payment_method){
                return ucwords($payment_method);
            })->label(trans('cruds.transaction.fields.payment_method'))->sortable()->searchable(),

            Column::callback(['status'],function($status){
                return ucwords($status);
            })->label(trans('cruds.transaction.fields.status'))->sortable()->searchable(),

        ];
    }

}

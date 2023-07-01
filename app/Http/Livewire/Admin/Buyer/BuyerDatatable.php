<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class BuyerDatatable extends LivewireDatatable
{
    public $model = Buyer::class;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::index($this)->unsortable(),
            Column::name('first_name')->label(trans('cruds.buyer.fields.name'))->sortable()->searchable(),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Ban']);
            })->label(trans('cruds.buyer.fields.status'))->sortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id', 'user_id'], function ($id, $user_id) {
                $array = ['show', 'edit', 'delete'];
                if(auth()->user()->id != $user_id){
                    $array = array_diff( $array, ['show', 'edit'] );
                }
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

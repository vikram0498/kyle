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
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Block']);
            })->label(trans('cruds.buyer.fields.status'))->sortable(),

            Column::callback(['id', 'size_min'], function ($id) {
                return 0;
            })->label(trans('cruds.buyer.fields.like'))->sortable(),

            Column::callback(['id', 'size_max'], function () {
                return 0;
            })->label(trans('cruds.buyer.fields.dislike'))->sortable(),

            Column::callback(['id', 'is_ban'], function ($id, $isBan) {
                $flgHtml = '';
                if($isBan == 1){
                    $flgHtml = '<a href="javascript:void(0);" class="seller_flg_mark" data-id="'.$id.'"><img src="'.asset('images/icons/red-flag.svg').'" /></a>';
                }
                
                return $flgHtml;
            })->label(trans('cruds.buyer.fields.flag_mark'))->sortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable()->defaultSort('desc'),
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

<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class BuyerDatatable extends LivewireDatatable
{
    public function mount($model = false, $include = [], $exclude = [], $hide = [], $dates = [], $times = [], $searchable = [], $sort = null, $hideHeader = null, $hidePagination = null, $perPage = null, $exportable = false, $hideable = false, $beforeTableSlot = false, $buttonsSlot = false, $afterTableSlot = false, $params = [])
    {
        parent::mount($model, $include, $exclude, $hide, $dates, $times, $searchable, $sort, $hideHeader, $hidePagination, $perPage, $exportable, $hideable, $beforeTableSlot, $buttonsSlot, $afterTableSlot, $params);

        // $this->resetTable();
        $this->perPage = config('livewire-datatables.default_per_page', 10);
        $this->sort(6, 'desc');
        $this->search = null;
        $this->setPage(1);
    }

    public function builder()
    {
        return Buyer::query();
    }
  
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
            })->label(trans('cruds.buyer.fields.like'))->unsortable(),

            Column::callback(['id', 'size_max'], function () {
                return 0;
            })->label(trans('cruds.buyer.fields.dislike'))->unsortable(),

            Column::callback(['id', 'is_ban'], function ($id, $isBan) {
                $flgHtml = '';
                if($isBan == 1){
                    $flgHtml = '<a href="javascript:void(0);" class="seller_flg_mark" data-id="'.$id.'"><img src="'.asset('images/icons/red-flag.svg').'" /></a>';
                }
                
                return $flgHtml;
            })->label(trans('cruds.buyer.fields.flag_mark'))->unsortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable()->defaultSort('desc'),
            Column::callback(['id', 'user_id'], function ($id, $user_id) {
                $array = ['show', 'edit', 'delete'];
                if(auth()->user()->id != $user_id){
                    $array = array_diff( $array, ['delete', 'edit'] );
                }
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

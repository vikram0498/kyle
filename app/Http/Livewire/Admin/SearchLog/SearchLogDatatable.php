<?php

namespace App\Http\Livewire\Admin\SearchLog;

use App\Models\SearchLog;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class SearchLogDatatable extends LivewireDatatable
{
    
    public function builder()
    {
        return SearchLog::query()->with('seller'); // Include the related table data
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
            Column::callback(['user_id'], function ($user_id) {
                return User::find($user_id)->name;
            })->label(trans('cruds.search_log.fields.user_id'))->sortable(),

            // Column::name('seller.name')->label(trans('cruds.search_log.fields.user_id'))->sortable()->searchable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id'], function ($id) {
                $arr = ['show'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $arr]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

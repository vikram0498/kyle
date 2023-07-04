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
        // return SearchLog::query(); // Include the related table data
        return SearchLog::query()->leftJoin('users', 'users.id', 'search_logs.user_id');
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
            
            Column::name('users.name')->label(trans('cruds.search_log.fields.user_id'))->sortable()->searchable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable()->defaultSort('desc'),
            Column::callback(['id'], function ($id) {
                $arr = ['show'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $arr]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

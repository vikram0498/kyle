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
    public function mount($model = false, $include = [], $exclude = [], $hide = [], $dates = [], $times = [], $searchable = [], $sort = null, $hideHeader = null, $hidePagination = null, $perPage = null, $exportable = false, $hideable = false, $beforeTableSlot = false, $buttonsSlot = false, $afterTableSlot = false, $params = [])
    {
        parent::mount($model, $include, $exclude, $hide, $dates, $times, $searchable, $sort, $hideHeader, $hidePagination, $perPage, $exportable, $hideable, $beforeTableSlot, $buttonsSlot, $afterTableSlot, $params);

        // $this->resetTable();
        $this->perPage = config('livewire-datatables.default_per_page', 10);
        $this->sort(2, 'desc');
        $this->search = null;
        $this->setPage(1);
    }

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

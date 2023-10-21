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
        $this->sort(3, 'desc');
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
            Column::callback(['id'], function ($id) {
                return $id;
            })->sortable()->defaultSort('desc')->hide(),

            Column::index($this)->unsortable(),
            
            Column::callback(['users.name'],function($name){
                return ucwords($name);
            })->label(trans('cruds.search_log.fields.user_id'))->sortable()->searchable(),

            DateColumn::name('created_at')->label(trans('global.created'))->format(config('constants.date_format'))->sortable()->searchable(),
            Column::callback(['id', 'deleted_at'], function ($id) {
                $arr = ['show'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $arr]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

<?php

namespace App\Http\Livewire\Admin\Video;

use App\Models\Video;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class VideoDatatable extends LivewireDatatable
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
        return Video::query(); // Include the related table data
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
            Column::name('title')->label(trans('cruds.video.fields.title'))->sortable()->searchable(),

            Column::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Inactive']);
            })->label(trans('cruds.video.fields.status'))->sortable(),


            DateColumn::name('created_at')->label(trans('global.created'))->format(config('constants.date_format'))->sortable()->searchable(),
            Column::callback(['id', 'deleted_at'], function ($id) {
            $array = ['show', 'edit' /*,'delete'*/];
                return view('livewire.datatables.actions', ['id' => $id,'events' => $array,]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

<?php

namespace App\Http\Livewire\Admin\Addon;

use App\Models\Addon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class AddonDatatable extends LivewireDatatable
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
        return Addon::query();
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
            Column::name('title')->label(trans('cruds.addon.fields.title'))->sortable()->searchable(),

            // Column::name('price')->label(trans('cruds.addon.fields.price'))->sortable()->searchable(),
            Column::callback(['price'], function ($price) {
                return '$'.number_format($price,2);
            })->label(trans('cruds.addon.fields.price'))->sortable()->searchable(),

            Column::name('credit')->label(trans('cruds.addon.fields.credit'))->sortable()->searchable(),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Inactive']);
            })->label(trans('cruds.addon.fields.status'))->sortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id', 'deleted_at'], function ($id) {
                return view('livewire.datatables.actions', ['id' => $id]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

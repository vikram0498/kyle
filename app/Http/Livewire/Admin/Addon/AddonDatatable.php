<?php

namespace App\Http\Livewire\Admin\Addon;

use App\Models\Addon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class AddonDatatable extends LivewireDatatable
{
    public $model = Addon::class;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
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

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable()->defaultSort('desc'),
            Column::callback(['id'], function ($id) {
                return view('livewire.datatables.actions', ['id' => $id]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

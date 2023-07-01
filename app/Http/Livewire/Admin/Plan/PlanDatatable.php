<?php

namespace App\Http\Livewire\Admin\Plan;

use App\Models\Plan;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class PlanDatatable extends LivewireDatatable
{
    public $model = Plan::class;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            Column::index($this)->unsortable(),
            Column::name('title')->label(trans('cruds.plan.fields.title'))->sortable()->searchable(),
            Column::name('month_amount')->label(trans('cruds.plan.fields.month_amount'))->sortable()->searchable(),
            Column::name('year_amount')->label(trans('cruds.plan.fields.year_amount'))->sortable()->searchable(),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Inactive']);
            })->label(trans('cruds.plan.fields.status'))->sortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id'], function ($id) {
                
                return view('livewire.datatables.actions', ['id' => $id]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

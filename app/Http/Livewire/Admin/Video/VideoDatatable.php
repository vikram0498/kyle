<?php

namespace App\Http\Livewire\Admin\Video;

use App\Models\Video;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class VideoDatatable extends LivewireDatatable
{

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
            Column::index($this)->unsortable(),
            Column::name('title')->label(trans('cruds.video.fields.title'))->sortable()->searchable(),

            Column::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Inactive']);
            })->label(trans('cruds.video.fields.status'))->sortable(),


            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id'], function ($id) {
                return view('livewire.datatables.actions', ['id' => $id]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

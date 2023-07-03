<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class SellerDatatable extends LivewireDatatable
{

    public function builder()
    {
        return User::query()->withCount('buyers')
        ->whereHas('roles',function($query){
            $query->whereIn('id',[2]);
        }); // Include the related table data
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
            Column::name('name')->label(trans('cruds.user.fields.name'))->sortable()->searchable(),

            Column::callback(['id', 'is_active'], function ($id, $is_active) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $is_active, 'onLable' => 'Active', 'offLable' => 'Inactive']);
            })->label(trans('cruds.user.fields.status'))->sortable(),

            // Column::name('buyer_count')->label(trans('cruds.user.fields.buyer_count'))->sortable()->searchable(),
            Column::callback(['id'], function ($id) {
                return User::find($id)->buyers()->count();
            })->label(trans('cruds.user.fields.buyer_count'))->sortable(),

            Column::callback(['id', 'updated_at'], function ($id) {
                return 0;
            })->label(trans('cruds.user.fields.purchased_buyer'))->sortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            Column::callback(['id', 'phone'], function ($id, $phone) {
                $array = ['show', 'delete'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class SellerDatatable extends LivewireDatatable
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
        return User::query()->withCount('buyers')
        ->whereHas('roles',function($query){
            $query->whereIn('id',[2]);
        });
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
            Column::name('name')->label(trans('cruds.user.fields.name'))->sortable()->searchable(),

            Column::callback(['id', 'is_active'], function ($id, $is_active) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $is_active, 'type' => 'is_active', 'onLable' => 'Active', 'offLable' => 'Block']);
            })->label(trans('cruds.user.fields.status'))->sortable(),

            NumberColumn::name('buyers.id:count')->label(trans('cruds.user.fields.buyer_count'))->sortable()->searchable(),
            // Column::callback(['id'], function ($id) {
            //     return User::find($id)->buyers()->count();
            // })->label(trans('cruds.user.fields.buyer_count'))->sortable(),

            Column::callback(['deleted_at'], function ($id) {
                return 'Level 1';
            })->label(trans('cruds.user.fields.level_type'))->unsortable(),

            Column::callback(['address'], function ($id) {
                return 'Free';
            })->label(trans('cruds.user.fields.package'))->unsortable(),

            Column::callback(['updated_at'], function ($id) {
                return 0;
            })->label(trans('cruds.user.fields.purchased_buyer'))->unsortable(),

            DateColumn::name('created_at')->label(trans('global.created_at'))->sortable()->searchable(),
            
            Column::callback(['id', 'phone'], function ($id, $phone) {
                $array = ['show', 'delete'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

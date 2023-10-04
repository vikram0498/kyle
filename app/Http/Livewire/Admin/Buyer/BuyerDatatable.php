<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
  
class BuyerDatatable extends LivewireDatatable
{
    public function mount($model = false, $include = [], $exclude = [], $hide = [], $dates = [], $times = [], $searchable = [], $sort = null, $hideHeader = null, $hidePagination = null, $perPage = null, $exportable = false, $hideable = false, $beforeTableSlot = false, $buttonsSlot = false, $afterTableSlot = false, $params = [])
    {
        parent::mount($model, $include, $exclude, $hide, $dates, $times, $searchable, $sort, $hideHeader, $hidePagination, $perPage, $exportable, $hideable, $beforeTableSlot, $buttonsSlot, $afterTableSlot, $params);

        // $this->resetTable();
        $this->perPage = config('livewire-datatables.default_per_page', 10);
        $this->sort(8, 'desc');
        $this->search = null;
        $this->setPage(1);
    }

    public function builder()
    {
        // dd($this->sort);
        // return Buyer::query();
        $buyers = Buyer::query()->orderBy('updated_at','desc');

        $statusSearch = null;
        $searchValue = $this->search;
        if (Str::contains('active', strtolower($searchValue))) {
            $statusSearch = 1;
        } else if (Str::contains('block', strtolower($searchValue))) {
            $statusSearch = 0;
        }

        if($this->search){
            $buyers = $buyers->where(function ($query) use ($searchValue, $statusSearch) {
                $query->where('first_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('last_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('status', $statusSearch);
            });
        }
        
        return $buyers;
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
            })->sortable()/*->defaultSort('desc')*/->hide(),
            
            Column::index($this)->unsortable(),
            // Column::name('first_name')->label(trans('cruds.buyer.fields.name'))->sortable()->searchable(),

            Column::callback(['first_name', 'last_name'], function ($firstName, $lastName) {
                return ucfirst($firstName).' '. ucfirst($lastName);
            })->label(trans('cruds.buyer.fields.name'))->sortable()->searchable(),

            NumberColumn::callback(['id', 'status'], function ($id, $status) {
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $status, 'onLable' => 'Active', 'offLable' => 'Block']);
            })->label(trans('cruds.buyer.fields.status'))->sortable()->searchable(),

            Column::callback(['id', 'size_min'], function ($id) {
                $buyer = Buyer::find($id);

                return $buyer->likes()->count();
            })->label(trans('cruds.buyer.fields.like'))->unsortable(),

            Column::callback(['id', 'size_max'], function ($id) {
                $buyer = Buyer::find($id);

                return $buyer->unlikes()->count();
            })->label(trans('cruds.buyer.fields.dislike'))->unsortable(),

            Column::callback(['id', 'is_ban'], function ($id, $isBan) {
                $buyer = Buyer::find($id);
                $buyerFlagCount = $buyer->redFlagedData()->where('status', 0)->count();
                
                return view('livewire.datatables.red_flag_btn', ['id' => $id, 'flag_count' => $buyerFlagCount]);
                
            })->label(trans('cruds.buyer.fields.flag_mark'))->unsortable(),

            DateColumn::name('created_at')->label(trans('global.created'))->format(config('constants.date_format'))->sortable()->searchable()/*->defaultSort('desc')*/,

            DateColumn::name('updated_at')->label(trans('global.updated'))->format(config('constants.date_format'))->sortable()->searchable(),

            Column::callback(['id', 'user_id'], function ($id, $user_id) {
                $array = ['show', 'edit', 'delete'];
                $buyer = Buyer::find($id);
                $buyerFlagCount = $buyer->redFlagedData()->where('status', 0)->count();
                if(auth()->user()->id != $user_id){
                    $array = array_diff( $array, ['delete', 'edit'] );
                }
                if($buyerFlagCount > 0){
                    $array[] = 'flag_btn';
                }
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array, 'buyerFlagCount' => $buyerFlagCount]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

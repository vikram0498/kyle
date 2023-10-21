<?php

namespace App\Http\Livewire\Admin\Seller;

use App\Models\User;
use Illuminate\Support\Str;
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
        $this->sort(7, 'desc');
        $this->search = null;
        $this->setPage(1);
    }

    public function builder()
    {
        $statusSearch = null;
        $searchValue = $this->search;
        if (Str::contains('active', strtolower($searchValue))) {
            $statusSearch = 1;
        } else if (Str::contains('block', strtolower($searchValue))) {
            $statusSearch = 0;
        }
        
        return User::query()->with(['buyers','purchasedBuyers'])
        ->whereHas('roles',function($query){
            $query->whereIn('id',[2]);
        })->where(function ($query) use ($searchValue, $statusSearch) {
                
        	$query->where('name', 'like', '%' . $searchValue . '%')
                                
            ->orWhere('is_active', $statusSearch)
                                
            ->orWhereRaw("date_format(created_at, '" . config('constants.search_date_format') . "') like ?", ['%' . $searchValue . '%']);
                    
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
                
                // if(strtolower($this->search) == 'active'){
                //     $is_active = 1;
                // }elseif(strtolower($this->search) == 'block'){
                //     $is_active = 0;
                // }
                
                return view('livewire.datatables.toggle-switch', ['id' => $id, 'status' => $is_active, 'type' => 'is_active', 'onLable' => 'Active', 'offLable' => 'Block']);
              
            })->label(trans('cruds.user.fields.status'))->sortable()->searchable(function($query,$searchValue){
                if(strtolower($searchValue) == 'active'){
                    $query->where('is_active',1);
                }elseif(strtolower($searchValue) == 'block'){
                    $query->where('is_active',0);
                }
                
            }),

            Column::name('buyers.user_id:count')->label(trans('cruds.user.fields.buyer_count'))->sortable()->searchable(),
        
            Column::callback(['level_type'], function ($level_type) {
                return 'Level '.$level_type;
            })->label(trans('cruds.user.fields.level_type'))->sortable()->searchable(),

            Column::name('purchasedBuyers.user_id:count')->label(trans('cruds.user.fields.purchased_buyer'))->sortable()->searchable(),

            DateColumn::name('created_at')->label(trans('cruds.user.fields.created_at'))->format('m/d/Y')->sortable()->searchable(),

            Column::callback(['id', 'phone'], function ($id, $phone) {
                $array = ['show', 'delete'];
                return view('livewire.datatables.actions', ['id' => $id, 'events' => $array]);
            })->label(trans('global.action'))->unsortable(),
        ];
    }
}

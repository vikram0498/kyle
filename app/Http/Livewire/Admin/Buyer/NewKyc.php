<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\User;
use App\Models\ProfileVerification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

use Livewire\Component;

class NewKyc extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $search = '',  $viewMode = false;

    public $sortColumnName = 'updated_at', $sortDirection = 'desc', $perPage = 10;

    public $details;

    protected $listeners = ['refreshTable' =>'render','confirmedToggleActionView'];

    public function mount(){
        abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($columnName)
    {
        $this->resetPage();

        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumnName = $columnName;
    }
    
    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $searchValue = $this->search;
       
        $kycBuyers = User::query()->where(function ($query) use ($searchValue) {
            $query->where('name','like',["%{$searchValue}%"])
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(updated_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        })->whereHas('buyerVerification',function($query){
            $query->where('is_phone_verification', 1)
            ->where(function ($sub_query) {
                $sub_query->where([
                    ['is_driver_license', 1],
                    ['driver_license_status', 'pending']
                ])
                ->orWhere([
                    ['is_proof_of_funds', 1],
                    ['proof_of_funds_status', 'pending']
                ])
                ->orWhere([
                    ['is_llc_verification', 1],
                    ['llc_verification_status', 'pending']
                ]);
            })
            ->where('is_application_process', 0);
        })
        ->whereHas('roles',function($query){
            $query->whereIn('id',[3]);
        })
        ->orderBy($this->sortColumnName, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.admin.buyer.new-kyc',compact('kycBuyers'));
    }

    public function show($userId){
        $this->viewMode = true;

        $this->details = User::find($userId);
    }

    public function confirmedToggleActionView($data){
        $id = $data['id'];
        $status = $data['status'];
        $type = $data['type'];

        $model = ProfileVerification::find($id);
        $model->update([$type => $status]);

        $userId = $model->user_id;
        $this->details = User::find($userId);

        $this->flash('success', trans('messages.change_status_success_message'));
        return redirect()->route('admin.new-kyc');
    }
    
    public function cancel(){
        $this->reset();
    }


}

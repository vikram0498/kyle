<?php

namespace App\Http\Livewire\Admin\BuyerInvitation;

use Carbon\Carbon; 
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\BuyerInvitation;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;


class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $search = null;
    
    public $sortColumnName = 'updated_at', $sortDirection = 'desc', $perPage = 10;

    public $filterReminder, $selectedRows = [], $selectAll = false;

    protected $listeners = [
        'sendReminder','deleteConfirm'
    ];
    
    public function mount(){
        abort_if(Gate::denies('buyer_invitation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function updatedPerPage(){
            $this->resetPage();
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

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            
            $totalReminder = count(config('constants.reminders'));
            $this->selectedRows = BuyerInvitation::where('reminder_count','<',$totalReminder)->where('status',0)->pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();

        } else {
            $this->selectedRows = [];
        }
    }

    public function updatedSelectedRows()
    {
        $this->selectAll = false;
    }

    public function render() 
    {
        $searchValue = $this->search;
        $statusSearch = null;
        if(Str::contains('accepted', strtolower($searchValue))){
            $statusSearch = 1;
        }else if(Str::contains('pending', strtolower($searchValue))){
            $statusSearch = 0;
        }

        $buyerInvitations = BuyerInvitation::query()
        ->where(function ($query) use ($searchValue,$statusSearch) {
            $query->where('email','like',"%{$searchValue}%")
            ->orWhereRelation('createdBy', 'name', 'like',  ["%{$searchValue}%"])
            ->orWhere('reminder_count','like',"%{$searchValue}%")
            ->orWhere('status',$statusSearch)
            ->orWhereRaw("date_format(created_at, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%'])
            ->orWhereRaw("date_format(last_reminder_sent, '".config('constants.search_datetime_format')."') like ?", ['%'.$searchValue.'%']);
        });

        if($this->filterReminder){
            $buyerInvitations = $buyerInvitations->where('reminder_count',$this->filterReminder);
        }

        if($this->sortColumnName == 'name'){
            $buyerInvitations = $buyerInvitations->orderBy(User::select($this->sortColumnName)->whereColumn('users.id', 'buyer_invitations.created_by'), $this->sortDirection);
        } else {
            $buyerInvitations = $buyerInvitations->orderBy($this->sortColumnName, $this->sortDirection);
        }
        $buyerInvitations = $buyerInvitations->paginate($this->perPage);

        return view('livewire.admin.buyer-invitation.index',compact('buyerInvitations'));
    }


    public function sendReminder(){
       if(count($this->selectedRows) == 0){
            $this->alert('warning',trans('messages.no_row_selected'));
       }else{
            foreach($this->selectedRows as $rowId){
                $buyerInvitation = BuyerInvitation::where('id',$rowId)->where('status',0)->first();

                if($buyerInvitation->reminder_count < 3){

                    $reminderNo = (int)$buyerInvitation->reminder_count+1;

                    $subject = config('constants.reminder_mail_subject');

                    $buyerInvitation->sendInvitationEmail($subject,$reminderNo);

                    $buyerInvitation->reminder_count = $reminderNo;
                    $buyerInvitation->last_reminder_sent = Carbon::now();
                    $buyerInvitation->save();

                    $this->resetInputFields();
                    $this->alert('success', trans('messages.reminder_sent_success'));
                }
                
            }
       }
    }

    public function deleteConfirm($id) {
        $model = BuyerInvitation::find($id);
        if($model){
            $model->delete();
            $this->alert('success', trans('messages.delete_success_message'));
        }else{
            $this->alert('error', trans('messages.no_record_found'));
        }
       
    }
    
    private function resetInputFields(){
        $this->reset(['selectAll','selectedRows']);
    }

    public function initializePlugins(){
        $this->dispatchBrowserEvent('loadPlugins');
    }

}

<?php

namespace App\Http\Livewire\Admin\DeletedUsers;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;
use App\Models\PurchasedBuyer;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $viewMode = false;

    public $user_id = null; 

    protected $deletedUsers = null;

    protected $listeners = [
        'show','cancel','resetUserBack'
    ];

    public function mount(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.deleted-users.index');
    }

    public function show($id){
        $this->user_id = $id;
        $this->viewMode = true;
    }

    public function cancel(){
        $this->reset(['user_id','viewMode']);
    }
    
    public function resetUserBack($id){
       
        $model = User::where('id',$id)->onlyTrashed()->first();
        if($model){
            $model->buyers()->onlyTrashed()->where('user_id',$id)->update(['deleted_at'=>null]);
            PurchasedBuyer::where('user_id',$id)->onlyTrashed()->update(['deleted_at'=>null]);
            
            $model->deleted_at = null;
            $model->save();
            
            $this->emit('refreshLivewireDatatable');
    
            $this->alert('success', 'User restored successfully!');
        }else{
            $this->alert('error', 'Something went wrong!');
        }
    }
    
    
}

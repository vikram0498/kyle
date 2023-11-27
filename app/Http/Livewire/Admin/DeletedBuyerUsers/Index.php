<?php

namespace App\Http\Livewire\Admin\DeletedBuyerUsers;


use Livewire\Component;
use App\Models\Buyer;
use App\Models\PurchasedBuyer;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;


class Index extends Component
{
    use WithPagination, LivewireAlert, WithFileUploads;

    protected $layout = null;

    public $search = '';

    protected $listeners = [
        'show','cancel','resetUserBack'
    ];

    protected $buyer = null;

    public  $viewMode = false;

    public $buyer_id =null, $buyer_user_id = null;

    public function mount(){
        abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.deleted-buyer-users.index');
    }

    public function show($id){
        $this->buyer_id = $id;
        $this->viewMode = true;
    }

    public function cancel(){
        $this->reset();
    }

    public function resetUserBack($id){
       
        $model = User::where('id',$id)->onlyTrashed()->first();
        if($model){
            $model->buyers()->onlyTrashed()->where('user_id',$id)->update(['deleted_at'=>null]);
            PurchasedBuyer::where('user_id',$id)->onlyTrashed()->update(['deleted_at'=>null]);
            
            $model->deleted_at = null;
            $model->save();
            
            $this->emit('refreshTable');
    
            $this->alert('success', 'Buyer restored successfully!');
        }else{
            $this->alert('error', 'Something went wrong!');
        }
    }
}

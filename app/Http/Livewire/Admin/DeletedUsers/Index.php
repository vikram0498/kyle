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
        'show','cancel'
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
    
    
}

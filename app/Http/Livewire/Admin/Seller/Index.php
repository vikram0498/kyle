<?php

namespace App\Http\Livewire\Admin\Seller;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false, $viewMode = false, $viewDetails = null;

    public $user_id = null, $first_name, $last_name, $email,$phone; 

    protected $users = null;

    protected $listeners = [
        'show', 'confirmedToggleAction','deleteConfirm', 'blockConfirmedToggleAction'
    ];

    public function mount(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.seller.index');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->resetValidation();
        $this->resetValidation();
        $this->formMode = true;
    }

    private function resetInputFields(){
        $this->first_name = '';
        $this->last_name  = '';
        $this->email      = '';
        $this->phone      = '';
    }

    public function store(){
        $validatedDate = $this->validate([
            'first_name'  => 'required|regex:/^[A-Za-z]+( [A-Za-z]+)?$/u',
            'last_name'   => 'required|regex:/^[A-Za-z]+( [A-Za-z]+)?$/u',
            'email'       => 'required|unique:users,email,NULL,id,deleted_at,NUL',
            'phone'         => 'required|digits:10'
        ]);

        $referral_user_id = User::where('my_referral_code',$this->referral_code)->value('id');

        $userDetails = [];
        $userDetails['first_name'] = $this->first_name;
        $userDetails['last_name']  = $this->last_name;
        $userDetails['name']       = $this->first_name.' '.$this->last_name;
        $userDetails['email']      = $this->email;
        $userDetails['phone']      = $this->phone;
        
        $createdUser = User::create($userDetails);

        //Send email verification link
        $createdUser->sendEmailVerificationNotification();

        $createdUser->roles()->sync(2);

        $this->resetInputFields();

        $this->flash('success',trans('messages.add_success_message'));
      
        return redirect()->route('admin.seller');
    }

    public function edit($id){
        dd('working..');

    }

    public function update(){

    }

    public function deleteConfirm($id){
        $model = User::find($id);
        $model->delete();
        
        $this->emit('refreshLivewireDatatable');

        $this->alert('success', trans('messages.delete_success_message'));
    }

    public function show($id){
        $this->user_id = $id;
        $this->formMode = false;
        $this->viewMode = true;
    }

    public function cancel(){
        $this->formMode = false;
        $this->updateMode = false;
        $this->viewMode = false;
        $this->resetInputFields();
        $this->resetValidation();
    }
    public function confirmedToggleAction($data)
    {
        $id = $data['id'];
        $type = $data['type'];
        
        $model = User::find($id );
        $model->update([$type => !$model->$type]);
        $this->alert('success', trans('messages.change_status_success_message'));
    }

    /* public function changeStatus($statusVal){
        $this->is_active = (!$statusVal) ? 1 : 0;
    } */
    
}

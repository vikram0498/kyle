<?php

namespace App\Http\Livewire\Admin\Seller;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchasedBuyer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false, $viewMode = false, $viewDetails = null;

    public $user_id = null, $first_name, $last_name, $email,$phone, $credit_limit;

    protected $users = null;

    protected $listeners = [
        'show', 'confirmedToggleAction','deleteConfirm', 'blockConfirmedToggleAction','editCreditLimit'
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

        if($model){
            $model->buyers()->delete();
            PurchasedBuyer::where('user_id',$id)->delete();

            $model->delete();

            $this->emit('refreshTable');

            $this->emit('refreshLivewireDatatable');

            $this->alert('success', trans('messages.delete_success_message'));
        }

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
        $this->resetPage();
    }

    public function confirmedToggleAction($data)
    {
        $id = $data['id'];
        $type = $data['type'];

        if($type == 'level_3'){

            $model = User::find($id);
            $levelType = !$model->$type ? 3 : $model->prev_level_type;

            $prevLevelType = $levelType==3 ? $model->level_type : $model->prev_level_type;
            if($levelType == 2){
                $prevLevelType = 1;
            }
            $model->update(['prev_level_type'=>$prevLevelType,'level_type' => $levelType, $type => !$model->$type]);

            $this->alert('success', trans('messages.level_3_status_success_message'));
            $this->emit('refreshTable');
        }else{
            $model = User::find($id);
            $model->update([$type => !$model->$type]);
            $this->alert('success', trans('messages.change_status_success_message'));
        }
    }

    public function editCreditLimit($id){
        $this->resetInputFields();
        $this->resetValidation();

        $model = User::find($id);
        if($model){
            $this->user_id = $model->id;
            $this->credit_limit = $model->credit_limit;
        }
    }

    public function updateCreditLimit(){

        $validateArr = [
            'credit_limit'  => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
  
        $validatedData = $this->validate($validateArr);

        try {
            DB::beginTransaction();

            User::where('id',$this->user_id)->update($validatedData);

            DB::commit();

            $this->dispatchBrowserEvent('closed-modal');
            $this->emit('refreshTable');
            $this->resetInputFields();
            $this->resetValidation();

            $this->alert('success',trans('messages.edit_success_message'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage().'->'.$e->getLine());
            \Log::info('Error in Livewire/Admin/Seller/Index::updateCreditLimit (' . $e->getCode() . '): ' . $e->getMessage() . ' at line ' . $e->getLine());
            $this->alert('error', trans('messages.error_message'));
        }
    }


}

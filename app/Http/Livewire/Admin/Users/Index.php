<?php

namespace App\Http\Livewire\Admin\Users;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Buyer;
use App\Models\PurchasedBuyer;
use App\Models\ProfileVerification;

use App\Rules\CheckMaxValue;
use App\Rules\CheckMinValue;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $search = '', $formMode = false , $updateMode = false, $viewMode = false, $viewDetails = null;

    public $user_id = null, $first_name, $last_name, $email,$phone, $credit_limit, $referral_code, $bonus_credits, $allBuyerPlans = null;

    protected $users = null;

    protected $listeners = [
        'show', 'confirmedToggleAction','deleteConfirm', 'blockConfirmedToggleAction','editCreditLimit','editRepCode'
    ];

    public function mount(){
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.users.index');
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
            'email'       => 'required|unique:users,email,NULL,id',
            'phone'       => 'required|digits:10'
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

        return redirect()->route('admin.users');
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
            // PurchasedBuyer::where('user_id',$id)->delete();

            $model->delete();

            $this->emit('refreshTable');

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
            \Log::info('Error in Livewire/Admin/Users/Index::updateCreditLimit (' . $e->getCode() . '): ' . $e->getMessage() . ' at line ' . $e->getLine());
            $this->alert('error', trans('messages.error_message'));
        }
    }


    public function editRepCode($id){
        $this->resetInputFields();
        $this->resetValidation();

        $model = User::find($id);
        if($model){
            $this->user_id = $model->id;
            $this->referral_code = $model->referral_code;
            $this->bonus_credits = !empty($model->bonus_credits) ? $model->bonus_credits : '';
        }
    }

    public function updateRepCode(){
        $validateArr = [
            'referral_code'  => ['required',Rule::unique('users', 'referral_code')->ignore($this->user_id)],
            'bonus_credits'  => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
  
        $validatedData = $this->validate($validateArr,[],[
            'referral_code' => 'REP Code'
        ]);

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
            \Log::info('Error in Livewire/Admin/Users/Index::updateRepCode (' . $e->getCode() . '): ' . $e->getMessage() . ' at line ' . $e->getLine());
            $this->alert('error', trans('messages.error_message'));
        }
    }

    

}

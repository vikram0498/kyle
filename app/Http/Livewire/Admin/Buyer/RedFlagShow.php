<?php

namespace App\Http\Livewire\Admin\Buyer;

use App\Models\Buyer;
use App\Models\User;
use Livewire\Component;
use App\Mail\FlagRejectMail;
use App\Mail\FlagResolvedMail;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RedFlagShow extends Component
{
    use LivewireAlert;

    protected $layout = null;
    
    public $data,$buyerId,$userId;

    public $first_name,$last_name,$email,$phone,$message;

    public $isNameUpdate = false, $isEmailUpdate = false, $isPhoneUpdate =false;

    protected $listeners = [
        'resolveAllFlag', 'rejectFlag','setIsNameUpdate','setIsEmailUpdate','setIsPhoneUpdate','setUserId',
    ];

    public function mount($buyer_id){
        $this->buyerId = $buyer_id;
        $this->data = Buyer::find($buyer_id);

        $this->first_name = $this->data->userDetail->first_name;
        $this->last_name = $this->data->userDetail->last_name;
        $this->email = $this->data->userDetail->email;
        $this->phone = $this->data->userDetail->phone;
    }

    public function setUserId($id){
        $this->userId = $id;
    }

    public function setIsNameUpdate($isUpdate){
        $this->isNameUpdate = $isUpdate;
    }

    
    public function setIsEmailUpdate($isUpdate){
        $this->isEmailUpdate = $isUpdate;
    }

    
    public function setIsPhoneUpdate($isUpdate){
        $this->isPhoneUpdate = $isUpdate;
    }

    public function render()
    {
        return view('livewire.admin.buyer.red-flag-show');
    }

    public function cancel(){
        $this->dispatchBrowserEvent('closed-modal');
        $this->emitUp('cancel');
    }

    public function resolveAllFlag(){
        $rules = [
            'message' => 'required|string',
        ];

        if($this->isNameUpdate){
            $rules['first_name'] = 'required|string';
            $rules['last_name'] = 'required|string';
        }

        if($this->isEmailUpdate){
            $rules['email'] = 'required|email:dns';
        }

        if($this->isPhoneUpdate){
            $rules['phone'] = 'required|numeric|digits:10';
        }

        $validatedData = $this->validate($rules);

        try{

            $buyer = Buyer::find($this->buyerId);

            $updateDetail =[];
            if($this->isNameUpdate){
                $updateDetail['first_name'] = ucwords($this->first_name);
                $updateDetail['last_name'] = ucwords($this->last_name);
                $updateDetail['name'] = $updateDetail['first_name'].' '.$updateDetail['last_name'];
            }

            if($this->isEmailUpdate){
                $updateDetail['email'] = $this->email;
            }

            if($this->isPhoneUpdate){
                $updateDetail['phone'] = $this->phone;
            }

            if(count($updateDetail) > 0){
                 User::where('id',$buyer->buyer_user_id)->update($updateDetail);
            }
           
            $buyer->redFlagedData()->wherePivot('buyer_id', $this->buyerId)->wherePivot('user_id',$this->userId)->update(['status' => 1]);

            
            $getUserDetail = User::find($this->userId);
            $email_id = $getUserDetail->email;
            $name = $getUserDetail->name ?? null;
            if($email_id && $name){
                $subject = 'Resolved Issue';
                Mail::to($email_id)->queue(new FlagResolvedMail($subject, $name,$this->message));
            }

            $this->reset(['buyerId','userId','first_name','last_name','email','phone','isNameUpdate','isEmailUpdate','isPhoneUpdate']);
            $this->alert('success', trans("Buyer's all flag is resolved"));

            $this->cancel();
    
        } catch (\Exception $e) {
            // dd($e->getMessage().'->'.$e->getLine());
            $this->alert('error', 'Something went wrong!');
        }
    }


    public function rejectFlag(){
        $rules = [
            'message' => 'required|string',
        ];

        $validatedData = $this->validate($rules);

        $buyer = Buyer::find($this->buyerId);

        // $buyer->redFlagedData()->updateExistingPivot($data['seller_id'], ['status' => 2]);
        $buyer->redFlagedData()->wherePivot('buyer_id', $this->buyerId)->wherePivot('user_id',$this->userId)->update(['status' => 2]);

        // $buyerFlagCount = $buyer->redFlagedData()->where('status', 0)->count();

        $getUserDetail = User::find($this->userId);
        $email_id = $getUserDetail->email;
        $name = $getUserDetail->name ?? null;
        if($email_id && $name){
            $subject = 'Rejected Issue';
            Mail::to($email_id)->queue(new FlagRejectMail($subject, $name,$this->message));
        }

        $this->reset(['buyerId','userId','first_name','last_name','email','phone','isNameUpdate','isEmailUpdate','isPhoneUpdate']);

        $this->alert('success', trans("Flag is rejected"));

        // if($buyerFlagCount == 0){
            $this->cancel();
        // }
    }
}

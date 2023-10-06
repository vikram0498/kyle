<?php

namespace App\Http\Livewire\Admin\Support;

use Livewire\Component;
use App\Mail\ReplySupportMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Support as CutomerSupport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Validator;


class ReplyModal extends Component
{
    use LivewireAlert;

    public $support_id = null;

    public $isSent = false, $reply_message='';

    public $isDraftBtn = false, $isSendBtn = false;

    protected $listeners = [
        'storeReply','setReplyMessage',
    ];

    public function mount($support_id){
        $this->support_id = $support_id;

        $supportReply = CutomerSupport::where('id',$this->support_id)->first();
        if($supportReply){
            if(is_null($supportReply->reply) || $supportReply->is_draft == 1){
                $this->isSent = false;
            }else if($supportReply->is_draft == 0){
                $this->isSent = true;
            }

            if(!is_null($supportReply->reply)){    
                $this->reply_message = $supportReply->reply;
            }
            
        }
    }

    public function setReplyMessage($message){
        $this->reply_message = $message;
    }

    public function render()
    {
        
        return view('livewire.admin.support.reply-modal');
    }

    public function storeReply($isDraft){
        $this->validate([
            'reply_message'=>'required|string|without_spaces'
        ],[
            'without_spaces' => 'The :attribute field is required'
          ]
        );

        if($isDraft){
            $updated = CutomerSupport::where('id',$this->support_id)->update(['reply'=>$this->reply_message,'is_draft'=>1]);
        }else{
            $updated = CutomerSupport::where('id',$this->support_id)->update(['reply'=>$this->reply_message,'is_draft'=>0]);
        }

        if($updated){
            if($isDraft){
                $this->alert('success','Saved Successfully!');
            }else{
                $subject = 'Reply';
                $customer = CutomerSupport::where('id',$this->support_id)->first();

                Mail::to($customer->email)->queue(new ReplySupportMail($subject, $customer->name,$this->reply_message));
                $this->alert('success','Replied successfully!');
            }

            // $this->reset(['reply_message']);
            $this->dispatchBrowserEvent('closed-modal');
        }else{
            $this->alert('error',trans('messages.error_message'));
        }
    }

    public function cancel(){
        $this->emit('cancel');
    }
}

// Custom validation rule
Validator::extend('without_spaces', function ($attribute, $value, $parameters, $validator) {
    $cleanValue = trim(strip_tags($value));
    $replacedVal = trim(str_replace(['&nbsp;', '&ensp;', '&emsp;'], ['','',''], $cleanValue));
    
    if (empty($replacedVal)) {
        return false;
    }
    return true;
});

<?php

namespace App\Http\Livewire\Auth\Admin;

use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon; 
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ForgetPassword extends Component
{
    use LivewireAlert;

    // protected $layout = null;

    public $email;

    public function render()
    {
        return view('livewire.auth.admin.forget-password');
    }

    public function submit(){
        $this->validate(['email' => 'required|email|exists:users'], getCommonValidationRuleMsgs());

        $user = User::where('email',$this->email)->whereHas('roles', function($q){ $q->where('id', 1);})->first();
        if($user){
            $token = Str::random(64);
            $email_id = $this->email;
            
            $userDetails = array();
            $userDetails['name'] = ucwords($user->first_name.' '.$user->last_name);

            $userDetails['reset_password_url'] = route('auth.reset-password',[$token,encrypt($email_id)]);
        
            DB::table('password_resets')->insert([
                'email' => $email_id, 
                'token' => $token, 
                'created_at' => Carbon::now()
            ]);

            $subject = 'Reset Password Notification';
            Mail::to($email_id)->queue(new ResetPasswordMail($userDetails['name'],$userDetails['reset_password_url'], $subject));

            // Mail::send('emails.auth.reset-password', $userDetails, function($message) use($email_id){
                
            //     $message->to($email_id)->subject('Reset Password Notification');

            // });

            // Set Flash Message
            $this->alert('success', trans('passwords.sent'));
        }else{
            // Set Flash Message
            $this->alert('error', 'Something went wrong!');
        }
      
        $this->resetInputFields();
    }

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields(){
        $this->email = '';
    }
}

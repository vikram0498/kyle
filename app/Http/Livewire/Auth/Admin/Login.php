<?php

namespace App\Http\Livewire\Auth\Admin;

use Mail;
use Auth;
use Livewire\Component;
use App\Models\User;
use App\Http\Livewire\BaseComponent;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Login extends BaseComponent
{
    use LivewireAlert;

    // protected $layout = null;
    
    public $email, $password,$remember_me;

    public $verifyMailComponent = false;

    public function render()
    {
        return view('livewire.auth.admin.login');
    }

    private function resetInputFields(){
        $this->email = '';
        $this->password = '';
    }

    public function submitLogin()
    {
        $validated = $this->validate([
            'email'    => ['required','email'],
            'password' => 'required',
        ]);
         
        $remember_me = !is_null($this->remember_me) ? true : false;
        $credentialsOnly = [
            'email'    => $this->email,
            'password' => $this->password,
        ]; 

        try {
            $checkVerified = User::where('email',$this->email)->whereNull('email_verified_at')->first();
            if(!$checkVerified){
                if (Auth::guard('web')->attempt($credentialsOnly, $remember_me)) {
            
                    $this->resetInputFields();
                    $this->resetErrorBag();
                    $this->resetValidation();

                    $this->flash('success', trans('panel.message.login_success'));

                    if(Auth::user()->is_seller){
                        return redirect()->route('user.dashboard');
                    }else{
                        return redirect()->route('admin.dashboard');
                    }
                  
                }
        
                $this->addError('email', trans('auth.failed'));
            }else{
                // $this->addError('email', trans('panel.message.email_verify_first'));
                $checkVerified->sendEmailVerificationNotification();
                $this->verifyMailComponent = true;
            }
            
            $this->resetInputFields();
        
        } catch (ValidationException $e) {

            $this->addError('email', $e->getMessage());
        }
    
    }


    public function checkEmail()
    {
        $validated = $this->validate([
            'email'    => ['required','email'],
        ], getCommonValidationRuleMsgs());

        // $user = User::where('email', $this->email)->whereNull('email_verified_at')->first();
        // if ($user) {
        //     // $this->showResetBtn = true;
        //     $this->addError('email', trans('panel.message.email_verify_first'));
        // }
    }

    public function backToLogin(){
        $this->verifyMailComponent = false;
    }
}

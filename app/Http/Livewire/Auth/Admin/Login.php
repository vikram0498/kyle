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
            // $checkVerified = User::where('email',$this->email)->whereNull('email_verified_at')->first();
            $user = User::where('email', $this->email)->first();
            $checkVerified = $user->email_verified_at;
            
            if($user->is_admin){
                if(!is_null($checkVerified)){                
                    if (Auth::guard('web')->attempt($credentialsOnly, $remember_me)) {
                        $this->resetInputFields();
                        $this->resetErrorBag();
                        $this->resetValidation();
    
                        $this->flash('success', trans('panel.message.login_success'));

                        return redirect()->route('admin.dashboard');
                    }
                    $this->addError('email', trans('auth.failed'));
                }else{
                    // $user->sendEmailVerificationNotification();
                    $this->alert('error', trans('panel.message.email_verify_first'));
                    // $this->verifyMailComponent = true;

                }
            } else {
                $this->addError('email', trans('auth.failed'));
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

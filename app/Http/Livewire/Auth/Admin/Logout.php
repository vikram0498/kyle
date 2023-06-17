<?php

namespace App\Http\Livewire\Auth\Admin;

use Auth;
use Livewire\Component;

class Logout extends Component
{

    protected $layout = null;

    public function render()
    {
        return view('livewire.auth.admin.logout');
    }

    public function logout()
    {
        Auth::logout();
        
        // Redirect to the login page
        return redirect()->route('auth.login');
    }
}

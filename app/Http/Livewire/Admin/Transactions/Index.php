<?php

namespace App\Http\Livewire\Admin\Transactions;

use Livewire\Component;

class Index extends Component
{
    public $viewMode = false;

    public function render()
    {
        return view('livewire.admin.transactions.index');
    }
}

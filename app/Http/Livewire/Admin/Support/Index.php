<?php

namespace App\Http\Livewire\Admin\Support;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Support as CutomerSupport;
use Livewire\WithPagination;
use App\Mail\ReplySupportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class Index extends Component
{
    use WithPagination, LivewireAlert;

    protected $layout = null;

    public $viewMode = false, $replyView=false, $support_id = null;

    protected $listeners = [
        'show','cancel','reply'
    ];

    public function mount(){
        abort_if(Gate::denies('support_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function render()
    {
        return view('livewire.admin.support.index');
    }

    public function show($id){
        $this->support_id = $id;
        $this->replyView =false;
        $this->viewMode = true;
    }

    public function cancel(){
        $this->reset(['support_id','viewMode','replyView']);
    }

    public function reply($id){
        $this->support_id = $id;
        $this->viewMode = false;
        $this->replyView = true;
        $this->dispatchBrowserEvent('loadTextEditorPlugin');
    }

}


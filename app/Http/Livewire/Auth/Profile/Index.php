<?php

namespace App\Http\Livewire\Auth\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Index extends Component
{
    use LivewireAlert,WithFileUploads;

    protected $layout = null;

    public $editMode =false, $showConfirmCancel = false;

    public $authUser, $profile_image = null;

    public $first_name, $last_name, $email,$phone; 

    protected $listeners = [
        'confirmUpdateProfileImage','cancelUpdateProfileImage','openEditSection','closedEditSection'
    ];

    public function mount(){
        $this->authUser = auth()->user();
    }

    public function render()
    {
        return view('livewire.auth.profile.index');
    }

    public function validateProfileImage()
    {
        $this->showConfirmCancel = true;

        $this->validate([
            'profile_image' => 'image|max:1024', // Maximum file size of 1MB
        ]);
       
    }

    public function confirmUpdateProfileImage()
    {
        $this->showConfirmCancel = false;
        $this->validate([
            'profile_image' => 'image|max:1024', // Maximum file size of 1MB
        ]);
        $user = $this->authUser;

        $actionType = 'save';
        $uploadId = null;
        if($profileImageRecord = $user->profileImage){
            $uploadId = $profileImageRecord->id;
            $actionType = 'update';
        }
        $response = uploadImage($user, $this->profile_image, 'user/profile-images',"profile", 'original', $actionType, $uploadId);

        
        $this->reset(['profile_image']);

        $this->authUser->profile_image_url = $response->file_url;

        $this->render();
        if ($response) {
            // Set Flash Message
            $this->alert('success', 'Profile image has been updated.');
        } else {

            $this->alert(trans('panel.alert-type.error'), trans('panel.message.error'));
        }
    }

    public function cancelUpdateProfileImage()
    {
        $this->showConfirmCancel = false;

        $this->reset(['profile_image']);
    }


    public function openEditSection(){
        $this->editMode = true;
      
        $this->first_name = $this->authUser->first_name;
        $this->last_name  = $this->authUser->last_name;
        $this->email      = $this->authUser->email;
        $this->phone      = $this->authUser->phone;
    }

    public function closedEditSection(){
        $this->editMode = false;
        $this->resetFields();
    }

    public function updateProfile(){
    //    dd($this->all());
        $validatedDate = $this->validate([
            'first_name'  => 'required',
            'last_name'   => 'required',
            'phone'         => 'required|digits:10'
        ]);

        $userDetails = [];
        $userDetails['first_name'] = $this->first_name;
        $userDetails['last_name']  = $this->last_name;
        $userDetails['name']       = $this->first_name.' '.$this->last_name;
        $userDetails['phone']      = $this->phone;

        $this->authUser->update($userDetails);

        $this->closedEditSection();
        $this->alert('success', 'Profile has been updated.');
    }

    public function resetFields(){
        $this->first_name = '';
        $this->last_name  = '';
        $this->email      = '';
        $this->phone      = '';
    }

}

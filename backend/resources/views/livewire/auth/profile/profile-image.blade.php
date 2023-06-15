<div class="col-lg-4">
    <div class="card mb-4">
        <div class="card-body text-center">
        
            <!-- <div wire:loading wire:target="profile_image" class="loader" role="status" aria-hidden="true"></div>
            <div wire:loading wire:target="cancelUpdateProfileImage" class="loader" role="status" aria-hidden="true"></div> -->
            

            @if ($profile_image)  
            <img src="{{ $profile_image->temporaryUrl() }}" alt="avatar"
            class="rounded-circle img-fluid" style="width: 150px;">
            @else
            <img src="{{ ($authUser->profileImage()->first()) ? $authUser->profileImage()->first()->file_url : asset(config('constants.default.profile_image')) }}" alt="avatar"
            class="rounded-circle img-fluid" style="width: 150px;">
            @endif   

        <h5 class="my-3">{{ ucfirst($authUser->name) }}</h5>
        <p class="text-muted mb-1">{{ $authUser->my_referral_code ?? ''  }}</p>
        <p class="text-muted mb-1">{{ $authUser->profile->profession ?? ''  }}</p>
        <div class="d-flex justify-content-center mb-2">

        @if($showConfirmCancel)
            <button class="btn btn-outline-success ms-1 mr-1" wire:key="action-{{generateRandomString(5)}}" wire:click.prevent="$emitSelf('confirmUpdateProfileImage')"><i class="fa fa-check"></i></button>

            <button class="btn btn-outline-danger ms-1" wire:key="action-{{generateRandomString(5)}}"  wire:click.prevent="$emitSelf('cancelUpdateProfileImage')"><i class="fa fa-close"></i></button>
        @else
            <input id="profile-image-upload" wire:model.defer="profile_image" class="d-none" type="file" wire:change="validateProfileImage" accept="image/*">
            <button type="button" class="btn btn-outline-primary ms-1" onclick="document.getElementById('profile-image-upload').click();" >Change</button>
        @endif
                
        </div>
        </div>
    </div>
</div>
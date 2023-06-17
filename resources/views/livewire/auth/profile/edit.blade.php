 
 <form wire:submit.prevent="updateProfile">
 <!--Start row-1  -->
 <div class="row">
    
    @include('livewire.auth.profile.profile-image')

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">            
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.user.fields.first_name') }}</label>
                            <input type="text" class="form-control" wire:model.defer="first_name" placeholder="{{ __('cruds.user.fields.first_name') }}" />
                            @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.user.fields.last_name') }}</label>
                            <input type="text" class="form-control" wire:model.defer="last_name" placeholder="{{ __('cruds.user.fields.last_name') }}"/>
                            @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.user.fields.email') }}</label>
                            <input type="email" class="form-control" wire:model.defer="email" placeholder="{{ __('cruds.user.fields.email') }}" disabled/>
                            @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.user.fields.phone') }}</label>
                            <input type="text" class="form-control" wire:model.defer="phone" placeholder="{{ __('cruds.user.fields.phone') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 " step="1"  autocomplete="off"/>
                            @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3">
    <button class="btn btn-success" type="submit" wire:loading.attr="disabled">
        {{ __('global.update')}}
        <span wire:loading wire:target="updateProfile">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
    <button class="btn btn-secondary" wire:loading.attr="disabled" wire:click.prevent="closedEditSection">
        {{ __('global.back')}}
        <span wire:loading wire:target="closedEditSection">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
</div>

</form>
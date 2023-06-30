 
 <h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.user.title_singular'))}}</h4>

 <div wire:loading wire:target="update,store" class="loader"></div>
 <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}">
 <!--Start row-1  -->
 <div class="row">
    
    <div class="col-lg-12">
        <div class="card mb-4">
        <div class="card-body">
            <div class="row">
           
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.user.fields.first_name') }}</label>
                        <input type="text" class="form-control" wire:model.defer="first_name" placeholder="{{ __('cruds.user.fields.first_name') }}" />
                        @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.user.fields.last_name') }}</label>
                        <input type="text" class="form-control" wire:model.defer="last_name" placeholder="{{ __('cruds.user.fields.last_name') }}"/>
                        @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

            </div>
           
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.user.fields.email') }}</label>
                        <input type="email" class="form-control" wire:model.defer="email" placeholder="{{ __('cruds.user.fields.email') }}" />
                        @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
         
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.fields.phone') }}</label>
                    <input type="text" class="form-control" wire:model.defer="phone" placeholder="{{ __('cruds.user.fields.phone') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 " step="1"  autocomplete="off"/>
                    @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.gender') }}</label>
                    <select wire:model.defer="gender" class="form-control">
                        <option value="">Select gender</option>
                        <option value="male" {{$gender == 'male' ? 'selected' : ''}}>Male</option>
                        <option value="female" {{$gender == 'female' ? 'selected' : ''}}>Female</option>
                        <option value="other" {{$gender == 'other' ? 'selected' : ''}}>Other</option>
                    </select>
                    @error('gender') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.marital_status') }}</label>
                    <select wire:model.defer="marital_status" class="form-control">
                        <option value="">Select marital status</option>
                        <option value="married" {{$gender == 'married' ? 'selected' : ''}}>Married</option>
                        <option value="unmarried" {{$gender == 'unmarried' ? 'selected' : ''}}>Unmarried</option>
                    </select>
                    @error('marital_status') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.fields.dob') }}</label>
                    <input type="date" class="form-control" wire:model.defer="dob" placeholder="{{ __('cruds.user.fields.dob') }}"/>
                    @error('dob') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.guardian_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="guardian_name" placeholder="{{ __('cruds.user.profile.guardian_name') }}"/>
                    @error('guardian_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
      
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.profession') }}</label>
                    <input type="text" class="form-control" wire:model.defer="profession" placeholder="{{ __('cruds.user.profile.profession') }}"/>
                    @error('profession') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.address') }}</label>
                    <textarea class="form-control" wire:model.defer="address" rows="4"></textarea>
                    @error('address') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.city') }}</label>
                    <input type="text" class="form-control" wire:model.defer="city" placeholder="{{ __('cruds.user.profile.city') }}"/>
                    @error('city') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.state') }}</label>
                    <input type="text" class="form-control" wire:model.defer="state" placeholder="{{ __('cruds.user.profile.state') }}"/>
                    @error('state') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.pin_code') }}</label>
                    <input type="text" class="form-control" wire:model.defer="pin_code" placeholder="{{ __('cruds.user.profile.pin_code') }}"/>
                    @error('pin_code') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Referral Code -->
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.fields.referral_code') }}</label>
                    <input type="text" class="form-control" wire:model.defer="referral_code" placeholder="{{ __('cruds.user.fields.referral_code') }}" />
                    @error('referral_code') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.fields.referral_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="referral_name" placeholder="{{ __('cruds.user.fields.referral_name') }}" />
                    @error('referral_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.fields.date_of_join') }}</label>
                    <input type="date" class="form-control" wire:model.defer="date_of_join"  />
                    @error('date_of_join') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
        </div>

    

        <!-- Start nominee details -->
        <p class="mb-4">Nominee Details</p>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.nominee_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="nominee_name" placeholder="{{ __('cruds.user.profile.nominee_name') }}"/>
                    @error('nominee_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.nominee_dob') }}</label>
                    <input type="date" class="form-control" wire:model.defer="nominee_dob" placeholder="{{ __('cruds.user.profile.nominee_dob') }}"/>
                    @error('nominee_dob') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.nominee_relation') }}</label>
                    <input type="text" class="form-control" wire:model.defer="nominee_relation" placeholder="{{ __('cruds.user.profile.nominee_relation') }}"/>
                    @error('nominee_relation') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
        </div>
        <!-- End nominee details -->

        <!-- Start nominee details -->
        <p class="mb-4">Bank Details</p>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.bank_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="bank_name" placeholder="{{ __('cruds.user.profile.bank_name') }}"/>
                    @error('bank_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.branch_name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="branch_name" placeholder="{{ __('cruds.user.profile.branch_name') }}"/>
                    @error('branch_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.account_number') }}</label>
                    <input type="text" class="form-control" wire:model.defer="account_number" placeholder="{{ __('cruds.user.profile.account_number') }}"/>
                    @error('account_number') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.ifsc_code') }}</label>
                    <input type="text" class="form-control" wire:model.defer="ifsc_code" placeholder="{{ __('cruds.user.profile.ifsc_code') }}"/>
                    @error('ifsc_code') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.user.profile.pan_card_number') }}</label>
                    <input type="text" class="form-control" wire:model.defer="pan_card_number" placeholder="{{ __('cruds.user.profile.pan_card_number') }}"/>
                    @error('pan_card_number') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>


        </div>
        </div>
    </div>
</div>
<!--End row-1  -->


<div class="text-center mt-3">
    <button class="btn btn-fill btn-blue" type="submit" wire:loading.attr="disabled">
       {{ $updateMode ? __('global.update') : __('global.submit') }}     
        <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
    <button class="btn btn-fill btn-light" wire:loading.attr="disabled" wire:click.prevent="cancel">
        {{ __('global.back')}}
        <span wire:loading wire:target="cancel">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
</div>

</form>
<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title fs-5 font-weight-bold" id="changePasswordLabel">{{ trans('global.change_password') }}</h3>
                    <button type="button" class="close close-modal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading wire:target="updatePassword" class="loader"></div>
                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{__('global.current_password') }}</label>
                            <div class="input-group show_hide_password" id="show_hide_password">
                                <input type="password" class="form-control" wire:model.defer="current_password" id="current-password" autocomplete="off">
                                <div class="input-group-addon">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('current_password') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">{{__('global.new_password')}}</label>
                            <div class="input-group show_hide_password" id="show_hide_password">
                                <input type="password" class="form-control" wire:model.defer="password" id="new-password" autocomplete="off">
                                <div class="input-group-addon">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('password') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{__('global.confirm_new_password')}}</label>
                            <div class="input-group show_hide_password" id="show_hide_password">
                                <input  type="password" wire:model.defer="password_confirmation" class="form-control" id="password_confirmation" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </div>
                            </div>                            
                            @error('password_confirmation') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn btn-fill btn-blue uppassword">{{__('global.update_password')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end model  -->

</div>
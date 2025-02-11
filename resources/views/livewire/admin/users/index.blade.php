<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
    
                    @if($formMode)
    
                        @include('livewire.admin.users.form')
    
                    @elseif($viewMode)
    
                        @livewire('admin.users.show', ['user_id' => $user_id])
    
                    @else
                        <div wire:loading wire:target="create" class="loader"></div>
                        <div class="card-title top-box-set">
                            <h4 class="card-title-heading">All {{__('cruds.user.title')}} {{ __('global.list') }}</h4>
                        </div>
                        <div class="table-responsive search-table-data">
    
                            @livewire('admin.users.user-table')
    
                        </div>
    
                    @endif
    
                </div>
            </div>
        </div>
    </div>
    
    {{-- Edit REP Code Modal --}}
    <div wire:ignore.self class="modal fade" id="editRepCodeModal" tabindex="-1" role="dialog" aria-labelledby="editRepCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRepCodeModalLabel">{{ $referral_code ? 'Edit' : 'Add' }} REP Code</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="updateRepCode" class="forms-sample" autocomplete="off">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <input type="text" wire:model.defer="referral_code" class="form-control" placeholder="REP Code">
                        @error('referral_code') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <input type="text" wire:model.defer="bonus_credits" class="form-control" placeholder="Bonus Credits">
                        @error('bonus_credits') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                        Submit
                        <span wire:loading wire:target="updateRepCode">
                            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    {{-- End Edit REP Code Modal --}}

    {{-- Edit Credit Limit Modal --}}
    <div wire:ignore.self class="modal fade" id="editCreditLimitModal" tabindex="-1" role="dialog" aria-labelledby="editCreditLimitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCreditLimitModalLabel">Edit Credits</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="updateCreditLimit" class="forms-sample" autocomplete="off">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <input type="text" wire:model.defer="credit_limit" class="form-control" placeholder="Credit Limit">
                        @error('credit_limit') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                        Submit
                        <span wire:loading wire:target="updateCreditLimit">
                            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    {{-- End Edit Credit Limit Modal --}}
    
    </div>
    
    @push('styles')
    
    @endpush
    
    @push('scripts')
    <script type="text/javascript">
        $(document).on('click', '.toggleSwitchMain', function(e){
            var _this = $(this);
            var id = _this.data('id');
            var type = _this.data('type');
    
            var data = { id: id, type: type }
    
            var flag = true;
            if(_this.prop("checked")){
                flag = false;
            }
            Swal.fire({
                title: (type == 'level_3') ? 'Are you sure you want to change the status of level 3?' : 'Are you sure you want to change the status?',
                showDenyButton: true,
                icon: 'warning',
                confirmButtonText: 'Yes, change it',
                denyButtonText: `No, cancel!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.emit('confirmedToggleAction', data);
                } else if (result.isDenied) {
                    _this.prop("checked", flag);
                }
            })
        })

        $(document).on('click', '.deleteBtn', function(e){
            var _this = $(this);
            var id = _this.attr('data-id');
    
            Swal.fire({
                title: 'Are you sure you want to delete it?',
                showDenyButton: true,
                icon: 'warning',
                confirmButtonText: 'Yes, Delete',
                denyButtonText: `No, cancel!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.emit('deleteConfirm', id);
                }
            })
        })
    
        $(document).on('click', '.editCreditLimitBtn', function(e){
            var _this = $(this);
            var id = _this.attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to update credit limit?',
                showDenyButton: true,
                icon: 'warning',
                confirmButtonText: 'Yes',
                denyButtonText: `No, cancel!`,
            }).then((result) => {
                if (result.isConfirmed) {
                   
                    @this.emit('editCreditLimit', id);
    
                    $(document).find('#editCreditLimitModal').modal('show');
    
                }
            })
        });

    /* Start Rep Code js Script*/
        $(document).on('click', '.editRepCodeBtn', function(e){
            var _this = $(this);
            var id = _this.attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to proceed with this update?',
                showDenyButton: true,
                icon: 'warning',
                confirmButtonText: 'Yes',
                denyButtonText: `No, cancel!`,
            }).then((result) => {
                if (result.isConfirmed) {
                   
                    @this.emit('editRepCode', id);
    
                    $(document).find('#editRepCodeModal').modal('show');
    
                }
            })
        });
    /* End Rep Code js Script*/
        
    /* Start Closed Model Script*/
        document.addEventListener('closed-modal', function (event) {
            $(document).find('#editCreditLimitModal').modal('hide');
            $(document).find('#editRepCodeModal').modal('hide');
        });
    
        $(document).on('click','.close-modal',function(){
            $(document).find('#editCreditLimitModal').modal('hide');
            $(document).find('#editRepCodeModal').modal('hide');
        });
    /* End Closed Model Script */
    </script>
    @endpush
    
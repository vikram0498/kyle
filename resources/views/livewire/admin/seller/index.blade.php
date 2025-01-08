<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if($formMode)

                    @include('livewire.admin.seller.form')

                @elseif($viewMode)

                    @livewire('admin.seller.show', ['user_id' => $user_id])

                @else
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{__('cruds.user.title')}} {{ __('global.list') }}</h4>
                        {{-- <div class="card-top-box-item"> <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text btn-header">
                            <i class="ti-plus btn-icon-prepend"></i>
                                {{__('global.add')}}
                        </button></div> --}}
                    </div>
                    <div class="table-responsive search-table-data">

                        @livewire('admin.seller.user-table')

                    </div>

                @endif

            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
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
{{-- End Edit Modal --}}

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

    document.addEventListener('closed-modal', function (event) {
        $(document).find('#editCreditLimitModal').modal('hide');
    });

    $(document).on('click','.close-modal',function(){
        // console.log('closed');
        $(document).find('#editCreditLimitModal').modal('hide');
    });
</script>
@endpush

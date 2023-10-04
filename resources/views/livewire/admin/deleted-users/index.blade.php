<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if($viewMode)

                    @livewire('admin.deleted-users.show', ['user_id' => $user_id])
                  
                @else
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{__('global.deleted')}} {{__('cruds.user.title')}} {{ __('global.list') }}</h4>
                    </div>
                    <div class="table-responsive search-table-data">
                        @livewire('admin.deleted-users.deleted-users-datatable') 
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>
</div>

@push('styles')

@endpush

@push('scripts')
<script type="text/javascript">
    $(document).on('click', '.resetUserBtn', function(e){
        var _this = $(this);
        var id = _this.attr('data-id');
       
        Swal.fire({
            title: 'Are you sure you want to restore this user?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('resetUserBack', id);
            }
        })
    })
</script>
@endpush
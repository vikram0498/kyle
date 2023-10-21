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
                        {{-- @livewire('admin.seller.seller-datatable')  --}}

                        @livewire('admin.seller.user-table') 

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
            title: 'Are you sure you want to change the status?',
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
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('deleteConfirm', id);
            }
        })
    })
</script>
@endpush
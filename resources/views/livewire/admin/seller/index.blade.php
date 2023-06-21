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
                    <div class="card-title">
                        <h4 class="float-left">{{__('cruds.user.title')}} {{ __('global.list') }}</h4>
                        {{-- <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text float-right">
                            <i class="ti-plus btn-icon-prepend"></i>                                                    
                                {{__('global.add')}}
                        </button> --}}
                    </div>
                    <div class="table-responsive">
                        <div class="align-items-center border-top mt-4 pt-2 row justify-content-between">
                            <div class="col-md-2">
                                <select wire:change="changeNumberOfList($event.target.value)" class="form-control">
                                    @foreach($numberOfrowsList as $key => $val)
                                        <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach 
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="table-additional-plugin ">
                                    <input type="text" class="form-control" wire:model="search" placeholder="{{ __('global.search')}}">
                                </div>
                            </div>
                        </div>   
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    {{ trans('cruds.user.fields.name') }}
                                    <span wire:click="sortBy('name')" class="float-right text-sm" style="cursor: pointer;">
                                        <i class="fa fa-arrow-up {{ $sortColumnName === 'name' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                        <i class="fa fa-arrow-down {{ $sortColumnName === 'name' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                    </span>
                                </th>
                                <th>{{ trans('global.status') }}</th>
                                <!-- <th>{{ trans('cruds.user.fields.block_status') }}</th> -->
                                <th>
                                    {{ trans('cruds.user.fields.buyer_count') }}
                                    <span wire:click="sortBy('buyers_count')" class="float-right text-sm" style="cursor: pointer;">
                                        <i class="fa fa-arrow-up {{ $sortColumnName === 'buyers_count' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                        <i class="fa fa-arrow-down {{ $sortColumnName === 'buyers_count' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                    </span>
                                </th>
                                <th>
                                    {{ trans('global.created_at') }}
                                    <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                                        <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                        <i class="fa fa-arrow-down {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                    </span>
                                </th>
                                <th>{{ trans('global.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($allUser->count() > 0)
                                @foreach($allUser as $serialNo => $user)
                                    <tr>
                                        <td>{{ $serialNo+1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>                        
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch toggleSwitchMain" data-type="is_active" data-id="{{$user->id}}" {{ $user->is_active == 1 ? 'checked' : '' }}>
                                                <div class="switch-slider round" data-on="Active" data-off="Inactive"></div>
                                            </label>
                                        </td>
                                        <!-- <td>                        
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch" wire:click="blockToggle({{$user->id}})" {{ $user->is_block == 1 ? 'checked' : '' }}>
                                                <div class="switch-slider" data-on="Active" data-off="Inactive"></div>
                                            </label>
                                        </td> -->
                                        <td> {{ $user->buyers_count }} </td>
                                        <td>{{ convertDateTimeFormat($user->created_at,'datetime') }}</td>
                                        <td>
                                            <button type="button" wire:click="show({{$user->id}})" class="btn btn-primary btn-rounded btn-icon">
                                                <i class="ti-eye"></i>
                                            </button>
                                            
                                            
                                           {{-- <button type="button" wire:click="edit({{$user->id}})" class="btn btn-info btn-rounded btn-icon">
                                                <i class="ti-pencil-alt"></i>
                                            </button> --}}

                                            <button type="button" data-id="{{$user->id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5">{{ __('messages.no_record_found')}}</td>
                            </tr>
                            @endif
                        
                        </tbody>
                        </table>
                    
                        {{ $allUser->links('vendor.pagination.bootstrap-5') }}
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
        var id = _this.data('id');
       
        Swal.fire({
            title: 're you sure you want to delete it?',
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
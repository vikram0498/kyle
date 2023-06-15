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
                    {{--<div class="card-title">
                        <h4 class="float-left">{{__('cruds.user.title')}} {{ __('global.list') }}</h4>
                        <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text float-right">
                            <i class="ti-plus btn-icon-prepend"></i>                                                    
                                {{__('global.add')}}
                        </button>
                    </div> --}}
                    <div class="table-responsive">
                        <div class="table-additional-plugin">
                            <input type="text" class="form-control col-2" wire:model="search" placeholder="{{ __('global.search')}}">
                        </div>
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('global.sno') }}</th>
                                <th>{{ trans('cruds.user.fields.name') }}</th>
                                <th>{{ trans('global.status') }}</th>
                                <th>{{ trans('cruds.user.fields.block_status') }}</th>
                                <th>{{ trans('global.created_at') }}</th>
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
                                                <input type="checkbox" class="toggleSwitch" wire:click="activeToggle({{$user->id}})" {{ $user->is_active == 1 ? 'checked' : '' }}>
                                                <div class="switch-slider round"></div>
                                            </label>
                                        </td>
                                        <td>                        
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch" wire:click="blockToggle({{$user->id}})" {{ $user->is_block == 1 ? 'checked' : '' }}>
                                                <div class="switch-slider switch-slider-block round"></div>
                                            </label>
                                        </td>
                                        <td>{{ convertDateTimeFormat($user->created_at,'datetime') }}</td>
                                        <td>
                                            <button type="button" wire:click="show({{$user->id}})" class="btn btn-primary btn-rounded btn-icon">
                                                <i class="ti-eye"></i>
                                            </button>
                                            
                                            
                                           {{-- <button type="button" wire:click="edit({{$user->id}})" class="btn btn-info btn-rounded btn-icon">
                                                <i class="ti-pencil-alt"></i>
                                            </button> --}}

                                            <button type="button" wire:click="delete({{$user->id}})" class="btn btn-danger btn-rounded btn-icon">
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

</script>
@endpush
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($formMode)
        
                        @include('livewire.admin.buyer.form')

                    @elseif($viewMode)

                        @livewire('admin.buyer.show', ['buyer_id' => $buyer_id])

                    @else
                        <div wire:loading wire:target="{{ $updateMode ? 'edit' : 'create' }}" class="loader"></div>
                        <div class="card-title">
                            <h4 class="float-left">{{__('cruds.buyer.title')}} {{ __('global.list') }}</h4>
                            
                            <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text float-right">
                                <i class="ti-plus btn-icon-prepend"></i>                                                    
                                    {{__('global.add')}}
                            </button>
                            <a href="{{ route('admin.import-buyers') }}" class="btn btn-sm btn-primary mr-2 btn-icon-text float-right">
                                <i class="fa fa-download"></i>                                                
                                    {{__('cruds.buyer.fields.buyer_csv_import')}}
                            </a>
                            <a href="{{ asset('default/sample_template_import_buyer.csv') }}" download="{{ asset('default/sample_template_import_buyer.csv') }}" class="btn btn-sm btn-info mr-2 btn-icon-text float-right">
                                <i class="fa fa-upload"></i>                                                
                                    {{__('cruds.buyer.fields.buyer_csv_template')}}
                            </a>
                            <a href="{{ $buyerFormLink }}" class="btn btn-sm btn-dark mr-2 btn-icon-text float-right copy_link position-relative">
                                <i class="fa fa-copy"></i>                                                
                                    {{__('cruds.buyer.fields.copy_add_buyer_link')}}
                            </a>
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
                                            {{ trans('cruds.buyer.fields.name') }}
                                            <span wire:click="sortBy('first_name')" class="float-right text-sm" style="cursor: pointer;">
                                                <i class="fa fa-arrow-up {{ $sortColumnName === 'first_name' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                <i class="fa fa-arrow-down {{ $sortColumnName === 'first_name' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                            </span>
                                        </th>
                                        <th>{{ trans('global.status') }}</th>
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
                                    @if($allBuyers->count() > 0)
                                        @foreach($allBuyers as $serialNo => $buyer)
                                            <tr>
                                                <td>{{ $serialNo+1 }}</td>
                                                <td>{{ ucfirst($buyer->first_name).' '. ucfirst($buyer->last_name) }}</td>
                                                <td>
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" class="toggleSwitch toggleSwitchMain" data-type="status"  data-id="{{$buyer->id}}"  {{ $buyer->status == 1 ? 'checked' : '' }}>
                                                        <span class="switch-slider" data-on="Active" data-off="Ban"></span>
                                                    </label>
                                                </td>
                                                <td>{{ convertDateTimeFormat($buyer->created_at,'datetime') }}</td>
                                                <td>
                                                    <button type="button" wire:click="show({{$buyer->id}})" class="btn btn-primary btn-rounded btn-icon">
                                                        <i class="ti-eye"></i>
                                                    </button>
                                                    @if($buyer->user_id == auth()->user()->id)
                                                        <button type="button" wire:click="edit({{$buyer->id}})" class="btn btn-info btn-rounded btn-icon">
                                                            <i class="ti-pencil-alt"></i>
                                                        </button>

                                                        <button type="button" data-id ="{{$buyer->id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
                                                            <i class="ti-trash"></i>
                                                        </button>
                                                    @endif
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

                            {{ $allBuyers->links('vendor.pagination.bootstrap-5') }}
                        
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" integrity="sha512-aD9ophpFQ61nFZP6hXYu4Q/b/USW7rpLCQLX6Bi0WJHXNO7Js/fUENpBQf/+P4NtpzNX0jSgR5zVvPOJp+W2Kg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@push('scripts')

<script type="text/javascript">
    document.addEventListener('loadPlugins', function (event) {
        $('.select2').select2({
            theme: "classic"
        });
    });

    $(document).on('click','.copy_link', function (e) {
        e.preventDefault();
        var copyText = $(this).attr('href');
        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
        }, true);
        document.execCommand('copy');
    });

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

    $(document).on('change','.select2', function(e){
        var pr = $(this).data('property');
        var pr_vals = $(this).val();
        @this.set('state.'+pr, pr_vals);
        if(pr == 'buyer_type'){
            @this.emit('changeBuyerType', $('.buyer_type').select2('val'));
        } 
    });

    $(document).on('click', '.toggleSwitchMain', function(e){
        var _this = $(this);
        var id = _this.data('id');
        var type = _this.data('type');

        var data = {
            id: id,
            type: type
        }
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
   

</script>
@endpush
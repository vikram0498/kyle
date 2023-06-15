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
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title">
                        <h4 class="float-left">{{__('cruds.buyer.title')}} {{ __('global.list') }}</h4>
                        <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text float-right">
                            <i class="ti-plus btn-icon-prepend"></i>                                                    
                                {{__('global.add')}}
                        </button>
                    </div>                
                    <div class="table-responsive">
                        <div class="table-additional-plugin">
                            <input type="text" class="form-control col-2" wire:model="search" placeholder="{{ __('global.search')}}">
                        </div>
                       
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('global.sno') }}</th>
                                <th>{{ trans('cruds.buyer.fields.name') }}</th>
                                <th>{{ trans('cruds.buyer.fields.email') }}</th>
                                <th>{{ trans('cruds.buyer.fields.phone') }}</th>
                                <th>{{ trans('global.status') }}</th>
                                <th>{{ trans('global.created_at') }}</th>
                                <th>{{ trans('global.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($allBuyers->count() > 0)
                                @foreach($allBuyers as $serialNo => $buyer)
                                    <tr>
                                        <td>{{ $serialNo+1 }}</td>
                                        <td>{{ ucfirst($buyer->first_name).' '. ucfirst($buyer->last_name) }}</td>
                                        <td>{{ $buyer->email }}</td>
                                        <td> {{ $buyer->phone }}</td>
                                        <td>
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch"  wire:click="toggle({{$buyer->id}})" {{ $buyer->status == 1 ? 'checked' : '' }}>
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>
                                        <!-- <td>
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch"  wire:click="toggle({{$buyer->id}})" {{ $buyer->status == 1 ? 'checked' : '' }}>
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td> -->
                                        <td>{{ convertDateTimeFormat($buyer->created_at,'datetime') }}</td>
                                        <td>
                                            <button type="button" wire:click="show({{$buyer->id}})" class="btn btn-primary btn-rounded btn-icon">
                                                <i class="ti-eye"></i>
                                            </button>
                                            
                                            <button type="button" wire:click="edit({{$buyer->id}})" class="btn btn-info btn-rounded btn-icon">
                                                <i class="ti-pencil-alt"></i>
                                            </button>

                                            <button type="button" wire:click="delete({{$buyer->id}})" class="btn btn-danger btn-rounded btn-icon">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@push('scripts')

<script type="text/javascript">

    document.addEventListener('loadPlugins', function (event) {
        $('.select2').select2({
            theme: "classic"
        });

        
    });

    $(document).on('change','.select2', function(e){
        var pr = $(this).data('property');
        var pr_vals = $(this).val();
        @this.set('state.'+pr, pr_vals);
        if(pr == 'buyer_type'){
            @this.emit('changeBuyerType', $('.buyer_type').select2('val'));
        } 
    });
   

</script>
@endpush
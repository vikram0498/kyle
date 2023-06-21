<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ (__('cruds.buyer.fields.buyer_csv_import'))}}
                    </h4>
                    <form wire:submit.prevent="{{ 'searchBuyers' }}" class="forms-sample" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.address')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="state.address" placeholder="{{ __('cruds.buyer.fields.address')}}" autocomplete="off" >
                                    @error('address') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.city')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="state.city" placeholder="{{ __('cruds.buyer.fields.city')}}" autocomplete="off" >
                                    @error('city') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.state')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="state.state" placeholder="{{ __('cruds.buyer.fields.state')}}" >
                                    @error('state') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.zip_code')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="state.zip_code" placeholder="{{ __('cruds.buyer.fields.zip_code')}}" autocomplete="off"  min="0">
                                    @error('zip_code') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_min')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="state.bedroom_min" placeholder="{{ __('cruds.buyer.fields.bedroom_min')}}" autocomplete="off"  min="0">
                                    @error('bedroom_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_max')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="state.bedroom_max" placeholder="{{ __('cruds.buyer.fields.bedroom_max')}}"  min="0">
                                    @error('bedroom_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_min')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.bath_min" placeholder="{{ __('cruds.buyer.fields.bath_min')}}" autocomplete="off"  min="0">
                                    @error('bath_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_max')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.bath_max" placeholder="{{ __('cruds.buyer.fields.bath_max')}}" autocomplete="off"  min="0">
                                    @error('bath_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_min')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="state.size_min" placeholder="{{ __('cruds.buyer.fields.size_min')}}"  min="0">
                                    @error('size_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_max')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="state.size_max" placeholder="{{ __('cruds.buyer.fields.size_max')}}" autocomplete="off"  min="0">
                                    @error('size_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_min')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.lot_size_min" placeholder="{{ __('cruds.buyer.fields.lot_size_min')}}" autocomplete="off"  min="0">
                                    @error('lot_size_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_max')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.lot_size_max" placeholder="{{ __('cruds.buyer.fields.lot_size_max')}}"  min="0">
                                    @error('lot_size_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_min')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.build_year_min" placeholder="{{ __('cruds.buyer.fields.build_year_min')}}" autocomplete="off"  min="0">
                                    @error('build_year_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_max')}} </label>
                                    <input type="number" class="form-control" wire:model.defer="state.build_year_max" placeholder="{{ __('cruds.buyer.fields.build_year_max')}}" autocomplete="off"  min="0">
                                    @error('build_year_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.arv_min')}}</label>
                                    <input type="number" class="form-control" wire:model.defer="state.arv_min" placeholder="{{ __('cruds.buyer.fields.arv_min')}}" min="0">
                                    @error('arv_min') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.arv_max')}}</label>
                                    <input type="number" class="form-control" wire:model.defer="state.arv_max" placeholder="{{ __('cruds.buyer.fields.arv_max')}}" autocomplete="off" min="0">
                                    @error('arv_max') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.parking')}}</label>
                                    <div wire:ignore>
                                        <select wire:model.defer="state.parking" class="form-control parking select2" data-property="parking">
                                            <option value="">Select {{ __('cruds.buyer.fields.parking')}}</option>
                                            @foreach($parkingValues as $key => $value)
                                                <option value="{{ $key }}"> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('parking') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.property_type')}} <span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select wire:model.defer="state.property_type" class="form-control property_type select2" data-property="property_type">
                                            <option value="">Select {{ __('cruds.buyer.fields.property_type')}}</option>
                                            @foreach($propertyTypes as $key => $value)
                                                <option value="{{ $key }}"> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('property_type') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.property_flaw')}}</label>
                                    <div wire:ignore>
                                        <select wire:model.defer="state.property_flaw" class="form-control property_flaw select2" data-property="property_flaw" multiple data-placeholder="Select {{ __('cruds.buyer.fields.property_flaw')}}">
                                            @foreach($propertyFlaws as $key => $value)
                                                <option value="{{ $key }}"> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('property_flaw') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($radioButtonFields as $fieldName => $fieldData)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{ __("cruds.buyer.fields.".$fieldName) }}</label>
                                        <div class="form-group">
                                            @foreach($fieldData as $data)
                                                <input type="radio" class="" name="{{ $fieldName }}" wire:model.defer="state.{{ $fieldName }}" id="{{ $data['id'] }}" value="{{ $data['value'] }}"> 
                                                <label for="{{ $data['id'] }}"> {{ __("global.".$data['label']) }}</label>
                                            @endforeach
                                        </div>
                                        @error($fieldName) <span class="error text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.purchase_method')}} <span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select wire:model.defer="state.purchase_method" class="form-control purchase_method select2" data-property="purchase_method" id="purchase_method" multiple data-placeholder="Select {{ __('cruds.buyer.fields.purchase_method')}}" >
                                            @foreach($purchaseMethods as $key => $value)
                                                <option value="{{ $key }}"> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('purchase_method') <span class="error text-danger">{{ $message }}</span>@enderror
                                </div>            
                            </div>
                        </div>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary mr-2">
                            {{ __('global.search') }}
                            <span wire:loading wire:target="{{ 'searchBuyers' }}">
                                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            </span>
                        </button>
                        <a href="{{route('admin.buyer')}}" class="btn btn-secondary">
                            {{ __('global.back')}}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Result -->
    @if($showResult)
    <p>test</p>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('cruds.buyer.fields.name') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($allBuyers->count() > 0)
                @foreach($allBuyers as $serialNo => $buyer)
                    <tr>
                        <td>{{ $serialNo+1 }}</td>
                        <td>{{ ucfirst($buyer->first_name).' '. ucfirst($buyer->last_name) }}</td>                   
                    </tr>
                @endforeach
            @else
            <tr>
                <td colspan="5">{{ __('messages.no_record_found')}}</td>
            </tr>
            @endif
        
        </tbody>
    </table>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" integrity="sha512-aD9ophpFQ61nFZP6hXYu4Q/b/USW7rpLCQLX6Bi0WJHXNO7Js/fUENpBQf/+P4NtpzNX0jSgR5zVvPOJp+W2Kg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
@push('scripts')

<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'classic'
        });
    });

    $(document).on('change','.select2', function(e){
        var pr = $(this).data('property');
        var pr_vals = $(this).val();
        @this.set('state.'+pr, pr_vals);        

        // // @this.emit('updatePropertyValue', { property : pr, value: pr_vals});        
    });


</script>
@endpush
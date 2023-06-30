<div>  
    <h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.buyer.title_singular'))}}</h4>

    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample" autocomplete="off">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.first_name')}} <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" wire:model.defer="state.first_name" placeholder="{{ __('cruds.buyer.fields.first_name')}}" >
                    @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.last_name')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.last_name" placeholder="{{ __('cruds.buyer.fields.last_name')}}" autocomplete="off" >
                    @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.email')}} <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" wire:model.defer="state.email" placeholder="{{ __('cruds.buyer.fields.email')}}" autocomplete="off" >
                    @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.phone')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.phone" placeholder="{{ __('cruds.buyer.fields.phone') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 " step="1"  autocomplete="off" />
                    @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
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
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.company_name')}} </label>
                    <input type="text" class="form-control" wire:model.defer="state.company_name" placeholder="{{ __('cruds.buyer.fields.company_name')}}" autocomplete="off" >
                    @error('company_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.occupation')}} </label>
                    <input type="text" class="form-control" wire:model.defer="state.occupation" placeholder="{{ __('cruds.buyer.fields.occupation')}}" >
                    @error('occupation') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.replacing_occupation')}}</label>
                    <input type="text" class="form-control" wire:model.defer="state.replacing_occupation" placeholder="{{ __('cruds.buyer.fields.replacing_occupation')}}" autocomplete="off" >
                    @error('replacing_occupation') <span class="error text-danger">{{ $message }}</span>@enderror
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
                        <select wire:model.defer="state.parking" class="form-control parking select2" data-property="parking" multiple data-placeholder="Select {{ __('cruds.buyer.fields.parking')}}">
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
                        <select wire:model.defer="state.property_type" class="form-control property_type select2" data-property="property_type" multiple data-placeholder="Select {{ __('cruds.buyer.fields.property_type')}}" >
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
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="radio-block-group">
                            <label class="font-weight-bold">{{ __("cruds.buyer.fields.".$fieldName) }}</label>
                            @foreach($fieldData as $data)
                            <div class="label-container">
                                <input type="radio" class="" name="{{ $fieldName }}" wire:model.defer="state.{{ $fieldName }}" id="{{ $data['id'] }}" value="{{ $data['value'] }}"> 
                                <label for="{{ $data['id'] }}"> {{ __("global.".$data['label']) }}</label>
                            </div>
                            @endforeach
                            @error($fieldName) <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.buyer_type')}} <span class="text-danger">*</span> </label>
                    <div wire:ignore>
                        <select wire:model.defer="state.buyer_type"  class="form-control select2 buyer_type" data-property="buyer_type" data-placeholder="Select {{ __('cruds.buyer.fields.buyer_type')}}"  multiple>
                            @foreach($buyerTypes as $key => $value)
                                <option value="{{ $key }}"> {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('buyer_type') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        @if($creativeBuyer)
            <!-- Creative Buyer -->
            <div class="" id="creative_buyer_main">
                <h4> {{ __('cruds.buyer.creative_buyer') }} </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_down_payment_percentage')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.max_down_payment_percentage" placeholder="{{ __('cruds.buyer.fields.max_down_payment_percentage')}}" autocomplete="off"  max="100" min="0"> 
                            @error('max_down_payment_percentage') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_down_payment_money')}}</label>
                            <input type="number" class="form-control" wire:model.defer="state.max_down_payment_money" placeholder="{{ __('cruds.buyer.fields.max_down_payment_money')}}" autocomplete="off" min="0">
                            @error('max_down_payment_money') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_interest_rate')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.max_interest_rate" placeholder="{{ __('cruds.buyer.fields.max_interest_rate')}}" autocomplete="off" max="100" min="0" >
                            @error('max_interest_rate') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.balloon_payment')}} <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <input type="radio" name="balloon_payment" wire:model.defer="state.balloon_payment" id="yes_balloon_payment" value="1" > <label for="yes_balloon_payment"> {{ __('global.yes') }}</label>
                                <input type="radio" name="balloon_payment" wire:model.defer="state.balloon_payment" id="no_balloon_payment" value="0" > <label for="no_balloon_payment"> {{ __('global.no') }}</label>
                            </div>
                            @error('balloon_payment') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($multiFamilyBuyer)
            <!-- Multi Family Buyer -->
            <div class="" id="multi_family_buyer_main">
                <h4> {{ __('cruds.buyer.multi_family_buyer') }} </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_min')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.unit_min" placeholder="{{ __('cruds.buyer.fields.unit_min')}}" autocomplete="off"  min="0"> 
                            @error('unit_min') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_max')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.unit_max" placeholder="{{ __('cruds.buyer.fields.unit_max')}}" autocomplete="off" min="0" >
                            @error('unit_max') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.building_class')}} <span class="text-danger">*</span></label>
                            <div wire:ignore>
                                <select wire:model.defer="state.building_class" class="form-control building_class select2" data-property="building_class" multiple data-placeholder="Select {{ __('cruds.buyer.fields.building_class')}}" >
                                    @foreach($buildingClassValue as $key => $value)
                                        <option value="{{ $key }}"> {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('building_class') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.value_add')}} <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <input type="radio" name="value_add" wire:model.defer="state.value_add" id="yes_value_add" value="1" > <label for="yes_value_add"> {{ __('global.yes') }}</label>
                                <input type="radio" name="value_add" wire:model.defer="state.value_add" id="no_value_add" value="0" > <label for="no_value_add"> {{ __('global.no') }}</label>
                            </div>
                            @error('balloon_payment') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

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


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{__('global.status')}}</label>
                    <div class="form-group">
                        <label class="toggle-switch">
                            <input type="checkbox" class="toggleSwitch" wire:change.prevent="changeStatus({{$status}})" value="{{ $status }}" {{ $status ==1 ? 'checked' : '' }}>
                            <span class="switch-slider" data-on="Active" data-off="Inactive"></span>
                        </label>
                    </div>
                    @error('status') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue mr-2">
            {{ $updateMode ? __('global.update') : __('global.submit') }}
            <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
        <button wire:click.prevent="cancel" class="btn btn-fill btn-light">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </form>
</div>
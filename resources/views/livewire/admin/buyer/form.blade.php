<div>  
    <h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.buyer.title_singular'))}}</h4>

    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample" autocomplete="off">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="first_name" class="font-weight-bold">{{ __('cruds.buyer.fields.first_name')}} <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" wire:model.defer="state.first_name" placeholder="{{ __('cruds.buyer.fields.first_name')}}" id="first_name">
                    @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="last_name" class="font-weight-bold">{{ __('cruds.buyer.fields.last_name')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.last_name" placeholder="{{ __('cruds.buyer.fields.last_name')}}" autocomplete="off" id="last_name">
                    @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.email')}} <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" wire:model.defer="state.email" placeholder="{{ __('cruds.buyer.fields.email')}}" autocomplete="off" >
                    @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        
            <div class="col-md-3">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.phone')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.phone" placeholder="{{ __('cruds.buyer.fields.phone') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 " step="1"  autocomplete="off" />
                    @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <!-- <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.address')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.address" placeholder="{{ __('cruds.buyer.fields.address')}}" autocomplete="off" >
                    @error('address') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div> -->
           
            <div class="col-md-12">
                <div class="form-group">
                    <label for="state" class="font-weight-bold">{{ __('cruds.buyer.fields.state')}} 
                        </label>
                    <!-- <input type="text" class="form-control" wire:model.defer="state.state" placeholder="{{ __('cruds.buyer.fields.state')}}" > -->
                   <div wire:ignore> 
                        <select wire:model.defer="state.state" id="state" class="form-control state select2"  data-property="state" data-placeholder="Select {{ __('cruds.buyer.fields.state')}}">
                            <option value="">Select {{ __('cruds.buyer.fields.state')}}</option>
                            @foreach($allStates as $key => $stateName)
                                <option value="{{$key}}"> {{$stateName}} </option>
                            @endforeach
                        </select>
                    </div> 
                    @error('state') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.city')}} </label>
                    <!-- <div wire:ignore> -->
                    <select wire:model.defer="state.city" id="city" class="form-control city select2" id="city" data-property="city" data-placeholder="Select {{ __('cruds.buyer.fields.city')}}">
                        <option value="">Select {{ __('cruds.buyer.fields.city')}}</option>
                        @foreach($allCities as $key => $cityName)
                            <option value="{{$key}}"> {{$cityName}} </option>
                        @endforeach
                    </select>
                    <!-- </div> -->
                    @error('city') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>       
            <!-- <div class="col-md-3">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.zip_code')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.zip_code" placeholder="{{ __('cruds.buyer.fields.zip_code')}}" autocomplete="off"  onkeydown="return (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(event.code) ? true : (!isNaN(Number(event.key))) && event.code !== 'Space' && this.value.length < 9)">
                    @error('zip_code') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div> -->
            <!--<div class="col-md-4">
                <div class="form-group">
                    <label for="country" class="font-weight-bold">{{ __('cruds.buyer.fields.country')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="state.country" disabled>
                    @error('country') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div> -->                       
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.company_name')}} </label>
                    <input type="text" class="form-control" wire:model.defer="state.company_name" placeholder="{{ __('cruds.buyer.fields.company_name')}}" autocomplete="off" >
                    @error('company_name') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.market_preferance')}} <span class="text-danger">*</span></label>
                    <select class="form-control" wire:model.defer="state.market_preferance">
                        <option value="null">Select {{ __('cruds.buyer.fields.market_preferance')}}</option>
                        @foreach ($market_preferances as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('market_preferance') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>            

            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.contact_preferance')}} <span class="text-danger">*</span></label>
                    <select class="form-control" wire:model.defer="state.contact_preferance">
                        <option value="null">Select {{ __('cruds.buyer.fields.contact_preferance')}}</option>
                        @foreach ($contact_preferances as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('contact_preferance') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.property_type')}} <span class="text-danger">*</span></label>
                    <div wire:ignore>
                        <select wire:model.defer="state.property_type" id="property_type" class="form-control property_type select2" data-property="property_type" multiple data-placeholder="Select {{ __('cruds.buyer.fields.property_type')}}" >
                            @foreach($propertyTypes as $key => $value)
                                <option value="{{ $key }}"> {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('property_type') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            @if($multiFamilyBuyer)
            <!-- Multi Family Buyer -->
            <div class="col-md-12" id="multi_family_buyer_main">
                <h4> {{ __('cruds.buyer.multi_family_buyer') }} </h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_min')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.unit_min" placeholder="{{ __('cruds.buyer.fields.unit_min')}}" autocomplete="off"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9"> 
                            @error('unit_min') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_max')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.unit_max" placeholder="{{ __('cruds.buyer.fields.unit_max')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9" >
                            @error('unit_max') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
            @if(isset($state['property_type']) && in_array(14,$state['property_type']))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.park')}} </label>
                    <select class="form-control" wire:model.defer="state.park">
                        <option value="null">Select {{ __('cruds.buyer.fields.park')}}</option>
                        @foreach ($park as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('park') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @endif

            @if(isset($state['property_type']) && in_array(15,$state['property_type']))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.rooms')}} </label>
                    <input type="number" class="form-control" wire:model.defer="state.rooms" placeholder="{{ __('cruds.buyer.fields.rooms')}}" autocomplete="off"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('rooms') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @endif

         {{--
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
            
            --}}
            
            @if(!isset($state['property_type']) || isset($state['property_type']) && !in_array(14, $state['property_type']))
                @if(!isset($state['property_type']) || isset($state['property_type']) && !in_array(15, $state['property_type']))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.bedroom_min" placeholder="{{ __('cruds.buyer.fields.bedroom_min')}}" autocomplete="off"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('bedroom_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.bedroom_max" placeholder="{{ __('cruds.buyer.fields.bedroom_max')}}"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('bedroom_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.bath_min" placeholder="{{ __('cruds.buyer.fields.bath_min')}}" autocomplete="off"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('bath_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.bath_max" placeholder="{{ __('cruds.buyer.fields.bath_max')}}" autocomplete="off"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('bath_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
                @endif
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.size_min" placeholder="{{ __('cruds.buyer.fields.size_min')}}"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('size_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.size_max"  placeholder="{{ __('cruds.buyer.fields.size_max')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('size_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_min')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" wire:model.defer="state.build_year_min" data-property="build_year_min" placeholder="{{ __('cruds.buyer.fields.build_year_min')}}" id="build_year_min" autocomplete="off"  min="0" data-provide="datepicker" data-date-format="yyyy">
                    @error('build_year_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_max')}} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" wire:model.defer="state.build_year_max" data-property="build_year_max" placeholder="{{ __('cruds.buyer.fields.build_year_max')}}" id="build_year_max" autocomplete="off"  min="0">
                    @error('build_year_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @endif

            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.lot_size_min" placeholder="{{ __('cruds.buyer.fields.lot_size_min')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('lot_size_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.lot_size_max" placeholder="{{ __('cruds.buyer.fields.lot_size_max')}}"  min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('lot_size_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.arv_min')}}</label>
                    <input type="number" class="form-control" wire:model.defer="state.arv_min" placeholder="{{ __('cruds.buyer.fields.arv_min')}}" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('arv_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.arv_max')}}</label>
                    <input type="number" class="form-control" wire:model.defer="state.arv_max" placeholder="{{ __('cruds.buyer.fields.arv_max')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('arv_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div> -->

            @if(!isset($state['property_type']) || isset($state['property_type']) && !in_array(7, $state['property_type']) && !in_array(14, $state['property_type']))
            <div class="col-md-4">
                <div class="form-group" id="stories_min">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.stories_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.stories_min" placeholder="{{ __('cruds.buyer.fields.stories_min')}}" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('stories_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" id="stories_max">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.stories_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.stories_max" placeholder="{{ __('cruds.buyer.fields.stories_max')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('stories_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @endif
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.price_min')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.price_min" placeholder="{{ __('cruds.buyer.fields.price_min')}}" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 15">
                    @error('price_min') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.price_max')}} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="state.price_max" placeholder="{{ __('cruds.buyer.fields.price_max')}}" autocomplete="off" min="0" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 9">
                    @error('price_max') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.parking')}}</label>
                    <div wire:ignore>
                        <select wire:model.defer="state.parking" class="form-control parking select2" data-property="parking" data-placeholder="Select {{ __('cruds.buyer.fields.parking')}}">
                            <option value="">Select {{ __('cruds.buyer.fields.parking')}}</option>
                            @foreach($parkingValues as $key => $value)
                                <option value="{{ $key }}"> {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('parking') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            
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
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.buyer_type')}} <span class="text-danger">*</span> </label>
                    <div wire:ignore>
                        <select wire:model.defer="state.buyer_type"  class="form-control select2 buyer_type" data-property="buyer_type" data-placeholder="Select {{ __('cruds.buyer.fields.buyer_type')}}" >
                            <option value="">Select {{ __('cruds.buyer.fields.buyer_type')}}</option>
                            @foreach($buyerTypes as $key => $value)
                                <option value="{{ $key }}"> {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('buyer_type') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
                
        @if(isset($state['property_type']) && in_array(7,$state['property_type']))
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.zoning')}} </label>
                    <select class="form-control zoning select2" wire:model.defer="state.zoning" data-property="zoning" multiple data-placeholder="Select {{ __('cruds.buyer.fields.zoning')}}">
                        @foreach ($zonings as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('zoning') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.utilities')}}</label>
                    <select class="form-control" wire:model.defer="state.utilities">
                        <option value="null">Select {{ __('cruds.buyer.fields.utilities')}}</option>
                        @foreach ($utilities as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('utilities') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.sewer')}}</label>
                    <select class="form-control" wire:model.defer="state.sewer">
                        <option value="null">Select {{ __('cruds.buyer.fields.sewer')}}</option>
                        @foreach ($sewers as $key=>$item)
                            <option value="{{$key}}"> {{$item}}</option>
                        @endforeach
                    </select>
                    @error('sewer') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

        </div>
        @endif
        
        
        <div class="row">           
            @php
            $property=$state['property_type']??null;
            @endphp

            @foreach($radioButtonFields as $fieldName => $fieldData)
                    
                @if (!isset($property) || isset($property) && !in_array(8, $property))
                    
                    @if($fieldName == 'permanent_affix')
                            @continue;
                    @endif                
                @endif
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
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
       
        @if($creativeBuyer)
            <!-- Creative Buyer -->
            <div class="" id="creative_buyer_main">
                <h4> {{ __('cruds.buyer.creative_buyer') }} </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_down_payment_percentage')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.max_down_payment_percentage" placeholder="{{ __('cruds.buyer.fields.max_down_payment_percentage')}}" autocomplete="off" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <=5"  min="0" max="100" step=".01" > 

                            @error('max_down_payment_percentage') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_down_payment_money')}}</label>
                            <input type="number" class="form-control" wire:model.defer="state.max_down_payment_money" placeholder="{{ __('cruds.buyer.fields.max_down_payment_money')}}" autocomplete="off" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <=15"  min="0" step=".01" >
                            @error('max_down_payment_money') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_interest_rate')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model.defer="state.max_interest_rate" placeholder="{{ __('cruds.buyer.fields.max_interest_rate')}}" autocomplete="off" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <=5"  min="0" max="100" step=".01" >
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

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="font-weight-bold">{{__('global.status')}}</label>
                    <div class="form-group">
                        <label class="toggle-switch">
                            <input type="checkbox" class="toggleSwitch" wire:change.prevent="changeStatus({{$this->state['status']}})" value="{{ $this->state['status'] }}" {{ $this->state['status'] ==1 ? 'checked' : '' }}>
                            <span class="switch-slider" data-on="Active" data-off="Block"></span>
                        </label>
                    </div>
                    @error('status') <span class="error text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between"> 
            <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue mr-2">
                {{ $updateMode ? __('global.update') : __('global.submit') }}
                <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
                    <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                </span>
            </button>
            <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
                {{ __('global.back')}}
                <span wire:loading wire:target="cancel">
                    <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                </span>
            </button>
        </div>
    </form>
</div>
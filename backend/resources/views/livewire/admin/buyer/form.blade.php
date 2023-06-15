<h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.buyer.title_singular'))}}</h4>

<form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample" autocomplete="off">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.first_name')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.first_name" placeholder="{{ __('cruds.buyer.fields.first_name')}}" >
                @error('first_name') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.last_name')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.last_name" placeholder="{{ __('cruds.buyer.fields.last_name')}}" autocomplete="off" >
                @error('last_name') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.email')}}</label>
                <input type="email" class="form-control" wire:model.defer="state.email" placeholder="{{ __('cruds.buyer.fields.email')}}" autocomplete="off" >
                @error('email') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.phone')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.phone" placeholder="{{ __('cruds.buyer.fields.phone') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length < 10 " step="1"  autocomplete="off" />
                @error('phone') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.address')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.address" placeholder="{{ __('cruds.buyer.fields.address')}}" autocomplete="off" >
                @error('address') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.city')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.city" placeholder="{{ __('cruds.buyer.fields.city')}}" autocomplete="off" >
                @error('city') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.state')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.state" placeholder="{{ __('cruds.buyer.fields.state')}}" >
                @error('state') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.zip_code')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.zip_code" placeholder="{{ __('cruds.buyer.fields.zip_code')}}" autocomplete="off"  min="0">
                @error('zip_code') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.company_name')}}</label>
                <input type="text" class="form-control" wire:model.defer="state.company_name" placeholder="{{ __('cruds.buyer.fields.company_name')}}" autocomplete="off" >
                @error('company_name') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.occupation')}}</label>
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
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_min')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.bedroom_min" placeholder="{{ __('cruds.buyer.fields.bedroom_min')}}" autocomplete="off"  min="0">
                @error('bedroom_min') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.bedroom_max')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.bedroom_max" placeholder="{{ __('cruds.buyer.fields.bedroom_max')}}"  min="0">
                @error('bedroom_max') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_min')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.bath_min" placeholder="{{ __('cruds.buyer.fields.bath_min')}}" autocomplete="off"  min="0">
                @error('bath_min') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.bath_max')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.bath_max" placeholder="{{ __('cruds.buyer.fields.bath_max')}}" autocomplete="off"  min="0">
                @error('bath_max') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_min')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.size_min" placeholder="{{ __('cruds.buyer.fields.size_min')}}"  min="0">
                @error('size_min') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.size_max')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.size_max" placeholder="{{ __('cruds.buyer.fields.size_max')}}" autocomplete="off"  min="0">
                @error('size_max') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_min')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.lot_size_min" placeholder="{{ __('cruds.buyer.fields.lot_size_min')}}" autocomplete="off"  min="0">
                @error('lot_size_min') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.lot_size_max')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.lot_size_max" placeholder="{{ __('cruds.buyer.fields.lot_size_max')}}"  min="0">
                @error('lot_size_max') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_min')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.build_year_min" placeholder="{{ __('cruds.buyer.fields.build_year_min')}}" autocomplete="off"  min="0">
                @error('build_year_min') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.build_year_max')}}</label>
                <input type="number" class="form-control" wire:model.defer="state.build_year_max" placeholder="{{ __('cruds.buyer.fields.build_year_max')}}" autocomplete="off"  min="0">
                @error('build_year_max') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
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
        <div class="col-md-4">
            <div class="form-group" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.parking')}}</label>
                <select wire:model.defer="state.parking" class="form-control parking select2" data-property="parking" multiple data-placeholder="Select {{ __('cruds.buyer.fields.parking')}}">
                    @foreach($parkingValues as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
                @error('parking') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.property_type')}}</label>
                <select wire:model.defer="state.property_type" class="form-control property_type select2" data-property="property_type" multiple data-placeholder="Select {{ __('cruds.buyer.fields.property_type')}}" >
                    @foreach($propertyTypes as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
                @error('property_type') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.property_flaw')}}</label>
                <select wire:model.defer="state.property_flaw" class="form-control property_flaw select2" data-property="property_flaw" multiple data-placeholder="Select {{ __('cruds.buyer.fields.property_flaw')}}">
                    @foreach($propertyFlaws as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
                @error('property_flaw') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.solar')}}</label>
                <div class="form-group">
                    <input type="radio" name="solar" wire:model.defer="state.solar" id="yes_solar" value="yes"> <label for="yes_solar"> {{ __('global.yes') }}</label>
                    <input type="radio" name="solar" wire:model.defer="state.solar" id="no_solar" value="no"> <label for="no_solar"> {{ __('global.no') }}</label>
                </div>
                @error('solar') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.pool')}}</label>
                <div class="form-group">
                    <input type="radio" name="pool" wire:model.defer="state.pool" id="yes_pool" value="yes"> <label for="yes_pool"> {{ __('global.yes') }}</label>
                    <input type="radio" name="pool" wire:model.defer="state.pool" id="no_pool" value="no"> <label for="no_pool"> {{ __('global.no') }}</label>
                </div>
                @error('pool') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.septic')}}</label>
                <div class="form-group">
                    <input type="radio" name="septic" wire:model.defer="state.septic" id="yes_septic" value="yes"> <label for="yes_septic"> {{ __('global.yes') }}</label>
                    <input type="radio" name="septic" wire:model.defer="state.septic" id="no_septic" value="no"> <label for="no_septic"> {{ __('global.no') }}</label>
                </div>
                @error('septic') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.well')}}</label>
                <div class="form-group">
                    <input type="radio" name="well" wire:model.defer="state.well" id="yes_well" value="yes"> <label for="yes_well"> {{ __('global.yes') }}</label>
                    <input type="radio" name="well" wire:model.defer="state.well" id="no_well" value="no"> <label for="no_well"> {{ __('global.no') }}</label>
                </div>
                @error('well') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.age_restriction')}}</label>
                <div class="form-group">
                    <input type="radio" name="age_restriction" wire:model.defer="state.age_restriction" id="yes_age_restriction" value="yes"> <label for="yes_age_restriction"> {{ __('global.yes') }}</label>
                    <input type="radio" name="age_restriction" wire:model.defer="state.age_restriction" id="no_age_restriction" value="no"> <label for="no_age_restriction"> {{ __('global.no') }}</label>
                </div>
                @error('age_restriction') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.rental_restriction')}}</label>
                <div class="form-group">
                    <input type="radio" name="rental_restriction" wire:model.defer="state.rental_restriction" id="yes_rental_restriction" value="yes"> <label for="yes_rental_restriction"> {{ __('global.yes') }}</label>
                    <input type="radio" name="rental_restriction" wire:model.defer="state.rental_restriction" id="no_rental_restriction" value="no"> <label for="no_rental_restriction"> {{ __('global.no') }}</label>
                </div>
                @error('rental_restriction') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.hoa')}}</label>
                <div class="form-group">
                    <input type="radio" name="hoa" wire:model.defer="state.hoa" id="yes_hoa" value="yes"> <label for="yes_hoa"> {{ __('global.yes') }}</label>
                    <input type="radio" name="hoa" wire:model.defer="state.hoa" id="no_hoa" value="no"> <label for="no_hoa"> {{ __('global.no') }}</label>
                </div>
                @error('hoa') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.tenant')}}</label>
                <div class="form-group">
                    <input type="radio" name="tenant" wire:model.defer="state.tenant" id="yes_tenant" value="yes"> <label for="yes_tenant"> {{ __('global.yes') }}</label>
                    <input type="radio" name="tenant" wire:model.defer="state.tenant" id="no_tenant" value="no"> <label for="no_tenant"> {{ __('global.no') }}</label>
                </div>
                @error('tenant') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.post_possession')}}</label>
                <div class="form-group">
                    <input type="radio" name="post_possession" wire:model.defer="state.post_possession" id="yes_post_possession" value="yes"> <label for="yes_post_possession"> {{ __('global.yes') }}</label>
                    <input type="radio" name="post_possession" wire:model.defer="state.post_possession" id="no_post_possession" value="no"> <label for="no_post_possession"> {{ __('global.no') }}</label>
                </div>
                @error('post_possession') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.building_required')}}</label>
                <div class="form-group">
                    <input type="radio" name="building_required" wire:model.defer="state.building_required" id="yes_building_required" value="yes"> <label for="yes_building_required"> {{ __('global.yes') }}</label>
                    <input type="radio" name="building_required" wire:model.defer="state.building_required" id="no_building_required" value="no"> <label for="no_building_required"> {{ __('global.no') }}</label>
                </div>
                @error('building_required') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.foundation_issues')}}</label>
                <div class="form-group">
                    <input type="radio" name="foundation_issues" wire:model.defer="state.foundation_issues" id="yes_foundation_issues" value="yes"> <label for="yes_foundation_issues"> {{ __('global.yes') }}</label>
                    <input type="radio" name="foundation_issues" wire:model.defer="state.foundation_issues" id="no_foundation_issues" value="no"> <label for="no_foundation_issues"> {{ __('global.no') }}</label>
                </div>
                @error('foundation_issues') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.mold')}}</label>
                <div class="form-group">
                    <input type="radio" name="mold" wire:model.defer="state.mold" id="yes_mold" value="yes"> <label for="yes_mold"> {{ __('global.yes') }}</label>
                    <input type="radio" name="mold" wire:model.defer="state.mold" id="no_mold" value="no"> <label for="no_mold"> {{ __('global.no') }}</label>
                </div>
                @error('mold') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.fire_damaged')}}</label>
                <div class="form-group">
                    <input type="radio" name="fire_damaged" wire:model.defer="state.fire_damaged" id="yes_fire_damaged" value="yes"> <label for="yes_fire_damaged"> {{ __('global.yes') }}</label>
                    <input type="radio" name="fire_damaged" wire:model.defer="state.fire_damaged" id="no_fire_damaged" value="no"> <label for="no_fire_damaged"> {{ __('global.no') }}</label>
                </div>
                @error('fire_damaged') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.rebuild')}}</label>
                <div class="form-group">
                    <input type="radio" name="rebuild" wire:model.defer="state.rebuild" id="yes_rebuild" value="yes"> <label for="yes_rebuild"> {{ __('global.yes') }}</label>
                    <input type="radio" name="rebuild" wire:model.defer="state.rebuild" id="no_rebuild" value="no"> <label for="no_rebuild"> {{ __('global.no') }}</label>
                </div>
                @error('rebuild') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.buyer_type')}}</label>
                <select wire:model.defer="state.buyer_type"  class="form-control select2 buyer_type" data-property="buyer_type" data-placeholder="Select {{ __('cruds.buyer.fields.buyer_type')}}"  multiple>
                    @foreach($buyerTypes as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
                @error('state.buyer_type') <span class="error text-danger">{{ $message }}</span>@enderror
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
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_down_payment_percentage')}}</label>
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
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.max_interest_rate')}}</label>
                        <input type="number" class="form-control" wire:model.defer="state.max_interest_rate" placeholder="{{ __('cruds.buyer.fields.max_interest_rate')}}" autocomplete="off" max="100" min="0" >
                        @error('max_interest_rate') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.balloon_payment')}}</label>
                        <div class="form-group">
                            <input type="radio" name="balloon_payment" wire:model.defer="state.balloon_payment" id="yes_balloon_payment" value="yes" > <label for="yes_balloon_payment"> {{ __('global.yes') }}</label>
                            <input type="radio" name="balloon_payment" wire:model.defer="state.balloon_payment" id="no_balloon_payment" value="no" > <label for="no_balloon_payment"> {{ __('global.no') }}</label>
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
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_min')}}</label>
                        <input type="number" class="form-control" wire:model.defer="state.unit_min" placeholder="{{ __('cruds.buyer.fields.unit_min')}}" autocomplete="off"  min="0"> 
                        @error('unit_min') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.unit_max')}}</label>
                        <input type="number" class="form-control" wire:model.defer="state.unit_max" placeholder="{{ __('cruds.buyer.fields.unit_max')}}" autocomplete="off" min="0" >
                        @error('unit_max') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" wire:ignore>
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.building_class')}}</label>
                        <select wire:model.defer="state.building_class" class="form-control building_class select2" data-property="building_class" multiple data-placeholder="Select {{ __('cruds.buyer.fields.building_class')}}" >
                            @foreach($buildingClassValue as $key => $value)
                                <option value="{{ $key }}"> {{ $value }}</option>
                            @endforeach
                        </select>
                        @error('building_class') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('cruds.buyer.fields.value_add')}}</label>
                        <div class="form-group">
                            <input type="radio" name="value_add" wire:model.defer="state.value_add" id="yes_value_add" value="yes" > <label for="yes_value_add"> {{ __('global.yes') }}</label>
                            <input type="radio" name="value_add" wire:model.defer="state.value_add" id="no_value_add" value="no" > <label for="no_value_add"> {{ __('global.no') }}</label>
                        </div>
                        @error('balloon_payment') <span class="error text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer.fields.purchase_method')}}</label>
                <select wire:model.defer="state.purchase_method" class="form-control purchase_method select2" data-property="purchase_method" id="purchase_method" multiple data-placeholder="Select {{ __('cruds.buyer.fields.purchase_method')}}" >
                    @foreach($purchaseMethods as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
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
                        <span class="switch-slider"></span>
                    </label>
                </div>
                @error('status') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary mr-2">
        {{ $updateMode ? __('global.update') : __('global.submit') }}
        <span wire:loading wire:target="store">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
    <button wire:click.prevent="cancel" class="btn btn-secondary">
        {{ __('global.back')}}
        <span wire:loading wire:target="cancel">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>
</form>
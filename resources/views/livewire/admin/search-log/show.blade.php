
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.search_log.title_singular'))}}</h4>

    <table class="table table-borderless">
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.user_id')}}</th>
            <td> {{ $details->seller->name ?? 'N/A' }}</td>
        </tr>        
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.address')}}</th>
            <td> {{ $details->address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.city')}}</th>
            <td> 
                @php
                   $AllCities = [];
                   if($details->city){
                        $AllCities = \DB::table('cities')->whereIn('id', $details->city)->pluck('name')->toArray();
                   }
                    
                @endphp
                {{  count($AllCities) > 0 ? implode(',',$AllCities) : 'N/A'   }}
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.state')}}</th>
            <td> 
                @php
                  $AllStates = [];
                  if($details->state){
                    $AllStates = \DB::table('states')->whereIn('id', $details->state)->pluck('name')->toArray();
                  }
                @endphp
                 {{  count($AllStates) > 0 ? implode(',',$AllStates) : 'N/A'   }}
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.zip_code')}}</th>
            <td> {{ $details->zip_code ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.bedroom')}}</th>
            <td> {{ $details->bedroom ?? 'N/A' }}</td>
        </tr>
        @if(!is_null($details->bath) && !empty($details->bath))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.bath')}}</th>
            <td> {{ $details->bath ?? 'N/A' }}</td>
        </tr>
        @endif
      
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.size')}}</th>
            <td> {{ $details->size ?? 'N/A' }}</td>
        </tr>
        
        @if(!is_null($details->lot_size) && !empty($details->lot_size))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.lot_size')}}</th>
            <td> {{ $details->lot_size ?? 'N/A' }}</td>
        </tr>
        @endif
       
        @if(!is_null($details->build_year) && !empty($details->build_year))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.build_year')}}</th>
            <td> {{ $details->build_year ?? 'N/A' }}</td>
        </tr>
        @endif
      
        @if(!is_null($details->arv) && !empty($details->arv))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.arv')}}</th>
                <td> {{ $details->arv ?? 'N/A' }}</td>
            </tr>
        @endif
       
        @if(!is_null($details->parking) && !empty($details->parking))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.parking')}}</th>
                <td> {{ $parkingValues[$details->parking] ?? 'N/A' }} </td>
            </tr>
        @endif
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.property_type')}}</th>
            <td> {{ $propertyTypes[$details->property_type] }} </td>
        </tr>
        @if(!is_null($details->property_flaw) && !empty($details->property_flaw))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.property_flaw')}}</th>
                <td> 
                    @foreach($details->property_flaw as $propertyFlaw)
                        <span class="badge bg-secondary"> {{ $propertyFlaws[$propertyFlaw] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif


        @if(!is_null($details->solar))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.solar')}}</th>
                <td> {{ $details->solar == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->pool))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.pool')}}</th>
                <td> {{ $details->pool == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->septic))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.septic')}}</th>
                <td> {{ $details->septic == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->well))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.well')}}</th>
                <td> {{ $details->well == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->age_restriction))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.age_restriction')}}</th>
                <td> {{ $details->age_restriction == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->rental_restriction))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.rental_restriction')}}</th>
                <td> {{ $details->rental_restriction == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->hoa))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.hoa')}}</th>
                <td> {{ $details->hoa == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->tenant))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.tenant')}}</th>
                <td> {{ $details->tenant == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->post_possession))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.post_possession')}}</th>
                <td> {{ $details->post_possession == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->building_required))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.building_required')}}</th>
                <td> {{ $details->building_required == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->foundation_issues))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.foundation_issues')}}</th>
                <td> {{ $details->foundation_issues == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->mold))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.mold')}}</th>
                <td> {{ $details->mold == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->fire_damaged))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.fire_damaged')}}</th>
                <td> {{ $details->fire_damaged == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->rebuild))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.rebuild')}}</th>
                <td> {{ $details->rebuild == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif

        @if(!is_null($details->purchase_method) && !empty($details->purchase_method))
            <tr>
                <th width="25%">{{ __('cruds.search_log.fields.purchase_method')}}</th>
                <td> 
                    @foreach($details->purchase_method as $purchaseMethod)
                        <span class="badge bg-secondary"> {{ $purchaseMethods[$purchaseMethod] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif
        <tr>
            <th width="25%">{{ __('global.created_at')}}</th>
            <td> {{ $details->created_at->format(config('constants.datetime_format')) }}</td>
        </tr>
    </table>
    <div class="text-right">
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>
               
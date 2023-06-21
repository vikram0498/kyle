
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.buyer.title_singular'))}}</h4>

    <table class="table table-borderless">
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.name')}}</th>
            <td>{{ $details->first_name.' '. $details->last_name  }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.email')}}</th>
            <td>{{ $details->email }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.phone')}}</th>
            <td> {{ $details->phone }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.address')}}</th>
            <td> {{ $details->address }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.city')}}</th>
            <td> {{ $details->city }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.state')}}</th>
            <td> {{ $details->state }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.zip_code')}}</th>
            <td> {{ $details->zip_code }}</td>
        </tr>
        @if(!is_null($details->company_name) && !empty($details->company_name))
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.company_name')}}</th>
            <td> {{ $details->company_name }}</td>
        </tr>
        @endif
        @if(!is_null($details->occupation) && !empty($details->occupation))
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.occupation')}}</th>
            <td> {{ $details->occupation }}</td>
        </tr>
        @endif
        @if(!is_null($details->replacing_occupation) && !empty($details->replacing_occupation))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.replacing_occupation')}}</th>
                <td> {{ $details->replacing_occupation }}</td>
            </tr>
        @endif
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_min')}}</th>
            <td> {{ $details->bedroom_min }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_max')}}</th>
            <td> {{ $details->bedroom_max }}</td>
        </tr>
        @if(!is_null($details->bath_min) && !empty($details->bath_min))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.bath_min')}}</th>
            <td> {{ $details->bath_min }}</td>
        </tr>
        @endif
        @if(!is_null($details->bath_max) && !empty($details->bath_max))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.bath_max')}}</th>
            <td> {{ $details->bath_max }}</td>
        </tr>
        @endif
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.size_min')}}</th>
            <td> {{ $details->size_min }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.size_max')}}</th>
            <td> {{ $details->size_max }}</td>
        </tr>
        @if(!is_null($details->lot_size_min) && !empty($details->lot_size_min))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.lot_size_min')}}</th>
            <td> {{ $details->lot_size_min }}</td>
        </tr>
        @endif
        @if(!is_null($details->lot_size_max) && !empty($details->lot_size_max))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.lot_size_max')}}</th>
            <td> {{ $details->lot_size_max }}</td>
        </tr>
        @endif
        @if(!is_null($details->build_year_min) && !empty($details->build_year_min))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.build_year_min')}}</th>
            <td> {{ $details->build_year_min }}</td>
        </tr>
        @endif
        @if(!is_null($details->build_year_max) && !empty($details->build_year_max))
        <tr>
            <th width="25%">{{ __('cruds.search_log.fields.build_year_max')}}</th>
            <td> {{ $details->build_year_max }}</td>
        </tr>
        @endif
        @if(!is_null($details->arv_min) && !empty($details->arv_min))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.arv_min')}}</th>
                <td> {{ $details->arv_min }}</td>
            </tr>
        @endif
        @if(!is_null($details->arv_max) && !empty($details->arv_max))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.arv_max')}}</th>
                <td> {{ $details->arv_max }}</td>
            </tr>
        @endif
        @if(!is_null($details->parking) && !empty($details->parking))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.parking')}}</th>
                <td> 
                    @foreach($details->parking as $parking)
                        <span class="badge bg-secondary"> {{ $parkingValues[$parking] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.property_type')}}</th>
            <td> 
                @foreach($details->property_type as $propertyType)
                    <span class="badge bg-secondary"> {{ $propertyTypes[$propertyType] }} </span>
                @endforeach
            </td>
        </tr>
        @if(!is_null($details->property_flaw) && !empty($details->property_flaw))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.property_flaw')}}</th>
                <td> 
                    @foreach($details->property_flaw as $propertyFlaw)
                        <span class="badge bg-secondary"> {{ $propertyFlaws[$propertyFlaw] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif


        @if(!is_null($details->solar))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.solar')}}</th>
                <td> {{ $details->solar == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->pool))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.pool')}}</th>
                <td> {{ $details->pool == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->septic))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.septic')}}</th>
                <td> {{ $details->septic == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->well))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.well')}}</th>
                <td> {{ $details->well == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->age_restriction))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.age_restriction')}}</th>
                <td> {{ $details->age_restriction == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->rental_restriction))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.rental_restriction')}}</th>
                <td> {{ $details->rental_restriction == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->hoa))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.hoa')}}</th>
                <td> {{ $details->hoa == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->tenant))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.tenant')}}</th>
                <td> {{ $details->tenant == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->post_possession))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.post_possession')}}</th>
                <td> {{ $details->post_possession == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->building_required))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.building_required')}}</th>
                <td> {{ $details->building_required == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->foundation_issues))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.foundation_issues')}}</th>
                <td> {{ $details->foundation_issues == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->mold))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.mold')}}</th>
                <td> {{ $details->mold == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->fire_damaged))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.fire_damaged')}}</th>
                <td> {{ $details->fire_damaged == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif
        @if(!is_null($details->rebuild))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.rebuild')}}</th>
                <td> {{ $details->rebuild == 1 ? __('global.yes') : __('global.no') }}</td>
            </tr>
        @endif

        @if(!is_null($details->buyer_type) && !empty($details->buyer_type))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.buyer_type')}}</th>
                <td> 
                    @foreach($details->buyer_type as $buyerType)
                        <span class="badge bg-secondary"> {{ $buyerTypes[$buyerType] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif
        <!-- creative Buyer -->
        @if(in_array(1, $details->buyer_type))
            <!-- <tr><td></td></tr> -->
            <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder"> {{ __('cruds.buyer.creative_buyer') }}</h4> </th>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_percentage')}}</th>
                <td> {{ ($details->max_down_payment_percentage ) }}</td>
            </tr>
            @if(!is_null($details->max_down_payment_money) && !empty($details->max_down_payment_money))
                <tr>
                    <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_money')}}</th>
                    <td> {{ ($details->max_down_payment_money ) }}</td>
                </tr>
            @endif
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_interest_rate')}}</th>
                <td> {{ ($details->max_interest_rate) }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.balloon_payment')}}</th>
                <td> {{ ($details->balloon_payment == 1 ? __('global.yes') : __('global.no')) }}</td>
            </tr>
        @endif

         <!-- Multi family Buyer -->
         @if(in_array(3, $details->buyer_type))
            <!-- <tr><td></td></tr> -->
            <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder">{{ __('cruds.buyer.multi_family_buyer') }}</h4> </th>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_min')}}</th>
                <td> {{ ($details->unit_min ) }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_max')}}</th>
                <td> {{ ($details->unit_max ) }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.building_class')}}</th>
                <td> 
                    @foreach($details->building_class as $buildingClass)
                        <span class="badge bg-secondary"> {{ $buildingClassValue[$buildingClass] }} </span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.value_add')}}</th>
                <td> {{ ($details->value_add == 1 ? __('global.yes') : __('global.no')) }}</td>
            </tr>
            <!-- <tr><td></td></tr> -->
        @endif

        @if(!is_null($details->purchase_method) && !empty($details->purchase_method))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.purchase_method')}}</th>
                <td> 
                    @foreach($details->purchase_method as $purchaseMethod)
                        <span class="badge bg-secondary"> {{ $purchaseMethods[$purchaseMethod] }} </span>
                    @endforeach
                </td>
            </tr>
        @endif
        


        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.status')}}</th>
            <td> {{ ($details->status ? 'Active' : 'Inactive') }}</td>
        </tr>
    </table>
    <button wire:click.prevent="cancel" class="btn btn-secondary">
        {{ __('global.back')}}
        <span wire:loading wire:target="cancel">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>

               
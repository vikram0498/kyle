
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.buyer.title_singular'))}}

    @php 
        $buyerFlagCount = $details->redFlagedData()->where('status', 0)->count();
    @endphp

    @if($buyerFlagCount)
        @include('livewire.datatables.red_flag_btn', ['id' => $details->id, 'flag_count' => $buyerFlagCount, 'title' => 'Red Flag', 'type' => 'icon-counter'])
    @endif
</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.name')}}</th>
            <td>{{ $details->first_name.' '. $details->last_name  }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.email')}}</th>
            <td>{{ $details->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.phone')}}</th>
            <td> {{ $details->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.address')}}</th>
            <td> {{ $details->address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.country')}}</th>
            <td> {{ $details->country ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.state')}}</th>
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
            <th width="25%">{{ __('cruds.buyer.fields.city')}}</th>
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
            <th width="25%">{{ __('cruds.buyer.fields.zip_code')}}</th>
            <td> {{ $details->zip_code ?? 'N/A' }}</td>
        </tr>

        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.market_preferance')}}</th>
            <td> {{ (!is_null($details->market_preferance) && !empty($details->market_preferance)) ? $market_preferances[$details->market_preferance] : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.contact_preferance')}}</th>
            <td> {{ (!is_null($details->contact_preferance) && !empty($details->contact_preferance)) ? $contact_preferances[$details->contact_preferance] : 'N/A' }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.company_name')}}</th>
            <td> {{ (!is_null($details->company_name) && !empty($details->company_name)) ? $details->company_name : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.occupation')}}</th>
            <td> {{ (!is_null($details->occupation) && !empty($details->occupation)) ? $details->occupation : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.replacing_occupation')}}</th>
            <td> {{ (!is_null($details->replacing_occupation) && !empty($details->replacing_occupation)) ? $details->replacing_occupation : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_min')}}</th>
            <td> {{ $details->bedroom_min ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_max')}}</th>
            <td> {{ $details->bedroom_max ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bath_min')}}</th>
            <td> {{ (!is_null($details->bath_min) && !empty($details->bath_min)) ? $details->bath_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bath_max')}}</th>
            <td> {{ (!is_null($details->bath_max) && !empty($details->bath_max)) ? $details->bath_max : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.size_min')}}</th>
            <td> {{ $details->size_min }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.size_max')}}</th>
            <td> {{ $details->size_max }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.lot_size_min')}}</th>
            <td> {{ (!is_null($details->lot_size_min) && !empty($details->lot_size_min)) ? $details->lot_size_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.lot_size_max')}}</th>
            <td> {{ (!is_null($details->lot_size_max) && !empty($details->lot_size_max)) ? $details->lot_size_max : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.build_year_min')}}</th>
            <td> {{ (!is_null($details->build_year_min) && !empty($details->build_year_min)) ? $details->build_year_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.build_year_max')}}</th>
            <td> {{ (!is_null($details->build_year_max) && !empty($details->build_year_max)) ? $details->build_year_max : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.arv_min')}}</th>
            <td> {{ (!is_null($details->arv_min) && !empty($details->arv_min)) ? $details->arv_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.arv_max')}}</th>
            <td> {{ (!is_null($details->arv_max) && !empty($details->arv_max)) ? $details->arv_max : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.stories_min')}}</th>
            <td> {{ (!is_null($details->stories_min) && !empty($details->stories_min)) ? $details->stories_min : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.stories_max')}}</th>
            <td> {{ (!is_null($details->stories_max) && !empty($details->stories_max)) ? $details->stories_max : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.price_min')}}</th>
            <td> {{ (!is_null($details->price_min) && !empty($details->price_min)) ? number_format($details->price_min,2) : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.price_max')}}</th>
            <td> {{ (!is_null($details->price_max) && !empty($details->price_max)) ? number_format($details->price_max,2) : 'N/A' }}</td>
        </tr>
        
       
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.parking')}}</th>
            <td> {{ (!is_null($details->parking) && !empty($details->parking)) ? $parkingValues[$details->parking] : 'N/A' }}</td>

            {{-- <td> 
                @if(!is_null($details->parking) && !empty($details->parking))
                    @foreach($details->parking as $parking)
                        <span class="badge bg-primary text-white"> {{ $parkingValues[$parking] }} </span>
                    @endforeach
                @else
                    N/A
                @endif
            </td> --}}
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.property_type')}}</th>
            
            <td> 
                @if(!is_null($details->property_type) && !empty($details->property_type))
                
                    @foreach($details->property_type as $propertyType)
                        <span class="badge bg-primary text-white"> {{ isset($propertyTypes[$propertyType]) ? $details->property_type : '' }} </span>
                    @endforeach
                @else
                N/A
                @endif
            </td>
        </tr>

       
        @if(isset($details->property_type) && in_array(7,$details->property_type))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.zoning')}}</th>
                <td> 
                    @if(!is_null($details->zoning) && !empty($details->zoning))
                        @foreach(json_decode($details->zoning,true) as $zoningVal)
                            <span class="badge bg-primary text-white"> {{ $zonings[$zoningVal] }} </span>
                        @endforeach
                    @else
                    N/A
                    @endif
                </td>
            </tr>

            
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.utilities')}}</th>
                <td> {{ (!is_null($details->utilities) && !empty($details->utilities)) ? $utilities[$details->utilities] : 'N/A' }}</td>
            </tr>

            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.sewer')}}</th>
                <td> {{ (!is_null($details->sewer) && !empty($details->sewer)) ? $sewers[$details->sewer] : 'N/A' }}</td>
            </tr>

        @endif

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.property_flaw')}}</th>
            <td> 
                @if(!is_null($details->property_flaw) && !empty($details->property_flaw))
                    @foreach($details->property_flaw as $propertyFlaw)
                        <span class="badge bg-primary text-white"> {{ $propertyFlaws[$propertyFlaw] }} </span>
                    @endforeach
                @else 
                    N/A
                @endif
            </td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.solar')}}</th>
            <td> {{ (!is_null($details->solar) ? ($details->solar == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.pool')}}</th>
            <td> {{ (!is_null($details->pool) ? ($details->pool == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.septic')}}</th>
            <td> {{ (!is_null($details->septic) ? ($details->septic == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.well')}}</th>
            <td> {{ (!is_null($details->well) ? ($details->well == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.age_restriction')}}</th>
            <td> {{ (!is_null($details->age_restriction) ? ($details->age_restriction == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.rental_restriction')}}</th>
            <td> {{ (!is_null($details->rental_restriction) ? ($details->rental_restriction == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.hoa')}}</th>
            <td> {{ (!is_null($details->hoa) ? ($details->hoa == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.tenant')}}</th>
            <td> {{ (!is_null($details->tenant) ? ($details->tenant == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.post_possession')}}</th>
            <td> {{ (!is_null($details->post_possession) ? ($details->post_possession == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.building_required')}}</th>
            <td> {{ (!is_null($details->building_required) ? ($details->building_required == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.foundation_issues')}}</th>
            <td> {{ (!is_null($details->foundation_issues) ? ($details->foundation_issues == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.mold')}}</th>
            <td> {{ (!is_null($details->mold) ? ($details->mold == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.fire_damaged')}}</th>
            <td> {{ (!is_null($details->fire_damaged) ? ($details->fire_damaged == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.rebuild')}}</th>
            <td> {{ (!is_null($details->rebuild) ? ($details->rebuild == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.squatters')}}</th>
            <td> {{ (!is_null($details->squatters) ? ($details->squatters == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.buyer_type')}}</th>
            <td> {{ (!is_null($details->buyer_type) && !empty($details->buyer_type)) ? $buyerTypes[$details->buyer_type] : 'N/A' }}</td>

            {{-- <td> 
            @if(!is_null($details->buyer_type) && !empty($details->buyer_type))
                @foreach($details->buyer_type as $buyerType)
                    <span class="badge bg-primary text-white"> {{ $buyerTypes[$buyerType] }} </span>
                @endforeach
            @else
                N/A
            @endif
            </td> --}}
        </tr>
        <!-- creative Buyer -->
            <!-- <tr><td></td></tr> -->
            <!-- <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder"> {{ __('cruds.buyer.creative_buyer') }}</h4> </th>
            </tr> -->
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_percentage')}}</th>
                <td> {{ (!is_null($details->max_down_payment_percentage) && !empty($details->max_down_payment_percentage)) ? $details->max_down_payment_percentage : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_money')}}</th>
                <td> {{ (!is_null($details->max_down_payment_money) && !empty($details->max_down_payment_money)) ? $details->max_down_payment_money : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_interest_rate')}}</th>
                <td> {{ (!is_null($details->max_interest_rate) && !empty($details->max_interest_rate)) ? $details->max_interest_rate : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.balloon_payment')}}</th>
                <td> {{ (!is_null($details->balloon_payment) ? ($details->balloon_payment == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
            </tr>

         <!-- Multi family Buyer -->
            <!-- <tr><td></td></tr> -->
            <!-- <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder">{{ __('cruds.buyer.multi_family_buyer') }}</h4> </th>
            </tr> -->
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_min')}}</th>
                <td> {{ (!is_null($details->unit_min) && !empty($details->unit_min)) ? $details->unit_min : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_max')}}</th>
                <td> {{ (!is_null($details->unit_max) && !empty($details->unit_max)) ? $details->unit_max : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.building_class')}}</th>
                <td> 
                    @if(!is_null($details->building_class) && !empty($details->building_class))
                        @foreach($details->building_class as $buildingClass)
                            <span class="badge bg-primary text-white"> {{ $buildingClassValue[$buildingClass] }} </span>
                        @endforeach

                    @else 
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.value_add')}}</th>
                <td> {{ (!is_null($details->value_add) ? ($details->value_add == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
            </tr>
            <!-- <tr><td></td></tr> -->

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.purchase_method')}}</th>
            <td> 
                @if(!is_null($details->purchase_method) && !empty($details->purchase_method))
                    @foreach($details->purchase_method as $purchaseMethod)
                        <span class="badge bg-primary text-white"> {{ $purchaseMethods[$purchaseMethod] }} </span>
                    @endforeach
                @else 
                    N/A
                @endif
            </td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.status')}}</th>
            <td> {{ ($details->status ? 'Active' : 'Block') }}</td>
        </tr>
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

               
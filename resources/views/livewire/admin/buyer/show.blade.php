<div>

    @php 
        $buyerFlagCount = $details->redFlagedData()->where('status', 0)->count();
    @endphp

    @if($buyerFlagCount)
        @include('livewire.datatables.red_flag_btn', ['id' => $details->id, 'flag_count' => $buyerFlagCount, 'title' => 'Red Flag', 'type' => 'icon-counter'])
    @endif
    
    <div class="flex-container">
        <h4 class="card-title">
            {{__('global.show')}}
            {{ strtolower(__('cruds.buyer.title_singular'))}}
        </h4>
    
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>

    

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.name')}}</th>
            <td class="remove-white-space">{{ $details->userDetail->name  }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.email')}}</th>
            <td class="remove-white-space">{{ $details->userDetail->email }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.phone')}}</th>
            <td class="remove-white-space"> {{ $details->userDetail->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">Description</th>
            <td class="remove-white-space"> {{ $details->userDetail->description ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.country')}}</th>
            <td class="remove-white-space"> {{ $details->country ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.state')}}</th>
            <td class="remove-white-space">
                @php
                
                  $AllStates = [];
                  if($details->state){
                    $AllStates = \DB::table('states')->whereIn('id', $details->state)->pluck('name')->toArray();
                  }
                @endphp
                 {{  count($AllStates) > 0 ? implode(', ',$AllStates) : 'N/A'   }}
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.city')}}</th>
            <td class="remove-white-space">
                @php
                  $AllCities = [];
                  if($details->city){
                    $AllCities = \DB::table('cities')->whereIn('id', $details->city)->pluck('name')->toArray();
                  }
                @endphp
                {{  count($AllCities) > 0 ? implode(', ',$AllCities) : 'N/A'   }}
            </td>
        </tr>        
        {{-- <tr>
            <th width="25%">{{ __('cruds.buyer.fields.zip_code')}}</th>
            <td class="remove-white-space"> {{ $details->zip_code ?? 'N/A' }}</td>
        </tr> --}}

        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.market_preferance')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->market_preferance) && !empty($details->market_preferance)) ? $market_preferances[$details->market_preferance] : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.contact_preferance')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->contact_preferance) && !empty($details->contact_preferance)) ? $contact_preferances[$details->contact_preferance] : 'N/A' }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.company_name')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->company_name) && !empty($details->company_name)) ? $details->company_name : 'N/A' }}</td>
        </tr>
       {{-- <tr>
            <th width="25%">{{ __('cruds.buyer.fields.occupation')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->occupation) && !empty($details->occupation)) ? $details->occupation : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.replacing_occupation')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->replacing_occupation) && !empty($details->replacing_occupation)) ? $details->replacing_occupation : 'N/A' }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_min')}}</th>
            <td class="remove-white-space"> {{ $details->bedroom_min ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bedroom_max')}}</th>
            <td class="remove-white-space"> {{ $details->bedroom_max ?? 'N/A' }}</td>
        </tr>
	--}}
	<tr>
            <th width="25%">{{ __('cruds.buyer.fields.rooms')}}</th>
            <td class="remove-white-space"> {{ $details->rooms ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bath_min')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->bath_min) && !empty($details->bath_min)) ? $details->bath_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.bath_max')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->bath_max) && !empty($details->bath_max)) ? $details->bath_max : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.size_min')}}</th>
            <td class="remove-white-space"> {{ $details->size_min }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.size_max')}}</th>
            <td class="remove-white-space"> {{ $details->size_max }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.lot_size_min')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->lot_size_min) && !empty($details->lot_size_min)) ? $details->lot_size_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.lot_size_max')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->lot_size_max) && !empty($details->lot_size_max)) ? $details->lot_size_max : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.build_year_min')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->build_year_min) && !empty($details->build_year_min)) ? $details->build_year_min : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.build_year_max')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->build_year_max) && !empty($details->build_year_max)) ? $details->build_year_max : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.stories_min')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->stories_min) && !empty($details->stories_min)) ? $details->stories_min : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.stories_max')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->stories_max) && !empty($details->stories_max)) ? $details->stories_max : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.price_min')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->price_min) && !empty($details->price_min)) ? number_format($details->price_min,2) : 'N/A' }}</td>
        </tr>

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.price_max')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->price_max) && !empty($details->price_max)) ? number_format($details->price_max,2) : 'N/A' }}</td>
        </tr>
        
       
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.parking')}}</th>
            {{-- <td class="remove-white-space"> {{ (!is_null($details->parking) && !empty($details->parking)) ? $parkingValues[$details->parking] : 'N/A' }}</td> --}}

            <td class="remove-white-space"> 
                @if(is_array($details->parking))
                    @if(!is_null($details->parking) && !empty($details->parking))
                        @foreach($details->parking as $parking)
                            <span class="badge bg-primary text-white"> {{ $parkingValues[$parking] }} </span>
                        @endforeach
                    @else
                        N/A
                    @endif
                @else
                    <span class="badge bg-primary text-white">{{ (!is_null($details->parking) && !empty($details->parking)) ? $parkingValues[$details->parking] : 'N/A' }}</span>
                @endif

            </td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.property_type')}}</th>
            
            <td class="remove-white-space"> 
                @if(!is_null($details->property_type) && !empty($details->property_type))
                
                    @foreach($details->property_type as $propertyType)
                        <span class="badge bg-primary text-white"> {{ isset($propertyTypes[$propertyType]) ? $propertyTypes[$propertyType] : '' }} </span>
                    @endforeach
                @else
                N/A
                @endif
            </td>
        </tr>

       
        @if(isset($details->property_type) && in_array(7,$details->property_type))
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.zoning')}}</th>
                <td class="remove-white-space"> 
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
                <td class="remove-white-space"> {{ (!is_null($details->utilities) && !empty($details->utilities)) ? $utilities[$details->utilities] : 'N/A' }}</td>
            </tr>

            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.sewer')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->sewer) && !empty($details->sewer)) ? $sewers[$details->sewer] : 'N/A' }}</td>
            </tr>

        @endif

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.property_flaw')}}</th>
            <td class="remove-white-space"> 
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
            <td class="remove-white-space"> {{ (!is_null($details->solar) ? ($details->solar == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.pool')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->pool) ? ($details->pool == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.septic')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->septic) ? ($details->septic == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.well')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->well) ? ($details->well == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.age_restriction')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->age_restriction) ? ($details->age_restriction == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.rental_restriction')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->rental_restriction) ? ($details->rental_restriction == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.hoa')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->hoa) ? ($details->hoa == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.tenant')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->tenant) ? ($details->tenant == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.post_possession')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->post_possession) ? ($details->post_possession == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.building_required')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->building_required) ? ($details->building_required == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.foundation_issues')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->foundation_issues) ? ($details->foundation_issues == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.mold')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->mold) ? ($details->mold == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.fire_damaged')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->fire_damaged) ? ($details->fire_damaged == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.rebuild')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->rebuild) ? ($details->rebuild == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.squatters')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->squatters) ? ($details->squatters == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.buyer_type')}}</th>
            <td class="remove-white-space"> {{ (!is_null($details->buyer_type) && !empty($details->buyer_type)) ? $buyerTypes[$details->buyer_type] : 'N/A' }}</td>

            {{-- <td class="remove-white-space"> 
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
            <!-- <tr><td class="remove-white-space"></td></tr> -->
            <!-- <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder"> {{ __('cruds.buyer.creative_buyer') }}</h4> </th>
            </tr> -->
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_percentage')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->max_down_payment_percentage) && !empty($details->max_down_payment_percentage)) ? $details->max_down_payment_percentage : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_down_payment_money')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->max_down_payment_money) && !empty($details->max_down_payment_money)) ? $details->max_down_payment_money : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.max_interest_rate')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->max_interest_rate) && !empty($details->max_interest_rate)) ? $details->max_interest_rate : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.balloon_payment')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->balloon_payment) ? ($details->balloon_payment == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
            </tr>

         <!-- Multi family Buyer -->
            <!-- <tr><td class="remove-white-space"></td></tr> -->
            <!-- <tr>
                <th colspan="2" class="text-left"> <h4 class="font-weight-bolder">{{ __('cruds.buyer.multi_family_buyer') }}</h4> </th>
            </tr> -->
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_min')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->unit_min) && !empty($details->unit_min)) ? $details->unit_min : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.unit_max')}}</th>
                <td class="remove-white-space"> {{ (!is_null($details->unit_max) && !empty($details->unit_max)) ? $details->unit_max : 'N/A' }}</td>
            </tr>
            <tr>
                <th width="25%">{{ __('cruds.buyer.fields.building_class')}}</th>
                <td class="remove-white-space"> 
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
                <td class="remove-white-space"> {{ (!is_null($details->value_add) ? ($details->value_add == 1 ? __('global.yes') : __('global.no')) : 'N/A' ) }}</td>
            </tr>
            <!-- <tr><td class="remove-white-space"></td></tr> -->

        <tr>
            <th width="25%">{{ __('cruds.buyer.fields.purchase_method')}}</th>
            <td class="remove-white-space"> 
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
            <td class="remove-white-space"> {{ ($details->status ? 'Active' : 'Inactive') }}</td>
        </tr>

        <tr>
            <th width="25%">Created By</th>
            <td class="remove-white-space"> {{ $details->createdByUser ? ucwords($details->createdByUser->name) : null }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ __('global.created_at')}}</th>
            <td class="remove-white-space"> {{ $details->created_at->format(config('constants.datetime_format')) }}</td>
        </tr>
    </table>
    <!-- profile Verification -->
    <hr>

    <div class="row profileCard-box">
        <div class="col-12 d-flex headBox-card">
            <h4 class="card-title">{{ __('cruds.buyer.profile_verification.title') }}</h4>
            @php 
                $phone_verify_uploaded = $details->userDetail->buyerVerification->is_phone_verification;
                $dl_uploaded = $details->userDetail->buyerVerification->is_driver_license;
                $pof_uploaded = $details->userDetail->buyerVerification->is_proof_of_funds;
                $llc_uploaded = $details->userDetail->buyerVerification->is_llc_verification;
                $payment_uploaded = $details->userDetail->buyerVerification->is_application_process;
            @endphp
        </div>
    </div>

    <table class="table table-design mb-4 buyer_profile_verification">
        <tr>
            <th width="25%">{{ __('cruds.buyer.profile_verification.phone_verification') }}</th>
            <td colspan="2">{{ $phone_verify_uploaded == 1 ? 'Yes' : 'No'  }}</td>
        </tr>
        <tr>
            <th rowspan="{{ $dl_uploaded == 1 ?2 : 1 }}" width="25%">{{ __('cruds.buyer.profile_verification.driver_license') }}</th>
            <td colspan="2"> 
                @if($dl_uploaded == 1)
                    {{ ucwords($details->userDetail->buyerVerification->driver_license_status) }}
                @else 
                    No
                @endif
            </td>
        </tr>
        @if($dl_uploaded == 1)
            <tr>
                @php 
                    $frontImageExist = $details->userDetail->uploads()->where('type', 'driver-license-front')->first();
                    $frontImage = '';
                    if(!is_null($frontImageExist) && !empty($frontImageExist) && $frontImageExist){
                        $frontImage = $frontImageExist->file_path;
                    }
                    $backImageExist = $details->userDetail->uploads()->where('type', 'driver-license-back')->first();
                    $backImage = '';
                    if(!is_null($backImageExist) && !empty($backImageExist) && $backImageExist){
                        $backImage = $backImageExist->file_path;
                    }
                @endphp
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.front_id_photo') }}</h5>
                    <div class="text-center">
                        
                        <a href="javascript:void(0)" class="modal_image_btn" data-src="{{ asset('storage/'.$frontImage) }}">
                            <img src="{{ asset('storage/'.$frontImage) }}"  alt="" width="150px" height="100px">
                        </a>
                    </div>
                </td>
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.back_id_photo') }}</h5>
                    <div class="text-center">
                        <a href="javascript:void(0)" class="modal_image_btn" data-src="{{ asset('storage/'.$backImage) }}">
                            <img src="{{ asset('storage/'.$backImage) }}" alt="" width="150px" height="100px">
                        </a>
                    </div>
                </td>
            </tr>
        @endif
        <tr>
            <th rowspan="{{ $pof_uploaded == 1 ?2 : 1 }}" width="25%">{{ __('cruds.buyer.profile_verification.proof_of_funds') }}</th>
            <td colspan="2">
                @if($pof_uploaded == 1)
                    {{ ucwords($details->userDetail->buyerVerification->proof_of_funds_status) }}
                @else 
                    No
                @endif
            </td>
        </tr>
        @if($pof_uploaded == 1)
            <tr>
                @php 
                    $bankStatementExist = $details->userDetail->uploads()->where('type', 'bank-statement-pdf')->first();
                    $bankStatementPdf = '';
                    if(!is_null($bankStatementExist) && !empty($bankStatementExist) && $bankStatementExist){
                        $bankStatementPdf = $bankStatementExist->file_path;
                    }
                @endphp
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.bank_statement') }}</h5>
                    <div class="text-center">
                        
                        <a href="{{ asset('storage/'.$bankStatementPdf) }}" target="_blank" class="btn btn-primary btn-rounded btn-icon viewpdf-btn" data-src="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 9l1 0" />
                                <path d="M9 13l6 0" />
                                <path d="M9 17l6 0" />
                            </svg>
                            View Pdf 
                        </a>
                    </div>
                </td>
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.other_proof_fund') }}</h5>
                    <div >
                        {{$details->userDetail->buyerVerification->other_proof_of_fund ?? 'N/A'}}
                    </div>
                </td>
            </tr>
        @endif
        <tr>
            <th rowspan="{{ $llc_uploaded == 1 ?2 : 1 }}" width="25%">{{ __('cruds.buyer.profile_verification.llc_verification') }}</th>
            <td colspan="2"> 
                @if($llc_uploaded == 1)
                    
                    {{ ucwords($details->userDetail->buyerVerification->llc_verification_status) }}
                    
                @else 
                    No
                @endif
            </td>
        </tr>
        @if($llc_uploaded == 1)
            <tr>
                @php 
                    $llcFrontImageExist = $details->userDetail->uploads()->where('type', 'llc-front-image')->first();
                    $llcFrontImage = '';
                    if(!is_null($llcFrontImageExist) && !empty($llcFrontImageExist) && $llcFrontImageExist){
                        $llcFrontImage = $llcFrontImageExist->file_path;
                    }
                    $llcBackImageExist = $details->userDetail->uploads()->where('type', 'llc-back-image')->first();
                    $llcBackImage = '';
                    if(!is_null($llcBackImageExist) && !empty($llcBackImageExist) && $llcBackImageExist){
                        $llcBackImage = $llcBackImageExist->file_path;
                    }
                @endphp
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.front_id_photo') }}</h5>
                    <div class="text-center">
                        <a href="javascript:void(0)" class="modal_image_btn" data-src="{{ asset('storage/'.$llcFrontImage) }}">
                            <img src="{{ asset('storage/'.$llcFrontImage) }}"  alt="" width="150px" height="100px">
                        </a>
                    </div>
                </td>
                <td class="remove-white-space">
                    <h5>{{ __('cruds.buyer.profile_verification.back_id_photo') }}</h5>
                    <div class="text-center">
                        <a href="javascript:void(0)" class="modal_image_btn" data-src="{{ asset('storage/'.$llcBackImage) }}">
                            <img src="{{ asset('storage/'.$llcBackImage) }}" alt="" width="150px" height="100px">
                        </a>
                    </div>
                </td>
            </tr>
        @endif
        <tr>
            <th rowspan="2" width="25%">{{ __('cruds.buyer.profile_verification.application_process') }}</th>
            <td colspan="2">{{ $payment_uploaded == 1 ? 'Yes' : 'No'  }}</td>
        </tr>
    </table>

    <div>
        <div class="modal fade kyc-modal" id="image_popup_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="" id="modal_image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
               

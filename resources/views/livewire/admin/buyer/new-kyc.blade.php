<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{ $viewMode ? ucwords($details->name) : 'Buyer Verification List'}}</h4>
                        
                        <div class="card-top-box-item">
                            @if($viewMode)
                            <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
                                {{ __('global.back')}}
                                <span wire:loading wire:target="cancel">
                                    <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                                </span>
                            </button>
                            @endif
                        </div>
                       
                    </div>  

                    @if($viewMode)

                        @php 
                            $phone_verify_uploaded = $details->buyerVerification->is_phone_verification;
                            $dl_uploaded = $details->buyerVerification->is_driver_license;
                            $pof_uploaded = $details->buyerVerification->is_proof_of_funds;
                            $llc_uploaded = $details->buyerVerification->is_llc_verification;
                            $certify_closer_pdf_uploaded = $details->buyerVerification->is_certified_closer;
                            $payment_uploaded = $details->buyerVerification->is_application_process;

                            $activeTab = 'phone_verification';

                            if($dl_uploaded == 1 && $details->buyerVerification->driver_license_status == 'pending'){
                                $activeTab = 'driver_license';
                            }elseif($pof_uploaded == 1 && $details->buyerVerification->proof_of_funds_status == 'pending'){
                                $activeTab = 'proof_of_funds';
                            }elseif($llc_uploaded == 1 && $details->buyerVerification->llc_verification_status == 'pending'){
                                $activeTab = 'llc_verification';
                            }elseif($certify_closer_pdf_uploaded == 1 && $details->buyerVerification->certified_closer_status == 'pending'){
                                $activeTab = 'certified_closer';
                            }elseif($payment_uploaded == 1){
                                $activeTab = 'application_process';
                            }

                        @endphp

                        <div class="row">
                            <div class="col-md-3">
                                <div class="nav nav-pills kyclisting_tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link {{ ($activeTab == 'phone_verification') ? 'active' : ''}}" id="v-pills-phone-verification-tab" data-toggle="pill" href="#v-pills-phone-verification" role="tab" aria-controls="v-pills-phone-verification" aria-selected="true">
                                        {{ __('cruds.buyer.profile_verification.phone_verification') }}
                                    </a>

                                    <a class="nav-link {{ ($activeTab == 'proof_of_funds') ? 'active' : ''}}" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                                        {{ __('cruds.buyer.profile_verification.proof_of_funds') }}
                                    </a>                                   

                                    <a class="nav-link {{ ($activeTab == 'driver_license') ? 'active' : ''}}" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                        {{ __('cruds.buyer.profile_verification.driver_license') }}
                                    </a>

                                    <a class="nav-link {{ ($activeTab == 'llc_verification') ? 'active' : ''}}" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                        {{ __('cruds.buyer.profile_verification.llc_verification') }}
                                    </a>

                                    <a class="nav-link {{ ($activeTab == 'certified_closer') ? 'active' : ''}}" id="v-pills-certified-tab" data-toggle="pill" href="#v-pills-certified" role="tab" aria-controls="v-pills-certified" aria-selected="false">
                                        {{ __('cruds.buyer.profile_verification.certified_closer') }}
                                    </a>

                                    <a class="nav-link {{ ($activeTab == 'application_process') ? 'active' : ''}}" id="v-pills-application-process-tab" data-toggle="pill" href="#v-pills-application-process" role="tab" aria-controls="v-pills-application-process" aria-selected="true">
                                        {{ __('cruds.buyer.profile_verification.application_process') }}
                                    </a>
                                
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content kyclisting_tab" id="v-pills-tabContent">
                                    <div class="tab-pane fade {{ $activeTab == 'phone_verification' ? 'show active' : ''}}" id="v-pills-phone-verification" role="tabpanel" aria-labelledby="v-pills-phone-verification-tab">
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        {{ $phone_verify_uploaded == 1 ? 'Yes' : 'No'  }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="tab-pane fade {{ $activeTab == 'driver_license' ? 'show active' : ''}}" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        @if($dl_uploaded == 1)
                                                            @if($details->buyerVerification->driver_license_status == 'pending')
                                                                <select class="select_profile_verify_status" data-id="{{ $details->buyerVerification->id }}" data-old_value="{{ $details->buyerVerification->driver_license_status }}" data-type="driver_license_status">
                                                                    @foreach(config('constants.buyer_profile_verification_status') as $key => $value)
                                                                        <option value="{{ $key }}" {{ ($details->buyerVerification->driver_license_status == $key) ? 'selected' : '' }} >{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{ ucwords($details->buyerVerification->driver_license_status) }}
                                                            @endif
                                                        @else 
                                                            No
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($dl_uploaded == 1)
                                                <tr>
                                                    @php 
                                                        $frontImageExist = $details->uploads()->where('type', 'driver-license-front')->first();
                                                        $frontImage = '';
                                                        if(!is_null($frontImageExist) && !empty($frontImageExist) && $frontImageExist){
                                                            $frontImage = $frontImageExist->file_path;
                                                        }
                                                        $backImageExist = $details->uploads()->where('type', 'driver-license-back')->first();
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
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="tab-pane fade {{ $activeTab == 'proof_of_funds' ? 'show active' : ''}}" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        @if($pof_uploaded == 1)
                                                            @if($details->buyerVerification->proof_of_funds_status == 'pending')
                                                                <select class="select_profile_verify_status" data-id="{{ $details->buyerVerification->id }}" data-old_value="{{ $details->buyerVerification->proof_of_funds_status }}" data-type="proof_of_funds_status">
                                                                    @foreach(config('constants.buyer_profile_verification_status') as $key => $value)
                                                                        <option value="{{ $key }}" {{ ($details->buyerVerification->proof_of_funds_status == $key) ? 'selected' : '' }} >{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{ ucwords($details->buyerVerification->proof_of_funds_status) }}
                                                            @endif
                                                        @else 
                                                            No
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($pof_uploaded == 1)
                                                <tr>
                                                    @php 
                                                        $bankStatementExist = $details->uploads()->where('type', 'bank-statement-pdf')->first();
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
                                                            {{$details->buyerVerification->other_proof_of_fund ?? 'N/A'}}
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>                                    

                                    <div class="tab-pane fade {{ $activeTab == 'llc_verification' ? 'show active' : ''}}" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        @if($llc_uploaded == 1)
                                                            @if($details->buyerVerification->llc_verification_status == 'pending')
                                                                <select class="select_profile_verify_status" data-id="{{ $details->buyerVerification->id }}" data-old_value="{{ $details->buyerVerification->llc_verification_status }}" data-type="llc_verification_status">
                                                                    @foreach(config('constants.buyer_profile_verification_status') as $key => $value)
                                                                        <option value="{{ $key }}" {{ ($details->buyerVerification->llc_verification_status == $key) ? 'selected' : '' }} >{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{ ucwords($details->buyerVerification->llc_verification_status) }}
                                                            @endif
                                                        @else 
                                                            No
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($llc_uploaded == 1)
                                                    <tr>
                                                        @php 
                                                            $llcFrontImageExist = $details->uploads()->where('type', 'llc-front-image')->first();
                                                            $llcFrontImage = '';
                                                            if(!is_null($llcFrontImageExist) && !empty($llcFrontImageExist) && $llcFrontImageExist){
                                                                $llcFrontImage = $llcFrontImageExist->file_path;
                                                            }
                                                            $llcBackImageExist = $details->uploads()->where('type', 'llc-back-image')->first();
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
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade {{ $activeTab == 'certified_closer' ? 'show active' : ''}}" id="v-pills-certified" role="tabpanel" aria-labelledby="v-pills-certified-tab">
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        @if($certify_closer_pdf_uploaded == 1)
                                                            @if($details->buyerVerification->certified_closer_status == 'pending')
                                                                <select class="select_profile_verify_status" data-id="{{ $details->buyerVerification->id }}" data-old_value="{{ $details->buyerVerification->certified_closer_status }}" data-type="certified_closer_status">
                                                                    @foreach(config('constants.buyer_profile_verification_status') as $key => $value)
                                                                        <option value="{{ $key }}" {{ ($details->buyerVerification->certified_closer_status == $key) ? 'selected' : '' }} >{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{ ucwords($details->buyerVerification->certified_closer_status) }}
                                                            @endif
                                                        @else 
                                                            No
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($certify_closer_pdf_uploaded == 1)
                                                <tr>
                                                    @php 
                                                        $certifiedClosureExist = $details->uploads()->where('type', 'certified-closer-pdf')->first();
                                                        $certifiedClosurePdf = '';
                                                        if(!is_null($certifiedClosureExist) && !empty($certifiedClosureExist) && $certifiedClosureExist){
                                                            $certifiedClosurePdf = $certifiedClosureExist->file_path;
                                                        }
                                                    @endphp
                                                    <td class="remove-white-space">
                                                        <h5>{{ __('cruds.buyer.profile_verification.bank_statement') }}</h5>
                                                        <div class="text-center">
                                                            
                                                            <a href="{{ asset('storage/'.$certifiedClosurePdf) }}" target="_blank" class="btn btn-primary btn-rounded btn-icon viewpdf-btn" data-src="">
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
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade {{ $activeTab == 'application_process' ? 'show active' : ''}}" id="v-pills-application-process" role="tabpanel" aria-labelledby="v-pills-application-process-tab">
                                        
                                        <table class="table table-design mb-4 buyer_profile_verification">
                                            <tbody>
                                                <tr>
                                                    <td colspan="4"> 
                                                        {{ $payment_uploaded == 1 ? 'Yes' : 'No'  }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row profileCard-box">
                            <div class="col-12 d-flex headBox-card">
                                @php 
                                    $phone_verify_uploaded = $details->buyerVerification->is_phone_verification;
                                    $dl_uploaded = $details->buyerVerification->is_driver_license;
                                    $pof_uploaded = $details->buyerVerification->is_proof_of_funds;
                                    $llc_uploaded = $details->buyerVerification->is_llc_verification;
                                    $certify_closer_pdf_uploaded = $details->buyerVerification->is_certified_closer;
                                    $payment_uploaded = $details->buyerVerification->is_application_process;
                                @endphp

                            </div>
                        </div>
                    @else
     
                        <div class="table-responsive search-table-data">
                            <div>
                            <div class="relative">
       
                               <!-- Show entries & Search box -->
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center">                
                                        <div class="items-center justify-between p-2 sm:flex">
                                            <div class="flex items-center my-2 sm:my-0">
                                                <span class="items-center justify-between p-2 sm:flex"> 
                                                    Show 
                                                    <select name="perPage" class="ml-2 mr-2 border block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 form-select leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5" wire:model="perPage">
                                                        @foreach(config('constants.datatable_entries') as $length)
                                                            <option value="{{ $length }}">{{ $length }}</option>
                                                        @endforeach
                                                    </select>
                                                    entries
                                                </span>
                                            </div>
                                            <div class="flex justify-end text-gray-600">
                                                <div class="flex rounded-lg w-96 shadow-sm">
                                                        <div class="relative flex-grow focus-within:z-10">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" stroke="currentColor" fill="none">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                                </svg>
                                                            </div>
                                                            <input wire:model.debounce.500ms="search" class="block w-full py-3 pl-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none" placeholder="Search" type="text">
                                                            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                                <button wire:click="$set('search', null)" class="text-gray-300 hover:text-red-600 focus:outline-none">
                                                                    <svg class="h-5 w-5 stroke-current w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Show entries & Search box -->
                                    
                                <div class="table-responsive mt-3 my-team-details table-record">
                                    <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-gray-500 text-xs font-medium">{{ trans('global.sno') }}</th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer.fields.name')}}
                                                <span wire:click="sortBy('name')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'name' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'name' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            
                                            
                                            <th class="text-gray-500 text-xs">{{ trans('global.created') }}
                                                <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            <th class="text-gray-500 text-xs">{{ trans('global.updated') }}
                                                <span wire:click="sortBy('updated_at')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'updated_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'updated_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            <th class="text-gray-500 text-xs">{{ trans('global.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($kycBuyers->count() > 0)
                                            @foreach($kycBuyers as $serialNo => $buyer)
                                                
                                            <tr>
                                                <td>{{ $serialNo+1 }}</td>
                                                <td>{{ $buyer->name ? ucwords($buyer->name) : '' }}</td>
                                               
                                                <td>{{ convertDateTimeFormat($buyer->created_at,'date') }}</td>
                                                <td>{{ convertDateTimeFormat($buyer->updated_at,'date') }}</td>

                                                <td>
                                                    <button type="button" wire:click="show('{{$buyer->id}}')" class="btn btn-primary btn-rounded btn-icon">
                                                        <i class="ti-eye"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="5">{{ __('messages.no_record_found')}}</td>
                                        </tr>
                                        @endif
                                    
                                    </tbody>
                                    </table>
                                </div>

                                {{ $kycBuyers->links('vendor.pagination.custom-pagination') }}
                            </div>
                            </div>

                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>

        <div class="modal fade kyc-modal" id="image_popup_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" alt="" id="modal_image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade reason-modal" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               
                <form class="forms-sample" wire:submit.prevent="storeReasonForm" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reasonModalLabel">Reason</h5>
                            <button type="button" wire:click.prevent="closeReasonModal" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type" class="font-weight-bold">Type <span class="text-danger">*</span> </label>
                                        <select id="reasonType" wire:model.defer="reason_type" class="form-control">
                                            <option value="">Select Type</option>
                                            @if($reasonTypes)
                                                @foreach($reasonTypes as $key => $value)
                                                    <option value="{{ $key }}" >{{ $value }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('reason_type') <span class="error text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                @if($reason_type == 'other')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type" class="font-weight-bold">Description<span class="text-danger">*</span> </label>
                                        <textarea class="form-control" rows="10" wire:model.defer="reason_content"></textarea>
                                        @error('reason_content') <span class="error text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                @endif

                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click.prevent="closeReasonModal" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-success mr-2 submit-btn">
                                Submit
                                <span wire:loading wire:target="storeReasonForm">
                                    <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
</div>


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"/>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>



<script type="text/javascript">
   
  
    $(document).on('change', '.select_profile_verify_status', function(e){
        var _this = $(this);
        var id = _this.data('id');
        var type = _this.data('type');
        var old_value = _this.data('old_value');
        var value = _this.val();

        var data = {
            id: id,
            type: type,
            status: value,
        }
        
        var msg = 'Are you sure you want to change status to '+value+' ?';
        
        Swal.fire({
            title: msg,
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('confirmedToggleActionView', data);

                _this.data('old_value', value);
                _this.attr('data-old_value', value);
            } else if (result.isDenied) {
                _this.val(old_value);
            }
        })
    });

    $(document).on('click', '.modal_image_btn', function(e){
        e.preventDefault();

        var img_src = $(this).data('src');

        $('#modal_image').attr('src', img_src);

        $('#image_popup_modal').modal('show');

    }); 

    $(document).on('click', '.close-btn', function(e){
        e.preventDefault();

        $('#modal_image').attr('src', '');

        $('#image_popup_modal').modal('hide');

    });

    $(document).on('change', '#reasonType', function(e){
        e.preventDefault();
        @this.set('reason_type',$(this).val());
    });
    
    document.addEventListener('reInitialize', function(event) {
        // $('#reasonType').select2({
        //     placeholder:'Select Type',
        // });
    });

    document.addEventListener('openReasonModal', function(event) {
        $('#reasonModal').modal('show');
    });

    document.addEventListener('closeReasonModal', function(event) {
        $('#reasonModal').modal('hide');
    });


</script>
@endpush
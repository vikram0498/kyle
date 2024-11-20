<div>
    <!-- End Show entries & Search box -->
            <!-- End Show entries & Search box -->
            <div class="card-title top-box-set">
                <h4 class="card-title-heading">{{ __('cruds.adBanner.fields.ad_name')}} : {{ $adBanner->ad_name}}</h4>                
                <h4 class="card-title-heading">{{ __('cruds.adBanner.fields.advertiser_name')}} : {{ $adBanner->advertiser_name}}</h4>                
            </div>
    <div class="row">
        <div class="col-12 col-lg-6 col-md-6 transparent">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 stretch-card transparent">                 
                    <div class="card card-light-purple">
                        <a href="#">
                        <div class="card-body border-0 d-flex align-items-center justify-content-between">
                            <div class="card-body-content">
                                <p class="heading-card">{{ __('cruds.adBanner.fields.impressions_purchased')}}</p>
                                <p class="box-count">{{ $adBanner->impressions_purchased ?? 0 }}</p>
                            </div>
                            <div class="card-body-icon">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23.2477 5.66663C25.996 5.66663 28.206 7.8908 28.206 10.625C28.206 13.3025 26.081 15.4841 23.4319 15.5833C23.3095 15.5691 23.1859 15.5691 23.0635 15.5833M25.9818 28.3333C27.0018 28.1208 27.9652 27.71 28.7585 27.1008C30.9685 25.4433 30.9685 22.7091 28.7585 21.0516C27.9793 20.4566 27.0302 20.06 26.0244 19.8333M12.9768 15.3991C12.8352 15.385 12.6652 15.385 12.5093 15.3991C10.8833 15.3439 9.34259 14.658 8.21349 13.4866C7.08439 12.3152 6.45556 10.7503 6.46018 9.1233C6.46018 5.65247 9.26518 2.8333 12.7502 2.8333C14.4165 2.80324 16.0265 3.43636 17.2261 4.59338C18.4256 5.7504 19.1164 7.33655 19.1464 9.00288C19.1765 10.6692 18.5434 12.2792 17.3863 13.4788C16.2293 14.6783 14.6432 15.3691 12.9768 15.3991ZM5.89352 20.6266C2.46518 22.9216 2.46518 26.6616 5.89352 28.9425C9.78935 31.5491 16.1785 31.5491 20.0743 28.9425C23.5027 26.6475 23.5027 22.9075 20.0743 20.6266C16.1927 18.0341 9.80352 18.0341 5.89352 20.6266Z" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 stretch-card transparent">
                    <div class="card card-light-green">
                        <a href="#">
                        <div class="card-body border-0 d-flex align-items-center justify-content-between">
                            <div class="card-body-content">
                                <p class="heading-card"> {{ __('cruds.adBanner.fields.click_count')}} </p>
                                <p class="box-count">{{ $adBanner->click_count ?? 0 }}</p>
                            </div>
                            <div class="card-body-icon">
                                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.97266 32.0833C4.97266 26.4395 10.5872 21.875 17.4997 21.875C18.8997 21.875 20.256 22.0645 21.5247 22.4145M17.4997 17.5C19.4336 17.5 21.2883 16.7317 22.6557 15.3643C24.0232 13.9968 24.7914 12.1422 24.7914 10.2083C24.7914 8.27442 24.0232 6.41976 22.6557 5.05231C21.2883 3.68485 19.4336 2.91663 17.4997 2.91663C15.5659 2.91663 13.7112 3.68485 12.3438 5.05231C10.9763 6.41976 10.2081 8.27442 10.2081 10.2083C10.2081 12.1422 10.9763 13.9968 12.3438 15.3643C13.7112 16.7317 15.5659 17.5 17.4997 17.5Z" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M32.0827 26.25C32.0827 26.7166 32.0244 27.1687 31.9077 27.6062C31.7764 28.1895 31.5431 28.7583 31.2369 29.2541C30.7228 30.1182 29.9925 30.8335 29.118 31.3295C28.2434 31.8256 27.2548 32.0854 26.2494 32.0833C24.8136 32.0875 23.4287 31.5513 22.3702 30.5812C21.9327 30.202 21.5535 29.75 21.2619 29.2541C20.7067 28.3506 20.4139 27.3104 20.416 26.25C20.4151 25.4837 20.5653 24.7247 20.8581 24.0165C21.1509 23.3083 21.5806 22.6649 22.1224 22.123C22.6643 21.5812 23.3077 21.1515 24.0159 20.8587C24.7241 20.5659 25.483 20.4157 26.2494 20.4166C27.9702 20.4166 29.5306 21.1604 30.5806 22.3562C31.5139 23.3916 32.0827 24.7625 32.0827 26.25Z" stroke="#0A2540" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M24.5 27.5591C24.5 28.3116 25.0775 28.9183 25.795 28.9183H27.2592C27.8833 28.9183 28.3908 28.3875 28.3908 27.7341C28.3908 27.0225 28.0817 26.7716 27.6208 26.6083L25.27 25.7916C24.8092 25.6283 24.5 25.3775 24.5 24.6658C24.5 24.0125 25.0075 23.4816 25.6317 23.4816H27.0958C27.8133 23.4816 28.3908 24.0883 28.3908 24.8408M26.4413 22.7V29.7" stroke="#0A2540" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>                    
            </div>
        </div>
        <div class="col-12 col-lg-6 col-md-6 transparent">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 stretch-card transparent">          
                    <div class="card card-light-purple">
                        <a href="#">
                        <div class="card-body border-0 d-flex align-items-center justify-content-between">
                            <div class="card-body-content">
                                <p class="heading-card">{{ __('cruds.adBanner.fields.impressions_served')}}</p>
                                <p class="box-count">{{ $adBanner->impressions_served ?? 0 }}</p>
                            </div>
                            <div class="card-body-icon">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23.2477 5.66663C25.996 5.66663 28.206 7.8908 28.206 10.625C28.206 13.3025 26.081 15.4841 23.4319 15.5833C23.3095 15.5691 23.1859 15.5691 23.0635 15.5833M25.9818 28.3333C27.0018 28.1208 27.9652 27.71 28.7585 27.1008C30.9685 25.4433 30.9685 22.7091 28.7585 21.0516C27.9793 20.4566 27.0302 20.06 26.0244 19.8333M12.9768 15.3991C12.8352 15.385 12.6652 15.385 12.5093 15.3991C10.8833 15.3439 9.34259 14.658 8.21349 13.4866C7.08439 12.3152 6.45556 10.7503 6.46018 9.1233C6.46018 5.65247 9.26518 2.8333 12.7502 2.8333C14.4165 2.80324 16.0265 3.43636 17.2261 4.59338C18.4256 5.7504 19.1164 7.33655 19.1464 9.00288C19.1765 10.6692 18.5434 12.2792 17.3863 13.4788C16.2293 14.6783 14.6432 15.3691 12.9768 15.3991ZM5.89352 20.6266C2.46518 22.9216 2.46518 26.6616 5.89352 28.9425C9.78935 31.5491 16.1785 31.5491 20.0743 28.9425C23.5027 26.6475 23.5027 22.9075 20.0743 20.6266C16.1927 18.0341 9.80352 18.0341 5.89352 20.6266Z" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 stretch-card transparent">
                    <div class="card card-light-green">
                        <a href="#">
                        <div class="card-body border-0 d-flex align-items-center justify-content-between">
                            <div class="card-body-content">
                                <p class="heading-card"> {{ __('cruds.adBanner.fields.impressions_count')}} </p>
                                <p class="box-count">{{ $adBanner->impressions_count ?? 0 }}</p>
                            </div>
                            <div class="card-body-icon">
                                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.97266 32.0833C4.97266 26.4395 10.5872 21.875 17.4997 21.875C18.8997 21.875 20.256 22.0645 21.5247 22.4145M17.4997 17.5C19.4336 17.5 21.2883 16.7317 22.6557 15.3643C24.0232 13.9968 24.7914 12.1422 24.7914 10.2083C24.7914 8.27442 24.0232 6.41976 22.6557 5.05231C21.2883 3.68485 19.4336 2.91663 17.4997 2.91663C15.5659 2.91663 13.7112 3.68485 12.3438 5.05231C10.9763 6.41976 10.2081 8.27442 10.2081 10.2083C10.2081 12.1422 10.9763 13.9968 12.3438 15.3643C13.7112 16.7317 15.5659 17.5 17.4997 17.5Z" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M32.0827 26.25C32.0827 26.7166 32.0244 27.1687 31.9077 27.6062C31.7764 28.1895 31.5431 28.7583 31.2369 29.2541C30.7228 30.1182 29.9925 30.8335 29.118 31.3295C28.2434 31.8256 27.2548 32.0854 26.2494 32.0833C24.8136 32.0875 23.4287 31.5513 22.3702 30.5812C21.9327 30.202 21.5535 29.75 21.2619 29.2541C20.7067 28.3506 20.4139 27.3104 20.416 26.25C20.4151 25.4837 20.5653 24.7247 20.8581 24.0165C21.1509 23.3083 21.5806 22.6649 22.1224 22.123C22.6643 21.5812 23.3077 21.1515 24.0159 20.8587C24.7241 20.5659 25.483 20.4157 26.2494 20.4166C27.9702 20.4166 29.5306 21.1604 30.5806 22.3562C31.5139 23.3916 32.0827 24.7625 32.0827 26.25Z" stroke="#0A2540" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M24.5 27.5591C24.5 28.3116 25.0775 28.9183 25.795 28.9183H27.2592C27.8833 28.9183 28.3908 28.3875 28.3908 27.7341C28.3908 27.0225 28.0817 26.7716 27.6208 26.6083L25.27 25.7916C24.8092 25.6283 24.5 25.3775 24.5 24.6658C24.5 24.0125 25.0075 23.4816 25.6317 23.4816H27.0958C27.8133 23.4816 28.3908 24.0883 28.3908 24.8408M26.4413 22.7V29.7" stroke="#0A2540" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>                    
            </div>
        </div>
    </div>

    <div class="table-responsive search-table-data">
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

            
            
            <div class="table-responsive mt-3 my-team-details table-record">
                <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-gray-500 text-xs font-medium">{{ trans('global.sno') }}</th>
                        
                        <th class="text-gray-500 text-xs">
                            {{ __('cruds.adBanner.fields.event_type')}}
                            <span wire:click="sortBy('event_type')" class="float-right text-sm" style="cursor: pointer;">
                                <i class="fa fa-arrow-up {{ $sortColumnName === 'event_type' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'event_type' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                            </span>
                        </th>
                        <th class="text-gray-500 text-xs">
                            {{ __('cruds.adBanner.fields.user_ip')}}
                            <span wire:click="sortBy('user_ip')" class="float-right text-sm" style="cursor: pointer;">
                                <i class="fa fa-arrow-up {{ $sortColumnName === 'user_ip' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'user_ip' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                            </span>
                        </th>
                        
                        <th class="text-gray-500 text-xs">{{ trans('cruds.adBanner.fields.date') }}
                            <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                                <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($adPerformaceLogs->count() > 0)
                        @foreach($adPerformaceLogs as $serialNo => $adPerformaceLog)
                        <tr>
                            <td>{{ $serialNo+1 }}</td>
                            <td>{{ ucwords($adPerformaceLog->event_type) }}</td>                
                            <td>{{ $adPerformaceLog->user_ip ?? '' }}</td>                
                            <td>{{ convertDateTimeFormat($adPerformaceLog->created_at,'date') }}</td>                        
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan="4">{{ __('messages.no_record_found')}}</td>
                    </tr>
                    @endif
                </tbody>
                </table>
            </div>
            {{ $adPerformaceLogs->links('vendor.pagination.custom-pagination') }}
        </div>
    </div>
</div>
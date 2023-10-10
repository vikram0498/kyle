<div>
    <div class="relative">
       
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center">                
                <div class="items-center justify-between p-2 sm:flex">
                    <div class="flex items-center my-2 sm:my-0">
                        <span class="items-center justify-between p-2 sm:flex"> 
                            Show 
                            <select name="perPage" class="ml-2 mr-2 border block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 form-select leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5" wire:model="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
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

    <div class="table-header-plugins">
        <!-- Start show length -->
        <div class="dataTables_length">
            <label>Show 
            <select wire:change="$emit('updatePaginationLength', $event.target.value)"> 
                @foreach(config('constants.datatable_entries') as $length)
                <option value="{{ $length }}">{{ $length }}</option>
                @endforeach
            </select> 
            entries</label>
        </div>
        <!-- End show length -->

        <!--Start search  -->
        <div class="search-container">
            <input type="text" class="form-control" id="searchInput" placeholder="{{ __('global.search')}}" wire:model="search"/>
            <span id="clearSearch" class="clear-icon" wire:click.prevent="clearSearch"><i class="fas fa-times"></i></span>
        </div>
        <!-- End Search -->
    </div>               
    <div class="table-responsive mt-3 my-team-details table-record">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>{{ trans('global.sno') }}</th>
                <th>{{ __('cruds.transaction.fields.user')}}</th>
                <th>{{ __('cruds.transaction.fields.plan')}}</th>
                <th>{{ __('cruds.transaction.fields.amount')}}</th>
                <th>{{ __('cruds.transaction.fields.currency')}}</th>
                <th>{{ trans('cruds.transaction.fields.status') }}</th>
                <th>{{ trans('global.created') }}
                    <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                        <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                        <i class="fa fa-arrow-down {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                    </span>
                </th>
                <th>{{ trans('global.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($transactions->count() > 0)
                @foreach($transactions as $serialNo => $transaction)
                  <tr>
                      <td>{{ $serialNo+1 }}</td>
                      <td>{{ $transaction->user ? ucwords($transaction->user->name) : '' }}</td>
                      <td>
                        @if($transaction->is_addon)
                          {{ $transaction->addonPlan ? ucwords($transaction->addonPlan->title) : '' }}
                        @else
                          {{ $transaction->plan ? ucwords($transaction->plan->title) : '' }}
                        @endif
                      </td>

                      <td><i class='fa fa-dollar'></i>{{ number_format($transaction->amount,2) }}</td>
                      <td>{{ strtoupper($transaction->currency) }}</td>
                      <td>{{ ucfirst($transaction->status) }}</td>

                      <td>{{ convertDateTimeFormat($transaction->created_at,'date') }}</td>

                      <td>
                         <button type="button" wire:click="$emitUp('show', {{$transaction->id}})" class="btn btn-primary btn-rounded btn-icon">
                              <i class="ti-eye"></i>
                          </button>
                      </td>

                  </tr>
                @endforeach
            @else
            <tr>
                <td class="text-center" colspan="6">{{ __('messages.no_record_found')}}</td>
            </tr>
            @endif
        
        </tbody>
        </table>
    </div>

    {{ $transactions->links('vendor.pagination.bootstrap-5') }}
</div>
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
                        {{ __('cruds.addon.fields.title')}}
                        <span wire:click="sortBy('title')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'title' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'title' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                    <th class="text-gray-500 text-xs">
                        {{ __('cruds.addon.fields.price')}}
                        <span wire:click="sortBy('price')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'price' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'price' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                   
                    <th class="text-gray-500 text-xs">
                        {{ __('cruds.addon.fields.credit')}}
                        <span wire:click="sortBy('credit')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'credit' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'credit' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                    <th class="text-gray-500 text-xs">
                        {{ __('cruds.buyer_plan.fields.position')}}
                        <span wire:click="sortBy('position')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'position' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'position' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                    <th class="text-gray-500 text-xs">
                        {{ __('cruds.addon.fields.status')}}
                        <span wire:click="sortBy('status')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'status' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'status' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                    <th class="text-gray-500 text-xs">{{ trans('global.created') }}
                        <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                            <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                            <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                        </span>
                    </th>
                    <th class="text-gray-500 text-xs">{{ trans('global.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($addonPlans->count() > 0)
                    @foreach($addonPlans as $serialNo => $plan)
                    <tr>
                        <td>{{ $serialNo+1 }}</td>
                        <td>{{ ucwords($plan->title) }}</td>
                        <td>{{ '$'.number_format($plan->price,2) }}</td>
                        <td>{{ $plan->credit ?? 0 }}</td>
                        <td>{{ $plan->position }}</td>
                        <td>
                            <label class="toggle-switch">
                                <input type="checkbox" class="toggleSwitch toggleSwitchMain" data-type="status"  data-id="{{$plan->id}}"  {{ $plan->status == 1 ? 'checked' : '' }}>
                                <span class="switch-slider" data-on="Active" data-off="Inactive"></span>
                            </label>
                        </td>
                     
                        <td>{{ convertDateTimeFormat($plan->created_at,'date') }}</td>

                        <td>
                            <button type="button" wire:click="$emitUp('show', {{$plan->id}})" class="btn btn-primary btn-rounded btn-icon">
                                <i class="ti-eye"></i>
                            </button>
                            <button type="button" wire:click="$emitUp('edit', {{$plan->id}})" class="btn btn-info btn-rounded btn-icon">
                                <i class="ti-pencil-alt"></i>
                            </button>
                            <button type="button" data-id="{{$plan->id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
                                <i class="ti-trash"></i>
                            </button>
                        </td>

                    </tr>
                    @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="7">{{ __('messages.no_record_found')}}</td>
                </tr>
                @endif
            
            </tbody>
            </table>
        </div>

        {{ $addonPlans->links('vendor.pagination.custom-pagination') }}
    </div>

</div>
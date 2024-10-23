<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div wire:loading wire:target="sendReminder,filterReminder" class="loader"></div>
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{__('cruds.buyer_invitation.title')}}</h4>
                        
                        <div class="card-top-box-item">
                            <button wire:click="sendReminder" type="button" class="btn btn-sm btn-success btn-icon-text btn-header">
                                <i class="ti-bell btn-icon-prepend"></i>                                                    
                                    {{__('cruds.buyer_invitation.remider_btn')}}
                            </button>
                        </div>
                    </div>

                    <!-- Start Custom Filter -->
                    <div class="card">
                        <div class="card-body">
                            <h4>Filter</h4>
                            <div class="row">
                                <div class="col-sm-2">
                                    <select name="filter_reminder" class="form-control"  wire:model="filterReminder">
                                        <option value="">All Reminder</option>
                                        @foreach(config('constants.reminders') as $remVal=>$reminder)
                                            <option value="{{ $remVal }}">{{ ucwords($reminder) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>    
                    </div>    
                    <!-- End Custom Filter -->


                    <!-- Start Datatable  -->
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
                                            <th class="text-gray-500 text-xs font-medium">
                                                <input type="checkbox" wire:model="selectAll">
                                            </th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer_invitation.fields.email')}}
                                                <span wire:click="sortBy('email')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'email' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'email' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer_invitation.fields.seller_name')}}
                                            </th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer_invitation.fields.reminder')}}
                                                <span wire:click="sortBy('reminder_count')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'reminder_count' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'reminder_count' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer_invitation.fields.last_reminder_sent')}}
                                                <span wire:click="sortBy('last_reminder_sent')" class="float-right text-sm" style="cursor: pointer;">
                                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'last_reminder_sent' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                                    <i class="fa fa-arrow-down m-0 {{ $sortColumnName === 'last_reminder_sent' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                                </span>
                                            </th>
                                            <th class="text-gray-500 text-xs">
                                                {{ __('cruds.buyer_invitation.fields.status')}}
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
                                        @if($buyerInvitations->count() > 0)

                                          @foreach($buyerInvitations as $row)
                                            @php
                                                $isCheckboxDisabled = false;
                                                if(( $row->reminder_count == count(config('constants.reminders')) ) || ($row->status == 1) ){
                                                    $isCheckboxDisabled = true;
                                                }    
                                            @endphp
                                            <tr>
                                                <td><input type="checkbox" wire:model="selectedRows" value="{{ $row->id }}" {{ $isCheckboxDisabled ? 'disabled' : '' }}></td>
                                                <td>{{ $row->email }}</td>
                                                <td>{{ $row->createdBy->name ?? '' }}</td>
                                                <td>{{ $row->reminder_count }}</td>
                                                <td>{{ $row->last_reminder_sent ? convertDateTimeFormat($row->last_reminder_sent,'datetime')  : '' }}</td>
                                                <td>{{ config('constants.invitation_status')[$row->status] ? ucwords(config('constants.invitation_status')[$row->status]) : '' }}</td>
                                                <td>{{ convertDateTimeFormat($row->created_at,'datetime') }}</td>
                                                <td>
                                                    @if($row->status == 0)
                                                    <button type="button" data-id="{{$row->id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                          @endforeach

                                        @else 
                                        <tr>
                                            <td class="text-center" colspan="8">{{ __('messages.no_record_found')}}</td>
                                        </tr>
                                        @endif
                                    
                                    </tbody>
                                    </table>
                                </div>

                                {{ $buyerInvitations->links('vendor.pagination.custom-pagination') }}
                                
                            </div>
                        </div>
                    
                    </div>
                    <!-- End Datatable  -->


                
                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"/>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>

<script type="text/javascript">
    document.addEventListener('loadPlugins', function (event) {
        
        $('.select2').select2({
            theme: "classic"
        });
       
    });

    $(document).on('click', '.deleteBtn', function(e){
        var _this = $(this);
        var id = _this.attr('data-id');
       
        Swal.fire({
            title: 'Are you sure you want to delete it?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('deleteConfirm', id);
            }
        })
    });

</script>
@endpush
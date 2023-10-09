<div>
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
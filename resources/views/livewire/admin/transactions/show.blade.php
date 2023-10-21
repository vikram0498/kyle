<div>
    
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.transaction.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.transaction.fields.user')}}</th>
            <td>{{ $details->user->name ?? ''}}</td>
        </tr>
        <tr>
            <th width="25%">Plan</th>
            <td>
                @php
                 $planTitle = '';
                 if($details->is_addon){
                        $planTitle =  \App\Models\Addon::where('id',$details->plan_id)->value('title');
                 }else{
                        $planTitle = \App\Models\Plan::where('id',$details->plan_id)->value('title');
                 }
                @endphp
            
                {{ $planTitle }}
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.transaction.fields.amount')}}</th>
            <td><i class='fa fa-dollar'></i>{{ number_format($details->amount,2) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.transaction.fields.currency')}}</th>
            <td> {{ strtoupper($details->currency) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.transaction.fields.status')}}</th>
            <td> {{ ucfirst($details->status) }}</td>
        </tr>
        
        <tr>
            <th width="25%">{{ trans('global.created_at') }}</th>
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

</div>

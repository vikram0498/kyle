
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.chat_report.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        
        <tr>
            <th width="25%">{{ __('cruds.chat_report.fields.conversation')}}</th>
            <td>{{ ucwords($details->conversation->participantOne->name ) }} - {{ ucwords($details->conversation->participantTwo->name) }}</td> 
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.chat_report.fields.reported_by')}}</th>
            <td>{{ $details->reportedBy ? ucwords($details->reportedBy->name) : '' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.chat_report.fields.reason')}}</th>
            <td>{{ $details->reasonDetail  ? $details->reasonDetail->name : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.chat_report.fields.comment')}}</th>
            <td>{{ $details->comment ?? 'N/A' }}</td>
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

               
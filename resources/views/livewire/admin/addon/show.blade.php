
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.addon.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.title')}}</th>
            <td>{{ $details->title ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.price')}}</th>
            <td>{{ $details->price ? '$'.number_format($details->price,2) : 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.credit')}}</th>
            <td> {{ $details->credit ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.status')}}</th>
            <td> {{ ($details->status ? 'Active' : 'Inactive') }}</td>
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

               
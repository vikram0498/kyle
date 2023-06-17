
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.addon.title_singular'))}}</h4>

    <table class="table table-borderless">
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.title')}}</th>
            <td>{{ $details->title }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.price')}}</th>
            <td><i class="fa-solid {{ config('constants.currency_icon') }}"></i> {{ number_format($details->price,2) }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.credit')}}</th>
            <td> {{ $details->credit }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.addon.fields.status')}}</th>
            <td> {{ ($details->status ? 'Active' : 'Inactive') }}</td>
        </tr>
    </table>
    <button wire:click.prevent="cancel" class="btn btn-secondary">
        {{ __('global.back')}}
        <span wire:loading wire:target="cancel">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>

               
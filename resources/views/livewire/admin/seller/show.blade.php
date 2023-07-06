
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.user.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.user.fields.first_name')}}</th>
            <td> {{ $seller->first_name ?? 'N/A' }} </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.last_name')}}</th>
            <td>{{ $seller->last_name ?? 'N/A'  }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.email')}}</th>
            <td> {{$seller->email}}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.phone')}}</th>
            <td> {{ $seller->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.buyer_count')}}</th>
            <td> {{ $selfBuyerCount ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.purchased_buyer')}}</th>
            <td> {{ $purchasedBuyerCount ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.level_type')}}</th>
            <td> Level 1</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.package')}}</th>
            <td> Free</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.user.fields.status')}}</th>
            <td> {{ ($seller->is_active ? 'Active' : 'Block') }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('global.created_at')}}</th>
            <td> {{ $seller->created_at->format(config('constants.datetime_format')) }}</td>
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

               
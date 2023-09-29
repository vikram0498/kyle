<div>
    
<h4 class="card-title">
    {{__('global.show')}} {{ strtolower(__('cruds.support.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        <tr>
            <th width="25%">{{ __('cruds.support.fields.name')}}</th>
            <td> {{ $support->name ?? 'N/A' }} </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.support.fields.email')}}</th>
            <td>{{ $support->email ?? 'N/A'  }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.support.fields.phone_number')}}</th>
            <td> {{$support->phone_number ?? 'N/A'}}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.support.fields.message')}}</th>
            <td> {{ $support->message ?? 'N/A' }}</td>
        </tr>
       
        <tr>
            <th width="25%">{{ __('global.created_at')}}</th>
            <td> {{ $support->created_at->format(config('constants.datetime_format')) }}</td>
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

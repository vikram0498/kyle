
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.video.title_singular'))}}</h4>

    <table class="table table-borderless">
        
        <tr>
            <th width="25%">{{ __('cruds.video.fields.video')}}</th>
            <td>
                <video width="320" height="240" controls>
                    <source src="{{ $details->video_url }}" >
                </video>
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.title')}}</th>
            <td>{{ $details->title }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.description')}}</th>
            <td>{!! $details->description !!}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.status')}}</th>
            <td> {{ ($details->status ? 'Active' : 'Inactive') }}</td>
        </tr>
    </table>
    <button wire:click.prevent="cancel" class="btn btn-secondary">
        {{ __('global.back')}}
        <span wire:loading wire:target="cancel">
            <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
        </span>
    </button>

               
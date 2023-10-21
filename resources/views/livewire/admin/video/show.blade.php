
<h4 class="card-title">
    {{__('global.show')}}
    {{ strtolower(__('cruds.video.title_singular'))}}</h4>

    <table class="table table-design mb-4">
        
        <tr>
            <th width="25%">{{ __('cruds.video.fields.video')}}</th>
            <td>
                <video controls="" width="450" height="315" preload="none" poster="" id="clip-video"  playsinline>
                    <source class="js-video" src="{{ $details->video_url }}" type="video/{{$details->uploadedVideo->extension }}">
                </video>
            </td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.title')}}</th>
            <td>{{ $details->title ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.sub_title')}}</th>
            <td>{{ $details->sub_title ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.description')}}</th>
            <td>{!! $details->description !!}</td>
        </tr>
        <tr>
            <th width="25%">{{ __('cruds.video.fields.status')}}</th>
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

               
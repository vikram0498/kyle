
<h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.video.title_singular'))}}</h4>

<form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.video.fields.title')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="title" placeholder="{{ __('cruds.video.fields.title')}}">
                @error('title') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.video.fields.sub_title')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="sub_title" placeholder="{{ __('cruds.video.fields.sub_title')}}">
                @error('sub_title') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group mb-0" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.video.fields.description')}} <span class="text-danger">*</span></label>
                <textarea class="form-control" id="summernote" wire:model.defer="description" rows="4"></textarea>
            </div>
            @error('description') <span class="error text-danger">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group mb-0" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.video.fields.video')}} </label>
                <button type="button"  class="btn btn-xs btn-dark float-right" data-toggle="modal" data-target="#previewModal">
                    Preview
                </button>
                <div class='file-input mt-2'>
                  <input type="file"  wire:model.defer="video" class="form-control" accept="video/webm, video/mp4, video/avi,video/wmv,video/flv,video/mov">
                  <span class='button'>Choose</span>
                  <span class='label' data-js-label>No file selected</label>
                </div>
                <span wire:loading wire:target="video">
                    <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                </span>
            </div>
            @if($errors->has('video'))
            <span class="error text-danger">
                {{ $errors->first('video') }}
            </span>
            @endif
        </div>
    </div>
   
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">{{__('global.status')}}</label>
                <div class="form-group">
                    <label class="toggle-switch">
                        <input type="checkbox" class="toggleSwitch" wire:change.prevent="changeStatus({{$status}})" value="{{ $status }}" {{ $status ==1 ? 'checked' : '' }}>
                        <span class="switch-slider" data-on="Active" data-off="Inactive"></span>
                    </label>
                </div>
                @error('status') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between"> 
        <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue  mr-2">
            {{ $updateMode ? __('global.update') : __('global.submit') }}
            <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark ">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</form>

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="previewModalLabel">Preview</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <video controls="" width="450" height="315" preload="none" poster="" id="clip-video"  playsinline>
                <source class="js-video" src="{{ $video_link }}" type="video/{{$videoExtension }}">
            </video>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var inputs = document.querySelectorAll('.file-input')

    for (var i = 0, len = inputs.length; i < len; i++) {
      customInput(inputs[i])
    }

    function customInput (el) {
      const fileInput = el.querySelector('[type="file"]')
      const label = el.querySelector('[data-js-label]')
      
      fileInput.onchange =
      fileInput.onmouseout = function () {
        if (!fileInput.value) return
        
        var value = fileInput.value.replace(/^.*[\\\/]/, '')
        el.className += ' -chosen'
        label.innerText = value
      }
    }
</script>
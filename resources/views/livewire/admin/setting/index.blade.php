<div>
    <div class="content-wrapper">
    
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Settings</h4>

                        {{-- Start Step Form --}}
                        <div class="row">
                            <div class="col-lg-12">
                                @if($allSettingType)
                                <!-- Step form tab menu -->
                                <ul class="nav nav-pills border-0">
                                @foreach ($allSettingType as $key=>$groupType)
                                
                                    @php
                                        $groupName = str_replace('_',' ',$groupType)
                                    @endphp
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab == $groupType  ? 'active' : '' }}" wire:click.prevent="changeTab('{{$groupType}}')" data-toggle="pill" href="javascript:void(0);">{{ ucwords($groupName) }}</a>
                                    </li>
                    
                                @endforeach
                                </ul>
                                @endif
                            
                                <!-- Step form content -->
                                <div class="tab-content p-0 border-0">
                                    <div class="tab-pane fade show active" id="{{$tab}}">
                                    
                                        <div class="card mb-4">
                                            <div class="card-header border-0">
                                                <label class="font-weight-bold">{{ ucwords(str_replace('_',' ',$tab)) }}</label>
                                            </div>
                                            <div class="card-body"> 
                                            
                                                <form wire:submit.prevent="update">
                                                    @if($settings)
                    
                                                    <div class="row">
                                                    @foreach($settings as $setting)
                                                    
                                                        @if($setting->type == 'text')
                                                            <div class="{{ in_array($setting->group,array('site','introduction_video')) ? 'col-sm-12' : 'col-sm-6'}}">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold justify-content-start">{{ $setting->display_name }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="text" class="form-control" wire:model.defer="state.{{$setting->key}}" placeholder="{{$setting->display_name}}" />
                                                                    @error('state.'.$setting->key) <span class="error text-danger">{{ $message }}</span>@enderror
                                                                </div>
                                                            </div>
                                                        @elseif($setting->type == 'text_area')
                                                            <div class="col-sm-12 mb-4">
                                                                <div class="form-group mb-0" wire:ignore>
                                                                    <label class="font-weight-bold justify-content-start">{{ $setting->display_name }}
                                                                        @if($setting->group != 'mail')
                                                                        <span class="text-danger">*</span>
                                                                        @endif
                                                                    </label>
                    
                                                                    @if($setting->details)
                                                                        @php
                                                                        $parameterArray = explode(', ',$setting->details);
                                                                        @endphp
                                                                        @if($parameterArray)
                                                                            @foreach($parameterArray as $parameter)
                                                                            <button class="btn btn-sm btn-info copy-btn mb-1" data-elementVal="{{$parameter}}">{{ $parameter }}</button>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                    <textarea class="form-control summernote" wire:model.defer="state.{{$setting->key}}" data-elementName ="state.{{$setting->key}}" placeholder="{{$setting->display_name}}" rows="4">{{$setting->value}}</textarea>
                                                                
                                                                </div>
                    
                                                                @error('state.'.$setting->key) <span class="error text-danger">{{ $message }}</span>@enderror
                                                            </div>
                                                        @elseif($setting->type == 'image')
                                                            <div class="col-md-12 mb-4">
                                                                <div class="form-group mb-0" wire:ignore>
                                                                    <label class="font-weight-bold">
                                                                        {{ $setting->display_name }} 
                    
                                                                        @if($setting->details)
                                                                            <span>Size : {{ $setting->details }} </span>
                                                                        @endif
                    
                                                                    </label>
                                                                    <input type="file" id="{{$setting->key}}-image" wire:model.defer="state.{{$setting->key}}" class="dropify" data-default-file="{{ $setting->image_url }}"  data-show-loader="true" data-errors-position="outside" data-allowed-file-extensions="jpeg png jpg svg" data-min-file-size-preview="1M" data-max-file-size-preview="3M" accept="image/jpeg, image/png, image/jpg,image/svg">
                                                                    <span wire:loading wire:target="state.{{$setting->key}}">
                                                                        <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Loading
                                                                    </span>
                                                                </div>
                                                                @if($errors->has('state.'.$setting->key))
                                                                <span class="error text-danger">
                                                                    {{ $errors->first('state.'.$setting->key) }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        @elseif($setting->type == 'video')
                                                            <div class="col-md-12 mb-4">
                                                                <div class="form-group mb-0" wire:ignore>
                                                                    <div class="mb-3">
                                                                        <label class="font-weight-bold">{{ $setting->display_name }}</label>
                                                                        <button type="button"  class="btn btn-xs btn-dark float-right" wire:click.prevent="previewVideo('{{$setting->key}}')">
                                                                            Preview
                                                                        </button>
                                                                    </div>
                                                                    <input type="file" id="{{$setting->key}}-video" wire:model.defer="state.{{$setting->key}}" class="dropify" data-default-file="{{  $setting->video_url }}"  data-show-loader="true" data-errors-position="outside" data-allowed-file-extensions="webm mp4 avi wmv flv mov" data-min-file-size-preview="1M" data-max-file-size-preview="3M" accept="video/webm, video/mp4, video/avi,video/wmv,video/flv,video/mov">
                    
                                                                    <span wire:loading wire:target="state.{{$setting->key}}">
                                                                        <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Loading
                                                                    </span>
                                                                </div>
                                                                @if($errors->has('state.'.$setting->key))
                                                                <span class="error text-danger">
                                                                    {{ $errors->first('state.'.$setting->key) }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        
                                                        @elseif($setting->type == 'toggle')
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold justify-content-start">{{ $setting->display_name }}
                                                                        <i class="fas fa-asterisk"></i>
                                                                    </label>
                                                                    <div class="form-group">
                                                                        <label class="toggle-switch">
                                                                            <input type="checkbox" id="{{$setting->key}}" value="{{$state[$setting->key]}}"  class="toggleSwitch setting-toggle" {{ $state[$setting->key] == 'active' ? 'checked': '' }}>
                                                                            <span class="switch-slider"></span>
                                                                        </label>
                                                                        @if($errors->has('state.'.$setting->key))
                                                                            <span class="error text-danger">
                                                                                {{ $errors->first('state.'.$setting->key) }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                    @endforeach
                                                    </div>
                    
                                                    @endif
                                                    
                                                    <div class="text-right mt-3">
                                                        <button class="btn btn-success" type="submit" wire:loading.attr="disabled">
                                                        {{ __('global.update') }}     
                                                            <span wire:loading wire:target="update">
                                                                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    
                                                </form>  
                                            </div>
                                        </div>
                                        
                    
                                    </div>
                                </div>
                    
                            </div>
                        </div>
                        {{-- End Step Form --}}

                    </div>
                </div>
            </div>
        </div>
    
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewModalLabel">Preview</h4>
                <button type="button" class="close close-modal"  aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video controls="" width="450" height="315" preload="none" poster="" id="preview-video"  playsinline>
                    <source class="js-video" src="#" type="video/">
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    </div>
    
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    @endpush
    
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script type="text/javascript">
      
        @if($settings)
            $(document).ready(function(){
                $('.dropify').dropify();
                $('.dropify-errors-container').remove();
                $('.dropify-clear').click(function(e) {
                    e.preventDefault();
                    var elementName = $(this).siblings('input[type=file]').attr('id');
                    elementName = elementName.split('-');
                    if(elementName[1] == 'image'){
                        @this.set('state.'+elementName[0],null);
                        @this.set('removeFile.remove_'+elementName[0],true);
                    }else if(elementName[1] == 'video'){
                        @this.set('state.'+elementName[0],null);
                        @this.set('removeFile.remove_'+elementName[0],true);
                    }
                });
            });
    
            $('textarea.summernote').summernote({
                placeholder: 'Type somthing...',
                tabsize: 2,
                height: 200,
                fontNames: ['Arial', 'Helvetica', 'Times New Roman', 'Courier New','sans-serif'],
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    // ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', [/*'link', 'picture', 'video'*/]],
                    ['view', [/*'fullscreen',*/ 'codeview', /*'help'*/]],
                ],
                callbacks: {
                    onChange: function(content) {
                        // Update the Livewire property when the Summernote content changes
                        var variableName = $(this).attr('data-elementName');
                        @this.set(variableName, content);
                    }
                }
            });
        @endif
    
        document.addEventListener('loadPlugins', function (event) {
          
            $('.dropify').dropify();
            $('.dropify-errors-container').remove();
            $('.dropify-clear').click(function(e) {
                e.preventDefault();
                var elementName = $(this).siblings('input[type=file]').attr('id');
                elementName = elementName.split('-');
                if(elementName[1] == 'image'){
                    @this.set('state.'+elementName[0],'delete');
                    @this.set('removeFile.remove_'+elementName[0],true);
                }else if(elementName[1] == 'video'){
                    @this.set('state.'+elementName[0],'delete');
                    @this.set('removeFile.remove_'+elementName[0],true);
                }
            });
    
            $('textarea.summernote').summernote({
                placeholder: 'Type somthing...',
                tabsize: 2,
                height: 200,
                fontNames: ['Arial', 'Helvetica', 'Times New Roman', 'Courier New','sans-serif'],
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    // ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', [/*'link', 'picture', 'video'*/]],
                    ['view', [/*'fullscreen',*/ 'codeview', /*'help'*/]],
                ],
                callbacks: {
                    onChange: function(content) {
                        // Update the Livewire property when the Summernote content changes
                        var variableName = $(this).attr('data-elementName');
                        @this.set(variableName, content);
                    }
                }
            });
    
        });
    
    
        $(document).ready(function(){
    
            $(document).on('change','.section-type',function(){
                var sectionType = $(this).val();
                Livewire.emit('changeType',sectionType);
    
            });
    
            $(document).on('change','.setting-toggle',function(){
    
                var $this = $(this);
                var elementId = $this.attr('id');
                var status = $this.val();
    
                if(status == 'inactive'){
                    @this.set('state.'+elementId,'active');
                }else if(status == 'active'){
                    @this.set('state.'+elementId,'inactive');
                }
              
            });

            $(document).on('click','.close-modal',function(e){
                e.preventDefault();
                $('#previewModal').modal('hide');

                previewVideo('pause');

            });

        });
    
        document.addEventListener('openVideoPreviewModal', function (event) {
            $('#previewModal').modal('show');

            previewVideo('play',event.detail.videoUrl,event.detail.extension);
            
        });

        function previewVideo($mode = 'play',url='',extension=''){
            var video = $('#previewModal video')[0]; 
            if (video) {
              
                if( (url!= '') && (extension != '')){
                    video.src = url;
                    video.type = 'video/' + extension;
                    video.load();
                }

                if($mode == 'play'){
                    video.play(); 
                }else if($mode == 'pause'){
                    video.pause(); 
                }
                 
            } else {
                console.error("Video element not found in the modal.");
            }
        }

      
    </script>
    @endpush
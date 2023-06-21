<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if($formMode)
    
                    @include('livewire.admin.video.form')

                @elseif($viewMode)

                    @livewire('admin.video.show', ['video_id' => $video_id])

                @else
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title">
                        <h4 class="float-left">{{__('cruds.video.title')}} {{ __('global.list') }}</h4>
                        <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text float-right">
                            <i class="ti-plus btn-icon-prepend"></i>                                                    
                                {{__('global.add')}}
                        </button>
                    </div>                
                    <div class="table-responsive">
                        <div class="align-items-center border-top mt-4 pt-2 row justify-content-between">
                            <div class="col-md-2">
                                <select wire:change="changeNumberOfList($event.target.value)" class="form-control">
                                    @foreach($numberOfrowsList as $key => $val)
                                        <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach 
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="table-additional-plugin ">
                                    <input type="text" class="form-control" wire:model="search" placeholder="{{ __('global.search')}}">
                                </div>
                            </div>
                        </div> 
                       
                        <table class="table table-hover">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>
                                {{ trans('cruds.video.fields.title') }}
                                <span wire:click="sortBy('title')" class="float-right text-sm" style="cursor: pointer;">
                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'title' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                    <i class="fa fa-arrow-down {{ $sortColumnName === 'title' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                </span>
                            </th>
                            <!-- <th>{{ trans('cruds.video.fields.description') }}</th> -->
                            <th>{{ trans('global.status') }}</th>
                            <th>
                                {{ trans('global.created_at') }}
                                <span wire:click="sortBy('created_at')" class="float-right text-sm" style="cursor: pointer;">
                                    <i class="fa fa-arrow-up {{ $sortColumnName === 'created_at' && $sortDirection === 'asc' ? '' : 'text-muted' }}"></i>
                                    <i class="fa fa-arrow-down {{ $sortColumnName === 'created_at' && $sortDirection === 'desc' ? '' : 'text-muted' }}"></i>
                                </span>
                            </th>
                            <th>{{ trans('global.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($allVideos->count() > 0)
                                @foreach($allVideos as $serialNo => $video)
                                    <tr>
                                        <td>{{ $serialNo+1 }}</td>
                                        <td>{{ ucfirst($video->title) }}</td>
                                        <td>
                        
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggleSwitch toggleSwitchMain" data-type="status"  data-id="{{$video->id}}" {{ $video->status == 1 ? 'checked' : '' }}>
                                                <span class="switch-slider" data-on="Active" data-off="Inactive"></span>
                                            </label>

                                        </td>
                                        <td>{{ convertDateTimeFormat($video->created_at,'datetime') }}</td>
                                        <td>
                                            <button type="button" wire:click="show({{$video->id}})" class="btn btn-primary btn-rounded btn-icon">
                                                <i class="ti-eye"></i>
                                            </button>
                                            
                                            <button type="button" wire:click="edit({{$video->id}})" class="btn btn-info btn-rounded btn-icon">
                                                <i class="ti-pencil-alt"></i>
                                            </button>

                                            <button type="button" data-id="{{$video->id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5">{{ __('messages.no_record_found')}}</td>
                            </tr>
                            @endif
                        
                        </tbody>
                        </table>

                        {{ $allVideos->links('vendor.pagination.bootstrap-5') }}
                       
                    </div>

                @endif

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

    document.addEventListener('loadPlugins', function (event) {
      
        $('.dropify').dropify();
        $('.dropify-errors-container').remove();
      
        $('textarea#summernote').summernote({
            placeholder: 'Type somthing...',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', /*'picture', 'video'*/]],
                // ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onChange: function(content) {
                    // Update the Livewire property when the Summernote content changes
                    @this.set('description', content);
                }
            }
        });
      
    });
    
    $(document).on('click', '.toggleSwitchMain', function(e){
        var _this = $(this);
        var id = _this.data('id');
        var type = _this.data('type');

        var data = { id: id, type: type }
        
        var flag = true;
        if(_this.prop("checked")){
            flag = false;
        }
        Swal.fire({
            title: 'Are you sure you want to change the status?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('confirmedToggleAction', data);
            } else if (result.isDenied) {
                _this.prop("checked", flag);
            }
        })
    })
    $(document).on('click', '.deleteBtn', function(e){
        var _this = $(this);
        var id = _this.data('id');
       
        Swal.fire({
            title: 're you sure you want to delete it?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes, change it',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('deleteConfirm', id);
            }
        })
    })
   

</script>
@endpush
<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if($formMode)
    
                    @include('livewire.admin.plan.form')

                @elseif($viewMode)

                    @livewire('admin.plan.show', ['plan_id' => $plan_id])

                @else
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{__('cruds.plan.title')}} {{ __('global.list') }}</h4>
                        <div class="card-top-box-item">
                            <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text btn-header">
                                <i class="ti-plus btn-icon-prepend"></i>                                                    
                                    {{__('global.add')}}
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive search-table-data">
                           {{-- @livewire('admin.plan.plan-datatable')  --}}

                            @livewire('admin.plan.plan-table') 
                       
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
            placeholder: 'Type something...',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', [/*'link', 'picture', 'video'*/]],
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

    $(document).on('click', '.dropify-clear', function(e) {
        e.preventDefault();
        var elementName = $(this).siblings('input[type=file]').attr('id');
        if(elementName == 'dropify-image'){
            $('.dropify').dropify('reset');

            @this.set('image',null);
            @this.set('originalImage',null);
            @this.set('removeImage',true);
        }
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
        var id = _this.attr('data-id');
       
        Swal.fire({
            title: 'Are you sure you want to delete it?',
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
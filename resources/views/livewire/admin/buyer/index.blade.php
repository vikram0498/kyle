<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($formMode)
        
                        @include('livewire.admin.buyer.form')

                    @elseif($viewMode)

                        @livewire('admin.buyer.show', ['buyer_id' => $buyer_id])
                    @elseif($redFlagView)
                        @livewire('admin.buyer.red-flag-show', ['buyer_id' => $buyer_id])
                    @else
                        <div wire:loading wire:target="{{ $updateMode ? 'edit' : 'create' }}" class="loader"></div>
                        <div class="card-title top-box-set">
                            <h4 class="card-title-heading">{{__('cruds.buyer.title')}} {{ __('global.list') }}</h4>
                            
                            <div class="card-top-box-item">
                                <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text btn-header">
                                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                                        {{__('global.add')}}
                                </button>
                                <a href="{{ route('admin.import-buyers') }}" class="btn btn-sm btn-primary mr-2 btn-icon-text btn-header">
                                    <i class="fa fa-download"></i>                                                
                                        {{__('cruds.buyer.fields.buyer_csv_import')}}
                                </a>
                                <a href="{{ asset('default/sample_template_import_buyer.csv') }}" download="{{ asset('default/sample_template_import_buyer.csv') }}" class="btn btn-sm btn-info mr-2 btn-icon-text btn-header">
                                    <i class="fa fa-upload"></i>                                                
                                    {{__('cruds.buyer.fields.buyer_csv_template')}}
                                </a>
                            </div>
                            <!-- <a href="{{ $buyerFormLink }}" class="btn btn-sm btn-dark mr-2 btn-icon-text copy_link position-relative">
                                <i class="fa fa-copy"></i>                                                
                                    {{__('cruds.buyer.fields.copy_add_buyer_link')}}
                            </a> -->
                        </div>                
                        <div class="table-responsive search-table-data">

                            @livewire('admin.buyer.buyer-datatable') 
                        
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js" integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    document.addEventListener('loadPlugins', function (event) {
        
        $('.select2').select2({
            theme: "classic"
        });

        $('.country, .state, .city, .parking, .buyer_type').select2();

       
        // $('#build_year_min').datepicker({
        //     format: "yyyy",
        //     viewMode: "years", 
        //     minViewMode: "years",
        //     autoclose:true,
        // });
        
    });

    // $(document).on('change','#build_year_min', function (e) {
    //     console.log($(this).val());
    // });

    $(document).on('click','.copy_link', function (e) {
        e.preventDefault();
        var copyText = $(this).attr('href');
        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
        }, true);
        document.execCommand('copy');
    });

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

    $(document).on('change','.select2', function(e){
        var pr = $(this).data('property');
        var pr_vals = $(this).val();
        // @this.set('state.'+pr, pr_vals);
        
        @this.emit('updateProperty', {property: pr, pr_vals: pr_vals});
        if(pr == 'buyer_type'){
            // @this.emit('changeBuyerType', $('.buyer_type').select2('val'));
            @this.emit('initializePlugins');
        } else if(pr == 'country'){
            @this.emit('getStates', $('.country').select2('val'));
        } else if(pr == 'state'){
            @this.emit('getCities', $('.state').select2('val'));
        } else if(pr == 'city'){
            @this.emit('initializePlugins');
        } else if(pr == 'zoning'){
            @this.emit('initializePlugins');
        } else if(pr == 'parking'){
            @this.emit('initializePlugins');
        }

        
    });

    $(document).on('click', '.toggleSwitchMain', function(e){
        var _this = $(this);
        var id = _this.data('id');
        var type = _this.data('type');

        var data = {
            id: id,
            type: type
        }
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


    // red flag page events
    $(document).on('click', '#resolveAllFlag', function(e){
        var _this = $(this);
        var id = _this.data('buyer_id');
       
        Swal.fire({
            title: 'Are you sure you want to resolve all flags?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('resolveAllFlag', id);
            }
        })
    })

    $(document).on('click', '.resolve_flag', function(e){
        var _this = $(this);
        var buyer_id = _this.data('buyer_id');
        var seller_id = _this.data('seller_id');
       
        var data = { buyer_id: buyer_id, seller_id: seller_id }
        Swal.fire({
            title: 'Are you sure you want to resolve this flag?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('resolveFlag', data);
            }
        })
    })

    $(document).on('click', '.reject_flag', function(e){
        var _this = $(this);
        var buyer_id = _this.data('buyer_id');
        var seller_id = _this.data('seller_id');
       
        var data = { buyer_id: buyer_id, seller_id: seller_id }
        Swal.fire({
            title: 'Are you sure you want to resolve this flag?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Yes',
            denyButtonText: `No, cancel!`,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.emit('rejectFlag', data);
            }
        })
    })
</script>
@endpush
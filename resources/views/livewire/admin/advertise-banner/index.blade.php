<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
    
                    @if($formMode)
        
                        @include('livewire.admin.advertise-banner.form')
    
                    @elseif($viewMode)
    
                        @livewire('admin.advertise-banner.show', ['adBanner_id' => $adBanner_id])
    
                    @elseif($adPerformanceLogsViewMode)
                       
                        @livewire('admin.advertise-banner.show-ad-performace-logs', ['adBanner_id' => $adBanner_id])
                       
                    @else
                        <div wire:loading wire:target="create" class="loader"></div>
                        <div class="card-title top-box-set">
                            <h4 class="card-title-heading">{{__('cruds.adBanner.title')}} {{ __('global.list') }}</h4>
                            {{-- <div class="card-top-box-item">
                                <button wire:click="create()" type="button" class="btn btn-sm btn-success btn-icon-text btn-header">
                                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                                        {{__('global.add')}}
                                </button>
                            </div> --}}
                        </div>
                        <div class="table-responsive search-table-data">    
                                @livewire('admin.advertise-banner.ad-banner-table')                          
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush
    
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
    
        document.addEventListener('loadPlugins', function (event) {          
            $('.dropify').dropify();
            $('.dropify-errors-container').remove(); 
            
            $('input[id="start_date"]').daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                minDate: new Date(),
                locale: {
                    format: 'DD-MM-YYYY'
                },
            }, function(start, end, label) {
                let startDate = start.format('DD-MM-YYYY');
                @this.set('start_date', startDate);

                const isToday = start.isSame(new Date(), 'day');
    
                var now = moment();
                if (start.isSame(now, 'day')) {
                   var time = moment().startOf('hour').minute(moment().minute());
                }else{
                    var time = moment().startOf('day');
                }
                // Initialize the start time picker
                $('#start_time').daterangepicker({
                    autoApply: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    singleDatePicker: true,
                    autoUpdateInput: false,
                    minDate: time,
                    startDate: time,
                    locale: {
                        format: 'HH:mm'
                    },

                }).on('apply.daterangepicker', function(ev, picker) {
                    @this.set('start_time', picker.startDate.format('HH:mm'));
                    @this.set('end_time', null); // Clear end_time when start_time is updated
                }).on('show.daterangepicker', function(ev, picker) {
                    picker.container.find(".calendar-table").hide(); // Hide calendar (date picker)
                });

                // @this.set('start_time', null);
                @this.set('end_time', null);               

                $('input[id="end_date"]').daterangepicker({
                    autoApply: true,
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    minDate: start.toDate(),
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                }, function(start, end, label) {
                    @this.set('end_date', start.format('DD-MM-YYYY'));
                });
            });
            
            // Initialize start_time picker
            let startTime = @this.start_time; // Get start_time value from Livewire
            let timeNow = moment().startOf('hour').minute(moment().minute()); // Default to current tim
            
            $('#start_time').daterangepicker({
                autoApply: true,
                timePicker: true,
                timePicker24Hour: true,
                singleDatePicker: true,
                autoUpdateInput: false,
                startDate: startTime ? moment(startTime, 'HH:mm') : timeNow, // Use start_time from Livewire or default
                locale: {
                    format: 'HH:mm'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                @this.set('start_time', picker.startDate.format('HH:mm'));
                @this.set('end_time', null); // Clear end_time when start_time is updated
            }).on('show.daterangepicker', function(ev, picker) {
                picker.container.find(".calendar-table").hide(); // Hide calendar (date picker)
            });

            // Initialize end_time picker
            let endTime = @this.end_time; // Get end_time value from Livewire

            $('#end_time').daterangepicker({
                autoApply: true,
                timePicker: true,
                timePicker24Hour: true,
                singleDatePicker: true,
                autoUpdateInput: false,
                startDate: endTime ? moment(endTime, 'HH:mm') : timeNow.add(1, 'hour'), // Use end_time from Livewire or default
                locale: {
                    format: 'HH:mm'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                @this.set('end_time', picker.startDate.format('HH:mm'));
            }).on('show.daterangepicker', function(ev, picker) {
                picker.container.find(".calendar-table").hide(); // Hide calendar (date picker)
            });

    
            $('input[id="end_date"]').daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                minDate: new Date(),
                locale: {
                    format: 'DD-MM-YYYY'
                },
            },
            function(start, end, label) {
                @this.set('end_date', start.format('DD-MM-YYYY'));
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
    
        $(document).on('click', '.deleteBtn', function(e){
            var _this = $(this);
            var id = _this.attr('data-id');
           
            Swal.fire({
                title: 'Are you sure you want to delete it?',
                showDenyButton: true,
                icon: 'warning',
                confirmButtonText: 'Yes, Delete',
                denyButtonText: `No, cancel!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.emit('deleteConfirm', id);
                }
            })
        });

 


      
    
    </script>
@endpush
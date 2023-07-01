<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    @if($viewMode)

                        @livewire('admin.search-log.show', ['search_log_id' => $search_log_id])

                    @else
                        <div wire:loading wire:target="{{ $updateMode ? 'edit' : 'create' }}" class="loader"></div>
                        <div class="card-title">
                            <h4 class="float-left">{{__('cruds.search_log.title')}} {{ __('global.list') }}</h4>                            
                        </div>                
                        <div class="table-responsive">
                        @livewire('admin.search-log.search-log-datatable') 
                        
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


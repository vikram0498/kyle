<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        {{ (__('cruds.buyer.fields.buyer_csv_import'))}}
                    </h4>
                    <form wire:submit.prevent="importBuyers" class="forms-sample">                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group mb-0" wire:ignore>
                                    <label class="font-weight-bold">{{ __('cruds.buyer.fields.csv_file')}}</label>
                                    <input type="file"  wire:model.defer="state.csv_file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                </div>
                                @if($errors->has('csv_file'))<span class="error text-danger">{{ $errors->first('csv_file') }}</span>@endif
                            </div>
                        </div>
                    
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary mr-2">
                            {{ __('cruds.buyer.fields.import_buyers') }}
                            <span wire:loading wire:target="importBuyers">
                                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            </span>
                        </button>
                        <a href="{{route('admin.buyer')}}" class="btn btn-secondary">
                            {{ __('global.back')}}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
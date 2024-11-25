<h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.adBanner.title_singular'))}}</h4>

<form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.advertiser_name')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="advertiser_name" placeholder="{{ __('cruds.adBanner.fields.advertiser_name')}}">
                @error('advertiser_name') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>       

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.ad_name')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="ad_name" placeholder="{{ __('cruds.adBanner.fields.ad_name')}}">
                @error('ad_name') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.page_type')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="page_type" placeholder="{{ __('cruds.adBanner.fields.page_type')}}" readonly>
                @error('page_type') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.target_url')}} <span class="text-danger">*</span></label>
                <input type="url" class="form-control" wire:model.defer="target_url" placeholder="{{ __('cruds.adBanner.fields.target_url')}}">
                @error('target_url') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.impressions_purchased')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="impressions_purchased" placeholder="{{ __('cruds.adBanner.fields.impressions_purchased')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" step=".01" autocomplete="off">
                @error('impressions_purchased') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>    

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.start_date')}}<span class="text-danger">*</span></label>
                <input type="text" id="start_date" class="form-control" wire:model.defer="start_date" placeholder="{{ __('cruds.adBanner.fields.start_date')}}" autocomplete="off">
                @error('start_date') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold justify-content-start">{{ __('cruds.adBanner.fields.start_time')}}<span class="text-danger">*</span></label>
                <input type="text" id="start_time" class="form-control" wire:model.defer="start_time" placeholder="{{ __('cruds.adBanner.fields.start_time')}}" autocomplete="off" readonly="true">
                @error('start_time') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.end_date')}}<span class="text-danger">*</span></label>
                <input type="text" id="end_date" class="form-control" wire:model.defer="end_date" placeholder="{{ __('cruds.adBanner.fields.end_date')}}" autocomplete="off">
                @error('end_date') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold justify-content-start">{{ __('cruds.adBanner.fields.end_time')}}<span class="text-danger">*</span></label>
                <input type="text" id="end_time" class="form-control" wire:model.defer="end_time" placeholder="{{ __('cruds.adBanner.fields.end_time')}}" autocomplete="off" readonly="true">
                @error('end_time') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('global.status') }}</label>
                <select class="form-control" wire:model.defer="status">
                    @foreach (config('constants.ad_banner_status') as $key => $value)
                        <option value="{{ $key }}">{{ ucwords($value) }}</option>
                    @endforeach
                </select>
                @error('status') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="form-group mb-0" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.adBanner.fields.image')}} <span class="text-danger">*</span></label>
                <input type="file"  wire:model.defer="image" class="dropify" data-default-file="{{ $originalImage }}"  data-show-loader="true" data-errors-position="outside" data-allowed-file-extensions="jpeg png jpg svg" data-min-file-size-preview="1M" data-max-file-size-preview="3M" accept="image/jpeg, image/png, image/jpg,image/svg" id="dropify-image" />
            </div>
            @if($errors->has('image'))
            <span class="error text-danger">
                {{ $errors->first('image') }}
            </span>
            @endif
        </div>

        
    </div>
       
    <div class="d-flex justify-content-between"> 
        <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue  mr-2">
            {{ $updateMode ? __('global.update') : __('global.submit') }}
            <span wire:loading wire:target="{{ $updateMode ? 'update' : 'store' }}">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</form>
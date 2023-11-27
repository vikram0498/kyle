
<h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.buyer_plan.title_singular'))}}</h4>

<div class="alert alert-warning alert-dismissible warning-alert" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i><b>Buyer Plan can't be edited. Please recheck before submitting the details.</b>
</div>
<form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.title')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="title" placeholder="{{ __('cruds.buyer_plan.fields.title')}}">
                @error('title') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.amount')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="amount" placeholder="{{ __('cruds.buyer_plan.fields.amount')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" step=".01" autocomplete="off">
                @error('amount') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.type')}} <span class="text-danger">*</span></label>
                <select class="form-control" wire:model.defer="type">
                    <option>Select Type</option>
                    <option value="monthly" {{ $type =='monthly' ? 'selected' : ''}}>Monthly</option>
                    <option value="yearly" {{ $type =='yearly' ? 'selected' : ''}}>Yearly</option>
                </select>
                @error('type') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.position')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="position" placeholder="{{ __('cruds.buyer_plan.fields.position')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" autocomplete="off">
                @error('position') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group mb-0" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.description')}} <span class="text-danger">*</span></label>
                <textarea class="form-control" id="summernote" wire:model.defer="description" rows="4"></textarea>
            </div>
            @error('description') <span class="error text-danger">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group mb-0" wire:ignore>
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.image')}} <span class="text-danger">*</span></label>
                <input type="file"  wire:model.defer="image" class="dropify" data-default-file="{{ $originalImage }}"  data-show-loader="true" data-errors-position="outside" data-allowed-file-extensions="svg" data-min-file-size-preview="1M" data-max-file-size-preview="3M" accept="image/svg" id="dropify-image" />
            </div>
            @if($errors->has('image'))
            <span class="error text-danger">
                {{ $errors->first('image') }}
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

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.buyer_plan.fields.user_limit')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="user_limit" placeholder="{{ __('cruds.buyer_plan.fields.user_limit')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" min="0" step=".01" autocomplete="off">
                @error('user_limit') <span class="error text-danger">{{ $message }}</span>@enderror
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
        <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
            {{ __('global.back')}}
            <span wire:loading wire:target="cancel">
                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</form>

<h4 class="card-title">
    {{ $updateMode ? __('global.edit') : __('global.create') }} 
    {{ strtolower(__('cruds.addon.title_singular'))}}</h4>


<form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" class="forms-sample">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.addon.fields.title')}} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" wire:model.defer="title" placeholder="{{ __('cruds.addon.fields.title')}}">
                @error('title') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.addon.fields.price')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="price" placeholder="{{ __('cruds.addon.fields.price')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" step=".01" autocomplete="off">
                @error('price') <span class="error text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ __('cruds.addon.fields.credit')}} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" wire:model.defer="credit" placeholder="{{ __('cruds.addon.fields.credit')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" autocomplete="off">
                @error('credit') <span class="error text-danger">{{ $message }}</span>@enderror
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
        <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue mr-2">
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
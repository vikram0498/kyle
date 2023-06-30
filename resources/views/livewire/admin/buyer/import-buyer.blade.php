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
                                    <div class='file-input'>
                                      <input type="file"  wire:model.defer="state.csv_file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                      <span class='button'>Choose</span>
                                      <span class='label' data-js-label>No file selected</label>
                                    </div>
                                </div>
                                @if($errors->has('csv_file'))<span class="error text-danger">{{ $errors->first('csv_file') }}</span>@endif
                            </div>
                        </div>
                    
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-fill btn-blue mr-2">
                            {{ __('cruds.buyer.fields.import_buyers') }}
                            <span wire:loading wire:target="importBuyers">
                                <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            </span>
                        </button>
                        <a href="{{route('admin.buyer')}}" class="btn btn-fill btn-light">
                            {{ __('global.back')}}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var inputs = document.querySelectorAll('.file-input')

    for (var i = 0, len = inputs.length; i < len; i++) {
      customInput(inputs[i])
    }

    function customInput (el) {
      const fileInput = el.querySelector('[type="file"]')
      const label = el.querySelector('[data-js-label]')
      
      fileInput.onchange =
      fileInput.onmouseout = function () {
        if (!fileInput.value) return
        
        var value = fileInput.value.replace(/^.*[\\\/]/, '')
        el.className += ' -chosen'
        label.innerText = value
      }
    }
</script>
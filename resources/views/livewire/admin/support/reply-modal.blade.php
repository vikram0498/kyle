<div>
        <h4 class="card-title">{{ $isSent ? 'Replied to' :'Reply to' }} {{ ucwords($name) ?? ''}}</h4>
        <p class="card-description">
            <p>Email: {{ $email ?? ''}}</p>
        </p>

    <div class="mt-4">        
        <label class="font-weight-bold">Message</label> @if(!$isSent) <span class="text-danger">*</span>@endif
        @if($isSent)
            <div>
                <p>{!! $reply_message !!}</p>
            </div>
            <div class="text-right mt-2">
                <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
                    {{ __('global.back')}}
                    <span wire:loading wire:target="cancel">
                        <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                    </span>
                </button>
            </div>
        @else
             
            <div class="form-group mb-0" wire:ignore>
                <textarea id="summernote" cols="30" rows="10" wire:model.defer="reply_message"  class="form-control" placeholder="Reply Message"></textarea>
            </div>
            @error('reply_message') <span class="error text-danger">{{ $message }}</span>@enderror
            
            <div class="text-right mt-2">
                <button wire:click.prevent="cancel" class="btn btn-fill btn-dark">
                    {{ __('global.back')}}
                    <span wire:loading wire:target="cancel">
                        <i class="fa fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                    </span>
                </button>
                <button type="button" wire:click.prevent="storeReply(true)"  wire:loading.attr="disabled" class="btn btn-fill btn-secondary draft-btn">
                    Draft
                </button>
                
                <button type="button"  wire:click.prevent="storeReply(false)" wire:loading.attr="disabled" class="btn btn-fill btn-success send-reply-btn">
                    Send
                </button>
            </div>
            
        @endif         
    </div>
</div>

<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                @if($viewMode)

                    @livewire('admin.support.show', ['support_id' => $support_id])
                
                @elseif($replyView)

                    @livewire('admin.support.reply-modal', ['support_id' => $support_id])

                @else
                    <div wire:loading wire:target="create" class="loader"></div>
                    <div class="card-title top-box-set">
                        <h4 class="card-title-heading">{{__('cruds.support.title')}} {{ __('global.list') }}</h4>
                    </div>
                    <div class="table-responsive search-table-data">
                        @livewire('admin.support.support-datatable') 
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>

    {{-- Start Reply Modal --}}
   {{-- <div  wire:ignore.self class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                @if($isSent)
                    <div class="modal-body">
                        <p>{!! $reply_message !!}</p>
                    </div>
                @else
                    <form wire:submit.prevent="storeReply" class="forms-sample" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group" wire:ignore>
                                <textarea id="summernote" cols="30" rows="10" wire:model.defer="reply_message"  class="form-control" placeholder="Reply Message"></textarea>
                            </div>
                            @error('reply_message') <span class="error text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="modal-footer">
                            <button type="submit"  wire:loading.attr="disabled" class="btn btn-secondary draft-btn">
                                Draft
                            </button>
                            
                            <button type="submit"  wire:loading.attr="disabled" class="btn btn-sm btn-success send-reply-btn">
                                Send
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>--}}
    {{-- End Reply Modal --}}

</div>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script type="text/javascript">
    document.addEventListener('loadTextEditorPlugin', function (event) {
        $('textarea#summernote').summernote({
            placeholder: 'Type something...',
            tabsize: 2,
            height: 200,
            toolbar: [
                // ['style', ['style']],
                ['font', ['bold', 'underline', /*'clear'*/]],
                // ['fontname', ['fontname']],
                // ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['view', [/*'fullscreen','codeview', 'help'*/]],
            ],
            callbacks: {
                onChange: function(content) {
                    // Update the Livewire property when the Summernote content changes
                    @this.emit('setReplyMessage', content);
                }
            }
        });

    });
</script>
@endpush

<div>
    @if(!isset($events) || (isset($events) && in_array('show', $events)))
        <button type="button" wire:click="$emitUp('show', {{$id}})" class="btn btn-primary btn-rounded btn-icon">
            <i class="ti-eye"></i>
        </button>
    @endif
    @if(!isset($events) || (isset($events) && in_array('edit', $events)))
        <button type="button" wire:click="$emitUp('edit', {{$id}})" class="btn btn-info btn-rounded btn-icon">
            <i class="ti-pencil-alt"></i>
        </button>
    @endif
    @if(!isset($events) || (isset($events) && in_array('delete', $events)))
        <button type="button" data-id="{{$id}}" class="btn btn-danger btn-rounded btn-icon deleteBtn">
            <i class="ti-trash"></i>
        </button>
    @endif
</div>

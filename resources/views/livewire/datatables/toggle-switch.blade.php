<div>
    <label class="toggle-switch">
        <input type="checkbox" class="toggleSwitch toggleSwitchMain" data-type="status"  data-id="{{$id}}"  {{ $status == 1 ? 'checked' : '' }}>
        <span class="switch-slider" data-on="{{$onLable}}" data-off="{{$offLable}}"></span>
    </label>
</div>
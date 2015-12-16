<div class="field">
    <div class="ui checkbox">
        <input type='hidden' value="0" name='{{$moduleSettingName}}'>
        <input type="checkbox" name="{{$moduleSettingName}}" value="1"
               @if($settings['setting']) checked @endif
               >
        <label for="{{$moduleSettingName}}">{{trans($settings['title'])}}</label>
    </div>
</div>
<script>$('.ui.checkbox').checkbox();</script>

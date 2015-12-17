<div class="field">
    <div class="ui checkbox">
        <input type='hidden' value="0" name='{{$moduleSettingName}}'>
        <input type="checkbox" name="{{$moduleSettingName}}" value="1"

               @if(Setting::get($moduleSettingName)) checked @endif
               >
        <label for="{{$moduleSettingName}}">{{trans($settings['title'])}}</label>
    </div>
    @if(isset($settings['description']))
        <div class="ui label">
            {{ trans($settings['description'])}}
        </div>
    @endif
</div>
<script>$('.ui.checkbox').checkbox();</script>

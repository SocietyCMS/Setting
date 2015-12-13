<div class="field">
    <div class="ui checkbox">
        <input type='hidden' value='false' name='{{$moduleSettingName}}'>
        <input type="checkbox" name="{{$moduleSettingName}}" value="true"
               @if($settings['setting'] == "true") checked @endif
               >
        <label for="{{$moduleSettingName}}">{{trans($settings['title'])}}</label>
    </div>
</div>
<script>$('.ui.checkbox').checkbox();</script>

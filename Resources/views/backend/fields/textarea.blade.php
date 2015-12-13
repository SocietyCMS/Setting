<div class="field">
    <label for="{{$moduleSettingName}}">
        {{trans($settings['title'])}}
    </label>
    <textarea name="{{$moduleSettingName}}"
              placeholder="{{ trans($settings['description'])}}">{{ old($moduleSettingName, trans($settings['setting']) )}}</textarea>
</div>

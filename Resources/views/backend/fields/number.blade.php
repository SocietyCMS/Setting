<div class="field">
    <label for="{{$moduleSettingName}}">
        {{trans($settings['title'])}}
    </label>

    <input type="number"
           name="{{$moduleSettingName}}"
           placeholder="{{ trans($settings['description'])}}"
           value="{{ old($moduleSettingName, trans($settings['setting'])) }}"
    >
</div>

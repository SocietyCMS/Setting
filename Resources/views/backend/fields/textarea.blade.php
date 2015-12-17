<div class="field">
    <label for="{{$moduleSettingName}}">
        {{trans($settings['title'])}}
    </label>
    <textarea name="{{$moduleSettingName}}"
              >{{ old($moduleSettingName, trans(Setting::get($moduleSettingName)) )}}</textarea>

    @if(isset($settings['description']))
        <div class="ui label">
            {{ trans($settings['description'])}}
        </div>
    @endif
</div>

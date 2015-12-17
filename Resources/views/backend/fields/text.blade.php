<div class="field">

    <label for="{{$moduleSettingName}}">
        {{trans($settings['title'])}}
    </label>

    <input type="text"
           name="{{$moduleSettingName}}"
           value="{{ old($moduleSettingName, trans(Setting::get($moduleSettingName))) }}"
    >
    @if(isset($settings['description']))
        <div class="ui label">
            {{ trans($settings['description'])}}
        </div>
    @endif
</div>

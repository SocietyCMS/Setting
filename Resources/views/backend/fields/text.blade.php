<div class="field">

    <label for="{{$moduleSettingName}}">
        {{trans($settings['title'])}}
    </label>

    <input type="text"
           name="{{$moduleSettingName}}"
           placeholder="{{ trans($settings['description'])}}"
           value="{{ old($moduleSettingName, trans($settings['setting'])) }}"
    >
    @if($settings['description'])
        <div class="ui label">
            {{ trans($settings['description'])}}
        </div>
    @endif
</div>

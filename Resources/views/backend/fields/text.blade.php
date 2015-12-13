<div class="field">
    <label for="{{$settingName}}">
        {{trans($settings['title'])}}
    </label>

    <input type="text"
           name="{{$settingName}}"
           placeholder="{{ trans($settings['description'])}}"
           value="{{ old($settingName, trans($settings['setting'])) }}"
    >
</div>

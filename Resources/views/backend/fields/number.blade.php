<div class="field">
    <label for="{{$settingName}}">
        {{trans($settings['title'])}}
    </label>

    <input type="number"
           name="{{$settingName}}"
           placeholder="{{ trans($settings['description'])}}"
           value="{{ old($settingName, trans($settings['setting'])) }}"
    >
</div>

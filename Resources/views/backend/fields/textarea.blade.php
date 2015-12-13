<div class="field">
    <label for="{{$settingName}}">
        {{trans($settings['title'])}}
    </label>
    <textarea name="{{$settingName}}"
              placeholder="{{ trans($settings['description'])}}">{{ old($settingName, trans($settings['setting']) )}}</textarea>
</div>

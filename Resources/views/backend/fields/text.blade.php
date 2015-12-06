<div class="field">
    <label for="{{$settingName}}">
        {{trans($moduleInfo['description'])}}
    </label>

    <input type="text"
           name="{{$settingName}}"
           placeholder="{{ trans($moduleInfo['description'])}}"
           value="{{ old($settingName, isset($dbSettings[$settingName])?$dbSettings[$settingName]->value:null) }}"
    >
</div>
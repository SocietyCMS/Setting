<div class="field">
    <label for="{{$settingName}}">
        {{trans($moduleInfo['description'])}}
    </label>

    <textarea name="{{$settingName}}"
              placeholder="{{ trans($moduleInfo['description'])}}">{{ old($settingName, isset($dbSettings[$settingName])?$dbSettings[$settingName]->value:null) }}</textarea>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="{{$settingName}}"
                {{ isset($dbSettings[$settingName]) && (bool)$dbSettings[$settingName]->value == true ? 'checked' : '' }}>
        <label for="{{$settingName}}">{{trans($moduleInfo['description'])}}</label>
    </div>
</div>

{{dd($moduleInfo)}}

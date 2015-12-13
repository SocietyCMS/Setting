<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="{{$settingName}}"
               @if($settings['setting']) checked @endif
               >
        <label for="{{$settingName}}">{{trans($settings['title'])}}</label>
    </div>
</div>

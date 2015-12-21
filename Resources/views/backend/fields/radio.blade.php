<div class="grouped fields">
    <label>{{trans($settings['title'])}}</label>
    @foreach ($settings['options'] as $value => $optionName)
    <div class="field">
        <div class="ui radio checkbox">
            <input type="radio" name="{{$moduleSettingName}}" value="{{$value}}" @if(Setting::get($moduleSettingName) == $value) checked @endif>
            <label>{{ trans($optionName) }}</label>
        </div>
    </div>
    @endforeach

    @if(isset($settings['description']))
        <div class="ui label">
            {{ trans($settings['description'])}}
        </div>
    @endif

</div>
<script>$('.ui.checkbox').checkbox();</script>

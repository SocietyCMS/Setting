@foreach ($currentModuleSettings as $settingName => $settings)

    <?php $fieldView = str_contains($settings['view'], '::') ? $settings['view'] : "setting::backend.fields.{$settings['view']}" ?>

    @include($fieldView, [
        'settingName' => $settingName,
        'settings' => $settings,
        'moduleSettingName' => strtolower($currentModule) . '::' . $settingName
    ])

@endforeach

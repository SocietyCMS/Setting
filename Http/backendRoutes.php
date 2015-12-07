<?php

$router->bind('module', function ($module) {
    return app(\Pingpong\Modules\Repository::class)->find($module);
});

$router->group(['prefix' => '/setting'], function () {
    get('settings', ['as' => 'backend::setting.settings.index', 'uses' => 'SettingController@index']);
    get('settings/{module}', ['as' => 'backend::setting.settings.edit', 'uses' => 'SettingController@edit']);
    post('settings', ['as' => 'backend::setting.settings.store', 'uses' => 'SettingController@store']);
});

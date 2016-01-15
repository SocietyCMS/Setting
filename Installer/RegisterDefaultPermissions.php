<?php

namespace Modules\Setting\Installer;

class RegisterDefaultPermissions
{

    public $defaultPermissions = [

        'manage-setting' => [
            'display_name' => 'setting::module-permissions.manage-setting.display_name',
            'description'  => 'setting::module-permissions.manage-setting.description',
        ],

    ];
}
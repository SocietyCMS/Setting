<?php

namespace Modules\Setting\Repositories;

use Illuminate\Support\Arr;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

/**
 * Class SettingRepository
 * @package Modules\Setting\Repositories
 */
class SettingRepository extends EloquentBaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Setting\\Entities\\Setting';
    }

    protected $settingBlueprint = [
        'title'       => '',
        'description' => '',
        'type'        => 'string',
        'view'        => 'text',
        'default'     => '',
        'setting'     => '',
    ];

    /**
     * Create or update the settings.
     *
     * @param $settings
     *
     * @return mixed|void
     */
    public function createOrUpdate($settings)
    {
        $this->removeTokenKey($settings);

        foreach ($settings as $settingName => $settingValues) {

            if ($setting = $this->findByName($settingName)) {
                $this->updateSetting($setting, $settingValues);
                continue;
            }
            $this->createForName($settingName, $settingValues);
        }
    }

    /**
     * Remove the token from the input array.
     *
     * @param $settings
     */
    private function removeTokenKey(&$settings)
    {
        unset($settings['_token']);
    }

    /**
     * Find a setting by its name.
     *
     * @param $settingName
     *
     * @return mixed
     */
    public function findByName($settingName)
    {
        return $this->model->where('name', $settingName)->first();
    }

    /**
     * Create a setting with the given name.
     *
     * @param string $settingName
     * @param        $settingValues
     */
    private function createForName($settingName, $settingValues)
    {
        $setting = new $this->model();
        $setting->name = $settingName;

        $setting->value = $this->getSettingValue($settingName, $settingValues);

        return $setting->save();
    }

    /**
     * Update the given setting.
     *
     * @param object setting
     * @param $settingValues
     */
    private function updateSetting($setting, $settingValues)
    {
        $name = $setting->name;

        $setting->value = $this->getSettingValue($name, $settingValues);

        return $setting->save();
    }

    /**
     * Return all modules that have settings
     * with its settings.
     *
     * @param array|string $modules
     *
     * @return array
     */
    public function moduleConfig($modules)
    {
        if (is_string($modules)) {
            $config = config('society.' . strtolower($modules) . '.settings');

            return $config;
        }

        $config = [];
        foreach ($modules as $module) {
            if ($moduleSettings = config('society.' . strtolower($module->getName()) . '.settings')) {
                $config[$module->getName()] = $moduleSettings;
            }
        }

        return $config;
    }

    /**
     * @param $module
     * @return mixed
     */
    public function moduleSettings($module)
    {
        $settings = [];

        $settingsConfig = $this->moduleConfig($module);

        foreach ($settingsConfig as $sectionTitle => $section) {

            $settings[$sectionTitle] = [];

            foreach ($section as $name => $options) {

                $setting = $this->settingBlueprint;

                if (isset($options['title'])) {
                    $setting['title'] = $options['title'];
                }

                if (isset($options['description'])) {
                    $setting['description'] = $options['description'];
                }

                if (isset($options['view'])) {
                    $setting['view'] = $options['view'];
                }


                if (isset($options['default'])) {
                    $setting['default'] = $options['default'];
                    $setting['setting'] = $options['default'];
                }

                if ($dbSetting = $this->get("$module::$name")) {
                    $setting['setting'] = $dbSetting;
                }

                $settings[$sectionTitle][$name] =  $setting;
            }

        }

        return $settings;
    }

    /**
     * Return the saved module settings.
     *
     * @param $module
     *
     * @return mixed
     */
    public function savedModuleSettings($module)
    {
        $moduleSettings = [];
        foreach ($this->findByModule($module) as $setting) {
            $moduleSettings[$setting->name] = $setting;
        }

        return $moduleSettings;
    }

    /**
     * Find settings by module name.
     *
     * @param string $module Module name
     *
     * @return mixed
     */
    public function findByModule($module)
    {
        return $this->model->where('name', 'LIKE', $module . '::%')->get();
    }

    /**
     * Find the given setting name for the given module.
     *
     * @param string $settingName
     *
     * @return mixed
     */
    public function get($settingName)
    {
        return unserialize($this->model->where('name', 'LIKE', "{$settingName}")->value('value'));
    }



    /**
     * Return the setting value(s). If values are an array, json_encode them.
     *
     * @param string|array $settingValues
     *
     * @return string
     */
    private function getSettingValue($settingName, $settingValues)
    {
        $type = $this->getTypeConfigFor($settingName);

        if($type == 'boolean')
        {
            $settingValues =  (bool)$settingValues;
        }

        return serialize($settingValues);
    }


    /**
     * Get the default value from the settings configuration file,
     * for the given setting name.
     *
     * @param string $name
     *
     * @return string
     */
    private function getTypeConfigFor($name)
    {
        list($module, $settingName) = explode('::', $name);

        $result = array();
        foreach(config("society.$module.settings") as $sub) {
            $result = array_merge($result, $sub);
        }

        return Arr::get($result, "$settingName.type", 'string');
    }
}

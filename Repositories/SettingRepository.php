<?php

namespace Modules\Setting\Repositories;

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

        $setting->value = $this->getSettingValue($settingValues);

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

        $oldValues = $setting->value;
        $setting->value = $this->getSettingValue($settingValues);

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
        $settings = $this->moduleConfig($module);

        foreach ($settings as $name => $options) {

            $settings[$name]['setting'] = "";

            if (!isset($settings[$name]['description'])) {
                $settings[$name]['description'] = "";
            }

            if (isset($settings[$name]['default'])) {
                $settings[$name]['setting'] = $settings[$name]['default'];
            }

            if ($dbSetting = $this->get("$module::$name")) {
                $settings[$name]['setting'] = $dbSetting->value;
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
        return $this->model->where('name', 'LIKE', "{$settingName}")->first();
    }

    /**
     * Return the setting value(s). If values are an array, json_encode them.
     *
     * @param string|array $settingValues
     *
     * @return string
     */
    private function getSettingValue($settingValues)
    {
        if (is_array($settingValues)) {
            return json_encode($settingValues);
        }

        return $settingValues;
    }
}

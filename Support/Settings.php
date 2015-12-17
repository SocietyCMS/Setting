<?php

namespace Modules\Setting\Support;

use Illuminate\Support\Arr;
use Modules\Core\Contracts\Setting;
use Modules\Setting\Repositories\SettingRepository;
use Symfony\Component\Process\Exception\InvalidArgumentException;

/**
 * Class Settings
 * @package Modules\Setting\Support
 */
class Settings implements Setting
{
    /**
     * @var SettingRepository
     */
    private $setting;

    /**
     * @param SettingRepository $setting
     */
    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->setting->has($name);
    }

    /**
     * Getting the setting.
     *
     * @param string $name
     * @param string $locale
     * @param string $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!str_contains($name, '::')) {
            throw new InvalidArgumentException("Setting key must be in the format '[module]::[setting]', '$name' given.");
        }

        $defaultFromConfig = $this->getDefaultFromConfigFor($name);

        if ($this->setting->has($name)) {
            return $this->setting->get($name);
        }

        return is_null($default) ? $defaultFromConfig : $default;
    }

    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        if ($config = $this->getConfigFor($key)) {

            if ($type = Arr::get($config, "type")) {
                @settype($value, $type);
            }
            $this->setting->set($key, $value);
        }
    }

    /**
     * Set multiple given configuration values from a request.
     *
     * @param mixed $settings
     *
     * @return void
     */
    public function setFromRequest($settings)
    {
        $this->removeTokenKey($settings);

        foreach ($settings as $settingName => $settingValues) {
            $this->set($settingName, $settingValues);
        }
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
            $config = config('society.'.strtolower($modules).'.settings');

            return $config;
        }

        $config = [];
        foreach ($modules as $module) {
            if ($moduleSettings = config('society.'.strtolower($module->getName()).'.settings')) {
                $config[$module->getName()] = $moduleSettings;
            }
        }

        return $config;
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
     * Get the default value from the settings configuration file,
     * for the given setting name.
     *
     * @param string $name
     *
     * @return string
     */
    private function getDefaultFromConfigFor($name)
    {
        return Arr::get($this->getConfigFor($name), "default")?:'';
    }

    /**
     * Get the settings configuration file
     *
     * @param $name
     * @return mixed
     */
    private function getConfigFor($name)
    {
        list($module, $settingName) = explode('::', $name);

        $result = array();
        foreach (config("society.$module.settings") as $sub) {
            $result = array_merge($result, $sub);
        }

        return Arr::get($result, "$settingName");
    }
}

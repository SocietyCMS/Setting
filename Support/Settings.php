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
        $default = microtime(true);

        return $this->get($name, $default) !== $default;
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

        $setting = $this->setting->get($name);

        if (!$setting) {
            return is_null($default) ? $defaultFromConfig : $default;
        }

        return empty($setting) ? $defaultFromConfig : $setting;
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
        list($module, $settingName) = explode('::', $name);

        $result = array();
        foreach(config("society.$module.settings") as $sub) {
            $result = array_merge($result, $sub);
        }

        return Arr::get($result, "$settingName.default");
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
    }
}

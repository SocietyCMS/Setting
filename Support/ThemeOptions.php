<?php

namespace Modules\Setting\Support;

use FloatingPoint\Stylist\Theme\Theme;
use Illuminate\Support\Str;

class ThemeOptions
{
    /**
     * @var Theme
     */
    private $theme;
    private $options;

    /**
     * ThemeOptions constructor.
     * @param Theme $theme
     */
    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    /**
     * Get the options defined in the themes json file
     *
     * @return mixed
     */
    public function getOptions()
    {
        if ($this->options) {
            return $this->options;
        }

        return $this->options = (new \FloatingPoint\Stylist\Theme\Json($this->theme->getPath()))->getJsonAttribute('options');
    }

    public function getOption($name, $default = null)
    {
        if(!$this->getOptions()) {
            return;
        }
        if (property_exists($this->getOptions(), $name)) {
            return $this->getOptions()->$name;
        }

        $options = $this->getOptions();
        foreach (explode('.', $name) as $segment) {
            if (!property_exists($options, $segment)) {
                return $default;
            }
            $options = $options->$segment;
        }

        return $options;

    }
}
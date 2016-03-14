<?php

namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function () {
            $this->registerAllThemes();
            $this->setActiveTheme();
        });
    }

    /**
     * Register all themes with activating them.
     */
    private function registerAllThemes()
    {
        $directories = $this->app['files']->directories(base_path('/themes'));

        foreach ($directories as $directory) {
            $this->app['stylist']->registerPath($directory);
        }
    }

    /**
     * Set the active theme based on the settings.
     */
    private function setActiveTheme()
    {
        if ($this->inAdministration()) {
            $themeName = $this->app['config']->get('society.core.core.admin-theme');

            return $this->app['stylist']->activate($themeName, true);
        }

        return $this->app['stylist']->activate($this->app['config']->get('society.core.core.frontend-theme'), true);
    }

    /**
     * Check if we are in the administration.
     *
     * @return bool
     */
    private function inAdministration()
    {
        $segment = 1;

        return $this->app['request']->segment($segment) === $this->app['config']->get('society.core.core.admin-prefix');
    }
}

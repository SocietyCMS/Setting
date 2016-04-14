<?php

namespace Modules\Setting\Repositories;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Prettus\Repository\Events\RepositoryEntityUpdated;

/**
 * Class SettingRepository.
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
     * @param $settingName
     *
     * @return bool
     */
    public function hasWithoutCache($settingName)
    {
        return $this->model->where('name', 'LIKE', "{$settingName}")->count() > 0;
    }

    /**
     * @param $settingName
     *
     * @return bool|mixed
     */
    public function has($settingName)
    {
        if ($this->isSkippedCache()) {
            return $this->hasWithoutCache($settingName);
        }

        $key = $this->getCacheKey('has', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($settingName) {
            return $this->hasWithoutCache($settingName);
        });

        return $value;
    }

    /**
     * @param $settingName
     *
     * @return mixed
     */
    public function getWithoutCache($settingName)
    {
        return unserialize($this->model->where('name', 'LIKE', "{$settingName}")->value('value'));
    }

    /**
     * @param $settingName
     *
     * @return mixed
     */
    public function get($settingName)
    {
        if ($this->isSkippedCache()) {
            return $this->getWithoutCache($settingName);
        }

        $key = $this->getCacheKey('get', func_get_args());
        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($settingName) {
            return $this->getWithoutCache($settingName);
        });

        return $value;
    }

    /**
     * @param $settingName
     * @param $value
     */
    public function set($settingName, $value)
    {
        $model = $this->model->firstOrCreate([
            'name' => $settingName,
        ]);
        $model->update(['value' => serialize($value)]);

        event(new RepositoryEntityUpdated($this, $model));
    }
}

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

    /**
     * @param $settingName
     * @return bool
     */
    public function has($settingName)
    {
        return $this->model->where('name', 'LIKE', "{$settingName}")->count() > 0;
    }

    /**
     * @param $settingName
     * @return mixed
     */
    public function get($settingName)
    {
        return unserialize($this->model->where('name', 'LIKE', "{$settingName}")->value('value'));
    }

    /**
     * @param $settingName
     * @param $value
     */
    public function set($settingName, $value)
    {
        $this->model->firstOrCreate([
            'name' => $settingName
        ])->update(['value' => serialize($value)]);
    }

}

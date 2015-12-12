<?php

namespace Modules\Setting\Http\Controllers\backend;

use Illuminate\Session\Store;
use Modules\Core\Http\Controllers\AdminBaseController;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;
use Pingpong\Modules\Module;

class SettingController extends AdminBaseController
{
    /**
     * @var SettingRepository
     */
    private $setting;
    /**
     * @var Module
     */
    private $module;

    public function __construct(SettingRepository $setting, Store $session)
    {
        parent::__construct();
        $this->setting = $setting;
        $this->module = app('modules');
        $this->session = $session;
    }

    public function index()
    {
        return redirect()->route('backend::setting.settings.edit', ['core']);
    }

    public function edit(Module $currentModule)
    {
        $currentModuleSettings = $this->setting->moduleSettings($currentModule->getLowerName());
        $modules = $this->setting->moduleConfig($this->module->enabled());

        return view('setting::backend.settings.module-settings', compact('currentModule','currentModuleSettings', 'modules'));
    }

    public function store(SettingRequest $request)
    {
        $this->setting->createOrUpdate($request->request->all());

        flash(trans('setting::messages.settings saved'));

        return redirect()->route('backend::setting.settings.edit', [$this->session->get('module', 'Core')]);
    }
}

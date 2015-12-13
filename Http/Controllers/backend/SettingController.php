<?php

namespace Modules\Setting\Http\Controllers\backend;

use Illuminate\Session\Store;
use Modules\Core\Http\Controllers\AdminBaseController;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;
use Pingpong\Modules\Module;

/**
 * Class SettingController
 * @package Modules\Setting\Http\Controllers\backend
 */
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

    /**
     * SettingController constructor.
     * @param SettingRepository $setting
     * @param Store             $session
     */
    public function __construct(SettingRepository $setting, Store $session)
    {
        parent::__construct();
        $this->setting = $setting;
        $this->module = app('modules');
        $this->session = $session;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('backend::setting.settings.edit', ['core']);
    }

    /**
     * @param Module $currentModule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Module $currentModule)
    {
        $currentModuleSettings = $this->setting->moduleSettings($currentModule->getLowerName());
        $modules = $this->setting->moduleConfig($this->module->enabled());

        return view('setting::backend.settings.module-settings', compact('currentModule','currentModuleSettings', 'modules'));
    }

    /**
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingRequest $request)
    {
        $this->setting->createOrUpdate($request->request->all());

        flash(trans('setting::messages.settings saved'));

        return redirect()->back();
    }
}

<?php

namespace Modules\Setting\Http\Controllers\backend;

use Modules\Core\Http\Controllers\AdminBaseController;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repositories\SettingRepository;
use Modules\Setting\Support\Settings;
use Pingpong\Modules\Module;

/**
 * Class SettingController.
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
     *
     * @param Settings $setting
     */
    public function __construct(Settings $setting)
    {
        parent::__construct();
        $this->setting = $setting;
        $this->module = app('modules');
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
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Module $currentModule)
    {
        $currentModuleSettings = $this->setting->moduleConfig($currentModule->getLowerName());
        $modules = $this->setting->moduleConfig($this->module->enabled());

        return view('setting::backend.settings.module-settings',
            compact('currentModule', 'currentModuleSettings', 'modules'));
    }

    /**
     * @param SettingRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingRequest $request)
    {
        $this->setting->setFromRequest($request->request->all());

        flash(trans('core::messages.resource.resource saved',['name' => trans('setting::setting.title.settings')]));

        return redirect()->back();
    }
}

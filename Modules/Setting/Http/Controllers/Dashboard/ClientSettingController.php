<?php

namespace Modules\Setting\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Repositories\Dashboard\SettingRepository as Setting;

class ClientSettingController extends Controller
{
    protected $setting;

    function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function index()
    {
        return view('setting::dashboard.client.index');
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->setting->set($request, 'client');
            DB::commit();
            return redirect()->back()->with(['msg' => __('setting::dashboard.settings.form.messages.settings_updated_successfully'), 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

}

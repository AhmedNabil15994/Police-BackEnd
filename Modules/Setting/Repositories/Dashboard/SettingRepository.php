<?php

namespace Modules\Setting\Repositories\Dashboard;

use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Modules\Order\Entities\Order;
use Setting;

class SettingRepository
{
    function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }

    public function set($request, $settingType = '')
    {
        $this->saveSettings($request->except('_token', '_method'), $settingType);
        return true;
    }

    public function saveSettings($request, $settingType)
    {
        foreach ($request as $key => $value) {
            if ($settingType == 'client') {
                if ($key == 'other') {
                    Setting::set('other.privacy_policy', $value['privacy_policy']);
                    Setting::set('other.terms', $value['terms']);
                    Setting::set('other.about_us', $value['about_us']);
                    Setting::set('other.webhook_url', $value['webhook_url']);
                    Setting::set('other.webhook_token', $value['webhook_token']);
                }

                if ($key == 'products')
                    Setting::set('products.minimum_products_qty', $value['minimum_products_qty']);

                if ($key == 'social' || $key == 'about_app')
                    Setting::set($key, $value);

                if ($key == 'contact_us') {
                    Setting::set('contact_us.whatsapp', $value['whatsapp']);
                    Setting::set('contact_us.mobile', $value['mobile']);
                    Setting::set('contact_us.technical_support', $value['technical_support']);
                }

                if ($key == 'images')
                    static::setImagesPath($value);
            } else {
                if ($key == 'translate')
                    static::setTranslatableSettings($value);

                if ($key == 'images')
                    static::setImagesPath($value);

                if ($key == 'env')
                    static::setEnv($value);

                Setting::set($key, $value);
            }

        }
    }

    public static function setTranslatableSettings($settings = [])
    {
        foreach ($settings as $key => $value) {
            Setting::lang(locale())->set($key, $value);
        }
    }

    public static function setImagesPath($settings = [])
    {
        foreach ($settings as $key => $value) {
            Setting::set($key, path_without_domain($value));
        }
    }

    public static function setEnv($settings = [])
    {
        foreach ($settings as $key => $value) {
            $file = DotenvEditor::setKey($key, $value, '', false);
        }

        $file = DotenvEditor::save();
    }
}

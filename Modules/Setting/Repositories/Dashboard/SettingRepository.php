<?php

namespace Modules\Setting\Repositories\Dashboard;

use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Modules\Core\Traits\Attachment\Attachment;
use Illuminate\Support\Facades\Cache;
use Setting;

class SettingRepository
{
    use Attachment;

    function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }

    public function set($request)
    {
        $tenantSubdomain = app('currentTenant')->subdomain;
        $this->saveSettings($request->except('_token', '_method'));

        Cache::forget("{$tenantSubdomain}_front_supported_currencies");
        Cache::forget("{$tenantSubdomain}_front_supported_countries");
        Cache::forget("{$tenantSubdomain}_front_supported_countries_with_cities");
        return true;
    }

    public function saveSettings($request)
    {
        foreach ($request as $key => $value) {

            if ($key == 'translate')
                  static::setTranslatableSettings($value);

            if ($key == 'images')
                  static::setImagesPath($value);

            // if ($key == 'env')
            //       static::setEnv($value);

            Setting::set($key, $value);
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
            $path = '/'.self::updateAttachment($value , Setting::get($key),'settings');
            Setting::set($key, $path);
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

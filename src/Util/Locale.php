<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 2:21 PM
 */

namespace Lattesoft\ApiMicroserviceCore\Util;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Locale
{
    public static function setLocale(Request $request)
    {
        if ($request->input('lang') != null) {
            $lang = $request->input('lang');
            if (in_array($lang, ["th", "en"])) {
                App::setLocale($lang);
            }
        }
        App::setLocale('th');
    }

    public static function setLocaleFromEntity($lang)
    {
//        if (in_array($lang, ["th", "en"])) {
//            App::setLocale($lang);
//        }
//        App::setLocale('th');
    }

    public static function getLocale()
    {
        return App::getLocale();
    }

}
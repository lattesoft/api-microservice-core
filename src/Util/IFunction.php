<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/25/2018
 * Time: 12:44 AM
 */

namespace Lattesoft\ApiMicroserviceCore\Util;

class IFunction
{
    public static function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function getMinuteDiff($dateStart, $dateEnd)
    {
        $date1 = strtotime($dateStart);
        $date2 = strtotime($dateEnd);
        $diff = ($date2 - $date1);
        return round($diff / 60);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 4:33 AM
 */

namespace Lattesoft\ApiMicroserviceCore\Response;

use Exception;
use Lattesoft\ApiMicroserviceCore\Util\IPagination;

class IResponse
{
    public static function responseValidator($errors)
    {
        return [
            'status' => 4001001,
            'errors' => $errors
        ];
    }

    public static function responseException(Exception $e)
    {
        return [
            'status' => 5031001,
            'errors' => [
                'messages' => [$e->getMessage()]
            ]
        ];
    }

    public static function responseError($code, $message)
    {
        return [
            'status' => $code,
            'errors' => [
                'messages' => [$message]
            ]
        ];
    }

    public static function responseService($code)
    {
        return [
            'status' => $code,
            'message' => trans('response.' . $code)
        ];
    }

    public static function responseServiceWithData($code,$data,$title = "data")
    {
        return [
            'status' => $code,
            'message' => trans('response.' . $code),
            $title => $data
        ];
    }

    public static function responseServiceWithErrors($code,$errors)
    {
        return [
            'status' => $code,
            'message' => trans('response.' . $code),
            'errors' => $errors
        ];
    }

    public static function responseServiceWithPagination($code, $items, $page = 1, $size = 15)
    {
        $items = $items->toArray();
        $items["status"] = $code;
        $items["message"] = trans('response.' . $code);

        return $items;
    }

    public static function responseStation($code)
    {
        return [
            'response' => [
                'code' => $code,
                'message' => trans('response.station.' . $code)
            ]
        ];
    }

    public static function responseMessage($code)
    {
        return trans('response.' . $code);
    }

}
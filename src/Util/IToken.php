<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/25/2018
 * Time: 3:15 PM
 */

namespace Finiz\Util;


use Firebase\JWT\JWT;
use Exception;

class IToken
{
    public static function getAuthorizationToken($request)
    {
        $token = '';
        $header = $request->header('Authorization');
        if ($header != null) {
            $authorization = explode(" ", $header);
            if (count($authorization) >= 2) {
                $token = $authorization[1];
            }
        }
        return $token;
    }

    public static function getPayloadFromAuthorizationToken($token)
    {
        try {
            $payload = JWT::decode($token, config('web.jwt.secret_key'), array('HS256'));
            return (array)$payload;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function verifyTokenExpiredByPayload($payload)
    {
        try {
            if ($payload['tt'] != null) {
                $tt = (array)$payload['tt'];
                $iat = $tt['iat'];
                $exp = $tt['exp'];
                $minuteDif = IFunction::getMinuteDiff($exp, date("Y-m-d H:i:s"));
                if ($minuteDif >= 0) {
                    return true;
                }
            }
        } catch (Exception $e) {
            return true;
        }
        return false;
    }

    public static function verifyTokenExpiredLimitByPayload($payload)
    {
        try {
            if ($payload['tt'] != null) {
                $tt = (array)$payload['tt'];
                $iat = $tt['iat'];
                $exp = $tt['exp'];
                $minuteDif = IFunction::getMinuteDiff($exp, date("Y-m-d H:i:s"));
                if ($minuteDif >= 0 && $minuteDif <= 15) {
                    return true;
                }
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 12:02 AM
 */

namespace Lattesoft\ApiMocroserviceCore\VerifyRequest;

use Lattesoft\ApiMocroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyByPassRequest
{
    public static function validateRole(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (Exception $e) {
            LineNotification::sendAlertMessage($e->getMessage());
            return response()->json(IResponse::responseException($e), 503);
        }
    }
}
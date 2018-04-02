<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 12:02 AM
 */

namespace Lattesoft\ApiMicroserviceCore\VerifyRequest;

use Lattesoft\ApiMicroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMicroserviceCore\Response\IResponse;
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
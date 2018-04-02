<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/8/2018
 * Time: 11:43 PM
 */

namespace Lattesoft\ApiMocroserviceCore\VerifyRequest;

use Lattesoft\ApiMocroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyPositionRequest {
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
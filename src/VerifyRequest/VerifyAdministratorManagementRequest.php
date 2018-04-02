<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/9/2018
 * Time: 1:26 AM
 */

namespace Lattesoft\ApiMicroserviceCore\VerifyRequest;

use Lattesoft\ApiMicroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMicroserviceCore\Response\IResponse;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyAdministratorManagementRequest
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
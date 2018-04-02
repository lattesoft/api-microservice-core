<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 2:23 AM
 */

namespace Finiz\VerifyRequest;

use Closure;
use Finiz\Notification\LineNotification;
use Finiz\Response\IResponse;
use Illuminate\Http\Request;
use Exception;

class VerifyAdminPermissionRequest
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
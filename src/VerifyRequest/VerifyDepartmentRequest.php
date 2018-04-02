<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/7/2018
 * Time: 4:02 PM
 */

namespace Finiz\VerifyRequest;

use Finiz\Notification\LineNotification;
use Finiz\Response\IResponse;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyDepartmentRequest
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
<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 4:48 AM
 */

namespace Finiz\VerifyRequest;

use Finiz\Notification\LineNotification;
use Finiz\Response\IResponse;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyMasterDataRequest
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
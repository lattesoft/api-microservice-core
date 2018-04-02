<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 2:37 PM
 */

namespace Lattesoft\ApiMocroserviceCore\VerifyRequest;

use Lattesoft\ApiMocroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;
use Lattesoft\ApiMocroserviceCore\Service\AdminProfileService;
use Lattesoft\ApiMocroserviceCore\Util\IConstant;
use Lattesoft\ApiMocroserviceCore\Util\IToken;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyAdministratorRoleRequest
{
    public static function validateRole(Request $request, Closure $next)
    {
        try {
            $token = IToken::getAuthorizationToken($request);
            $method = $request->getMethod();
            $roleVerify = [];
            if ($method == IConstant::HTTP_METHOD_GET) {
                $roleVerify = [IConstant::MENU_ROLE_EDIT, IConstant::MENU_ROLE_SHOW];
            } else if ($method == IConstant::HTTP_METHOD_PUT || $method == IConstant::HTTP_METHOD_POST || $method == IConstant::HTTP_METHOD_DELETE) {
                $roleVerify = [IConstant::MENU_ROLE_EDIT];
            }
            $result = AdminProfileService::verifyUserAdminRole($token, [IConstant::MENU_ADMINISTRATOR_ROLE], $roleVerify);
            if (substr($result['status'], 0, 3) === '200') {
                return $next($request);
            } else {
                return response()->json(IResponse::responseError($result['status'], $result['message']), substr($result['status'], 0, 3));
            }
        } catch (Exception $e) {
            LineNotification::sendAlertMessage($e->getMessage());
            return response()->json(IResponse::responseException($e), 503);
        }
    }
}
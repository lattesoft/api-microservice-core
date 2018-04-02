<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/1/2018
 * Time: 11:05 PM
 */

namespace Lattesoft\ApiMicroserviceCore\VerifyRequest;

use Lattesoft\ApiMicroserviceCore\Notification\LineNotification;
use Lattesoft\ApiMicroserviceCore\Response\IResponse;
use Lattesoft\ApiMicroserviceCore\Service\AdminProfileService;
use Lattesoft\ApiMicroserviceCore\Util\IConstant;
use Lattesoft\ApiMicroserviceCore\Util\IToken;
use Illuminate\Http\Request;
use Closure;
use Exception;

class VerifyCompanyRequest
{
    public static function validateRole(Request $request, Closure $next)
    {
        try {
            $token = IToken::getAuthorizationToken($request);
            $method = $request->getMethod();
            $roleVerify = [];
            if ($method == IConstant::HTTP_METHOD_GET) {
                $roleVerify = [IConstant::MENU_ROLE_EDIT, IConstant::MENU_ROLE_SHOW];
            } else if ($method == IConstant::HTTP_METHOD_PUT || $method == IConstant::HTTP_METHOD_POST) {
                $roleVerify = [IConstant::MENU_ROLE_EDIT];
            }
            $result = AdminProfileService::verifyUserAdminRole($token, [IConstant::MENU_COMPANY_PROFILE], $roleVerify);
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
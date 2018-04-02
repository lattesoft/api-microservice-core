<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 4:19 AM
 */

namespace Finiz\Service;

use App\Domain\Admin\AdminCompany;
use App\Domain\Admin\AdminProfile;
use App\Domain\Company\Company;
use Finiz\QDoctrine\QAdminCompany;
use Finiz\QDoctrine\QAdminProfile;
use Finiz\QDoctrine\QCompany;
use Finiz\Response\IResponse;
use Finiz\Util\IConstant;
use Finiz\Util\ICryptor;
use Finiz\Util\IFunction;
use Finiz\Util\Locale;
use Firebase\JWT\JWT;

class LoginAdminService
{
    public static function loginAdminEmail($username, $password)
    {
        $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserByEmail($username);
        if ($adminProfileEntity != null) {
            $adminPassword = $adminProfileEntity->getPassword();
            $passwordEnc = ICryptor::encrypt($password);
            $adminStatus = $adminProfileEntity->getStatus();
            if ($adminPassword == $passwordEnc) {
                if ($adminStatus == AdminProfile::ADMIN_STATUS_ACTIVATED) {
                    QAdminProfile::updateAdminProfileLoginSuccess($adminProfileEntity);
                    $adminCompanyList = QAdminCompany::getAdminCompanyListActivateByAdminId($adminProfileEntity->getId());
                    if ($adminCompanyList != null) {
                        $checkMainCompany = false;
                        $companyList = array();
                        foreach ($adminCompanyList as $adminCompanyEntity) {
                            $isMainCompany = false;
                            $companyProfileEntity = QCompany::getCompanyProfileById($adminCompanyEntity->getCompanyId());
                            if ($companyProfileEntity != null) {
                                if ($companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED) {
                                    $companyName = $companyProfileEntity->getName();
                                    if ($adminCompanyEntity->getMainFlag() == AdminCompany::COMPANY_MAIN_FLAG_YES) {
                                        $checkMainCompany = true;
                                        $isMainCompany = true;
                                        $companyName .= ' (' . trans('word.main_company') . ')';
                                    }
                                    $payload = [
                                        'aid' => $adminProfileEntity->getId(),
                                        'cid' => $companyProfileEntity->getId(),
                                        'jti' => IFunction::generateRandomString(),
                                        'tt' => [
                                            'iat' => date("Y-m-d H:i:s"),
                                            'exp' => date("Y-m-d H:i:s", strtotime('+10 hours'))
                                        ]
                                    ];

                                    $token = JWT::encode($payload, config('web.jwt.secret_key'));
                                    QAdminCompany::updateAdminCompanyWebAdminToken($adminCompanyEntity, $token);
                                    array_push($companyList, [
                                        'token' => $token,
                                        'name' => $companyName,
                                        'flag' => $isMainCompany
                                    ]);
                                }
                            }
                        }
                        if ($checkMainCompany) {
                            return [
                                'status' => 2001001,
                                'message' => IResponse::responseMessage(2001001),
                                'company_list' => $companyList
                            ];
                        } else {
                            return IResponse::responseService('4011004');
                        }
                    } else {
                        return IResponse::responseService('4011004');
                    }
                } else if ($adminStatus == AdminProfile::ADMIN_STATUS_INACTIVATED) {
                    return IResponse::responseService('4011002');
                } else if ($adminStatus == AdminProfile::ADMIN_STATUS_LOCKED) {
                    return IResponse::responseService('4011003');
                }
            } else {
                QAdminProfile::updateAdminProfileLoginFailed($adminProfileEntity);
                return IResponse::responseService('4011001');
            }
        } else {
            return IResponse::responseService('4011001');
        }
    }
}
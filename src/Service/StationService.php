<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/7/2018
 * Time: 7:53 PM
 */

namespace Lattesoft\ApiMicroserviceCore\Service;

use Lattesoft\ApiMicroserviceCore\QDoctrine\QFingerPrint;
use Lattesoft\ApiMicroserviceCore\Response\IResponse;

class StationService
{
    public static function loginUsername($username, $password)
    {
        $fingerPrintEntity = QFingerPrint::getFingerPrintActiveByUsername($username);
        if($fingerPrintEntity != null) {

        } else {
            return IResponse::responseStation();
        }


        $profile = [
            'company_name' => 'company_en',
            'phone' => '0947940233',
            'login_code' => 'dsadsadsa',
            'token_id' => 'dsadasdasds'
        ];

//                return json_encode();
        return response()->json([
            'response' => [
                'code' => '000',
                'message' => 'success',
            ],
            'profile' => $profile
        ]);

        dd($fingerPrintEntity);
//        $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserByEmail($username);
//        if ($adminProfileEntity != null) {
//            $adminPassword = $adminProfileEntity->getPassword();
//            $passwordEnc = ICryptor::encrypt($password);
//            $adminStatus = $adminProfileEntity->getStatus();
//            if ($adminPassword == $passwordEnc) {
//                if ($adminStatus == AdminProfile::ADMIN_STATUS_ACTIVATED) {
//                    QAdminProfile::updateAdminProfileLoginSuccess($adminProfileEntity);
//                    $adminCompanyList = QAdminCompany::getAdminCompanyListActivateByAdminId($adminProfileEntity->getId());
//                    if ($adminCompanyList != null) {
//                        $checkMainCompany = false;
//                        $companyList = array();
//                        foreach ($adminCompanyList as $adminCompanyEntity) {
//                            $isMainCompany = false;
//                            $companyProfileEntity = QCompany::getCompanyProfileById($adminCompanyEntity->getCompanyId());
//                            if ($companyProfileEntity != null) {
//                                if ($companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED) {
//                                    $companyName = $companyProfileEntity->getName();
//                                    if ($adminCompanyEntity->getMainFlag() == AdminCompany::COMPANY_MAIN_FLAG_YES) {
//                                        $checkMainCompany = true;
//                                        $isMainCompany = true;
//                                        $companyName .= ' (' . trans('word.main_company') . ')';
//                                    }
//                                    $payload = [
//                                        'aid' => $adminProfileEntity->getId(),
//                                        'cid' => $companyProfileEntity->getId(),
//                                        'jti' => IFunction::generateRandomString(),
//                                        'tt' => [
//                                            'iat' => date("Y-m-d H:i:s"),
//                                            'exp' => date("Y-m-d H:i:s", strtotime('+10 hours'))
//                                        ]
//                                    ];
//
//                                    $token = JWT::encode($payload, config('web.jwt.secret_key'));
//                                    QAdminCompany::updateAdminCompanyWebAdminToken($adminCompanyEntity, $token);
//                                    array_push($companyList, [
//                                        'token' => $token,
//                                        'name' => $companyName,
//                                        'flag' => $isMainCompany
//                                    ]);
//                                }
//                            }
//                        }
//                        if ($checkMainCompany) {
//                            return [
//                                'status' => 2001001,
//                                'message' => IResponse::responseMessage(2001001),
//                                'company_list' => $companyList
//                            ];
//                        } else {
//                            return IResponse::responseService('4011004');
//                        }
//                    } else {
//                        return IResponse::responseService('4011004');
//                    }
//                } else if ($adminStatus == AdminProfile::ADMIN_STATUS_INACTIVATED) {
//                    return IResponse::responseService('4011002');
//                } else if ($adminStatus == AdminProfile::ADMIN_STATUS_LOCKED) {
//                    return IResponse::responseService('4011003');
//                }
//            } else {
//                QAdminProfile::updateAdminProfileLoginFailed($adminProfileEntity);
//                return IResponse::responseService('4011001');
//            }
//        } else {
//            return IResponse::responseService('4011001');
//        }
    }
}
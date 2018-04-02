<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/2/2018
 * Time: 10:17 PM
 */

namespace Lattesoft\ApiMicroserviceCore\Service;

use App\Domain\Company\Company;
use Lattesoft\ApiMicroserviceCore\QDoctrine\QCompany;
use Lattesoft\ApiMicroserviceCore\Response\IResponse;

class CompanyProfileService
{
    public static function getCompanyProfileById($id)
    {
        $companyProfileEntity = QCompany::getCompanyProfileById($id);
        if ($companyProfileEntity != null) {
            if ($companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED) {
                $companyList = array();
                array_push($companyList,
                    [
                        'id' => $companyProfileEntity->getId(),
                        'name' => $companyProfileEntity->getName(),
                        'status' => $companyProfileEntity->getStatus(),
                        'status_name' => $companyProfileEntity->getStatusName(),
                        'contact_phone' => $companyProfileEntity->getContactPhone(),
                        'contact_name' => $companyProfileEntity->getContactName(),
                        'contact_email' => $companyProfileEntity->getContactEmail(),
                        'address' => $companyProfileEntity->getAddress(),
                        'township' => $companyProfileEntity->getTownship(),
                        'district' => $companyProfileEntity->getDistrict(),
                        'province' => $companyProfileEntity->getProvince(),
                        'post' => $companyProfileEntity->getPost(),
                        'latitude' => $companyProfileEntity->getLatitude(),
                        'longitude' => $companyProfileEntity->getLongitude(),
                        'tax_id' => $companyProfileEntity->getTaxId(),
                        'social_security_no' => $companyProfileEntity->getSocialSecurityNo(),
                        'bank_id' => $companyProfileEntity->getBankId(),
                        'bankId' => $companyProfileEntity->getBankId(),
                        'bank_account' => $companyProfileEntity->getBankAccount()
                    ]
                );
                return [
                    'status' => 2001002,
                    'message' => IResponse::responseMessage(2001002),
                    'company_list' => $companyList
                ];
            } else {
                return IResponse::responseService('4011005');
            }
        } else {
            return IResponse::responseService('4011004');
        }
    }
}
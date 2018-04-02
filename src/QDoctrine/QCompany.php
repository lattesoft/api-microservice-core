<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 10:24 PM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Company\Company;
use Lattesoft\ApiMocroserviceCore\Util\IConstant;

class QCompany
{
    public static function getCompanyProfileById($id)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('c')
            ->from(Company::class, 'c')
            ->where('c.id = ?1')
            ->andWhere('c.status != ?2');
        $qb->setParameters(array(1 => $id, 2 => Company::COMPANY_STATUS_DELETED));
        $companyProfileList = $qb->getQuery()->getResult();
        if ($companyProfileList != null) {
            return $companyProfileList[0];
        } else {
            return null;
        }
    }

    public static function getCompanyProfileActivateById($id)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('c')
            ->from(Company::class, 'c')
            ->where('c.id = ?1')
            ->andWhere('c.status = ?2');
        $qb->setParameters(array(1 => $id, 2 => Company::COMPANY_STATUS_ACTIVATED));
        $companyProfileList = $qb->getQuery()->getResult();
        if ($companyProfileList != null) {
            return $companyProfileList[0];
        } else {
            return null;
        }
    }

    public static function saveCompanyProfile($companyProfileEntity)
    {
        app('em')->persist($companyProfileEntity);
        app('em')->flush();
    }
}
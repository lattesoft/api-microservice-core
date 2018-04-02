<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 9:25 PM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Admin\AdminCompany;
use App\Domain\Company\Company;
use Lattesoft\ApiMocroserviceCore\Util\IConstant;

class QAdminCompany
{

    public static function getAdminCompanyListActivateByAdminId($adminId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ac')
            ->from(AdminCompany::class, 'ac')
            ->where('ac.adminId = ?1')
            ->orderBy('ac.companyId', 'ASC');
        $qb->setParameters(array(1 => $adminId));
        return $qb->getQuery()->getResult();
    }

    public static function updateAdminCompanyWebAdminToken($adminCompanyEntity, $tokenId)
    {
        $adminCompanyEntity->setWebTokenId($tokenId);
        app('em')->persist($adminCompanyEntity);
        app('em')->flush();
    }

    public static function getAdminCompanyByTokenId($tokenId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ac')
            ->from(AdminCompany::class, 'ac')
            ->where('ac.webTokenId = ?1');
        $qb->setParameters(array(1 => $tokenId));
        $adminCompanyList = $qb->getQuery()->getResult();
        if ($adminCompanyList != null) {
            return $adminCompanyList[0];
        } else {
            return null;
        }
    }

    public static function getAdminCompanyRoleByRoleId($roleId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ac')
            ->from(AdminCompany::class, 'ac')
            ->where('ac.roleId = ?1')
            ->andWhere('ac.companyId = ?2')
            ->orderBy('ac.adminId', 'ASC');
        $qb->setParameters(array(1 => $roleId, 2 => $companyId));
        $adminCompanyList = $qb->getQuery()->getResult();
        return $adminCompanyList;
    }

}
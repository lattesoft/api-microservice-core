<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/26/2018
 * Time: 8:37 PM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Admin\AdminRole;
use App\Domain\Admin\AdminRoleDetail;
use Lattesoft\ApiMocroserviceCore\Util\IPagination;

class QAdminRole
{

    public static function getAdminRoleByRoleId($roleId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ar')
            ->from(AdminRole::class, 'ar')
            ->where('ar.id = ?1')
            ->andWhere('ar.companyId = ?2')
            ->andWhere('ar.status != ?3');
        $qb->setParameters(array(1 => $roleId, 2 => $companyId, 3 => AdminRole::ADMIN_ROLE_STATUS_DELETED));
        $adminRoleList = $qb->getQuery()->getResult();
        if ($adminRoleList != null) {
            return $adminRoleList[0];
        }
        return null;
    }

    public static function getAdminRoleDetailByRoleId($roleId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ard')
            ->from(AdminRoleDetail::class, 'ard')
            ->where('ard.roleId = ?1')
            ->andWhere('ard.companyId = ?2')
            ->orderBy('ard.menu', 'ASC');
        $qb->setParameters(array(1 => $roleId, 2 => $companyId));
        $adminRoleDetailList = $qb->getQuery()->getResult();
        return $adminRoleDetailList;
    }

    public static function getAdminRoleListByRoleId($roleId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ar')
            ->from(AdminRole::class, 'ar')
            ->where('ar.id = ?1')
            ->andWhere('ar.companyId = ?2')
            ->andWhere('ar.status != ?3');
        $qb->setParameters(array(1 => $roleId, 2 => $companyId, 3 => AdminRole::ADMIN_ROLE_STATUS_DELETED));
        $adminRoleList = $qb->getQuery()->getResult();
        if ($adminRoleList != null) {
            $adminRoleEntity = $adminRoleList[0];
            if ($adminRoleEntity->getStatus() == AdminRole::ADMIN_ROLE_STATUS_ACTIVED) {
                $qb->select('ard')
                    ->from(AdminRoleDetail::class, 'ard')
                    ->where('ard.roleId = ?1')
                    ->andWhere('ard.companyId = ?2');
                $qb->setParameters(array(1 => $roleId, 2 => $companyId));
                return $qb->getQuery()->getResult();
            }
        }
        return null;
    }

    public static function getAdminRoleListByCompanyId($companyId, $first, $limit, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ar')
            ->from(AdminRole::class, 'ar')
            ->where('ar.companyId = ?1')
            ->andWhere('ar.status != ?2')
            ->andWhere('ar.name like ?3')
            ->orderBy('ar.id', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($limit);
        $qb->setParameters(array(1 => $companyId, 2 => AdminRole::ADMIN_ROLE_STATUS_DELETED, 3 => '%' . $search . '%'));
        return $qb->getQuery()->getResult();
    }

    public static function countAdminRoleListByCompanyId($companyId, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(ar.id)')
            ->from(AdminRole::class, 'ar')
            ->where('ar.companyId = ?1')
            ->andWhere('ar.status != ?2')
            ->andWhere('ar.name like ?3');
        $qb->setParameters(array(1 => $companyId, 2 => AdminRole::ADMIN_ROLE_STATUS_DELETED, 3 => '%' . $search . '%'));
        $adminRoleCountEntityList = $qb->getQuery()->getResult();
        if ($adminRoleCountEntityList != null) {
            return intval($adminRoleCountEntityList[0][1]);
        } else {
            return 0;
        }
    }

    public static function saveAdminRole($adminRoleEntity)
    {
        app('em')->persist($adminRoleEntity);
        app('em')->flush();
    }

}
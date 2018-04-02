<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/26/2018
 * Time: 9:08 PM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Admin\AdminGroup;

class QAdminGroup
{
    public static function getAdminGroupList($adminId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ag')
            ->from(AdminGroup::class, 'ag')
            ->where('ag.adminId = ?1')
            ->andWhere('ag.companyId = ?2');
        $qb->setParameters(array(1 => $adminId, 2 => $companyId));
        return $qb->getQuery()->getResult();
    }
}
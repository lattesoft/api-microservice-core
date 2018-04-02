<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 1:40 PM
 */

namespace Lattesoft\ApiMicroserviceCore\QDoctrine;

use App\Domain\Position\Position;

class QPosition
{
    public static function countPositionListByCompanyId($companyId, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(p.id)')
            ->from(Position::class, 'p')
            ->where('p.companyId = ?1')
            ->andWhere('p.status != ?2')
            ->andWhere('p.name like ?3');
        $qb->setParameters(array(1 => $companyId, 2 => Position::POSITION_STATUS_DELETED, 3 => '%' . $search . '%'));
        $positionCountEntity = $qb->getQuery()->getResult();
        if ($positionCountEntity != null) {
            return intval($positionCountEntity[0][1]);
        } else {
            return 0;
        }
    }

    public static function getPositionListByCompany($companyId, $first, $limit, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('p')
            ->from(Position::class, 'p')
            ->where('p.companyId = ?1')
            ->andWhere('p.status != ?2')
            ->andWhere('p.name like ?3')
            ->orderBy('p.name', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($limit);
        $qb->setParameters(array(1 => $companyId, 2 => Position::POSITION_STATUS_DELETED, 3 => '%' . $search . '%'));
        return $qb->getQuery()->getResult();
    }


    public static function getPositionById($positionId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('p')
            ->from(Position::class, 'p')
            ->where('p.id = ?1')
            ->andWhere('p.companyId = ?2')
            ->andWhere('p.status != ?3');
        $qb->setParameters(array(1 => $positionId, 2 => $companyId, 3 => Position::POSITION_STATUS_DELETED));
        $deptEntityList = $qb->getQuery()->getResult();
        if ($deptEntityList != null) {
            return $deptEntityList[0];
        }
        return null;
    }

    public static function getPositionByDeptId($deptId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('p')
            ->from(Position::class, 'p')
            ->where('p.departmentId = ?1')
            ->andWhere('p.companyId = ?2')
            ->andWhere('p.status != ?3')
            ->orderBy('p.name', 'ASC');
        $qb->setParameters(array(1 => $deptId, 2 => $companyId, 3 => Position::POSITION_STATUS_DELETED));
        return $qb->getQuery()->getResult();
    }

    public static function savePosition($positionEntity)
    {
        app('em')->persist($positionEntity);
        app('em')->flush();
        app('em')->clear();
    }
}
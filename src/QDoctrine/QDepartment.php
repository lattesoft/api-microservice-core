<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 3:27 AM
 */

namespace Finiz\QDoctrine;

use App\Domain\Department\Department;

class QDepartment
{

    public static function getDepartmentListByCompany($companyId, $first, $limit, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('dp')
            ->from(Department::class, 'dp')
            ->where('dp.companyId = ?1')
            ->andWhere('dp.status != ?2')
            ->andWhere('dp.name like ?3')
            ->orderBy('dp.name', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($limit);
        $qb->setParameters(array(1 => $companyId, 2 => Department::DEPARTMENT_STATUS_DELETED, 3 => '%' . $search . '%'));
        return $qb->getQuery()->getResult();
    }

    public static function countDepartmentListByCompanyId($companyId, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(dp.id)')
            ->from(Department::class, 'dp')
            ->where('dp.companyId = ?1')
            ->andWhere('dp.status != ?2')
            ->andWhere('dp.name like ?3');
        $qb->setParameters(array(1 => $companyId, 2 => Department::DEPARTMENT_STATUS_DELETED, 3 => '%' . $search . '%'));
        $departmentCountEntity = $qb->getQuery()->getResult();
        if ($departmentCountEntity != null) {
            return intval($departmentCountEntity[0][1]);
        } else {
            return 0;
        }
    }

    public static function getDepartmentById($deptId, $companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('dp')
            ->from(Department::class, 'dp')
            ->where('dp.id = ?1')
            ->andWhere('dp.companyId = ?2')
            ->andWhere('dp.status != ?3');
        $qb->setParameters(array(1 => $deptId, 2 => $companyId, 3 => Department::DEPARTMENT_STATUS_DELETED));
        $deptEntityList = $qb->getQuery()->getResult();
        if ($deptEntityList != null) {
            return $deptEntityList[0];
        }
        return null;
    }

    public static function saveDepartment($deptEntityList)
    {
        app('em')->persist($deptEntityList);
        app('em')->flush();
        app('em')->clear();
    }

    public static function updateDefaultFlag($deptId, $companyId)
    {
        app('em')->getConnection()->beginTransaction();
        try {
            $qb = app('em')->createQueryBuilder();
            $qb->update(Department::class, 'dp')
                ->set('dp.defaultFlag', '?1')
                ->where('dp.companyId = ?2');
            $qb->setParameters(array(1 => Department::DEPARTMENT_DEFAULT_FLAG_NO, 2 => $companyId));
            $qb->getQuery()->execute();

            $qb = app('em')->createQueryBuilder();
            $qb->update(Department::class, 'dp')
                ->set('dp.defaultFlag', '?1')
                ->where('dp.companyId = ?2')
                ->andWhere('dp.id = ?3');
            $qb->setParameters(array(1 => Department::DEPARTMENT_DEFAULT_FLAG_YES, 2 => $companyId, 3 => $deptId));
            $qb->getQuery()->execute();

            app('em')->flush();
            app('em')->getConnection()->commit();
            app('em')->clear();
        } catch (Exception $e) {
            app('em')->rollBack();
        }
    }

    public static function getDeptIdNext($companyId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(dp.id)')
            ->from(Department::class, 'dp')
            ->where('dp.companyId = ?1');
        $qb->setParameters(array(1 => $companyId));
        $departmentCountEntity = $qb->getQuery()->getResult();
        if ($departmentCountEntity != null) {
            return intval($departmentCountEntity[0][1]) + 1;
        } else {
            return 1;
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/16/2018
 * Time: 2:51 AM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Employee\Employee;

class QEmployee
{
    public static function countEmployeeListByCompanyId($companyId, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(u.id)')
            ->from(Employee::class, 'u')
            ->where('u.companyId = ?1')
            ->andWhere('u.status != ?2')
            ->andWhere('u.firstnameTh like ?3');
        $qb->setParameters(array(1 => $companyId, 2 => Employee::EMPLOYEE_STATUS_DELETED, 3 => '%' . $search . '%'));
        $employeeCountEntity = $qb->getQuery()->getResult();
        if ($employeeCountEntity != null) {
            return intval($employeeCountEntity[0][1]);
        } else {
            return 0;
        }
    }

    public static function countEmployeeListByPositionId($companyId, $positionId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('count(u.id)')
            ->from(Employee::class, 'u')
            ->where('u.companyId = ?1')
            ->andWhere('u.status != ?2')
            ->andWhere('u.positionId = ?3');
        $qb->setParameters(array(1 => $companyId, 2 => Employee::EMPLOYEE_STATUS_DELETED, 3 => $positionId));
        $employeeCountEntity = $qb->getQuery()->getResult();
        if ($employeeCountEntity != null) {
            return intval($employeeCountEntity[0][1]);
        } else {
            return 0;
        }
    }

    public static function getEmployeeListByCompany($companyId, $first, $limit, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('u')
            ->from(Employee::class, 'u')
            ->where('u.companyId = ?1')
            ->andWhere('u.status != ?2')
            ->andWhere('u.firstnameTh like ?3')
            ->orderBy('u.firstnameTh', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($limit);
        $qb->setParameters(array(1 => $companyId, 2 => Employee::EMPLOYEE_STATUS_DELETED, 3 => '%' . $search . '%'));
        return $qb->getQuery()->getResult();
    }

    public static function getEmployeeById($companyId, $employeeId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('u')
            ->from(Employee::class, 'u')
            ->where('u.companyId = ?1')
            ->andWhere('u.status != ?2')
            ->andWhere('u.id = ?3');
        $qb->setParameters(array(1 => $companyId, 2 => Employee::EMPLOYEE_STATUS_DELETED, 3 => $employeeId));
        $employeeEntity = $qb->getQuery()->getResult();
        if ($employeeEntity != null) {
            return $employeeEntity[0];
        } else {
            return 0;
        }
    }

    public static function saveEmployee($employeeEntity)
    {
        app('em')->persist($employeeEntity);
        app('em')->flush();
        app('em')->clear();
    }

}
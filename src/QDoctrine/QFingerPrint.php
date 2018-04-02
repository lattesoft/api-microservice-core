<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/7/2018
 * Time: 7:13 PM
 */

namespace Lattesoft\ApiMocroserviceCore\QDoctrine;

use App\Domain\Channel\FingerPrint;

class QFingerPrint
{
    public static function getFingerPrintActiveById($companyId, $fingerId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('fg')
            ->from(FingerPrint::class, 'fg')
            ->where('fg.companyId = ?1')
            ->andWhere('fg.id = ?2')
            ->andWhere('fg.status != ?3');
        $qb->setParameters(array(1 => $companyId, 2 => $fingerId, 3 => FingerPrint::FINGER_PRINT_STATUS_ACTIVED));
        $fingerPrintEntity = $qb->getQuery()->getResult();
        if ($fingerPrintEntity != null) {
            return $fingerPrintEntity[0];
        }
        return null;
    }

    public static function getFingerPrintActiveByUsername($username)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('fg')
            ->from(FingerPrint::class, 'fg')
            ->where('fg.loginCode = ?1')
            ->andWhere('fg.status = ?2');
        $qb->setParameters(array(1 => $username, 2 => FingerPrint::FINGER_PRINT_STATUS_ACTIVED));
        $fingerPrintEntity = $qb->getQuery()->getResult();
        if ($fingerPrintEntity != null) {
            return $fingerPrintEntity[0];
        }
        return null;
    }


    public static function getFingerPrintListByCompanyId($companyId, $first, $limit, $search = '')
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('fg')
            ->from(FingerPrint::class, 'fg')
            ->where('fg.companyId = ?1')
            ->andWhere('fg.status != ?2')
            ->andWhere('fg.name like ?3')
            ->orderBy('fg.id', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($limit);
        $qb->setParameters(array(1 => $companyId, 2 => FingerPrint::FINGER_PRINT_STATUS_ACTIVED, 3 => '%' . $search . '%'));
        return $qb->getQuery()->getResult();
    }
}
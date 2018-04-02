<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 4:57 AM
 */

namespace Finiz\QDoctrine;

use App\Domain\Master\Bank;

class QBank
{
    public static function getBankList()
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('b')
            ->from(Bank::class, 'b')
            ->where('b.status = ?1')
            ->orderBy('b.id', 'ASC');
        $qb->setParameters(array(1 => Bank::BANK_STATUS_ACTIVED));
        return $qb->getQuery()->getResult();
    }

    public static function getBank($bankId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('b')
            ->from(Bank::class, 'b')
            ->where('b.id = ?1')
            ->andWhere('b.status = ?2');
        $qb->setParameters(array(1 => $bankId, 2 => Bank::BANK_STATUS_ACTIVED));
        $bankEntityList = $qb->getQuery()->getResult();
        if ($bankEntityList != null) {
            return $bankEntityList[0];
        } else {
            return null;
        }
    }
}
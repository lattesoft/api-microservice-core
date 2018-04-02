<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/14/2018
 * Time: 11:08 PM
 */

namespace Finiz\QDoctrine;

use App\Domain\Clock\ClockTime;
use App\Domain\Employee\Employee;

class QClockTime
{

    public static function saveClockTime($clocktimeEntity)
    {
        app('em')->persist($clocktimeEntity);
        app('em')->flush();
        app('em')->clear();
    }

    public static function getClockTimeList()
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('c')
            ->from("App\\Domain\\Clock\\ClockTime", 'c')
            ->orderBy('c.clockTime', 'ASC');
        return $qb->getQuery()->getResult();
    }

}
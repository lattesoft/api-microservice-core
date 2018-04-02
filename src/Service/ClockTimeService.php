<?php
/**
 * Created by PhpStorm.
 * User: kom
 * Date: 2018/03/16
 * Time: 9:20
 */

namespace Finiz\Service;


use Finiz\QDoctrine\QClockTime;
use Finiz\Response\IResponse;

class ClockTimeService
{
    public static function getClockTimeList()
    {
        $clocks = QClockTime::getClockTimeList();
        $response = [];
        foreach ($clocks as $clock){
            if(!array_key_exists($clock->getEmployeeId(),$response)){
                $response[$clock->getEmployeeId()] = [];
            }
            $response[$clock->getEmployeeId()][] = $clock->getClockTime();

        }
        return ($clocks ? IResponse::responseServiceWithData(2001002,$response,'clock_list') : IResponse::responseService(4030901));
    }
}
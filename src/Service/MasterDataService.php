<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 4:56 AM
 */

namespace Lattesoft\ApiMocroserviceCore\Service;

use Lattesoft\ApiMocroserviceCore\QDoctrine\QBank;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;

class MasterDataService
{
    public static function getBankList()
    {

        $bankListEntity = QBank::getBankList();
        if ($bankListEntity != null) {
            $bankList = array();
            foreach ($bankListEntity as $bankEntity) {
                array_push($bankList, [
                    'id' => $bankEntity->getId(),
                    'code' => $bankEntity->getCode(),
                    'number' => $bankEntity->getNumber(),
                    'name_th' => $bankEntity->getNameTh(),
                    'name_en' => $bankEntity->getNameEn()
                ]);
            }
            return [
                'status' => 2000101,
                'message' => IResponse::responseMessage(2000101),
                'bank_list' => $bankList
            ];
        } else {
            return IResponse::responseService('4030101');
        }

    }
}
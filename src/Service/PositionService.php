<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 1:38 PM
 */

namespace Finiz\Service;

use App\Domain\Position\Position;
use Finiz\QDoctrine\QDepartment;
use Finiz\QDoctrine\QPosition;
use Finiz\QDoctrine\QEmployee;
use Finiz\Response\IResponse;

class PositionService
{
    public static function getPositionListPage($companyId, $page, $size, $search)
    {
        $countPosition = QPosition::countPositionListByCompanyId($companyId, $search);
        if ($countPosition > 0) {
            $size = $size > 0 ? $size : 10;
            $first = ($page - 1) * $size;
            $first = $first >= 0 ? $first : 0;
            $positionEntityList = QPosition::getPositionListByCompany($companyId, $first, $size, $search);
            if ($positionEntityList != null) {
                $positionList = array();
                foreach ($positionEntityList as $positionEntity) {
                    $i = 1;
                    $positionId = $positionEntity->getId();
                    $userCount = QEmployee::countEmployeeListByPositionId($companyId, $positionId);
                    $departmentList = array();
                    $deptId = $positionEntity->getDepartmentId();
                    $departmentEntity = QDepartment::getDepartmentById($deptId, $companyId);
                    if ($departmentEntity != null) {
                        array_push($departmentList, [
                            'id' => $departmentEntity->getId(),
                            'name' => $departmentEntity->getName()
                        ]);
                    }

                    array_push($positionList, [
                        'id' => $positionEntity->getId(),
                        'name' => $positionEntity->getName(),
                        'description' => $positionEntity->getDescription(),
                        'department' => $departmentList,
                        'status' => $positionEntity->getStatus(),
                        'status_name' => $positionEntity->getStatusName(),
                        'default_flag' => $positionEntity->getDefaultFlag() == Position::POSITION_STATUS_ACTIVATED,
                        'default_message' => $positionEntity->getDefaultFlagMessage(),
                        'main_position' => '-',
                        'user_count' => $userCount
                    ]);
                }

                return [
                    'status' => 2000601,
                    'message' => IResponse::responseMessage(2000601),
                    'total' => $countPosition,
                    'per_page' => $size,
                    'current_page' => $page,
                    'last_page' => ceil($countPosition / $size),
                    'position_list' => $positionList
                ];
            } else {
                return IResponse::responseService('4030601');
            }
        } else {
            return IResponse::responseService('4030601');
        }
    }

    public static function deletePositionById($companyId, $positionId, $updatedBy)
    {
        $positionEntity = QPosition::getPositionById($positionId, $companyId);
        if ($positionEntity != null) {
            if ($positionEntity->getDefaultFlag() == Position::POSITION_DEFAULT_FLAG_YES) {
                return IResponse::responseService('4030602');
            }
            $positionEntity->setStatus(Position::POSITION_STATUS_DELETED);
            $positionEntity->setUpdateDate(new DateTime("now"));
            $positionEntity->setUpdateBy($updatedBy);
            QPosition::savePosition($positionEntity);
            return [
                'status' => 2000602,
                'message' => IResponse::responseMessage(2000602),
                'role' => [
                    'id' => $positionEntity->getId(),
                    'name' => $positionEntity->getName()
                ]
            ];
        } else {
            return IResponse::responseService('4090601');
        }
    }

    public static function getPositionPage($companyId, $positionId)
    {
        $positionEntity = QPosition::getPositionById($positionId, $companyId);
        if ($positionEntity != null) {
            $departmentList = array();
            $deptId = $positionEntity->getDepartmentId();
            $departmentEntity = QDepartment::getDepartmentById($companyId, $deptId);
            if ($departmentEntity != null) {
                array_push($departmentList, [
                    'id' => $departmentEntity->getId(),
                    'name' => $departmentEntity->getName()
                ]);
            }

            return [
                'status' => 2000601,
                'message' => IResponse::responseMessage(2000601),
                'position' => [
                    'id' => $positionEntity->getId(),
                    'name' => $positionEntity->getName(),
                    'description' => $positionEntity->getDescription(),
                    'department' => $departmentList,
                    'status' => $positionEntity->getStatus(),
                    'status_name' => $positionEntity->getStatusName(),
                    'default_flag' => $positionEntity->getDefaultFlag() == Position::POSITION_DEFAULT_FLAG_YES,
                    'default_message' => $positionEntity->getDefaultFlagMessage()
                ]
            ];
        } else {
            return IResponse::responseService('4030601');
        }
    }

}
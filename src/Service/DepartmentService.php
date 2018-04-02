<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 3:32 AM
 */

namespace Finiz\Service;

use App\Domain\Department\Department;
use Finiz\QDoctrine\QAdminRole;
use Finiz\QDoctrine\QDepartment;
use Finiz\QDoctrine\QPosition;
use Finiz\QDoctrine\QEmployee;
use Finiz\Response\IResponse;
use DateTime;

class DepartmentService
{
    public static function getDepartmentListPage($companyId, $page, $size, $search)
    {
        $countDepartment = QDepartment::countDepartmentListByCompanyId($companyId, $search);
        if ($countDepartment > 0) {
            $size = $size > 0 ? $size : 10;
            $first = ($page - 1) * $size;
            $first = $first >= 0 ? $first : 0;
            $departmentEntityList = QDepartment::getDepartmentListByCompany($companyId, $first, $size, $search);
            if ($departmentEntityList != null) {
                $departmentList = array();
                foreach ($departmentEntityList as $departmentEntity) {
                    $userCount = 0;
                    $deptId = $departmentEntity->getId();

                    $position_list = array();
                    $positionEntityList = QPosition::getPositionByDeptId($deptId, $companyId);
                    if ($positionEntityList != null) {

                        foreach ($positionEntityList as $positionEntity) {
                            $positionId = $positionEntity->getId();
                            $userCount += QEmployee::countEmployeeListByPositionId($companyId, $positionId);
                            array_push($position_list, [
                                'id' => $positionEntity->getId(),
                                'name' => $positionEntity->getName()
                            ]);
                        }
                    }

                    array_push($departmentList, [
                        'id' => $departmentEntity->getId(),
                        'name' => $departmentEntity->getName(),
                        'description' => $departmentEntity->getDescription(),
                        'status' => $departmentEntity->getStatus(),
                        'status_name' => $departmentEntity->getStatusName(),
                        'default_flag' => $departmentEntity->getDefaultFlag() == Department::DEPARTMENT_DEFAULT_FLAG_YES,
                        'default_message' => $departmentEntity->getDefaultFlagMessage(),
                        'position_list' => $position_list,
                        'user_count' => $userCount
                    ]);
                }

                return [
                    'status' => 2000401,
                    'message' => IResponse::responseMessage(2000401),
                    'total' => $countDepartment,
                    'per_page' => $size,
                    'current_page' => $page,
                    'last_page' => ceil($countDepartment / $size),
                    'department_list' => $departmentList
                ];
            } else {
                return IResponse::responseService('4030401');
            }
        } else {
            return IResponse::responseService('4030401');
        }
    }

    public static function getDepartmentPage($companyId, $deptId)
    {
        $departmentEntity = QDepartment::getDepartmentById($deptId, $companyId);
        if ($departmentEntity != null) {
            return [
                'status' => 2000401,
                'message' => IResponse::responseMessage(2000401),
                'department' => [
                    'id' => $departmentEntity->getId(),
                    'name' => $departmentEntity->getName(),
                    'description' => $departmentEntity->getDescription(),
                    'status' => $departmentEntity->getStatus(),
                    'status_name' => $departmentEntity->getStatusName(),
                    'default_flag' => $departmentEntity->getDefaultFlag() == Department::DEPARTMENT_DEFAULT_FLAG_YES,
                    'default_message' => $departmentEntity->getDefaultFlagMessage()
                ]
            ];
        } else {
            return IResponse::responseService('4030401');
        }
    }

    public static function deleteDepartmentById($companyId, $deptId, $updatedBy)
    {
        $departmentEntity = QDepartment::getDepartmentById($deptId, $companyId);
        if ($departmentEntity != null) {
            if ($departmentEntity->getDefaultFlag() == Department::DEPARTMENT_DEFAULT_FLAG_YES) {
                return IResponse::responseService('4030402');
            }
            $departmentEntity->setStatus(Department::DEPARTMENT_STATUS_DELETED);
            $departmentEntity->setUpdateDate(new DateTime("now"));
            $departmentEntity->setUpdateBy($updatedBy);
            QDepartment::saveDepartment($departmentEntity);
            return [
                'status' => 2000402,
                'message' => IResponse::responseMessage(2000402),
                'role' => [
                    'id' => $departmentEntity->getId(),
                    'name' => $departmentEntity->getName()
                ]
            ];
        } else {
            return IResponse::responseService('4090401');
        }
    }
}
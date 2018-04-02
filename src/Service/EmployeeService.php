<?php
/**
 * Created by PhpStorm.
 * Employee: Dearvincii
 * Date: 3/16/2018
 * Time: 2:49 AM
 */

namespace Lattesoft\ApiMocroserviceCore\Service;

use Lattesoft\ApiMocroserviceCore\QDoctrine\QDepartment;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QPosition;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QEmployee;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;

class EmployeeService
{
    public static function getEmployeeListPage($companyId, $page, $size, $search)
    {
        $countEmployee = QEmployee::countEmployeeListByCompanyId($companyId, $search);
        if ($countEmployee > 0) {
            $size = $size > 0 ? $size : 10;
            $first = ($page - 1) * $size;
            $first = $first >= 0 ? $first : 0;
            $employeeEntityList = QEmployee::getEmployeeListByCompany($companyId, $first, $size, $search);
            if ($employeeEntityList != null) {
                $employeeList = [];
                foreach ($employeeEntityList as $employeeEntity) {
                    $employeeId = $employeeEntity->getId();
                    $positionId = $employeeEntity->getPositionId();

                    $positionList = [];
                    $departmentList = [];
                    $positionEntity = QPosition::getPositionById($positionId, $companyId);
                    if ($positionEntity != null) {
                        array_push($positionList, [
                            'id' => $positionEntity->getId(),
                            'name' => $positionEntity->getName()
                        ]);
                        $deptId = $positionEntity->getDepartmentId();
                        $departmentEntity = QDepartment::getDepartmentById($deptId, $companyId);
                        if ($departmentEntity != null) {
                            array_push($departmentList, [
                                'id' => $departmentEntity->getId(),
                                'name' => $departmentEntity->getName()
                            ]);
                        }
                    }

                    array_push($employeeList, [
                        'id' => $employeeId,
                        'code' => $employeeEntity->getEmpNo(),
                        'firstname' => $employeeEntity->getFirstnameTh(),
                        'lastname' => $employeeEntity->getLastnameTh(),
                        'email' => '-',
                        'status' => $employeeEntity->getStatus(),
                        'status_name' => $employeeEntity->getStatusName(),
                        'position' => $positionList,
                        'department' => $departmentList
                    ]);
                }

                return [
                    'status' => 2000701,
                    'message' => IResponse::responseMessage(2000701),
                    'total' => $countEmployee,
                    'per_page' => $size,
                    'current_page' => $page,
                    'last_page' => ceil($countEmployee / $size),
                    'employee_list' => $employeeList
                ];
            } else {
                return IResponse::responseService('4030701');
            }
        } else {
            return IResponse::responseService('4030701');
        }
    }
}
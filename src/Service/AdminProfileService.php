<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/25/2018
 * Time: 6:21 PM
 */

namespace Lattesoft\ApiMocroserviceCore\Service;

use App\Domain\Admin\AdminCompany;
use App\Domain\Admin\AdminProfile;
use App\Domain\Admin\AdminRole;
use App\Domain\Company\Company;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QAdminCompany;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QAdminProfile;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QAdminRole;
use Lattesoft\ApiMocroserviceCore\QDoctrine\QCompany;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;
use Lattesoft\ApiMocroserviceCore\Util\IConstant;
use Lattesoft\ApiMocroserviceCore\Util\Locale;
use DateTime;
use Illuminate\Http\Request;

class AdminProfileService
{

    public static function getAdminProfilePermissionByToken($token)
    {
        $adminCompanyEntity = QAdminCompany::getAdminCompanyByTokenId($token);
        if ($adminCompanyEntity != null) {
            $adminId = $adminCompanyEntity->getAdminId();
            $companyId = $adminCompanyEntity->getCompanyId();
            $roleId = $adminCompanyEntity->getRoleId();
            $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserById($adminId);
            if ($adminProfileEntity != null) {
                Locale::setLocaleFromEntity($adminProfileEntity->getLang());
                if ($adminProfileEntity->getStatus() == AdminProfile::ADMIN_STATUS_ACTIVATED) {
                    $adminCompanyList = QAdminCompany::getAdminCompanyListActivateByAdminId($adminId);
                    if ($adminCompanyList != null) {
                        $checkMainCompany = false;
                        $checkCurrentCompany = false;
                        foreach ($adminCompanyList as $adminCompanyEntity) {
                            $companyProfileEntity = QCompany::getCompanyProfileById($adminCompanyEntity->getCompanyId());
                            if ($companyProfileEntity != null) {
                                if ($companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED &&
                                    $adminCompanyEntity->getMainFlag() == AdminCompany::COMPANY_MAIN_FLAG_YES) {
                                    $checkMainCompany = true;
                                }
                                if ($companyId == $companyProfileEntity->getId() && $companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED) {
                                    $checkCurrentCompany = true;
                                }
                            }
                        }
                        if ($checkMainCompany && $checkCurrentCompany) {
                            $profile = [
                                'firstname' => $adminProfileEntity->getFirstName(),
                                'lastname' => $adminProfileEntity->getLastName(),
                                'phone' => $adminProfileEntity->getPhone(),
                                'email' => $adminProfileEntity->getEmail(),
                                'status' => $adminProfileEntity->getStatus(),
                                'status_name' => $adminProfileEntity->getStatusName(),
                                'lang' => $adminProfileEntity->getLang(),
                                'profile' => $adminProfileEntity->getAdminProfileImageUrl()
                            ];

                            $menuList = array();
                            $adminRoleList = QAdminRole::getAdminRoleListByRoleId($roleId, $companyId);
                            if ($adminRoleList != null) {
                                foreach ($adminRoleList as $adminRoleEntity) {
                                    array_push($menuList, [
                                        'menu' => $adminRoleEntity->getMenu(),
                                        'role' => $adminRoleEntity->getRole()
                                    ]);
                                }
                            }

                            $positionList = array();

                            //Permission Success
                            return [
                                'status' => 2001002,
                                'message' => IResponse::responseMessage(2001002),
                                'profile' => $profile,
                                'menu_list' => $menuList,
                                'position_list' => $positionList
                            ];
                        } else {
                            //Company is not active
                            return IResponse::responseService('4011004');
                        }
                    } else {
                        //Admin Company List not found
                        return IResponse::responseService('4011004');
                    }
                } else {
                    //Admin User is Inactive
                    return IResponse::responseService('4011002');
                }
            } else {
                //Admin User not found
                return IResponse::responseService('4011006');
            }
        } else {
            return IResponse::responseService('4012001');
        }
    }

    public static function verifyUserAdminRole($token, array $menuList, array $roleList)
    {
        $adminCompanyEntity = QAdminCompany::getAdminCompanyByTokenId($token);
        if ($adminCompanyEntity != null) {
            $adminId = $adminCompanyEntity->getAdminId();
            $companyId = $adminCompanyEntity->getCompanyId();
            $roleId = $adminCompanyEntity->getRoleId();
            $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserById($adminId);
            if ($adminProfileEntity != null) {
                Locale::setLocaleFromEntity($adminProfileEntity->getLang());
                if ($adminProfileEntity->getStatus() == AdminProfile::ADMIN_STATUS_ACTIVATED) {
                    $adminCompanyList = QAdminCompany::getAdminCompanyListActivateByAdminId($adminId);
                    if ($adminCompanyList != null) {
                        $checkMainCompany = false;
                        $checkCurrentCompany = false;
                        foreach ($adminCompanyList as $adminCompanyEntity) {
                            $companyProfileEntity = QCompany::getCompanyProfileById($adminCompanyEntity->getCompanyId());
                            if ($companyProfileEntity != null) {
                                if ($companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED &&
                                    $adminCompanyEntity->getMainFlag() == AdminCompany::COMPANY_MAIN_FLAG_YES) {
                                    $checkMainCompany = true;
                                }
                                if ($companyId == $companyProfileEntity->getId() && $companyProfileEntity->getStatus() == Company::COMPANY_STATUS_ACTIVATED) {
                                    $checkCurrentCompany = true;
                                }
                            }
                        }
                        if ($checkMainCompany && $checkCurrentCompany) {
                            $chkRole = false;
                            $adminRoleList = QAdminRole::getAdminRoleListByRoleId($roleId, $companyId);
                            if ($adminRoleList != null) {
                                foreach ($adminRoleList as $adminRoleEntity) {
                                    $menu = $adminRoleEntity->getMenu();
                                    $role = $adminRoleEntity->getRole();
                                    if (in_array($menu, $menuList)) {
                                        if (in_array($role, $roleList)) {
                                            $chkRole = true;
                                        }
                                        break;
                                    }
                                }
                            }
                            if ($chkRole) {
                                return [
                                    'status' => 2001003,
                                    'message' => IResponse::responseMessage(2001003)
                                ];
                            } else {
                                return IResponse::responseService('3071001');
                            }
                        } else {
                            //Company is not active
                            return IResponse::responseService('4011004');
                        }
                    } else {
                        //Admin Company List not found
                        return IResponse::responseService('4011004');
                    }
                } else {
                    //Admin User is Inactive
                    return IResponse::responseService('4011002');
                }
            } else {
                //Admin User not found
                return IResponse::responseService('4011006');
            }
        } else {
            return IResponse::responseService('4012001');
        }
    }


    public static function getAdminRoleListPage($companyId, $page, $size, $search)
    {
        $countAdminRole = QAdminRole::countAdminRoleListByCompanyId($companyId, $search);
        if ($countAdminRole > 0) {
            $size = $size > 0 ? $size : 10;
            $first = ($page - 1) * $size;
            $first = $first >= 0 ? $first : 0;
            $adminRoleEntityList = QAdminRole::getAdminRoleListByCompanyId($companyId, $first, $size, $search);
            if ($adminRoleEntityList != null) {
                $roleList = array();
                foreach ($adminRoleEntityList as $adminRoleEntity) {
                    $i = 1;
                    $userList = array();
                    $userAdminEntityList = QAdminCompany::getAdminCompanyRoleByRoleId($adminRoleEntity->getId(), $companyId);
                    foreach ($userAdminEntityList as $userAdminEntity) {
                        $adminId = $userAdminEntity->getAdminId();
                        $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserById($adminId);
                        array_push($userList, [
                            'no' => $i,
                            'id' => $adminId,
                            'name' => $adminProfileEntity->getFirstName() . ' ' . $adminProfileEntity->getLastName()
                        ]);
                        $i++;
                    }
                    array_push($roleList, [
                        'id' => $adminRoleEntity->getId(),
                        'name' => $adminRoleEntity->getName(),
                        'description' => $adminRoleEntity->getDescription(),
                        'status' => $adminRoleEntity->getStatus(),
                        'status_name' => $adminRoleEntity->getStatusName(),
                        'default_flag' => $adminRoleEntity->getDefaultFlag() == AdminRole::ADMIN_ROLE_DEFAULT_FLAG_YES,
                        'default_message' => $adminRoleEntity->getDefaultFlagMessage(),
                        'main_flag' => $adminRoleEntity->getMainFlag() == AdminRole::ADMIN_ROLE_MAIN_FLAG_YES,
                        'main_flag_message' => $adminRoleEntity->getMainFlagMessage(),
                        'user_list' => $userList
                    ]);
                }

                return [
                    'status' => 2000301,
                    'message' => IResponse::responseMessage(2000301),
                    'total' => $countAdminRole,
                    'per_page' => $size,
                    'current_page' => $page,
                    'last_page' => ceil($countAdminRole / $size),
                    'role_list' => $roleList
                ];
            } else {
                return IResponse::responseService('4030301');
            }
        } else {
            return IResponse::responseService('4030301');
        }
    }

    public static function getAdminRolePage($companyId, $roleId)
    {
        $adminRoleEntity = QAdminRole::getAdminRoleByRoleId($roleId, $companyId);
        if ($adminRoleEntity != null) {
            $roleDetail = array();
            $adminRoleDetailEntityList = QAdminRole::getAdminRoleDetailByRoleId($roleId, $companyId);
            foreach ($adminRoleDetailEntityList as $adminRoleDetailEntity) {
                array_push($roleDetail, [
                    'menu' => $adminRoleDetailEntity->getMenu(),
                    'role' => $adminRoleDetailEntity->getRole()
                ]);
            }

            return [
                'status' => 2000301,
                'message' => IResponse::responseMessage(2000301),
                'role' => [
                    'id' => $adminRoleEntity->getId(),
                    'name' => $adminRoleEntity->getName(),
                    'description' => $adminRoleEntity->getDescription(),
                    'status' => $adminRoleEntity->getStatus(),
                    'status_name' => $adminRoleEntity->getStatusName(),
                    'default_flag' => $adminRoleEntity->getDefaultFlag() == AdminRole::ADMIN_ROLE_DEFAULT_FLAG_YES,
                    'default_message' => $adminRoleEntity->getDefaultFlagMessage(),
                    'main_flag' => $adminRoleEntity->getMainFlag() == AdminRole::ADMIN_ROLE_MAIN_FLAG_YES,
                    'main_flag_message' => $adminRoleEntity->getMainFlagMessage()
                ],
                'role_detail' => $roleDetail
            ];
        } else {
            return IResponse::responseService('4030301');
        }
    }

    public static function deleteAdminRoleByRoleId($companyId, $roleId, $updatedBy)
    {
        $adminRoleEntity = QAdminRole::getAdminRoleByRoleId($roleId, $companyId);
        if ($adminRoleEntity != null) {
            if ($adminRoleEntity->getMainFlag() == AdminRole::ADMIN_ROLE_MAIN_FLAG_YES) {
                return IResponse::responseService('4030302');
            }
            if ($adminRoleEntity->getDefaultFlag() == AdminRole::ADMIN_ROLE_DEFAULT_FLAG_YES) {
                return IResponse::responseService('4030303');
            }
            $userAdminEntityList = QAdminCompany::getAdminCompanyRoleByRoleId($roleId, $companyId);
            if ($userAdminEntityList != null) {
                foreach ($userAdminEntityList as $userAdminEntity) {
                    $adminId = $userAdminEntity->getAdminId();
                    $adminProfileEntity = QAdminProfile::getAdminProfileActiveUserById($adminId);
                    if ($adminProfileEntity != null) {
                        return IResponse::responseService('4030304');
                    }
                }
            }
            $adminRoleEntity->setStatus(AdminRole::ADMIN_ROLE_STATUS_DELETED);
            $adminRoleEntity->setUpdateDate(new DateTime("now"));
            $adminRoleEntity->setUpdateBy($updatedBy);
            QAdminRole::saveAdminRole($adminRoleEntity);
            return [
                'status' => 2000302,
                'message' => IResponse::responseMessage(2000302),
                'role' => [
                    'id' => $adminRoleEntity->getId(),
                    'name' => $adminRoleEntity->getName()
                ]
            ];
        } else {
            return IResponse::responseService('4090301');
        }
    }

    public static function updateAdminProfile($adminId,Request $request)
    {
        $updateAdmin = QAdminProfile::updateAdminProfileById($adminId,$request);
        if($updateAdmin){
            return response()->json(IResponse::responseServiceWithData(2000701,$updateAdmin));
        } else {
            return response()->json(IResponse::responseService(4000701),400);
        }
    }

}

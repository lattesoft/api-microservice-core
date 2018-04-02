<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 3:15 PM
 */

namespace Finiz\QDoctrine;

use App\Domain\Admin\AdminProfile;
use DateTime;
use Finiz\Util\IConstant;
use Finiz\Util\ICryptor;
use Illuminate\Http\Request;

class QAdminProfile
{
    public static function getAdminProfileActiveUserByEmail($username)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ap')
            ->from(AdminProfile::class, 'ap')
            ->where('ap.email = ?1')
            ->andWhere('ap.status != ?2');
        $qb->setParameters(array(1 => $username, 2 => AdminProfile::ADMIN_STATUS_DELETED));
        $adminProfileList = $qb->getQuery()->getResult();
        if ($adminProfileList != null) {
            return $adminProfileList[0];
        } else {
            return null;
        }
    }

    public static function updateAdminProfileById($adminId,Request $request)
    {
        $admin = app('em')->getRepository('App\\Domain\\Admin\\AdminProfile')->find($adminId);
        $admin->setFirstname($request->firstname);
        $admin->setLastname($request->lastname);
        $admin->setPhone($request->phone);

        if(!is_null($request->password)){
            $admin->setPassword(ICryptor::encrypt($request->password));
        }
        app('em')->persist($admin);
        app('em')->flush();

        return $admin;
    }

    public static function getAdminProfileActiveUserById($adminId)
    {
        $qb = app('em')->createQueryBuilder();
        $qb->select('ap')
            ->from(AdminProfile::class, 'ap')
            ->where('ap.id = ?1')
            ->andWhere('ap.status != ?2');
        $qb->setParameters(array(1 => $adminId, 2 => AdminProfile::ADMIN_STATUS_DELETED));
        $adminProfileList = $qb->getQuery()->getResult();
        if ($adminProfileList != null) {
            return $adminProfileList[0];
        } else {
            return null;
        }
    }

    public static function updateAdminProfileLoginFailed($adminProfileEntity)
    {
        $loginFailed = $adminProfileEntity->getLoginFailCount();
        $loginFailed++;
        $adminProfileEntity->setLoginFailCount($loginFailed);
        $adminProfileEntity->setUpdateDate(new DateTime("now"));
        $adminProfileEntity->setUpdateBy($adminProfileEntity->getId());
//        if ($loginFailed >= IConstant::LOGIN_FAILED_COUNT_MAXIMUM) {
//            $adminProfileEntity->setStatus(AdminProfile::ADMIN_STATUS_LOCKED);
//        }
        app('em')->persist($adminProfileEntity);
        app('em')->flush();
    }

    public static function updateAdminProfileLoginSuccess($adminProfileEntity)
    {
        $adminProfileEntity->setLoginFailCount(0);
        $adminProfileEntity->setLastLoginDate(new DateTime("now"));
        $adminProfileEntity->setStatus(AdminProfile::ADMIN_STATUS_ACTIVATED);
        app('em')->persist($adminProfileEntity);
        app('em')->flush();
    }

}
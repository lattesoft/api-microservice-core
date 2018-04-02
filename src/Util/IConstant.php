<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 2/24/2018
 * Time: 7:09 PM
 */

namespace Lattesoft\ApiMicroserviceCore\Util;

class IConstant
{
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

    const MENU_ROLE_HIDDEN = 'H';
    const MENU_ROLE_SHOW = 'S';
    const MENU_ROLE_EDIT = 'E';

    /*
     * MENU LIST
     */
    const MENU_COMPANY_PROFILE = 'COMPANY_PROFILE';
    const MENU_ADMINISTRATOR_ROLE = 'ADMINISTRATOR_ROLE';

    const LOGIN_FAILED_COUNT_MAXIMUM = 10;


    const LANGUAGE_THAI = 'th';
    const LANGUAGE_ENGLISH = 'en';

    const MY_PROFILE_ACTION_PERMISSION = 'permission';
    const MY_PROFILE_ACTION_PROFILE = 'profile';
    const MY_PROFILE_ACTION_ROLE = 'role';
    const MY_PROFILE_ACTION_GROUP = 'group';
    const MY_PROFILE_ACTION_COMPANY = 'company';


    const MIDDLEWARE_JWT_TARGET_ADMIN = 'admin';

}
<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/6/2018
 * Time: 4:36 PM
 */

namespace Lattesoft\ApiMicroserviceCore\Util;

use Doctrine\ORM\Query;
use LaravelDoctrine\ORM\Pagination\PaginatorAdapter;

class IPagination
{
    /**
     * @param Query $query
     * @param int $perPage
     * @param bool $fetchJoinCollection
     * @param string $pageName
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate(Query $query, $perPage, $pageName = 'page', $fetchJoinCollection = true)
    {
        return PaginatorAdapter::fromRequest(
            $query,
            $perPage,
            $pageName,
            $fetchJoinCollection
        )->make();
    }
}
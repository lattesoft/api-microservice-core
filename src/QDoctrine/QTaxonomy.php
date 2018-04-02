<?php
/**
 * Created by PhpStorm.
 * User: Dearvincii
 * Date: 3/15/2018
 * Time: 3:14 AM
 */

namespace Lattesoft\ApiMicroserviceCore\QDoctrine;

use App\Domain\Master\Taxonomy;

class QTaxonomy
{
    public static function getTaxonomyList($vocabulary)
    {

        $qb = app('em')->createQueryBuilder();
        $qb->select('t')
            ->from(Taxonomy::class, 't');
        $qb->where('t.active = ?1');

        if(is_array($vocabulary)){
            $parameters = [
                1 => Taxonomy::TAXONOMY_STATUS_ACTIVED
            ];
            foreach ($vocabulary as $key => $item){
                $qb->orWhere("t.vocabulary = ?".($key+2));
                $parameters[$key+2] = $item;
            }
            $qb->setParameters($parameters)
                ->orderBy('t.vocabulary', 'ASC');

        } else {
            $qb->where('t.vocabulary = ?2');
            $qb->setParameters([2=>$vocabulary]);
        }

        return $qb->orderBy('t.seq', 'ASC')->getQuery();
    }

}